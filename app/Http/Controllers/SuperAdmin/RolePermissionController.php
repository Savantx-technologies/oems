<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\SchoolSetting;
use App\Models\Setting;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    public function edit(Request $request)
    {
        $roles = Setting::defaultAdminSidebarPermissions();
        $sections = Setting::adminSidebarSections();
        $schools = School::orderBy('name')->get(['id', 'name']);
        $selectedSchoolId = $request->integer('school_id');

        if (!$selectedSchoolId && $schools->isNotEmpty()) {
            $selectedSchoolId = $schools->first()->id;
        }

        $permissions = SchoolSetting::getAdminSidebarPermissionsForSchool($selectedSchoolId);

        return view('superadmin.roles-permissions.index', compact(
            'roles',
            'sections',
            'permissions',
            'schools',
            'selectedSchoolId'
        ));
    }
    
    public function update(Request $request)
    {
        $request->validate([
            'school_id' => 'required|exists:schools,id',
        ]);

        $roles = array_keys(Setting::defaultAdminSidebarPermissions());
        $sections = array_keys(Setting::adminSidebarSections());
        $defaults = Setting::defaultAdminSidebarPermissions();

        $permissions = [];

        foreach ($roles as $role) {
            foreach ($sections as $section) {
                $permissions[$role][$section] = $request->boolean("permissions.$role.$section", false);
            }
        }

        $permissions[ 'school_admin']['dashboard'] = true;
        $permissions['school_admin']['logs'] = true;

        foreach ($defaults as $role => $defaultSections) {
            foreach (array_keys($defaultSections) as $section) {
                $permissions[$role][$section] = (bool) ($permissions[$role][$section] ?? false);
            }
        }

        SchoolSetting::updateOrCreate(
            [
                'school_id' => $request->integer('school_id'),
                'key' => SchoolSetting::ADMIN_SIDEBAR_PERMISSIONS_KEY,
            ],
            ['value' => $permissions]
        );

        return back()
            ->with('success', 'Role permissions updated successfully.')
            ->withInput();
    }
}
