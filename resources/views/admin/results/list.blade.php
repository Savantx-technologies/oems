@extends('layouts.admin')
@section('title','Final Results')

@section('content')

<div class="max-w-7xl mx-auto space-y-10">

    {{-- ================================
    CLASS CARD VIEW
    ================================ --}}
    @if(!isset($class))

    <div class="bg-white border rounded-xl shadow-sm p-6">

        <h2 class="text-lg font-semibold text-gray-800 mb-6 text-center">
            Select Class For View Result
        </h2>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">

            @forelse($classes as $className => $classAttempts)

            <a href="{{ route('admin.results.list',['class'=>$className]) }}"
                class="bg-indigo-50 border border-indigo-200 rounded-xl p-6 text-center hover:shadow-md transition">

                <div class="text-xl font-bold text-indigo-600">
                    Class {{ $className }}
                </div>

                <div class="text-sm text-gray-500 mt-2">
                    View Results
                </div>

            </a>

            @empty

            <div class="col-span-full text-center text-gray-500">
                No approved results found.
            </div>

            @endforelse

        </div>

    </div>

    {{-- ================================
    STUDENT MARKSHEET VIEW
    ================================ --}}
    @else

    <div class="flex justify-between items-center bottom-border-0 mb-6">
        <h2 class="text-xl font-semibold text-gray-800">
            Class {{ $class }} Final Results
        </h2>

        <a href="{{ route('admin.results.list') }}" class="text-sm text-indigo-600 hover:underline">
            ← Back to Classes
        </a>
    </div>


    @forelse($students as $studentId => $studentAttempts)

    @php
    $student = $studentAttempts->first()->user;
    $totalMax = 0;
    $totalObtained = 0;
    @endphp

    <div class="bg-white border rounded-xl shadow-sm p-8">

        <!-- Header -->
        <div class="text-center border-b pb-4 mb-6">
            <h2 class="text-lg font-bold text-gray-800">
                Final Examination Result
            </h2>
        </div>

        <!-- Student Info -->
        <div class="flex justify-between mb-6">
            <div>
                <p><strong>Name:</strong> {{ $student->name }}</p>
                <p><strong>Roll No:</strong> {{ $student->roll_no ?? 'N/A' }}</p>
            </div>
        </div>

        <!-- Subject Table -->
        <table class="min-w-full text-sm border">

            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-4 py-2 text-left">Subject</th>
                    <th class="border px-4 py-2 text-left">Max Marks</th>
                    <th class="border px-4 py-2 text-left">Obtained</th>
                    <th class="border px-4 py-2 text-left">Result</th>
                </tr>
            </thead>

            <tbody>

                @foreach($studentAttempts as $attempt)

                @php
                $max = $attempt->exam->total_marks;
                $score = $attempt->score;

                $totalMax += $max;
                $totalObtained += $score;

                $percentage = $max > 0 ? ($score / $max) * 100 : 0;

                // Grade Calculation
                if ($percentage >= 90) {
                $grade = 'A+';
                $color = 'bg-green-100 text-green-700';
                } elseif ($percentage >= 75) {
                $grade = 'A';
                $color = 'bg-blue-100 text-blue-700';
                } elseif ($percentage >= 60) {
                $grade = 'B';
                $color = 'bg-indigo-100 text-indigo-700';
                } elseif ($percentage >= 45) {
                $grade = 'C';
                $color = 'bg-yellow-100 text-yellow-700';
                } elseif ($percentage >= 33) {
                $grade = 'D';
                $color = 'bg-orange-100 text-orange-700';
                } else {
                $grade = 'F';
                $color = 'bg-red-100 text-red-700';
                }
                @endphp

                <tr>
                    <td class="border px-4 py-2">
                        {{ $attempt->exam->subject }}
                    </td>

                    <td class="border px-4 py-2">
                        {{ $max }}
                    </td>

                    <td class="border px-4 py-2">
                        {{ $score }}
                    </td>

                    <td class="border px-4 py-2">
                        <span class="px-3 py-1 text-xs font-semibold rounded {{ $color }}">
                            {{ $grade }}
                        </span>
                    </td>
                </tr>

                @endforeach

            </tbody>

        </table>

        <!-- Final Summary -->
        @php
        $percentage = $totalMax > 0
        ? round(($totalObtained / $totalMax) * 100, 2)
        : 0;

        // Final Grade Based On Overall Percentage
        if ($percentage >= 90) {
        $finalGrade = 'A+';
        $gradeColor = 'text-green-600';
        } elseif ($percentage >= 75) {
        $finalGrade = 'A';
        $gradeColor = 'text-blue-600';
        } elseif ($percentage >= 60) {
        $finalGrade = 'B';
        $gradeColor = 'text-indigo-600';
        } elseif ($percentage >= 45) {
        $finalGrade = 'C';
        $gradeColor = 'text-yellow-600';
        } elseif ($percentage >= 33) {
        $finalGrade = 'D';
        $gradeColor = 'text-orange-600';
        } else {
        $finalGrade = 'F';
        $gradeColor = 'text-red-600';
        }

        $finalStatus = $percentage >= 33 ? 'Pass' : 'Fail';
        @endphp

        <div class="mt-6 text-right space-y-1">
            <p><strong>Total Marks:</strong> {{ $totalMax }}</p>
            <p><strong>Total Obtained:</strong> {{ $totalObtained }}</p>
            <p><strong>Percentage:</strong> {{ $percentage }}%</p>

            <p class="font-semibold {{ $gradeColor }}">
                Overall Grade: {{ $finalGrade }}
            </p>

            <p class="mt-3 text-lg font-semibold
        {{ $finalStatus == 'Pass' ? 'text-green-600' : 'text-red-600' }}">
                Final Result: {{ $finalStatus }}
            </p>
        </div>

    </div>

    @empty

    <div class="text-center text-gray-500 py-10">
        No results found for this class.
    </div>

    @endforelse

    @endif

</div>

@endsection