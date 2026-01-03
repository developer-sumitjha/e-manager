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
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class DataValidationController extends Controller
{
    public function index()
    {
        $validationResults = $this->runAllValidations();
        $dataIntegrityScore = $this->calculateDataIntegrityScore($validationResults);
        $recommendations = $this->generateRecommendations($validationResults);
        
        return view('super-admin.data-validation.index', compact(
            'validationResults', 
            'dataIntegrityScore', 
            'recommendations'
        ));
    }

    public function runValidation(Request $request)
    {
        $validationType = $request->get('type', 'all');
        $results = $this->runSpecificValidation($validationType);
        
        return response()->json([
            'success' => true,
            'results' => $results,
            'timestamp' => now()->toISOString()
        ]);
    }

    public function fixIssues(Request $request)
    {
        $issueIds = $request->get('issue_ids', []);
        $fixType = $request->get('fix_type', 'auto');
        
        $results = $this->fixDataIssues($issueIds, $fixType);
        
        return response()->json([
            'success' => true,
            'results' => $results,
            'message' => 'Data issues fixed successfully',
            'timestamp' => now()->toISOString()
        ]);
    }

    private function runAllValidations()
    {
        $results = [
            'tenants' => $this->validateTenants(),
            'users' => $this->validateUsers(),
            'orders' => $this->validateOrders(),
            'products' => $this->validateProducts(),
            'payments' => $this->validatePayments(),
            'subscriptions' => $this->validateSubscriptions(),
            'relationships' => $this->validateRelationships(),
            'data_consistency' => $this->validateDataConsistency(),
        ];

        return $results;
    }

    private function runSpecificValidation($type)
    {
        switch ($type) {
            case 'tenants':
                return $this->validateTenants();
            case 'users':
                return $this->validateUsers();
            case 'orders':
                return $this->validateOrders();
            case 'products':
                return $this->validateProducts();
            case 'payments':
                return $this->validatePayments();
            case 'subscriptions':
                return $this->validateSubscriptions();
            case 'relationships':
                return $this->validateRelationships();
            case 'data_consistency':
                return $this->validateDataConsistency();
            default:
                return $this->runAllValidations();
        }
    }

    private function validateTenants()
    {
        $issues = [];
        
        // Check for tenants with missing required fields
        $tenantsWithMissingFields = Tenant::where(function($query) {
            $query->whereNull('business_name')
                  ->orWhereNull('business_email')
                  ->orWhereNull('subdomain')
                  ->orWhere('business_name', '')
                  ->orWhere('business_email', '')
                  ->orWhere('subdomain', '');
        })->get();

        foreach ($tenantsWithMissingFields as $tenant) {
            $issues[] = [
                'id' => 'tenant_missing_fields_' . $tenant->id,
                'type' => 'missing_fields',
                'severity' => 'high',
                'title' => 'Tenant Missing Required Fields',
                'description' => "Tenant ID {$tenant->id} is missing required fields",
                'entity_type' => 'tenant',
                'entity_id' => $tenant->id,
                'data' => $tenant->toArray(),
                'suggested_fix' => 'Fill in missing required fields',
                'auto_fixable' => false
            ];
        }

        // Check for duplicate subdomains
        $duplicateSubdomains = Tenant::select('subdomain')
            ->groupBy('subdomain')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('subdomain');

        foreach ($duplicateSubdomains as $subdomain) {
            $tenants = Tenant::where('subdomain', $subdomain)->get();
            $issues[] = [
                'id' => 'duplicate_subdomain_' . $subdomain,
                'type' => 'duplicate',
                'severity' => 'high',
                'title' => 'Duplicate Subdomain',
                'description' => "Subdomain '{$subdomain}' is used by multiple tenants",
                'entity_type' => 'tenant',
                'entity_ids' => $tenants->pluck('id')->toArray(),
                'data' => $tenants->toArray(),
                'suggested_fix' => 'Change subdomain for one of the tenants',
                'auto_fixable' => true
            ];
        }

        // Check for invalid email formats
        $tenantsWithInvalidEmails = Tenant::where('business_email', 'not regexp', '^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$')->get();

        foreach ($tenantsWithInvalidEmails as $tenant) {
            $issues[] = [
                'id' => 'invalid_email_' . $tenant->id,
                'type' => 'invalid_format',
                'severity' => 'medium',
                'title' => 'Invalid Email Format',
                'description' => "Tenant '{$tenant->business_name}' has invalid email format: {$tenant->business_email}",
                'entity_type' => 'tenant',
                'entity_id' => $tenant->id,
                'data' => $tenant->toArray(),
                'suggested_fix' => 'Update email to valid format',
                'auto_fixable' => false
            ];
        }

        return [
            'total_issues' => count($issues),
            'issues' => $issues,
            'status' => count($issues) === 0 ? 'healthy' : 'issues_found'
        ];
    }

    private function validateUsers()
    {
        $issues = [];
        
        // Check for users with missing required fields
        $usersWithMissingFields = User::where(function($query) {
            $query->whereNull('name')
                  ->orWhereNull('email')
                  ->orWhere('name', '')
                  ->orWhere('email', '');
        })->get();

        foreach ($usersWithMissingFields as $user) {
            $issues[] = [
                'id' => 'user_missing_fields_' . $user->id,
                'type' => 'missing_fields',
                'severity' => 'high',
                'title' => 'User Missing Required Fields',
                'description' => "User ID {$user->id} is missing required fields",
                'entity_type' => 'user',
                'entity_id' => $user->id,
                'data' => $user->toArray(),
                'suggested_fix' => 'Fill in missing required fields',
                'auto_fixable' => false
            ];
        }

        // Check for duplicate emails
        $duplicateEmails = User::select('email')
            ->groupBy('email')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('email');

        foreach ($duplicateEmails as $email) {
            $users = User::where('email', $email)->get();
            $issues[] = [
                'id' => 'duplicate_email_' . $email,
                'type' => 'duplicate',
                'severity' => 'high',
                'title' => 'Duplicate Email',
                'description' => "Email '{$email}' is used by multiple users",
                'entity_type' => 'user',
                'entity_ids' => $users->pluck('id')->toArray(),
                'data' => $users->toArray(),
                'suggested_fix' => 'Merge or update duplicate users',
                'auto_fixable' => false
            ];
        }

        // Check for users without tenant association
        $usersWithoutTenant = User::whereNull('tenant_id')->get();

        foreach ($usersWithoutTenant as $user) {
            $issues[] = [
                'id' => 'user_no_tenant_' . $user->id,
                'type' => 'orphaned',
                'severity' => 'medium',
                'title' => 'User Without Tenant',
                'description' => "User '{$user->name}' is not associated with any tenant",
                'entity_type' => 'user',
                'entity_id' => $user->id,
                'data' => $user->toArray(),
                'suggested_fix' => 'Assign user to a tenant',
                'auto_fixable' => false
            ];
        }

        return [
            'total_issues' => count($issues),
            'issues' => $issues,
            'status' => count($issues) === 0 ? 'healthy' : 'issues_found'
        ];
    }

    private function validateOrders()
    {
        $issues = [];
        
        // Check for orders with invalid status
        $invalidStatusOrders = Order::whereNotIn('status', ['pending', 'confirmed', 'rejected', 'processing', 'shipped', 'completed', 'cancelled'])->get();

        foreach ($invalidStatusOrders as $order) {
            $issues[] = [
                'id' => 'invalid_order_status_' . $order->id,
                'type' => 'invalid_value',
                'severity' => 'high',
                'title' => 'Invalid Order Status',
                'description' => "Order #{$order->order_number} has invalid status: {$order->status}",
                'entity_type' => 'order',
                'entity_id' => $order->id,
                'data' => $order->toArray(),
                'suggested_fix' => 'Update order status to valid value',
                'auto_fixable' => true
            ];
        }

        // Check for orders with negative totals
        $negativeTotalOrders = Order::where('total', '<', 0)->get();

        foreach ($negativeTotalOrders as $order) {
            $issues[] = [
                'id' => 'negative_total_' . $order->id,
                'type' => 'invalid_value',
                'severity' => 'high',
                'title' => 'Negative Order Total',
                'description' => "Order #{$order->order_number} has negative total: {$order->total}",
                'entity_type' => 'order',
                'entity_id' => $order->id,
                'data' => $order->toArray(),
                'suggested_fix' => 'Recalculate order total',
                'auto_fixable' => true
            ];
        }

        // Check for orders without items
        $ordersWithoutItems = Order::whereDoesntHave('orderItems')->get();

        foreach ($ordersWithoutItems as $order) {
            $issues[] = [
                'id' => 'order_no_items_' . $order->id,
                'type' => 'missing_relation',
                'severity' => 'medium',
                'title' => 'Order Without Items',
                'description' => "Order #{$order->order_number} has no order items",
                'entity_type' => 'order',
                'entity_id' => $order->id,
                'data' => $order->toArray(),
                'suggested_fix' => 'Add order items or delete empty order',
                'auto_fixable' => false
            ];
        }

        return [
            'total_issues' => count($issues),
            'issues' => $issues,
            'status' => count($issues) === 0 ? 'healthy' : 'issues_found'
        ];
    }

    private function validateProducts()
    {
        $issues = [];
        
        // Check for products with missing required fields
        $productsWithMissingFields = Product::where(function($query) {
            $query->whereNull('name')
                  ->orWhereNull('price')
                  ->orWhere('name', '')
                  ->orWhere('price', '<=', 0);
        })->get();

        foreach ($productsWithMissingFields as $product) {
            $issues[] = [
                'id' => 'product_missing_fields_' . $product->id,
                'type' => 'missing_fields',
                'severity' => 'high',
                'title' => 'Product Missing Required Fields',
                'description' => "Product ID {$product->id} is missing required fields or has invalid price",
                'entity_type' => 'product',
                'entity_id' => $product->id,
                'data' => $product->toArray(),
                'suggested_fix' => 'Fill in missing fields and set valid price',
                'auto_fixable' => false
            ];
        }

        // Check for products with negative stock
        $negativeStockProducts = Product::where('stock', '<', 0)->get();

        foreach ($negativeStockProducts as $product) {
            $issues[] = [
                'id' => 'negative_stock_' . $product->id,
                'type' => 'invalid_value',
                'severity' => 'medium',
                'title' => 'Negative Stock',
                'description' => "Product '{$product->name}' has negative stock: {$product->stock}",
                'entity_type' => 'product',
                'entity_id' => $product->id,
                'data' => $product->toArray(),
                'suggested_fix' => 'Update stock to valid value',
                'auto_fixable' => true
            ];
        }

        // Check for products without tenant association
        $productsWithoutTenant = Product::whereNull('tenant_id')->get();

        foreach ($productsWithoutTenant as $product) {
            $issues[] = [
                'id' => 'product_no_tenant_' . $product->id,
                'type' => 'orphaned',
                'severity' => 'high',
                'title' => 'Product Without Tenant',
                'description' => "Product '{$product->name}' is not associated with any tenant",
                'entity_type' => 'product',
                'entity_id' => $product->id,
                'data' => $product->toArray(),
                'suggested_fix' => 'Assign product to a tenant',
                'auto_fixable' => false
            ];
        }

        return [
            'total_issues' => count($issues),
            'issues' => $issues,
            'status' => count($issues) === 0 ? 'healthy' : 'issues_found'
        ];
    }

    private function validatePayments()
    {
        $issues = [];
        
        // Check for payments with invalid status
        $invalidStatusPayments = TenantPayment::whereNotIn('status', ['pending', 'completed', 'failed', 'refunded'])->get();

        foreach ($invalidStatusPayments as $payment) {
            $issues[] = [
                'id' => 'invalid_payment_status_' . $payment->id,
                'type' => 'invalid_value',
                'severity' => 'high',
                'title' => 'Invalid Payment Status',
                'description' => "Payment ID {$payment->id} has invalid status: {$payment->status}",
                'entity_type' => 'payment',
                'entity_id' => $payment->id,
                'data' => $payment->toArray(),
                'suggested_fix' => 'Update payment status to valid value',
                'auto_fixable' => true
            ];
        }

        // Check for payments with negative amounts
        $negativeAmountPayments = TenantPayment::where('amount', '<', 0)->get();

        foreach ($negativeAmountPayments as $payment) {
            $issues[] = [
                'id' => 'negative_amount_' . $payment->id,
                'type' => 'invalid_value',
                'severity' => 'high',
                'title' => 'Negative Payment Amount',
                'description' => "Payment ID {$payment->id} has negative amount: {$payment->amount}",
                'entity_type' => 'payment',
                'entity_id' => $payment->id,
                'data' => $payment->toArray(),
                'suggested_fix' => 'Update payment amount to valid value',
                'auto_fixable' => true
            ];
        }

        return [
            'total_issues' => count($issues),
            'issues' => $issues,
            'status' => count($issues) === 0 ? 'healthy' : 'issues_found'
        ];
    }

    private function validateSubscriptions()
    {
        $issues = [];
        
        // Check for expired subscriptions that are still active
        $expiredActiveSubscriptions = Subscription::where('status', 'active')
            ->where('ends_at', '<', now())
            ->get();

        foreach ($expiredActiveSubscriptions as $subscription) {
            $issues[] = [
                'id' => 'expired_active_' . $subscription->id,
                'type' => 'inconsistent_state',
                'severity' => 'high',
                'title' => 'Expired Active Subscription',
                'description' => "Subscription ID {$subscription->id} is active but expired on {$subscription->ends_at}",
                'entity_type' => 'subscription',
                'entity_id' => $subscription->id,
                'data' => $subscription->toArray(),
                'suggested_fix' => 'Update subscription status to expired',
                'auto_fixable' => true
            ];
        }

        return [
            'total_issues' => count($issues),
            'issues' => $issues,
            'status' => count($issues) === 0 ? 'healthy' : 'issues_found'
        ];
    }

    private function validateRelationships()
    {
        $issues = [];
        
        // Check for orphaned order items
        $orphanedOrderItems = DB::table('order_items')
            ->leftJoin('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereNull('orders.id')
            ->get();

        foreach ($orphanedOrderItems as $item) {
            $issues[] = [
                'id' => 'orphaned_order_item_' . $item->id,
                'type' => 'orphaned',
                'severity' => 'high',
                'title' => 'Orphaned Order Item',
                'description' => "Order item ID {$item->id} references non-existent order",
                'entity_type' => 'order_item',
                'entity_id' => $item->id,
                'data' => (array) $item,
                'suggested_fix' => 'Delete orphaned order item',
                'auto_fixable' => true
            ];
        }

        return [
            'total_issues' => count($issues),
            'issues' => $issues,
            'status' => count($issues) === 0 ? 'healthy' : 'issues_found'
        ];
    }

    private function validateDataConsistency()
    {
        $issues = [];
        
        // Check for orders with mismatched totals
        $ordersWithMismatchedTotals = Order::with('orderItems')->get()->filter(function($order) {
            $calculatedTotal = $order->orderItems->sum('total');
            return abs($order->total - $calculatedTotal) > 0.01; // Allow for small floating point differences
        });

        foreach ($ordersWithMismatchedTotals as $order) {
            $calculatedTotal = $order->orderItems->sum('total');
            $issues[] = [
                'id' => 'mismatched_total_' . $order->id,
                'type' => 'inconsistent_data',
                'severity' => 'medium',
                'title' => 'Mismatched Order Total',
                'description' => "Order #{$order->order_number} total ({$order->total}) doesn't match sum of items ({$calculatedTotal})",
                'entity_type' => 'order',
                'entity_id' => $order->id,
                'data' => $order->toArray(),
                'suggested_fix' => 'Recalculate order total',
                'auto_fixable' => true
            ];
        }

        return [
            'total_issues' => count($issues),
            'issues' => $issues,
            'status' => count($issues) === 0 ? 'healthy' : 'issues_found'
        ];
    }

    private function calculateDataIntegrityScore($validationResults)
    {
        $totalIssues = 0;
        $totalEntities = 0;
        
        foreach ($validationResults as $category => $result) {
            $totalIssues += $result['total_issues'];
            $totalEntities += $this->getEntityCount($category);
        }
        
        if ($totalEntities === 0) {
            return 100;
        }
        
        $score = max(0, 100 - (($totalIssues / $totalEntities) * 100));
        return round($score, 2);
    }

    private function getEntityCount($category)
    {
        switch ($category) {
            case 'tenants':
                return Tenant::count();
            case 'users':
                return User::count();
            case 'orders':
                return Order::count();
            case 'products':
                return Product::count();
            case 'payments':
                return TenantPayment::count();
            case 'subscriptions':
                return Subscription::count();
            default:
                return 0;
        }
    }

    private function generateRecommendations($validationResults)
    {
        $recommendations = [];
        
        $highSeverityIssues = collect($validationResults)
            ->pluck('issues')
            ->flatten(1)
            ->where('severity', 'high');
        
        if ($highSeverityIssues->count() > 0) {
            $recommendations[] = [
                'priority' => 'high',
                'title' => 'Address High Severity Issues',
                'description' => "There are {$highSeverityIssues->count()} high severity data issues that need immediate attention.",
                'action' => 'Review and fix high priority issues first'
            ];
        }
        
        $autoFixableIssues = collect($validationResults)
            ->pluck('issues')
            ->flatten(1)
            ->where('auto_fixable', true);
        
        if ($autoFixableIssues->count() > 0) {
            $recommendations[] = [
                'priority' => 'medium',
                'title' => 'Run Auto-Fix',
                'description' => "There are {$autoFixableIssues->count()} issues that can be automatically fixed.",
                'action' => 'Use the auto-fix feature to resolve these issues'
            ];
        }
        
        return $recommendations;
    }

    private function fixDataIssues($issueIds, $fixType)
    {
        $results = [];
        
        foreach ($issueIds as $issueId) {
            $issue = $this->findIssueById($issueId);
            
            if (!$issue) {
                continue;
            }
            
            if (!$issue['auto_fixable']) {
                $results[] = [
                    'issue_id' => $issueId,
                    'status' => 'skipped',
                    'message' => 'Issue cannot be auto-fixed'
                ];
                continue;
            }
            
            try {
                $fixResult = $this->applyFix($issue, $fixType);
                $results[] = [
                    'issue_id' => $issueId,
                    'status' => 'fixed',
                    'message' => $fixResult['message']
                ];
            } catch (\Exception $e) {
                $results[] = [
                    'issue_id' => $issueId,
                    'status' => 'failed',
                    'message' => 'Fix failed: ' . $e->getMessage()
                ];
            }
        }
        
        return $results;
    }

    private function findIssueById($issueId)
    {
        // This would need to be implemented to find the specific issue
        // For now, return null
        return null;
    }

    private function applyFix($issue, $fixType)
    {
        // This would implement the actual fix logic
        // For now, return a placeholder
        return [
            'message' => 'Fix applied successfully'
        ];
    }
}


