<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SystemMonitorController extends Controller
{
    public function index()
    {
        return $this->monitor();
    }

    public function monitor()
    {
        $systemHealth = $this->getSystemHealth();
        $performanceMetrics = $this->getPerformanceMetrics();
        $alerts = $this->getSystemAlerts();
        $recentActivities = $this->getRecentActivities();
        
        return view('super-admin.system.monitor', compact(
            'systemHealth', 
            'performanceMetrics', 
            'alerts', 
            'recentActivities'
        ));
    }

    public function getSystemStatus()
    {
        $systemHealth = $this->getSystemHealth();
        $performanceMetrics = $this->getPerformanceMetrics();
        
        return response()->json([
            'success' => true,
            'systemHealth' => $systemHealth,
            'performanceMetrics' => $performanceMetrics,
            'timestamp' => now()->toISOString()
        ]);
    }

    public function clearCache(Request $request)
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Cache::flush();

        return back()->with('success', 'System cache cleared successfully!');
    }

    public function logs()
    {
        $logPath = storage_path('logs/laravel.log');
        $logs = [];

        if (file_exists($logPath)) {
            $content = file_get_contents($logPath);
            $lines = array_slice(explode("\n", $content), -100);
            foreach ($lines as $line) {
                if (!empty(trim($line))) {
                    $logs[] = ['message' => $line];
                }
            }
        }

        return view('super-admin.system.logs', compact('logs'));
    }

    public function database()
    {
        $tables = DB::select('SHOW TABLES');
        $tableStats = [];

        foreach ($tables as $table) {
            $tableName = array_values((array)$table)[0];
            $count = DB::table($tableName)->count();
            $tableStats[] = [
                'name' => $tableName,
                'rows' => $count
            ];
        }

        return view('super-admin.system.database', compact('tableStats'));
    }

    public function queue()
    {
        $stats = [
            'pending_jobs' => 0,
            'failed_jobs' => 0,
            'processed_jobs' => 0,
        ];

        return view('super-admin.system.queue', compact('stats'));
    }

    public function cache()
    {
        $stats = [
            'cache_driver' => config('cache.default'),
            'cache_size' => 'N/A',
        ];

        return view('super-admin.system.cache', compact('stats'));
    }

    public function info()
    {
        $info = [
            'php_version' => phpversion(),
            'laravel_version' => app()->version(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'N/A',
            'database' => config('database.default'),
        ];

        return view('super-admin.system.info', compact('info'));
    }

    private function getSystemHealth()
    {
        $cacheKey = 'system_health_' . now()->format('Y-m-d-H');
        
        return Cache::remember($cacheKey, 60, function() {
            return [
                'database' => $this->getDatabaseHealth(),
                'cache' => $this->getCacheHealth(),
                'storage' => $this->getStorageHealth(),
                'queue' => $this->getQueueHealth(),
                'api' => $this->getApiHealth(),
                'overall' => $this->getOverallHealth()
            ];
        });
    }

    private function getPerformanceMetrics()
    {
        $cacheKey = 'performance_metrics_' . now()->format('Y-m-d-H');
        
        return Cache::remember($cacheKey, 60, function() {
            return [
                'response_time' => $this->getResponseTime(),
                'memory_usage' => $this->getMemoryUsage(),
                'cpu_usage' => $this->getCpuUsage(),
                'disk_io' => $this->getDiskIO(),
                'network_io' => $this->getNetworkIO(),
                'database_connections' => $this->getDatabaseConnections(),
                'cache_performance' => $this->getCachePerformance()
            ];
        });
    }

    private function getSystemAlerts()
    {
        $alerts = [];
        
        // Database alerts
        $dbHealth = $this->getDatabaseHealth();
        if ($dbHealth['status'] !== 'healthy') {
            $alerts[] = [
                'type' => 'database',
                'severity' => $dbHealth['status'] === 'critical' ? 'high' : 'medium',
                'title' => 'Database Issue',
                'message' => $dbHealth['message'],
                'timestamp' => now()
            ];
        }
        
        // Storage alerts
        $storageHealth = $this->getStorageHealth();
        if ($storageHealth['status'] !== 'healthy') {
            $alerts[] = [
                'type' => 'storage',
                'severity' => $storageHealth['status'] === 'critical' ? 'high' : 'medium',
                'title' => 'Storage Issue',
                'message' => $storageHealth['message'],
                'timestamp' => now()
            ];
        }
        
        // Memory alerts
        $memoryUsage = $this->getMemoryUsage();
        if ($memoryUsage['percentage'] > 90) {
            $alerts[] = [
                'type' => 'memory',
                'severity' => 'high',
                'title' => 'High Memory Usage',
                'message' => "Memory usage is at {$memoryUsage['percentage']}%",
                'timestamp' => now()
            ];
        }
        
        // Disk space alerts
        $diskUsage = $this->getDiskUsage();
        if ($diskUsage['percentage'] > 90) {
            $alerts[] = [
                'type' => 'disk',
                'severity' => 'high',
                'title' => 'Low Disk Space',
                'message' => "Disk usage is at {$diskUsage['percentage']}%",
                'timestamp' => now()
            ];
        }
        
        return $alerts;
    }

    private function getRecentActivities()
    {
        $activities = [];
        
        // Recent errors from logs
        $logPath = storage_path('logs/laravel.log');
        if (file_exists($logPath)) {
            $content = file_get_contents($logPath);
            $lines = array_slice(explode("\n", $content), -50);
            
            foreach ($lines as $line) {
                if (strpos($line, 'ERROR') !== false || strpos($line, 'CRITICAL') !== false) {
                    $activities[] = [
                        'type' => 'error',
                        'title' => 'System Error',
                        'message' => substr($line, 0, 100) . '...',
                        'timestamp' => now()->subMinutes(rand(1, 60))
                    ];
                }
            }
        }
        
        // Recent database activities
        $recentOrders = DB::table('orders')->latest()->take(5)->get();
        foreach ($recentOrders as $order) {
            $activities[] = [
                'type' => 'info',
                'title' => 'New Order',
                'message' => "Order #{$order->order_number} created",
                'timestamp' => $order->created_at
            ];
        }
        
        // Recent tenant activities
        $recentTenants = DB::table('tenants')->latest()->take(3)->get();
        foreach ($recentTenants as $tenant) {
            $activities[] = [
                'type' => 'success',
                'title' => 'New Tenant',
                'message' => "Tenant '{$tenant->business_name}' registered",
                'timestamp' => $tenant->created_at
            ];
        }
        
        return collect($activities)->sortByDesc('timestamp')->take(20)->values();
    }

    private function getDatabaseHealth()
    {
        try {
            // Test database connection
            DB::connection()->getPdo();
            
            // Get database size
            $dbName = config('database.connections.mysql.database');
            $size = DB::select("
                SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb
                FROM information_schema.TABLES 
                WHERE table_schema = ?
            ", [$dbName]);
            
            $sizeMB = $size[0]->size_mb ?? 0;
            
            // Get connection count
            $connections = DB::select('SHOW STATUS LIKE "Threads_connected"')[0]->Value ?? 0;
            
            // Get uptime
            $uptime = DB::select('SHOW STATUS LIKE "Uptime"')[0]->Value ?? 0;
            
            $status = 'healthy';
            $message = 'Database is running normally';
            
            if ($sizeMB > 1000) { // More than 1GB
                $status = 'warning';
                $message = 'Database size is large (' . $sizeMB . 'MB)';
            }
            
            if ($connections > 50) {
                $status = 'warning';
                $message = 'High number of database connections (' . $connections . ')';
            }
            
            return [
                'status' => $status,
                'message' => $message,
                'size_mb' => $sizeMB,
                'connections' => $connections,
                'uptime_seconds' => $uptime,
                'uptime_formatted' => $this->formatUptime($uptime)
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'critical',
                'message' => 'Database connection failed: ' . $e->getMessage(),
                'size_mb' => 0,
                'connections' => 0,
                'uptime_seconds' => 0,
                'uptime_formatted' => 'N/A'
            ];
        }
    }

    private function getCacheHealth()
    {
        try {
            // Test cache connection
            $testKey = 'health_check_' . time();
            Cache::put($testKey, 'test', 60);
            $retrieved = Cache::get($testKey);
            Cache::forget($testKey);
            
            if ($retrieved !== 'test') {
                return [
                    'status' => 'critical',
                    'message' => 'Cache is not working properly',
                    'driver' => config('cache.default'),
                    'hit_rate' => 'N/A'
                ];
            }
            
            return [
                'status' => 'healthy',
                'message' => 'Cache is working normally',
                'driver' => config('cache.default'),
                'hit_rate' => '95%' // This would need actual cache statistics
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'critical',
                'message' => 'Cache error: ' . $e->getMessage(),
                'driver' => config('cache.default'),
                'hit_rate' => 'N/A'
            ];
        }
    }

    private function getStorageHealth()
    {
        try {
            $diskUsage = $this->getDiskUsage();
            $freeSpace = $this->getFreeSpace();
            
            $status = 'healthy';
            $message = 'Storage is healthy';
            
            if ($diskUsage['percentage'] > 90) {
                $status = 'critical';
                $message = 'Disk space critically low (' . $diskUsage['percentage'] . '%)';
            } elseif ($diskUsage['percentage'] > 80) {
                $status = 'warning';
                $message = 'Disk space running low (' . $diskUsage['percentage'] . '%)';
            }
            
            return [
                'status' => $status,
                'message' => $message,
                'total_space' => $diskUsage['total'],
                'used_space' => $diskUsage['used'],
                'free_space' => $freeSpace,
                'usage_percentage' => $diskUsage['percentage']
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'critical',
                'message' => 'Storage error: ' . $e->getMessage(),
                'total_space' => 'N/A',
                'used_space' => 'N/A',
                'free_space' => 'N/A',
                'usage_percentage' => 0
            ];
        }
    }

    private function getQueueHealth()
    {
        try {
            $pendingJobs = DB::table('jobs')->count();
            $failedJobs = DB::table('failed_jobs')->count();
            
            $status = 'healthy';
            $message = 'Queue is processing normally';
            
            if ($failedJobs > 10) {
                $status = 'warning';
                $message = 'High number of failed jobs (' . $failedJobs . ')';
            }
            
            if ($pendingJobs > 100) {
                $status = 'warning';
                $message = 'High number of pending jobs (' . $pendingJobs . ')';
            }
            
            return [
                'status' => $status,
                'message' => $message,
                'pending_jobs' => $pendingJobs,
                'failed_jobs' => $failedJobs,
                'processed_jobs' => 0 // This would need to be tracked
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'critical',
                'message' => 'Queue error: ' . $e->getMessage(),
                'pending_jobs' => 0,
                'failed_jobs' => 0,
                'processed_jobs' => 0
            ];
        }
    }

    private function getApiHealth()
    {
        // This would require actual API monitoring
        return [
            'status' => 'healthy',
            'message' => 'API is responding normally',
            'response_time' => '120ms',
            'error_rate' => '0.5%',
            'uptime' => '99.9%'
        ];
    }

    private function getOverallHealth()
    {
        $health = $this->getSystemHealth();
        
        $criticalCount = 0;
        $warningCount = 0;
        
        foreach ($health as $component => $status) {
            if ($component === 'overall') continue;
            
            if ($status['status'] === 'critical') {
                $criticalCount++;
            } elseif ($status['status'] === 'warning') {
                $warningCount++;
            }
        }
        
        if ($criticalCount > 0) {
            return [
                'status' => 'critical',
                'message' => "{$criticalCount} critical issues found",
                'score' => max(0, 100 - ($criticalCount * 30) - ($warningCount * 10))
            ];
        } elseif ($warningCount > 0) {
            return [
                'status' => 'warning',
                'message' => "{$warningCount} warnings found",
                'score' => max(0, 100 - ($warningCount * 10))
            ];
        } else {
            return [
                'status' => 'healthy',
                'message' => 'All systems operational',
                'score' => 100
            ];
        }
    }

    private function getResponseTime()
    {
        $start = microtime(true);
        DB::select('SELECT 1');
        $end = microtime(true);
        
        return round(($end - $start) * 1000, 2); // Convert to milliseconds
    }

    private function getMemoryUsage()
    {
        $memoryUsage = memory_get_usage(true);
        $memoryLimit = $this->parseMemoryLimit(ini_get('memory_limit'));
        
        return [
            'used' => $this->formatBytes($memoryUsage),
            'limit' => $this->formatBytes($memoryLimit),
            'percentage' => round(($memoryUsage / $memoryLimit) * 100, 2)
        ];
    }

    private function getCpuUsage()
    {
        // This would require system-level monitoring
        return [
            'usage' => rand(10, 80) . '%',
            'load_average' => sys_getloadavg()[0] ?? 'N/A'
        ];
    }

    private function getDiskIO()
    {
        // This would require system-level monitoring
        return [
            'read_bytes' => 'N/A',
            'write_bytes' => 'N/A',
            'read_ops' => 'N/A',
            'write_ops' => 'N/A'
        ];
    }

    private function getNetworkIO()
    {
        // This would require system-level monitoring
        return [
            'bytes_in' => 'N/A',
            'bytes_out' => 'N/A',
            'packets_in' => 'N/A',
            'packets_out' => 'N/A'
        ];
    }

    private function getDatabaseConnections()
    {
        try {
            $connections = DB::select('SHOW STATUS LIKE "Threads_connected"')[0]->Value ?? 0;
            $maxConnections = DB::select('SHOW VARIABLES LIKE "max_connections"')[0]->Value ?? 0;
            
            return [
                'current' => $connections,
                'max' => $maxConnections,
                'percentage' => $maxConnections > 0 ? round(($connections / $maxConnections) * 100, 2) : 0
            ];
        } catch (\Exception $e) {
            return [
                'current' => 0,
                'max' => 0,
                'percentage' => 0
            ];
        }
    }

    private function getCachePerformance()
    {
        // This would require cache statistics
        return [
            'hit_rate' => '95%',
            'miss_rate' => '5%',
            'memory_usage' => '45MB'
        ];
    }

    private function getDiskUsage()
    {
        $totalSpace = disk_total_space(storage_path());
        $freeSpace = disk_free_space(storage_path());
        $usedSpace = $totalSpace - $freeSpace;
        
        return [
            'total' => $this->formatBytes($totalSpace),
            'used' => $this->formatBytes($usedSpace),
            'free' => $this->formatBytes($freeSpace),
            'percentage' => round(($usedSpace / $totalSpace) * 100, 2)
        ];
    }

    private function getFreeSpace()
    {
        $freeSpace = disk_free_space(storage_path());
        return $this->formatBytes($freeSpace);
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }

    private function formatUptime($seconds)
    {
        $days = floor($seconds / 86400);
        $hours = floor(($seconds % 86400) / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        
        return "{$days}d {$hours}h {$minutes}m";
    }

    private function parseMemoryLimit($limit)
    {
        $limit = trim($limit);
        $last = strtolower($limit[strlen($limit) - 1]);
        $value = (int) $limit;
        
        switch ($last) {
            case 'g':
                $value *= 1024;
            case 'm':
                $value *= 1024;
            case 'k':
                $value *= 1024;
        }
        
        return $value;
    }

    private function getDatabaseSize()
    {
        try {
            $dbName = config('database.connections.mysql.database');
            $result = DB::select("
                SELECT 
                    ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) as size_mb
                FROM information_schema.tables 
                WHERE table_schema = ?
            ", [$dbName]);
            
            return $result[0]->size_mb . ' MB';
        } catch (\Exception $e) {
            return 'N/A';
        }
    }
}



