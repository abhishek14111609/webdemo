<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class AdminSettingsController extends Controller
{
    /**
     * Show the settings form
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $settings = [
            'site_name' => setting('site_name', config('app.name')),
            'site_email' => setting('site_email', config('mail.from.address')),
            'site_phone' => setting('site_phone', ''),
            'site_address' => setting('site_address', ''),
            'currency' => setting('currency', 'USD'),
            'currency_symbol' => setting('currency_symbol', '$'),
            'timezone' => setting('timezone', config('app.timezone')),
            'date_format' => setting('date_format', 'Y-m-d'),
            'time_format' => setting('time_format', 'H:i:s'),
            'items_per_page' => setting('items_per_page', 10),
            'maintenance_mode' => setting('maintenance_mode', false),
            'maintenance_message' => setting('maintenance_message', 'Site is under maintenance. Please check back later.'),
        ];

        $timezones = \DateTimeZone::listIdentifiers(\DateTimeZone::ALL);
        
        return view('admin.settings', compact('settings', 'timezones'));
    }

    /**
     * Update the settings
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_email' => 'required|email|max:255',
            'site_phone' => 'nullable|string|max:50',
            'site_address' => 'nullable|string',
            'currency' => 'required|string|size:3',
            'currency_symbol' => 'required|string|max:5',
            'timezone' => 'required|timezone',
            'date_format' => 'required|string',
            'time_format' => 'required|string',
            'items_per_page' => 'required|integer|min:5|max:100',
            'maintenance_mode' => 'boolean',
            'maintenance_message' => 'nullable|string|max:500',
        ]);

        // Update each setting
        foreach ($validated as $key => $value) {
            setting([$key => $value]);
        }

        // Save all settings
        setting()->save();

        return redirect()->route('admin.settings')
            ->with('success', 'Settings updated successfully!');
    }
}
