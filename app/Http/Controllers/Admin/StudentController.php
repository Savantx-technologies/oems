<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function index()
    {
        $students = User::where('role', 'student')
            ->where('school_id', auth('admin')->user()->school_id)
            ->latest()
            ->paginate(10);

        return view('admin.students.index', compact('students'));
    }
    public function create()
    {
        return view('admin.students.create');
    }

    /**
     * Store a newly created student in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'admission_number' => ['nullable', 'string', 'max:50'],
            'grade' => ['nullable', 'string', 'max:50'],
            'phone_number' => ['required', 'string', 'max:20'],
            'aadhar_number' => ['nullable', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:1000'],
            'photo' => ['nullable', 'image', 'max:2048'], // Max 2MB
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('students/photos', 'public');
        }

        // Create the User account with student details
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'photo' => $photoPath,
            'phone_number' => $validated['phone_number'],
            'aadhar_number' => $validated['aadhar_number'],
            'address' => $validated['address'],
            'role' => 'student',
            'school_id' => auth('admin')->user()->school_id,
            'status' => 'active',
            'admission_number' => $validated['admission_number'],
            'grade' => $validated['grade'],
        ]);

        return redirect()->route('admin.students.create')->with('success', 'Student added successfully.');
    }

    /**
     * Show the form for editing the specified student.
     */
    public function edit(string $id)
    {
        $student = User::where('role', 'student')
            ->where('school_id', auth('admin')->user()->school_id)
            ->findOrFail($id);

        return view('admin.students.edit', compact('student'));
    }

    /**
     * Update the specified student in storage.
     */
    public function update(Request $request, string $id)
    {
        $student = User::where('role', 'student')
            ->where('school_id', auth('admin')->user()->school_id)
            ->findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $student->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'admission_number' => ['nullable', 'string', 'max:50'],
            'grade' => ['nullable', 'string', 'max:50'],
            'status' => ['required', 'in:active,inactive'],
            'phone_number' => ['required', 'string', 'max:20'],
            'aadhar_number' => ['nullable', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:1000'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'admission_number' => $validated['admission_number'],
            'grade' => $validated['grade'],
            'status' => $validated['status'],
            'phone_number' => $validated['phone_number'],
            'aadhar_number' => $validated['aadhar_number'],
            'address' => $validated['address'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        if ($request->hasFile('photo')) {
            if ($student->photo && Storage::disk('public')->exists($student->photo)) {
                Storage::disk('public')->delete($student->photo);
            }
            $data['photo'] = $request->file('photo')->store('students/photos', 'public');
        }

        $student->update($data);

        return redirect()->route('admin.students.edit', $student->id)->with('success', 'Student updated successfully.');
    }

    /**
     * Show the form for bulk creating students.
     */
    public function bulkCreate()
    {
        return view('admin.students.bulk_create');
    }

    /**
     * Store multiple students from CSV.
     */
    public function bulkStore(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('file');

        if (($handle = fopen($file->getPathname(), 'r')) !== false) {
            DB::beginTransaction();
            try {
                $header = fgetcsv($handle);
                $imported = 0;
                $skipped = 0;
                $failed = 0;

                while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                    // Basic check for required columns (Name, Email, Password, Phone, Address)
                    if (count($data) < 5 || empty(trim($data[0])) || empty(trim($data[1])) || empty(trim($data[2])) || empty(trim($data[3])) || empty(trim($data[4]))) {
                        $failed++;
                        continue;
                    }

                    // Skip if email already exists
                    if (User::where('email', trim($data[1]))->exists()) {
                        $skipped++;
                        continue;
                    }

                    User::create([
                        'name' => trim($data[0]),
                        'email' => trim($data[1]),
                        'password' => Hash::make(trim($data[2])),
                        'phone_number' => trim($data[3]),
                        'address' => trim($data[4]),
                        'admission_number' => isset($data[5]) ? trim($data[5]) : null,
                        'grade' => isset($data[6]) ? trim($data[6]) : null,
                        'aadhar_number' => isset($data[7]) ? trim($data[7]) : null,
                        'role' => 'student',
                        'school_id' => auth('admin')->user()->school_id,
                        'status' => 'active',
                    ]);
                    $imported++;
                }

                DB::commit();
                $report = ['imported' => $imported, 'skipped' => $skipped, 'failed' => $failed];
                return redirect()->route('admin.students.index')->with('bulk_report', $report);
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->with('error', 'An error occurred during the import process: ' . $e->getMessage());
            } finally {
                fclose($handle);
            }
        }

        return back()->with('error', 'Could not open the uploaded file.');
    }

    /**
     * Download a sample CSV file for bulk import.
     */
    public function downloadSample()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="student_import_sample.csv"',
        ];

        $columns = ['Name', 'Email', 'Password', 'Phone Number', 'Address', 'Admission Number', 'Grade', 'Aadhar Number'];

        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            fputcsv($file, ['John Doe', 'john.doe@example.com', 'Password@123', '9876543210', '123 Main St, City', 'ADM001', '10', '123456789012']);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show the batch assignment page.
     */
    public function batchAssign(Request $request)
    {
        $admin = auth('admin')->user();

        $query = User::where('role', 'student')
            ->where('school_id', $admin->school_id);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('admission_number', 'like', "%{$search}%");
            });
        }

        $students = $query->latest()->paginate(50);

        return view('admin.students.batch_assignment', compact('students'));
    }

    /**
     * Process bulk batch update.
     */
    public function batchUpdate(Request $request)
    {
        $request->validate([
            'student_ids' => 'required|array',
            'grade' => 'required|string|max:50',
        ]);

        User::whereIn('id', $request->student_ids)->update(['grade' => $request->grade]);

        return back()->with('success', 'Batch/Grade assigned successfully to selected students.');
    }
}
