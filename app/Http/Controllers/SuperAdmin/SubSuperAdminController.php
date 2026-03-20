<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\SuperAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SubSuperAdminController extends Controller
{
    public function index()
    {
        $subSuperAdmins = SuperAdmin::query()
            ->where('role', SuperAdmin::ROLE_SUB_SUPERADMIN)
            ->latest()
            ->paginate(10);

        return view('superadmin.sub-superadmins.index', compact('subSuperAdmins'));
    }

    public function create()
    {
        $sections = Setting::superAdminSidebarSections();
        $defaultPermissions = Setting::defaultSuperAdminSidebarPermissions()[SuperAdmin::ROLE_SUB_SUPERADMIN] ?? [];

        return view('superadmin.sub-superadmins.create', compact('sections', 'defaultPermissions'));
    }

    public function store(Request $request)
    {
        $sections = array_keys(Setting::superAdminSidebarSections());

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:super_admins,email'],
            'password' => ['required', 'string', 'min:8'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $permissions = [];
        foreach ($sections as $section) {
            $permissions[$section] = $request->boolean("permissions.$section");
        }

        $subSuperAdmin = new SuperAdmin();
        $subSuperAdmin->name = $validated['name'];
        $subSuperAdmin->email = $validated['email'];
        $subSuperAdmin->password = Hash::make($validated['password']);
        $subSuperAdmin->role = SuperAdmin::ROLE_SUB_SUPERADMIN;
        $subSuperAdmin->is_active = $request->boolean('is_active', true);
        $subSuperAdmin->assignSectionPermissions($permissions);
        $subSuperAdmin->save();

        return redirect()
            ->route('superadmin.sub-superadmins.index')
            ->with('success', 'Sub-superadmin created successfully.');
    }

    public function edit(SuperAdmin $subSuperAdmin)
    {
        abort_unless($subSuperAdmin->isSubSuperAdmin(), 404);

        $sections = Setting::superAdminSidebarSections();

        return view('superadmin.sub-superadmins.edit', compact('subSuperAdmin', 'sections'));
    }

    public function update(Request $request, SuperAdmin $subSuperAdmin)
    {
        abort_unless($subSuperAdmin->isSubSuperAdmin(), 404);

        $sections = array_keys(Setting::superAdminSidebarSections());

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('super_admins', 'email')->ignore($subSuperAdmin->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $permissions = [];
        foreach ($sections as $section) {
            $permissions[$section] = $request->boolean("permissions.$section");
        }

        $subSuperAdmin->name = $validated['name'];
        $subSuperAdmin->email = $validated['email'];
        $subSuperAdmin->is_active = $request->boolean('is_active', false);
        $subSuperAdmin->assignSectionPermissions($permissions);

        if (!empty($validated['password'])) {
            $subSuperAdmin->password = Hash::make($validated['password']);
        }

        $subSuperAdmin->save();

        return redirect()
            ->route('superadmin.sub-superadmins.index')
            ->with('success', 'Sub-superadmin updated successfully.');
    }
}
