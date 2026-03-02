<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::allKeyed();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'school_name'       => 'required|string|max:255',
            'school_name_ar'    => 'nullable|string|max:255',
            'school_short_name' => 'required|string|max:20',
            'school_tagline'    => 'nullable|string|max:255',
            'school_tagline_ar' => 'nullable|string|max:255',
            'school_logo'       => 'nullable|image|max:2048',
        ]);

        $textFields = ['school_name', 'school_name_ar', 'school_short_name', 'school_tagline', 'school_tagline_ar'];
        foreach ($textFields as $field) {
            Setting::set($field, $request->input($field), 'branding');
        }

        if ($request->hasFile('school_logo')) {
            // Delete old logo if it exists
            $oldLogo = Setting::get('school_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
            $path = $request->file('school_logo')->store('settings', 'public');
            Setting::set('school_logo', $path, 'branding');
        }

        if ($request->boolean('remove_logo')) {
            $oldLogo = Setting::get('school_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
            Setting::set('school_logo', null, 'branding');
        }

        return redirect()->route('admin.settings.index')
                         ->with('success', __('messages.settings_saved'));
    }
}
