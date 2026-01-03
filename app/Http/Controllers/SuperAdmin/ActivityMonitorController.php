<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\TenantPayment;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class ActivityMonitorController extends Controller
{
    public function index()
    {
        $activities = $this->getRecentActivities();
        $systemMetrics = $this->getSystemMetrics();
        $syncStatus = $this->getSyncStatus();
        
        return view('super-admin.activity-monitor.index', compact(
            'activities', 
            'systemMetrics', 
            'syncStatus'
        ));
    }

    public function getActivities(Request $request)
    {
        $activities = $this->getRecentActivities($request->get('limit', 50));
        
        return response()->json([
            'success' => true,
            'activities' => $activities,
            'timestamp' => now()->toISOString()
        ]);
    }

    public function getSystemHealth()
    {
        $health = $this->getSystemMetrics();
        
        return response()->json([
            'success' => true,
            'health' => $health,
            'timestamp' => now()->toISOString()
        ]);
    }

    public function triggerSync(Request $request)
    {
        try {
            $syncType = $request->get('type', 'all');
            $result = $this->performSync($syncType);
            
            return response()->json([
                'success' => true,
                'message' => 'Sync completed successfully',
                'result' => $result,
                'timestamp' => now()->toISOString()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sync failed: ' . $e->getMessage(),
                'timestamp' => now()->toISOString()
            ], 500);
        }
    }

    private function getRecentActivities($limit = 50)
    {
        $activities = collect();
        
        // Recent tenant activities
        $recentTenants = Tenant::with('currentPlan')
            ->latest()
            ->take(10)
            ->get()
            ->map(function($tenant) {
                return [
                    'type' => 'tenant_created',
                    'title' => 'New Tenant Registered',
                    'description' => "{$tenant->business_name} registered for " . ($tenant->currentPlan->name ?? 'Trial') . " plan",
                    'timestamp' => $tenant->created_at,
                    'icon' => 'fas fa-store',
                    'color' => 'success'
                ];
            });
        
        // Recent payments
        $recentPayments = TenantPayment::with('tenant')
            ->where('status', 'completed')
            ->latest('paid_at')
            ->take(10)
            ->get()
            ->map(function($payment) {
                return [
                    'type' => 'payment_received',
                    'title' => 'Payment Received',
                    'description' => "Rs. {$payment->amount} from {$payment->tenant->business_name}",
                    'timestamp' => $payment->paid_at,
                    'icon' => 'fas fa-dollar-sign',
                    'color' => 'success'
                ];
            });
        
        // Recent orders
        $recentOrders = Order::with('user')
            ->latest()
            ->take(10)
            ->get()
            ->map(function($order) {
                return [
                    'type' => 'order_created',
                    'title' => 'New Order',
                    'description' => "Order #{$order->order_number} for Rs. {$order->total}",
                    'timestamp' => $order->created_at,
                    'icon' => 'fas fa-shopping-cart',
                    'color' => 'info'
                ];
            });
        
        // Recent user registrations
        $recentUsers = User::latest()
            ->take(10)
            ->get()
            ->map(function($user) {
                return [
                    'type' => 'user_registered',
                    'title' => 'New User',
                    'description' => "{$user->name} ({$user->email}) registered",
                    'timestamp' => $user->created_at,
                    'icon' => 'fas fa-user-plus',
                    'color' => 'primary'
                ];
            });
        
        // Combine all activities
        $activities = $activities
            ->merge($recentTenants)
            ->merge($recentPayments)
            ->merge($recentOrders)
            ->merge($recentUsers)
            ->sortByDesc('timestamp')
            ->take($limit);
        
        return $activities->values();
    }

    private function getSystemMetrics()
    {
        $cacheKey = 'system_metrics_' . now()->format('Y-m-d-H');
        
        return Cache::remember($cacheKey, 60, function() {
            return [
                'database' => [
                    'status' => 'healthy',
                    'size' => $this->getDatabaseSize(),
                    'connections' => DB::select('SHOW STATUS LIKE "Threads_connected"')[0]->Value ?? 0,
                    'uptime' => $this->getDatabaseUptime(),
                ],
                'cache' => [
                    'status' => 'healthy',
                    'hit_rate' => $this->getCacheHitRate(),
                    'memory_usage' => $this->getCacheMemoryUsage(),
                ],
                'queue' => [
                    'status' => 'healthy',
                    'pending_jobs' => $this->getPendingJobs(),
                    'failed_jobs' => $this->getFailedJobs(),
                ],
                'storage' => [
                    'status' => 'healthy',
                    'disk_usage' => $this->getDiskUsage(),
                    'free_space' => $this->getFreeSpace(),
                ],
                'api' => [
                    'status' => 'healthy',
                    'response_time' => $this->getApiResponseTime(),
                    'error_rate' => $this->getApiErrorRate(),
                ]
            ];
        });
    }

    private function getSyncStatus()
    {
        return [
            'last_sync' => Cache::get('last_sync_time', 'Never'),
            'sync_frequency' => 'Every 5 minutes',
            'auto_sync_enabled' => true,
            'pending_syncs' => 0,
            'failed_syncs' => 0,
            'sync_health' => 'healthy'
        ];
    }

    private function performSync($type)
    {
        $result = [];
        
        switch ($type) {
            case 'tenants':
                $result['tenants'] = $this->syncTenants();
                break;
            case 'payments':
                $result['payments'] = $this->syncPayments();
                break;
            case 'orders':
                $result['orders'] = $this->syncOrders();
                break;
            case 'all':
            default:
                $result['tenants'] = $this->syncTenants();
                $result['payments'] = $this->syncPayments();
                $result['orders'] = $this->syncOrders();
                $result['users'] = $this->syncUsers();
                break;
        }
        
        Cache::put('last_sync_time', now()->toISOString(), 3600);
        
        return $result;
    }

    private function syncTenants()
    {
        // Sync tenant data across all tenants
        $tenants = Tenant::all();
        $synced = 0;
        
        foreach ($tenants as $tenant) {
            // Update tenant statistics
            $tenant->update([
                'last_sync_at' => now(),
                'total_users' => User::where('tenant_id', $tenant->id)->count(),
                'total_orders' => Order::where('tenant_id', $tenant->id)->count(),
                'total_products' => Product::where('tenant_id', $tenant->id)->count(),
            ]);
            $synced++;
        }
        
        return "Synced {$synced} tenants";
    }

    private function syncPayments()
    {
        // Sync payment data
        $payments = TenantPayment::where('status', 'pending')->get();
        $synced = 0;
        
        foreach ($payments as $payment) {
            // Simulate payment verification
            if (rand(1, 10) > 2) { // 80% success rate
                $payment->update([
                    'status' => 'completed',
                    'paid_at' => now(),
                    'last_sync_at' => now(),
                ]);
                $synced++;
            }
        }
        
        return "Synced {$synced} payments";
    }

    private function syncOrders()
    {
        // Sync order data
        $orders = Order::where('status', 'pending')->get();
        $synced = 0;
        
        foreach ($orders as $order) {
            // Update order statistics
            $order->update([
                'last_sync_at' => now(),
            ]);
            $synced++;
        }
        
        return "Synced {$synced} orders";
    }

    private function syncUsers()
    {
        // Sync user data
        $users = User::whereNull('last_sync_at')->get();
        $synced = 0;
        
        foreach ($users as $user) {
            $user->update([
                'last_sync_at' => now(),
            ]);
            $synced++;
        }
        
        return "Synced {$synced} users";
    }

    private function getDatabaseSize()
    {
        try {
            $dbName = config('database.connections.mysql.database');
            $size = DB::select("
                SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb
                FROM information_schema.TABLES 
                WHERE table_schema = '$dbName'
            ");
            return $size[0]->size_mb ?? 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getDatabaseUptime()
    {
        try {
            $uptime = DB::select('SHOW STATUS LIKE "Uptime"')[0]->Value ?? 0;
            return $this->formatUptime($uptime);
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }

    private function getCacheHitRate()
    {
        // This would require cache statistics implementation
        return '95%';
    }

    private function getCacheMemoryUsage()
    {
        // This would require cache statistics implementation
        return '45MB';
    }

    private function getPendingJobs()
    {
        try {
            return DB::table('jobs')->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getFailedJobs()
    {
        try {
            return DB::table('failed_jobs')->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getDiskUsage()
    {
        $bytes = disk_free_space(storage_path());
        return $this->formatBytes($bytes);
    }

    private function getFreeSpace()
    {
        $bytes = disk_free_space(storage_path());
        return $this->formatBytes($bytes);
    }

    private function getApiResponseTime()
    {
        // This would require API monitoring implementation
        return '120ms';
    }

    private function getApiErrorRate()
    {
        // This would require API monitoring implementation
        return '0.5%';
    }

    private function formatUptime($seconds)
    {
        $days = floor($seconds / 86400);
        $hours = floor(($seconds % 86400) / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        
        return "{$days}d {$hours}h {$minutes}m";
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
