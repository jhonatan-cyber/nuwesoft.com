<?php

namespace App\Http\Controllers;

use App\Jobs\UploadToCloudinary;
use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SettingsController extends Controller
{
    /**
     * Show the settings form.
     */
    public function index()
    {
        return Inertia::render('Dashboard/Settings/Index', [
            'settings' => Setting::getAll(),
        ]);
    }

    /**
     * Update settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name'      => 'nullable|string|max:255',
            'tagline'        => 'nullable|string|max:255',
            'email'          => 'nullable|email|max:255',
            'phone'          => 'nullable|string|max:50',
            'address'        => 'nullable|string|max:500',
            'social_facebook'=> 'nullable|url|max:500',
            'social_twitter' => 'nullable|url|max:500',
            'social_linkedin'=> 'nullable|url|max:500',
            'social_github'  => 'nullable|url|max:500',
            'social_youtube' => 'nullable|url|max:500',
            'social_tiktok'  => 'nullable|url|max:500',
            'logo'           => 'nullable|image|mimes:jpeg,jpg,png,gif,webp,svg|max:2048',
        ]);

        // Handle logo upload via queue
        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $path = $request->file('logo')->store('temp/uploads');

            UploadToCloudinary::dispatch(
                filePath: $path,
                folder: 'settings',
                modelType: 'settings',
            );
        }

        // Save all text fields
        $textFields = [
            'site_name', 'tagline', 'email', 'phone', 'address',
            'social_facebook', 'social_twitter', 'social_linkedin',
            'social_github', 'social_youtube', 'social_tiktok',
        ];

        foreach ($textFields as $field) {
            if (array_key_exists($field, $validated)) {
                Setting::setValue($field, $validated[$field] ?? '');
            }
        }

        event(new \App\Events\EntityUpdated('settings'));

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
