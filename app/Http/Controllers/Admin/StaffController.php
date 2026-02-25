<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $admin = auth('admin')->user();
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

        return view('admin.staff.index', compact('staff'));
    }
}
