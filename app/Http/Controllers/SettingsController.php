<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateSettingsRequest;
use App\Jobs\UploadToCloudinary;
use App\Models\Setting;
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
    public function update(UpdateSettingsRequest $request)
    {
        $validated = $request->validated();

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
