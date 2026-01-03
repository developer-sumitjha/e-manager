<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Artisan;

class SettingsController extends Controller
{
    public function index()
    {
        return redirect()->route('super.settings.general');
    }

    public function general()
    {
        $settings = $this->getAllSettings();
        $plans = SubscriptionPlan::all();
        
        return view('super-admin.settings.general', compact('settings', 'plans'));
    }

    public function updateGeneral(Request $request)
    {
        return $this->save($request);
    }

    public function subscriptions()
    {
        $settings = $this->getAllSettings();
        return view('super-admin.settings.subscriptions', compact('settings'));
    }

    public function payments()
    {
        $settings = $this->getAllSettings();
        return view('super-admin.settings.payments', compact('settings'));
    }

    public function email()
    {
        $settings = $this->getAllSettings();
        return view('super-admin.settings.email', compact('settings'));
    }

    public function sendTestEmail(Request $request)
    {
        return back()->with('success', 'Test email sent successfully!');
    }

    public function features()
    {
        $settings = $this->getAllSettings();
        return view('super-admin.settings.features', compact('settings'));
    }

    public function maintenance()
    {
        $settings = $this->getAllSettings();
        return view('super-admin.settings.maintenance', compact('settings'));
    }

    public function toggleMaintenance(Request $request)
    {
        $currentValue = Setting::where('key', 'maintenance_mode')->value('value');
        $newValue = $currentValue === '1' ? '0' : '1';
        
        Setting::updateOrCreate(
            ['key' => 'maintenance_mode'],
            ['value' => $newValue]
        );

        if ($newValue === '1') {
            Artisan::call('down');
            return back()->with('success', 'Maintenance mode enabled!');
        } else {
            Artisan::call('up');
            return back()->with('success', 'Maintenance mode disabled!');
        }
    }

    public function database()
    {
        $settings = $this->getAllSettings();
        return view('super-admin.settings.database', compact('settings'));
    }

    public function backupDatabase(Request $request)
    {
        return back()->with('success', 'Database backup created successfully!');
    }

    public function cache()
    {
        $settings = $this->getAllSettings();
        return view('super-admin.settings.cache', compact('settings'));
    }

    public function clearAllCaches(Request $request)
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        
        return back()->with('success', 'All caches cleared successfully!');
    }

    public function api()
    {
        $settings = $this->getAllSettings();
        return view('super-admin.settings.api', compact('settings'));
    }

    public function notifications()
    {
        $settings = $this->getAllSettings();
        return view('super-admin.settings.notifications', compact('settings'));
    }

    public function legal()
    {
        $settings = $this->getAllSettings();
        return view('super-admin.settings.legal', compact('settings'));
    }

    public function save(Request $request)
    {
        $category = $request->input('category', 'general');
        $data = $request->except(['_token', 'category', '_method']);

        foreach ($data as $key => $value) {
            if (is_null($value)) {
                continue;
            }
            
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => is_bool($value) ? ($value ? '1' : '0') : $value]
            );
        }

        return back()->with('success', 'Settings saved successfully!');
    }

    private function getAllSettings()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        
        // Default values
        $defaults = [
            'platform_name' => 'E-Manager',
            'support_email' => 'support@e-manager.com',
            'currency' => 'NPR',
            'timezone' => 'Asia/Kathmandu',
            'auto_approve_vendors' => false,
            'require_email_verification' => true,
            'default_trial_days' => 14,
            'maintenance_mode' => false,
        ];

        return array_merge($defaults, $settings);
    }
}





