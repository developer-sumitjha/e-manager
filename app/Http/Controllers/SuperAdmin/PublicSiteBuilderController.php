<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Setting;

class PublicSiteBuilderController extends Controller
{
    public function index()
    {
        $record = Setting::where('key', 'public_site_settings')->first();
        $defaults = [
            'branding' => [
                'site_name' => 'E‑Manager',
                'logo_url' => null,
                'favicon_url' => null,
                'primary_color' => '#6c5ce7',
            ],
            'hero' => [
                'title' => 'Run your business on one modern dashboard',
                'subtitle' => 'Inventory, orders, deliveries, accounting and analytics — all stitched together.',
                'cta_text' => 'Start free trial',
                'cta_link' => route('public.signup'),
                'image_url' => null,
            ],
            'sections' => [
                'show_about' => true,
                'show_features' => true,
                'show_clients' => true,
                'show_pricing' => true,
                'show_contact' => true,
            ],
        ];

        $settings = $record ? array_replace_recursive($defaults, json_decode($record->value, true) ?: []) : $defaults;

        return view('super-admin.site-builder.public', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'branding.site_name' => 'nullable|string|max:255',
            'branding.primary_color' => 'nullable|string|max:20',
            'hero.title' => 'nullable|string|max:255',
            'hero.subtitle' => 'nullable|string|max:500',
            'hero.cta_text' => 'nullable|string|max:80',
            'hero.cta_link' => 'nullable|string|max:255',
            'sections.show_about' => 'nullable|boolean',
            'sections.show_features' => 'nullable|boolean',
            'sections.show_clients' => 'nullable|boolean',
            'sections.show_pricing' => 'nullable|boolean',
            'sections.show_contact' => 'nullable|boolean',
        ]);

        // Persist settings
        $record = Setting::firstOrNew(['key' => 'public_site_settings']);
        $existing = $record->exists ? (json_decode($record->value, true) ?: []) : [];
        $merged = array_replace_recursive($existing, $validated);
        $record->value = json_encode($merged);
        $record->save();

        return back()->with('success', 'Public site settings saved successfully.');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'type' => 'required|in:logo,favicon,hero',
            'file' => 'required|image|max:2048',
        ]);

        $path = $request->file('file')->store('site-assets/public', 'public');

        $record = Setting::firstOrNew(['key' => 'public_site_settings']);
        $data = $record->exists ? (json_decode($record->value, true) ?: []) : [];

        $url = Storage::disk('public')->url($path);
        if (!isset($data['branding'])) { $data['branding'] = []; }
        if (!isset($data['hero'])) { $data['hero'] = []; }

        if ($request->type === 'logo') {
            $data['branding']['logo_url'] = $url;
        } elseif ($request->type === 'favicon') {
            $data['branding']['favicon_url'] = $url;
        } else {
            $data['hero']['image_url'] = $url;
        }

        $record->value = json_encode($data);
        $record->save();

        return response()->json(['success' => true, 'url' => $url]);
    }
}







