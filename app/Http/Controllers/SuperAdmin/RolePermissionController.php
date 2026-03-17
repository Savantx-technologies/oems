<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    public function edit()
    {
        $roles = Setting::defaultAdminSidebarPermissions();
        $sections = Setting::adminSidebarSections();
        $permissions = Setting::getAdminSidebarPermissions();

        return view('superadmin.roles-permissions.index', compact('roles', 'sections', 'permissions'));
    }

    public function update(Request $request)
    {
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

        Setting::updateOrCreate(
            ['key' => Setting::ADMIN_SIDEBAR_PERMISSIONS_KEY],
            ['value' => $permissions]
        );

        return back()->with('success', 'Role permissions updated successfully.');
    }
}
