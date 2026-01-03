<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\TenantPayment;
use App\Models\Subscription;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = $this->getAllNotifications();
        $unreadCount = $this->getUnreadCount();
        
        return view('super-admin.notifications.index', compact('notifications', 'unreadCount'));
    }

    public function getNotifications(Request $request)
    {
        $notifications = $this->getAllNotifications();
        $unreadCount = $this->getUnreadCount();
        
        return response()->json([
            'success' => true,
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
            'timestamp' => now()->toISOString()
        ]);
    }

    public function markAsRead(Request $request)
    {
        $notificationId = $request->get('id');
        
        if ($notificationId) {
            // Mark specific notification as read
            $this->markNotificationAsRead($notificationId);
        } else {
            // Mark all notifications as read
            $this->markAllAsRead();
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Notifications marked as read',
            'timestamp' => now()->toISOString()
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:info,warning,error,success',
            'priority' => 'required|in:low,medium,high,urgent',
            'target_tenants' => 'nullable|array',
            'target_tenants.*' => 'exists:tenants,id',
            'send_email' => 'boolean',
            'send_sms' => 'boolean',
        ]);

        $notification = $this->createNotification([
            'title' => $request->title,
            'message' => $request->message,
            'type' => $request->type,
            'priority' => $request->priority,
            'target_tenants' => $request->target_tenants ?? [],
            'send_email' => $request->boolean('send_email'),
            'send_sms' => $request->boolean('send_sms'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Notification created successfully',
            'notification' => $notification,
            'timestamp' => now()->toISOString()
        ]);
    }

    public function delete(Request $request)
    {
        $notificationId = $request->get('id');
        
        if ($notificationId) {
            $this->deleteNotification($notificationId);
            
            return response()->json([
                'success' => true,
                'message' => 'Notification deleted successfully',
                'timestamp' => now()->toISOString()
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Invalid notification ID',
            'timestamp' => now()->toISOString()
        ], 400);
    }

    private function getAllNotifications()
    {
        $notifications = collect();
        
        // System-generated notifications
        $systemNotifications = $this->getSystemNotifications();
        $notifications = $notifications->merge($systemNotifications);
        
        // Custom notifications (stored in cache for demo)
        $customNotifications = $this->getCustomNotifications();
        $notifications = $notifications->merge($customNotifications);
        
        // Sort by priority and timestamp
        return $notifications->sortByDesc(function($notification) {
            $priorityOrder = ['urgent' => 4, 'high' => 3, 'medium' => 2, 'low' => 1];
            return [$priorityOrder[$notification['priority']] ?? 0, $notification['timestamp']];
        })->values();
    }

    private function getSystemNotifications()
    {
        $notifications = collect();
        
        // Expiring subscriptions
        $expiringSubscriptions = Subscription::where('ends_at', '<=', now()->addDays(7))
            ->where('status', 'active')
            ->with('tenant')
            ->get();
        
        foreach ($expiringSubscriptions as $subscription) {
            $notifications->push([
                'id' => 'expiring_sub_' . $subscription->id,
                'type' => 'warning',
                'priority' => 'high',
                'title' => 'Subscription Expiring Soon',
                'message' => "{$subscription->tenant->business_name} subscription expires in " . $subscription->ends_at->diffForHumans(),
                'timestamp' => $subscription->ends_at,
                'icon' => 'fas fa-clock',
                'read' => $this->isNotificationRead('expiring_sub_' . $subscription->id),
                'actions' => [
                    ['label' => 'View Tenant', 'url' => route('super.tenants.show', $subscription->tenant->id)],
                    ['label' => 'Renew', 'url' => route('super.subscriptions.renew', $subscription->id)]
                ]
            ]);
        }
        
        // Failed payments
        $failedPayments = TenantPayment::where('status', 'failed')
            ->where('created_at', '>=', now()->subDays(7))
            ->with('tenant')
            ->get();
        
        foreach ($failedPayments as $payment) {
            $notifications->push([
                'id' => 'failed_payment_' . $payment->id,
                'type' => 'error',
                'priority' => 'high',
                'title' => 'Payment Failed',
                'message' => "Payment of Rs. {$payment->amount} failed for {$payment->tenant->business_name}",
                'timestamp' => $payment->created_at,
                'icon' => 'fas fa-exclamation-triangle',
                'read' => $this->isNotificationRead('failed_payment_' . $payment->id),
                'actions' => [
                    ['label' => 'View Payment', 'url' => route('super.payments.show', $payment->id)],
                    ['label' => 'Contact Tenant', 'url' => route('super.tenants.show', $payment->tenant->id)]
                ]
            ]);
        }
        
        // Pending tenant approvals
        $pendingTenants = Tenant::where('status', 'pending')
            ->where('created_at', '>=', now()->subDays(30))
            ->get();
        
        foreach ($pendingTenants as $tenant) {
            $notifications->push([
                'id' => 'pending_tenant_' . $tenant->id,
                'type' => 'info',
                'priority' => 'medium',
                'title' => 'Pending Tenant Approval',
                'message' => "{$tenant->business_name} is waiting for approval",
                'timestamp' => $tenant->created_at,
                'icon' => 'fas fa-user-clock',
                'read' => $this->isNotificationRead('pending_tenant_' . $tenant->id),
                'actions' => [
                    ['label' => 'Review', 'url' => route('super.tenants.show', $tenant->id)],
                    ['label' => 'Approve', 'url' => route('super.tenants.approve', $tenant->id)]
                ]
            ]);
        }
        
        // Suspended tenants
        $suspendedTenants = Tenant::where('status', 'suspended')
            ->where('updated_at', '>=', now()->subDays(7))
            ->get();
        
        foreach ($suspendedTenants as $tenant) {
            $notifications->push([
                'id' => 'suspended_tenant_' . $tenant->id,
                'type' => 'warning',
                'priority' => 'high',
                'title' => 'Tenant Suspended',
                'message' => "{$tenant->business_name} has been suspended",
                'timestamp' => $tenant->updated_at,
                'icon' => 'fas fa-ban',
                'read' => $this->isNotificationRead('suspended_tenant_' . $tenant->id),
                'actions' => [
                    ['label' => 'View Details', 'url' => route('super.tenants.show', $tenant->id)],
                    ['label' => 'Reactivate', 'url' => route('super.tenants.activate', $tenant->id)]
                ]
            ]);
        }
        
        // Low stock alerts
        $lowStockProducts = \App\Models\Product::where('stock', '<', 10)
            ->where('is_active', true)
            ->with('tenant')
            ->get();
        
        foreach ($lowStockProducts as $product) {
            $notifications->push([
                'id' => 'low_stock_' . $product->id,
                'type' => 'warning',
                'priority' => 'medium',
                'title' => 'Low Stock Alert',
                'message' => "{$product->name} has only {$product->stock} units left in {$product->tenant->business_name}",
                'timestamp' => $product->updated_at,
                'icon' => 'fas fa-box-open',
                'read' => $this->isNotificationRead('low_stock_' . $product->id),
                'actions' => [
                    ['label' => 'View Product', 'url' => route('super.tenants.show', $product->tenant->id)],
                    ['label' => 'Restock', 'url' => '#']
                ]
            ]);
        }
        
        // High order volume
        $highVolumeTenants = Tenant::withCount(['orders' => function($query) {
            $query->where('created_at', '>=', now()->subDays(7));
        }])
        ->having('orders_count', '>', 50)
        ->get();
        
        foreach ($highVolumeTenants as $tenant) {
            $notifications->push([
                'id' => 'high_volume_' . $tenant->id,
                'type' => 'success',
                'priority' => 'low',
                'title' => 'High Order Volume',
                'message' => "{$tenant->business_name} has {$tenant->orders_count} orders this week",
                'timestamp' => now(),
                'icon' => 'fas fa-chart-line',
                'read' => $this->isNotificationRead('high_volume_' . $tenant->id),
                'actions' => [
                    ['label' => 'View Analytics', 'url' => route('super.tenants.analytics', $tenant->id)],
                    ['label' => 'View Tenant', 'url' => route('super.tenants.show', $tenant->id)]
                ]
            ]);
        }
        
        return $notifications;
    }

    private function getCustomNotifications()
    {
        // Get custom notifications from cache (in a real app, this would be from database)
        return Cache::get('custom_notifications', collect())->map(function($notification) {
            $notification['read'] = $this->isNotificationRead($notification['id']);
            return $notification;
        });
    }

    private function createNotification($data)
    {
        $notification = [
            'id' => 'custom_' . uniqid(),
            'type' => $data['type'],
            'priority' => $data['priority'],
            'title' => $data['title'],
            'message' => $data['message'],
            'timestamp' => now(),
            'icon' => $this->getIconForType($data['type']),
            'read' => false,
            'target_tenants' => $data['target_tenants'],
            'send_email' => $data['send_email'],
            'send_sms' => $data['send_sms'],
            'actions' => [
                ['label' => 'View', 'url' => '#'],
                ['label' => 'Mark as Read', 'url' => '#']
            ]
        ];
        
        // Store in cache (in a real app, this would be in database)
        $customNotifications = Cache::get('custom_notifications', collect());
        $customNotifications->push($notification);
        Cache::put('custom_notifications', $customNotifications, 3600);
        
        return $notification;
    }

    private function getIconForType($type)
    {
        $icons = [
            'info' => 'fas fa-info-circle',
            'warning' => 'fas fa-exclamation-triangle',
            'error' => 'fas fa-times-circle',
            'success' => 'fas fa-check-circle',
        ];
        
        return $icons[$type] ?? 'fas fa-bell';
    }

    private function isNotificationRead($notificationId)
    {
        $readNotifications = Cache::get('read_notifications', collect());
        return $readNotifications->contains($notificationId);
    }

    private function markNotificationAsRead($notificationId)
    {
        $readNotifications = Cache::get('read_notifications', collect());
        $readNotifications->push($notificationId);
        Cache::put('read_notifications', $readNotifications, 3600);
    }

    private function markAllAsRead()
    {
        $allNotifications = $this->getAllNotifications();
        $readNotifications = $allNotifications->pluck('id');
        Cache::put('read_notifications', $readNotifications, 3600);
    }

    private function deleteNotification($notificationId)
    {
        $customNotifications = Cache::get('custom_notifications', collect());
        $customNotifications = $customNotifications->reject(function($notification) use ($notificationId) {
            return $notification['id'] === $notificationId;
        });
        Cache::put('custom_notifications', $customNotifications, 3600);
    }

    private function getUnreadCount()
    {
        $allNotifications = $this->getAllNotifications();
        return $allNotifications->where('read', false)->count();
    }
}


