<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\School;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SchoolController extends Controller
{   
    public function index(Request $request)
    {
        $query = School::query();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('code')) {
            $query->where('code', 'like', '%' . $request->code . '%');
        }

        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $schools = $query->latest()->paginate(15)->withQueryString();
        return view('superadmin.schools.index', compact('schools'));
    }

    public function analytics()
    {
        $stats = [
            'total' => School::count(),
            'active' => School::where('status', 'active')->count(),
            'inactive' => School::where('status', 'inactive')->count(),
            'draft' => School::where('status', 'draft')->count(),
        ];

        $schoolsByBoard = School::select('board', DB::raw('count(*) as total'))
            ->whereNotNull('board')
            ->groupBy('board')
            ->orderBy('total', 'desc')
            ->get();

        $schoolsByType = School::select('type', DB::raw('count(*) as total'))
            ->whereNotNull('type')
            ->groupBy('type')
            ->orderBy('total', 'desc')
            ->get();

        $topSchoolsByStudents = School::withCount('students')
            ->orderBy('students_count', 'desc')
            ->take(5)
            ->get();
            
        $topSchoolsByExams = School::withCount('exams')
            ->orderBy('exams_count', 'desc')
            ->take(5)
            ->get();

        return view('superadmin.schools.analytics', compact(
            'stats', 'schoolsByBoard', 'schoolsByType', 'topSchoolsByStudents', 'topSchoolsByExams'
        ));
    }

    public function suspension(Request $request)
    {
        $query = School::query();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }

        // Exclude drafts, as suspension usually applies to live schools
        $query->where('status', '!=', 'draft');

        $schools = $query->latest()->paginate(15);

        return view('superadmin.schools.suspension', compact('schools'));
    }

    public function toggleSuspension($id)
    {
        $school = School::findOrFail($id);

        if ($school->status === 'active') {
            $school->update(['status' => 'inactive', 'is_active' => false]);
            $message = 'School has been suspended successfully.';
        } else {
            $school->update(['status' => 'active', 'is_active' => true]);
            $message = 'School suspension has been revoked. School is now active.';
        }

        return back()->with('success', $message);
    }

    public function create()
    {
        return view('superadmin.schools.create'); // Step 1: Create school
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'code' => 'required|string|unique:schools,code',
            'email' => 'nullable|email',
            'contact_number' => 'nullable|string|max:15',
            'type' => 'nullable|string',
            'board' => 'nullable|string',
            'registration_no' => 'nullable|string',
            'established_year' => 'nullable|integer',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'pincode' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // Determine status based on which button was clicked
        $action = $request->input('action');
        
        if ($action === 'draft') {
            $validated['status'] = 'draft';
        } else {
            // If not draft, set status based on the is_active selection
            $validated['status'] = $request->boolean('is_active') ? 'active' : 'inactive';
        }

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('school-logos', 'public');
        }

        $school = School::create($validated);

        if ($action === 'draft') {
            return redirect()->route('superadmin.dashboard')->with('success', 'School saved as draft successfully.');
        }

        return redirect()->route('superadmin.schools.create-admin', $school->id);
    }

    public function createAdmin($schoolId)
    {
        $school = School::findOrFail($schoolId);
        return view('superadmin.schools.create-admin', compact('school'));
    }

    public function edit($id)
    {
        $school = School::findOrFail($id);
        return view('superadmin.schools.edit', compact('school'));
    }

    public function update(Request $request, $id)
    {
        $school = School::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'code' => 'required|string|unique:schools,code,' . $school->id,
            'email' => 'nullable|email',
            'contact_number' => 'nullable|string|max:15',
            'type' => 'nullable|string',
            'board' => 'nullable|string',
            'registration_no' => 'nullable|string',
            'established_year' => 'nullable|integer',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'pincode' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // Sync status with is_active for consistency (unless we want to keep 'draft' status logic here too)
        $validated['status'] = $request->boolean('is_active') ? 'active' : 'inactive';

        if ($request->hasFile('logo')) {
            // Delete old logo if it exists
            if ($school->logo) {
                Storage::disk('public')->delete($school->logo);
            }
            $validated['logo'] = $request->file('logo')->store('school-logos', 'public');
        }

        $school->update($validated);

        return redirect()->route('superadmin.schools.edit-admin', $school->id)->with('success', 'School updated successfully. Please update Admin details.');
    }

    public function editAdmin($schoolId)
    {
        $school = School::with('admins')->findOrFail($schoolId);
        // Assuming there is one primary school admin, or we take the first one found.
        $admin = $school->admins()->where('role', 'school_admin')->first();

        if (!$admin) {
            return redirect()->route('superadmin.schools.create-admin', $school->id)->with('info', 'No School Admin found. Please create one.');
        }

        return view('superadmin.schools.edit-admin', compact('school', 'admin'));
    }
}
