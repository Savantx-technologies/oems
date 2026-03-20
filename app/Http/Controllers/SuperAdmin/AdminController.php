<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\School;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\SchoolAdminCreated;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $admins = Admin::with('school')->latest()->paginate(10);
        $query = Admin::with('school');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('school_id')) {
            $query->where('school_id', $request->school_id);
        }

        $admins = $query->latest()->paginate(10)->withQueryString();
        $schools = School::orderBy('name')->get();

        return view('superadmin.admin.index', compact('admins', 'schools'));
    }

    public function create()
    {
        $schools = School::where('status', 'active')->get();
        return view('superadmin.admin.create', compact('schools'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'mobile' => 'nullable|string|max:15',
            'password' => 'required|string|min:8',
            'role' => 'required|in:superadmin,school_admin,sub_admin,invigilator,staff',
            'school_id' => 'nullable|exists:schools,id',
            'status' => 'required|in:active,blocked,pending',
            'login_method' => 'required|in:password,otp',
            'two_factor' => 'boolean',
        ]);

        $rawPassword = $validated['password'];
        $validated['password'] = Hash::make($validated['password']);
        $validated['two_factor'] = $request->has('two_factor');

        $admin = Admin::create($validated);

        if ($request->filled('school_id')) {
            $school = School::find($request->school_id);
            Mail::to($admin->email)->send(new SchoolAdminCreated($admin, $school, $rawPassword));
        }
        
        return redirect()->route('superadmin.admins.index')->with('success', 'Admin created successfully');
    }

    public function edit($id)
    {
        $admin = Admin::findOrFail($id);
        $schools = School::where('status', 'active')->get();
        return view('superadmin.admin.edit', compact('admin', 'schools'));
    }

    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $admin->id,
            'mobile' => 'nullable|string|max:15',
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:superadmin,school_admin,sub_admin,invigilator,staff',
            'school_id' => 'nullable|exists:schools,id',
            'status' => 'required|in:active,blocked,pending',
            'login_method' => 'required|in:password,otp',
            'two_factor' => 'boolean',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }
        $validated['two_factor'] = $request->has('two_factor');

        $admin->update($validated);

        return redirect()->route('superadmin.admins.index')->with('success', 'Admin updated successfully');
    }

    public function storeSchoolAdmin(Request $request, $schoolId)
    {
        $school = School::findOrFail($schoolId);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'mobile' => 'nullable|string|max:15',
            'password' => 'required|string|min:8',
            'role' => 'required|in:school_admin,sub_admin,invigilator,staff',
            'status' => 'required|in:active,blocked,pending',
            'aadhaar_number' => 'nullable|string',
            'aadhaar_name' => 'nullable|string',
            'aadhaar_dob' => 'nullable|date',
            'aadhaar_gender' => 'nullable|in:male,female,other',
            'login_method' => 'required|in:password,otp',
        ]);

        $rawPassword = $validated['password'];
        $validated['password'] = Hash::make($validated['password']);
        $validated['school_id'] = $school->id;

        $admin = Admin::create($validated);

        // Send Welcome Email
        Mail::to($admin->email)->send(new SchoolAdminCreated($admin, $school, $rawPassword));

        return redirect()->route('superadmin.schools.index')->with('success','School Admin Created Successfully');
    }

    public function updateSchoolAdmin(Request $request, $schoolId, $adminId)
    {
        $school = School::findOrFail($schoolId);
        $admin = Admin::where('school_id', $schoolId)->findOrFail($adminId);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $admin->id,
            'mobile' => 'nullable|string|max:15',
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:school_admin,sub_admin,invigilator,staff',
            'status' => 'required|in:active,blocked,pending',
            'aadhaar_number' => 'nullable|string',
            'aadhaar_name' => 'nullable|string',
            'aadhaar_dob' => 'nullable|date',
            'aadhaar_gender' => 'nullable|in:male,female,other',
            'login_method' => 'required|in:password,otp',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $admin->update($validated);

        return redirect()->route('superadmin.schools.index')->with('success', 'School Admin Updated Successfully');
    }
}
