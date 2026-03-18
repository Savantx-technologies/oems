<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\StaffRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $admin = auth('admin')->user();
        $tab = $request->get('tab', 'staff');
        $status = $request->get('status', 'all');

        if ($tab === 'requests') {
            $query = StaffRequest::where('school_id', $admin->school_id);

            if ($status === 'pending') {
                $query->where('status', 'pending_verification');
            } elseif (in_array($status, ['approved', 'rejected'], true)) {
                $query->where('status', $status);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('staff_type', 'like', "%{$search}%")
                        ->orWhere('role', 'like', "%{$search}%");
                });
            }

            $requests = $query->latest()->paginate(20);

            return view('admin.staff.index', compact('tab', 'status', 'requests'));
        }

        $query = Admin::where('school_id', $admin->school_id)
            ->where('role', '!=', 'school_admin');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('staff_type', 'like', "%{$search}%");
            });
        }

        $staff = $query->latest()->paginate(20);

        return view('admin.staff.index', compact('tab', 'status', 'staff'));
    }
}
