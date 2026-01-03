<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\SubscriptionPlan;
use App\Services\TenantManagerSingleDB as TenantManager;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class TenantController extends Controller
{
    protected $tenantManager;

    public function __construct(TenantManager $tenantManager)
    {
        $this->tenantManager = $tenantManager;
    }

    /**
     * Get all active subscription plans
     */
    public function getPlans()
    {
        $plans = SubscriptionPlan::where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(function($plan) {
                return [
                    'id' => $plan->id,
                    'plan_id' => $plan->plan_id,
                    'name' => $plan->name,
                    'slug' => $plan->slug,
                    'description' => $plan->description,
                    'price_monthly' => $plan->price_monthly,
                    'price_yearly' => $plan->price_yearly,
                    'yearly_discount' => $plan->getYearlyDiscount(),
                    'features' => $plan->getFeaturesList(),
                    'is_featured' => $plan->is_featured,
                    'trial_days' => $plan->trial_days,
                ];
            });

        return response()->json([
            'success' => true,
            'plans' => $plans
        ]);
    }

    /**
     * Register new tenant (vendor signup)
     */
    public function signup(Request $request)
    {
        try {
            $validated = $request->validate([
                'business_name' => 'required|string|max:255',
                'business_email' => 'required|email|unique:tenants,business_email',
                'business_phone' => 'nullable|string',
                'business_type' => 'nullable|string',
                'business_address' => 'nullable|string',
                'owner_name' => 'required|string|max:255',
                'owner_email' => 'required|email',
                'owner_phone' => 'required|string',
                'password' => 'required|string|min:8|confirmed',
                'plan_id' => 'required|exists:subscription_plans,id',
            ]);
            
            // Auto-generate subdomain from business name if not provided
            $subdomain = $request->input('subdomain');
            if (empty($subdomain)) {
                $subdomain = Str::slug($validated['business_name']);
                // Make it unique
                $originalSubdomain = $subdomain;
                $counter = 1;
                while (Tenant::where('subdomain', $subdomain)->exists()) {
                    $subdomain = $originalSubdomain . $counter;
                    $counter++;
                }
            }
            $validated['subdomain'] = $subdomain;

            Log::info('New tenant signup initiated', ['email' => $validated['business_email']]);

            // Generate unique tenant ID (avoid collisions under concurrency)
            do {
                $tenantId = 'TEN-' . strtoupper(Str::random(8));
            } while (Tenant::where('tenant_id', $tenantId)->exists());

            // Get plan for trial days
            $plan = SubscriptionPlan::find($validated['plan_id']);

            // Create tenant
            $tenant = Tenant::create([
                'tenant_id' => $tenantId,
                'business_name' => $validated['business_name'],
                'business_email' => $validated['business_email'],
                'business_phone' => $validated['business_phone'] ?? null,
                'business_type' => $validated['business_type'] ?? null,
                'business_address' => $validated['business_address'] ?? null,
                'owner_name' => $validated['owner_name'],
                'owner_email' => $validated['owner_email'],
                'owner_phone' => $validated['owner_phone'],
                'password' => Hash::make($validated['password']),
                'subdomain' => strtolower($validated['subdomain']),
                'current_plan_id' => $validated['plan_id'],
                'status' => 'trial',
                'trial_ends_at' => now()->addDays($plan->trial_days),
                'max_orders' => $plan->max_orders_per_month,
                'max_products' => $plan->max_products,
                'max_users' => $plan->max_users,
            ]);

            Log::info('Tenant created', ['tenant_id' => $tenantId]);

            // Setup tenant (create admin user in single database)
            $setupSuccess = $this->tenantManager->setupTenant($tenant, $validated['password']);

            if (!$setupSuccess) {
                throw new \Exception('Failed to setup tenant');
            }

            Log::info('Tenant setup completed', ['tenant_id' => $tenantId]);

            // Create trial subscription
            $subscription = $tenant->subscriptions()->create([
                'subscription_id' => 'SUB-' . strtoupper(Str::random(8)),
                'plan_id' => $plan->id,
                'billing_cycle' => 'monthly',
                'starts_at' => now(),
                'ends_at' => now()->addDays($plan->trial_days),
                'trial_ends_at' => now()->addDays($plan->trial_days),
                'amount' => 0,
                'status' => 'trial',
            ]);

            // Log activity
            $tenant->logActivity('signup', 'New tenant registered successfully');

            // TODO: Send welcome email
            // Mail::to($tenant->owner_email)->send(new WelcomeTenant($tenant));

            return response()->json([
                'success' => true,
                'message' => 'Account created successfully! Your trial has started.',
                'tenant' => [
                    'id' => $tenant->tenant_id,
                    'business_name' => $tenant->business_name,
                    'subdomain' => $tenant->subdomain,
                    'login_url' => url('/login'),  // Single login URL for all vendors
                    'trial_ends_at' => $tenant->trial_ends_at->format('Y-m-d'),
                    'trial_days_remaining' => $tenant->getDaysUntilTrialEnd(),
                ]
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Tenant signup failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Cleanup if tenant was created but database failed
            if (isset($tenant)) {
                $tenant->delete();
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create account. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Check subdomain availability
     */
    public function checkSubdomain(Request $request)
    {
        $subdomain = strtolower($request->input('subdomain'));
        
        $exists = Tenant::where('subdomain', $subdomain)->exists();
        
        return response()->json([
            'available' => !$exists,
            'subdomain' => $subdomain,
            'url' => "https://{$subdomain}.emanager.com"
        ]);
    }
}

