<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StaffRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class StaffRequestController extends Controller
{
    /**
     * STEP 1 – Basic Info
     */
    public function step1()
    {
        $data = session('staff_wizard.step1', []);
        return view('admin.staff.wizard.step1', compact('data'));
    }

    public function postStep1(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|
                            unique:admins,email|
                            unique:users,email|
                            unique:staff_requests,email',
            'mobile'     => 'required|string|max:15',
            'staff_type' => 'required|in:teacher,admin_staff,librarian,lab_assistant',
            'photo'      => 'nullable|image|max:2048',
            'aadhaar_name'   => 'nullable|string|max:191',
            'aadhaar_number' => 'nullable|string|max:191',
            'aadhaar_dob'    => 'nullable|date',
            'aadhaar_gender' => 'nullable|in:male,female,other',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')
                ->store('staff-photos', 'public');
        } else {
            $validated['photo'] = session('staff_wizard.step1.photo');
        }

        session(['staff_wizard.step1' => $validated]);

        return redirect()->route('admin.staff.create.step2');
    }

    /**
     * STEP 2 – Professional Details
     */
    public function step2()
    {
        if (!session()->has('staff_wizard.step1')) {
            return redirect()->route('admin.staff.create.step1');
        }

        $step1 = session('staff_wizard.step1');
        $data  = session('staff_wizard.step2', []);

        return view('admin.staff.wizard.step2', compact('step1', 'data'));
    }

    public function postStep2(Request $request)
    {
        $staffType = session('staff_wizard.step1.staff_type');

        $rules = $staffType === 'teacher'
            ? [
                'qualification'          => 'required|string',
                'subject_specialization' => 'required|string',
                'experience_years'       => 'nullable|integer|min:0',
            ]
            : [
                'department'  => 'required|string',
                'designation' => 'required|string',
            ];

        $validated = $request->validate($rules);

        session(['staff_wizard.step2' => $validated]);

        return redirect()->route('admin.staff.create.step3');
    }

    /**
     * STEP 3 – Role & Access
     */
    public function step3()
    {
        if (!session()->has('staff_wizard.step2')) {
            return redirect()->route('admin.staff.create.step2');
        }

        $data = session('staff_wizard.step3', []);
        return view('admin.staff.wizard.step3', compact('data'));
    }

    public function postStep3(Request $request)
    {
        $validated = $request->validate([
            'role'     => 'required|in:staff,sub_admin,invigilator',
            'password' => 'required|string|min:8|confirmed',
            'login_method' => 'required|in:password,otp',
            'two_factor'   => 'boolean',
        ]);

        $validated['two_factor'] = $request->has('two_factor');
        // Hash password immediately and store it.
        $validated['password'] = Hash::make($validated['password']);
        session(['staff_wizard.step3' => $validated]); // Now stores the hashed password

        return redirect()->route('admin.staff.create.review');
    }

    /**
     * STEP 4 – Review
     */
    public function review()
    {
        if (!session()->has('staff_wizard.step3')) {
            return redirect()->route('admin.staff.create.step3');
        }

        $wizardData = session('staff_wizard');
        return view('admin.staff.wizard.review', compact('wizardData'));
    }

    /**
     * FINAL SUBMIT – Create Request
     */
    public function submit()
    {
        $data = session('staff_wizard');

        // Prevent duplicate pending request
        if (
            StaffRequest::where('email', $data['step1']['email'])
            ->where('status', 'pending_verification')
            ->exists()
        ) {
            return redirect()
                ->route('admin.staff.create.step1')
                ->with('error', 'A staff request for this email is already pending.');
        }

        StaffRequest::create([
            'school_id'            => Auth::user()->school_id,
            'requester_id'         => Auth::id(),
            'name'                 => $data['step1']['name'],
            'email'                => $data['step1']['email'],
            'mobile'               => $data['step1']['mobile'],
            'photo'                => $data['step1']['photo'] ?? null,
            'staff_type'           => $data['step1']['staff_type'],
            'aadhaar_number'       => $data['step1']['aadhaar_number'] ?? null,
            'aadhaar_dob'          => $data['step1']['aadhaar_dob'] ?? null,
            'aadhaar_gender'       => $data['step1']['aadhaar_gender'] ?? null,
            'professional_details' => $data['step2'],
            'role'                 => $data['step3']['role'],
            'password'             => $data['step3']['password'], 
            'status'               => 'pending_verification',
            'login_method'         => $data['step3']['login_method'],
            'two_factor'           => $data['step3']['two_factor'],
        ]);

        session()->forget('staff_wizard');

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Staff request submitted for SuperAdmin verification.');
    }
}
