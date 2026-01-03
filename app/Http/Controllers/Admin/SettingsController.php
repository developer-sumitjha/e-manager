<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SettingsController extends Controller
{
    /**
     * Display settings dashboard
     */
    public function index()
    {
        $settingsGroups = [
            'general' => Setting::where('group', 'general')->get(),
            'email' => Setting::where('group', 'email')->get(),
            'payment' => Setting::where('group', 'payment')->get(),
            'notification' => Setting::where('group', 'notification')->get(),
            'shipping' => Setting::where('group', 'shipping')->get(),
            'tax' => Setting::where('group', 'tax')->get(),
            'order' => Setting::where('group', 'order')->get(),
            'security' => Setting::where('group', 'security')->get(),
            'api' => Setting::where('group', 'api')->get(),
            'system' => Setting::where('group', 'system')->get(),
        ];

        return view('admin.settings.index', compact('settingsGroups'));
    }

    /**
     * General Settings
     */
    public function general()
    {
        $settings = $this->getDefaultSettings('general');
        return view('admin.settings.general', compact('settings'));
    }

    /**
     * Email Settings
     */
    public function email()
    {
        $settings = $this->getDefaultSettings('email');
        return view('admin.settings.email', compact('settings'));
    }

    /**
     * Payment Settings
     */
    public function payment()
    {
        $settings = $this->getDefaultSettings('payment');
        return view('admin.settings.payment', compact('settings'));
    }

    /**
     * Notification Settings
     */
    public function notification()
    {
        $settings = $this->getDefaultSettings('notification');
        return view('admin.settings.notification', compact('settings'));
    }

    /**
     * Shipping Settings
     */
    public function shipping()
    {
        $settings = $this->getDefaultSettings('shipping');
        return view('admin.settings.shipping', compact('settings'));
    }

    /**
     * Tax & Currency Settings
     */
    public function tax()
    {
        $settings = $this->getDefaultSettings('tax');
        return view('admin.settings.tax', compact('settings'));
    }

    /**
     * Order Settings
     */
    public function order()
    {
        $settings = $this->getDefaultSettings('order');
        return view('admin.settings.order', compact('settings'));
    }

    /**
     * Security Settings
     */
    public function security()
    {
        $settings = $this->getDefaultSettings('security');
        return view('admin.settings.security', compact('settings'));
    }

    /**
     * API Settings
     */
    public function api()
    {
        $settings = $this->getDefaultSettings('api');
        return view('admin.settings.api', compact('settings'));
    }

    /**
     * System Settings
     */
    public function system()
    {
        $settings = $this->getDefaultSettings('system');
        
        // Get system info
        $systemInfo = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'database' => DB::connection()->getDatabaseName(),
            'cache_driver' => config('cache.default'),
            'queue_driver' => config('queue.default'),
            'storage_path' => storage_path(),
            'disk_space' => $this->getDiskSpace(),
        ];

        return view('admin.settings.system', compact('settings', 'systemInfo'));
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        try {
            $group = $request->input('group', 'general');
            $settings = $request->except(['_token', '_method', 'group']);

            foreach ($settings as $key => $value) {
                // Determine type
                $type = 'string';
                if (is_bool($value) || in_array($value, ['true', 'false', '1', '0'])) {
                    $type = 'boolean';
                    $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                } elseif (is_numeric($value)) {
                    $type = 'integer';
                } elseif (is_array($value)) {
                    $type = 'json';
                    $value = json_encode($value);
                }

                Setting::set($key, $value, $type, $group);
            }

            // Clear cache
            Setting::clearCache();
            Cache::flush();

            return redirect()->back()->with('success', 'Settings updated successfully!');
        } catch (\Exception $e) {
            Log::error('Settings update error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error updating settings: ' . $e->getMessage());
        }
    }

    /**
     * Clear cache
     */
    public function clearCache()
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');

            return redirect()->back()->with('success', 'Cache cleared successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error clearing cache: ' . $e->getMessage());
        }
    }

    /**
     * Run optimization
     */
    public function optimize()
    {
        try {
            Artisan::call('optimize');
            Artisan::call('config:cache');
            Artisan::call('route:cache');

            return redirect()->back()->with('success', 'System optimized successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error optimizing system: ' . $e->getMessage());
        }
    }

    /**
     * Test email configuration
     */
    public function testEmail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email',
        ]);

        try {
            // Send test email
            \Mail::raw('This is a test email from your e-manager system.', function ($message) use ($request) {
                $message->to($request->test_email)
                    ->subject('Test Email - E-Manager');
            });

            return response()->json([
                'success' => true,
                'message' => 'Test email sent successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error sending test email: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get default settings for a group
     */
    protected function getDefaultSettings($group)
    {
        $defaults = $this->getDefaultSettingsArray();
        
        $settings = [];
        if (isset($defaults[$group])) {
            foreach ($defaults[$group] as $key => $default) {
                $dbSetting = Setting::where('key', $key)->first();
                $settings[$key] = [
                    'value' => $dbSetting ? $dbSetting->value : $default['value'],
                    'label' => $default['label'],
                    'type' => $default['type'],
                    'description' => $default['description'] ?? '',
                    'options' => $default['options'] ?? null,
                ];
            }
        }

        return $settings;
    }

    /**
     * Get default settings array
     */
    protected function getDefaultSettingsArray()
    {
        return [
            'general' => [
                'site_name' => [
                    'label' => 'Site Name',
                    'value' => 'E-Manager',
                    'type' => 'text',
                    'description' => 'The name of your website',
                ],
                'site_tagline' => [
                    'label' => 'Site Tagline',
                    'value' => 'Order Management System',
                    'type' => 'text',
                    'description' => 'A short description of your site',
                ],
                'site_email' => [
                    'label' => 'Site Email',
                    'value' => 'admin@example.com',
                    'type' => 'email',
                    'description' => 'Primary contact email',
                ],
                'site_phone' => [
                    'label' => 'Site Phone',
                    'value' => '+977-1-1234567',
                    'type' => 'text',
                    'description' => 'Primary contact phone',
                ],
                'site_address' => [
                    'label' => 'Business Address',
                    'value' => 'Kathmandu, Nepal',
                    'type' => 'textarea',
                    'description' => 'Your business address',
                ],
                'timezone' => [
                    'label' => 'Timezone',
                    'value' => 'Asia/Kathmandu',
                    'type' => 'select',
                    'description' => 'Default timezone',
                    'options' => ['Asia/Kathmandu', 'UTC', 'Asia/Kolkata', 'Asia/Dhaka'],
                ],
                'date_format' => [
                    'label' => 'Date Format',
                    'value' => 'Y-m-d',
                    'type' => 'select',
                    'description' => 'Default date format',
                    'options' => ['Y-m-d', 'd-m-Y', 'm/d/Y', 'd/m/Y'],
                ],
                'time_format' => [
                    'label' => 'Time Format',
                    'value' => 'H:i:s',
                    'type' => 'select',
                    'description' => 'Default time format',
                    'options' => ['H:i:s', 'h:i A', 'H:i'],
                ],
            ],
            'email' => [
                'mail_driver' => [
                    'label' => 'Mail Driver',
                    'value' => 'smtp',
                    'type' => 'select',
                    'description' => 'Email sending method',
                    'options' => ['smtp', 'sendmail', 'mailgun', 'ses', 'log'],
                ],
                'mail_host' => [
                    'label' => 'SMTP Host',
                    'value' => 'smtp.gmail.com',
                    'type' => 'text',
                    'description' => 'SMTP server hostname',
                ],
                'mail_port' => [
                    'label' => 'SMTP Port',
                    'value' => '587',
                    'type' => 'number',
                    'description' => 'SMTP server port',
                ],
                'mail_username' => [
                    'label' => 'SMTP Username',
                    'value' => '',
                    'type' => 'text',
                    'description' => 'SMTP authentication username',
                ],
                'mail_password' => [
                    'label' => 'SMTP Password',
                    'value' => '',
                    'type' => 'password',
                    'description' => 'SMTP authentication password',
                ],
                'mail_encryption' => [
                    'label' => 'Encryption',
                    'value' => 'tls',
                    'type' => 'select',
                    'description' => 'Email encryption method',
                    'options' => ['tls', 'ssl', 'none'],
                ],
                'mail_from_address' => [
                    'label' => 'From Email',
                    'value' => 'noreply@example.com',
                    'type' => 'email',
                    'description' => 'Default sender email',
                ],
                'mail_from_name' => [
                    'label' => 'From Name',
                    'value' => 'E-Manager',
                    'type' => 'text',
                    'description' => 'Default sender name',
                ],
            ],
            'payment' => [
                'enable_cod' => [
                    'label' => 'Enable Cash on Delivery',
                    'value' => true,
                    'type' => 'checkbox',
                    'description' => 'Allow COD payments',
                ],
                'cod_charge' => [
                    'label' => 'COD Charge',
                    'value' => '0',
                    'type' => 'number',
                    'description' => 'Additional charge for COD',
                ],
                'enable_online_payment' => [
                    'label' => 'Enable Online Payment',
                    'value' => false,
                    'type' => 'checkbox',
                    'description' => 'Allow online payments',
                ],
                'payment_gateway' => [
                    'label' => 'Payment Gateway',
                    'value' => 'esewa',
                    'type' => 'select',
                    'description' => 'Default payment gateway',
                    'options' => ['esewa', 'khalti', 'ime_pay', 'stripe', 'paypal'],
                ],
                'esewa_merchant_id' => [
                    'label' => 'eSewa Merchant ID',
                    'value' => '',
                    'type' => 'text',
                    'description' => 'eSewa merchant identifier',
                ],
                'khalti_public_key' => [
                    'label' => 'Khalti Public Key',
                    'value' => '',
                    'type' => 'text',
                    'description' => 'Khalti API public key',
                ],
            ],
            'notification' => [
                'enable_email_notifications' => [
                    'label' => 'Email Notifications',
                    'value' => true,
                    'type' => 'checkbox',
                    'description' => 'Send email notifications',
                ],
                'enable_sms_notifications' => [
                    'label' => 'SMS Notifications',
                    'value' => false,
                    'type' => 'checkbox',
                    'description' => 'Send SMS notifications',
                ],
                'notify_order_placed' => [
                    'label' => 'Notify on Order Placed',
                    'value' => true,
                    'type' => 'checkbox',
                    'description' => 'Send notification when order is placed',
                ],
                'notify_order_confirmed' => [
                    'label' => 'Notify on Order Confirmed',
                    'value' => true,
                    'type' => 'checkbox',
                    'description' => 'Send notification when order is confirmed',
                ],
                'notify_order_shipped' => [
                    'label' => 'Notify on Order Shipped',
                    'value' => true,
                    'type' => 'checkbox',
                    'description' => 'Send notification when order is shipped',
                ],
                'notify_order_delivered' => [
                    'label' => 'Notify on Order Delivered',
                    'value' => true,
                    'type' => 'checkbox',
                    'description' => 'Send notification when order is delivered',
                ],
            ],
            'shipping' => [
                'default_shipping_method' => [
                    'label' => 'Default Shipping Method',
                    'value' => 'standard',
                    'type' => 'select',
                    'description' => 'Default shipping option',
                    'options' => ['standard', 'express', 'overnight'],
                ],
                'free_shipping_threshold' => [
                    'label' => 'Free Shipping Threshold',
                    'value' => '5000',
                    'type' => 'number',
                    'description' => 'Minimum order amount for free shipping',
                ],
                'standard_shipping_cost' => [
                    'label' => 'Standard Shipping Cost',
                    'value' => '100',
                    'type' => 'number',
                    'description' => 'Cost for standard shipping',
                ],
                'express_shipping_cost' => [
                    'label' => 'Express Shipping Cost',
                    'value' => '200',
                    'type' => 'number',
                    'description' => 'Cost for express shipping',
                ],
            ],
            'tax' => [
                'enable_tax' => [
                    'label' => 'Enable Tax',
                    'value' => true,
                    'type' => 'checkbox',
                    'description' => 'Apply tax to orders',
                ],
                'tax_rate' => [
                    'label' => 'Tax Rate (%)',
                    'value' => '13',
                    'type' => 'number',
                    'description' => 'Default tax percentage',
                ],
                'tax_label' => [
                    'label' => 'Tax Label',
                    'value' => 'VAT',
                    'type' => 'text',
                    'description' => 'Tax display name',
                ],
                'currency' => [
                    'label' => 'Currency',
                    'value' => 'NPR',
                    'type' => 'select',
                    'description' => 'Default currency',
                    'options' => ['NPR', 'USD', 'EUR', 'GBP', 'INR'],
                ],
                'currency_symbol' => [
                    'label' => 'Currency Symbol',
                    'value' => 'Rs.',
                    'type' => 'text',
                    'description' => 'Currency symbol',
                ],
            ],
            'order' => [
                'order_prefix' => [
                    'label' => 'Order Number Prefix',
                    'value' => 'ORD',
                    'type' => 'text',
                    'description' => 'Prefix for order numbers',
                ],
                'order_number_length' => [
                    'label' => 'Order Number Length',
                    'value' => '6',
                    'type' => 'number',
                    'description' => 'Number of digits in order number',
                ],
                'auto_confirm_orders' => [
                    'label' => 'Auto Confirm Orders',
                    'value' => false,
                    'type' => 'checkbox',
                    'description' => 'Automatically confirm new orders',
                ],
                'order_cancellation_time' => [
                    'label' => 'Order Cancellation Time (hours)',
                    'value' => '24',
                    'type' => 'number',
                    'description' => 'Time limit for order cancellation',
                ],
            ],
            'security' => [
                'enable_2fa' => [
                    'label' => 'Enable Two-Factor Authentication',
                    'value' => false,
                    'type' => 'checkbox',
                    'description' => 'Require 2FA for admin login',
                ],
                'password_min_length' => [
                    'label' => 'Minimum Password Length',
                    'value' => '8',
                    'type' => 'number',
                    'description' => 'Minimum characters for passwords',
                ],
                'require_strong_password' => [
                    'label' => 'Require Strong Password',
                    'value' => true,
                    'type' => 'checkbox',
                    'description' => 'Enforce strong password policy',
                ],
                'session_lifetime' => [
                    'label' => 'Session Lifetime (minutes)',
                    'value' => '120',
                    'type' => 'number',
                    'description' => 'User session duration',
                ],
                'max_login_attempts' => [
                    'label' => 'Max Login Attempts',
                    'value' => '5',
                    'type' => 'number',
                    'description' => 'Maximum failed login attempts',
                ],
            ],
            'api' => [
                'enable_api' => [
                    'label' => 'Enable API',
                    'value' => true,
                    'type' => 'checkbox',
                    'description' => 'Enable REST API access',
                ],
                'api_rate_limit' => [
                    'label' => 'API Rate Limit (per minute)',
                    'value' => '60',
                    'type' => 'number',
                    'description' => 'Maximum API requests per minute',
                ],
                'api_key' => [
                    'label' => 'API Key',
                    'value' => '',
                    'type' => 'text',
                    'description' => 'API authentication key',
                ],
            ],
            'system' => [
                'maintenance_mode' => [
                    'label' => 'Maintenance Mode',
                    'value' => false,
                    'type' => 'checkbox',
                    'description' => 'Enable maintenance mode',
                ],
                'debug_mode' => [
                    'label' => 'Debug Mode',
                    'value' => false,
                    'type' => 'checkbox',
                    'description' => 'Enable debug mode (not recommended for production)',
                ],
                'log_level' => [
                    'label' => 'Log Level',
                    'value' => 'error',
                    'type' => 'select',
                    'description' => 'Logging verbosity level',
                    'options' => ['debug', 'info', 'warning', 'error', 'critical'],
                ],
                'auto_backup' => [
                    'label' => 'Auto Backup',
                    'value' => false,
                    'type' => 'checkbox',
                    'description' => 'Enable automatic database backups',
                ],
                'backup_frequency' => [
                    'label' => 'Backup Frequency',
                    'value' => 'daily',
                    'type' => 'select',
                    'description' => 'How often to backup',
                    'options' => ['hourly', 'daily', 'weekly', 'monthly'],
                ],
            ],
        ];
    }

    /**
     * Get disk space information
     */
    protected function getDiskSpace()
    {
        $total = disk_total_space('/');
        $free = disk_free_space('/');
        $used = $total - $free;

        return [
            'total' => $this->formatBytes($total),
            'used' => $this->formatBytes($used),
            'free' => $this->formatBytes($free),
            'percentage' => round(($used / $total) * 100, 2),
        ];
    }

    /**
     * Format bytes to human readable
     */
    protected function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
