<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\School;

class SchoolSettingsController extends Controller
{
    public function edit()
    {
        $admin = auth('admin')->user();
        $school = $admin->school;
        
        if (!$school && $admin->school_id) {
             $school = School::find($admin->school_id);
        }

        return view('admin.settings.school', compact('school'));
    }

    public function update(Request $request)
    {
        $admin = auth('admin')->user();
        $school = $admin->school;

        if (!$school && $admin->school_id) {
             $school = School::find($admin->school_id);
        }
        
        if (!$school) {
            return back()->with('error', 'School not found.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pincode' => 'nullable|string|max:20',
            'contact_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|max:2048', // 2MB Max
        ]);

        $data = $request->only([
            'name', 'address', 'city', 'state', 'pincode', 
            'contact_number', 'email', 'website'
        ]);

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($school->logo && Storage::disk('public')->exists($school->logo)) {
                Storage::disk('public')->delete($school->logo);
            }
            $data['logo'] = $request->file('logo')->store('schools/logos', 'public');
        }

        $school->update($data);

        return back()->with('success', 'School profile updated successfully.');
    }
}
