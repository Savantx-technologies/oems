@extends('layouts.student')
@section('title','Marksheet')

@section('content')

@php
$first = $allAttempts->first();
$totalMax = 0;
$totalObtained = 0;
@endphp

@if(!$first)
<div class="max-w-4xl mx-auto mt-10 p-6 bg-red-50 border border-red-200 text-center rounded">
    <h2 class="text-lg font-semibold text-red-600">
        Result Not Available
    </h2>
    <p class="text-sm text-gray-600 mt-2">
        This result is not approved or does not exist.
    </p>
</div>
@return
@endif

<div class="max-w-5xl mx-auto bg-white p-10 border border-gray-300 shadow-xl relative">

    <!-- ================= SCHOOL HEADER ================= -->
    <div class="flex justify-between items-center border-b-4 border-gray-800 pb-6 mb-8">

        <!-- School Logo -->
        <div class="w-1/5">
            <img src="{{ asset('storage/'.$school->logo) }}" class="h-24 object-contain">
        </div>

        <!-- School Info -->
        <div class="w-3/5 text-center">
            <h1 class="text-3xl font-extrabold uppercase tracking-wide text-gray-800">
                {{ $school->name }}
            </h1>

            <p class="text-sm text-gray-600 mt-1">
                Affiliation No : {{ $school->code }}
            </p>

            <p class="text-sm text-gray-600">
                Ph: {{ $school->contact_number }} |
                Email: {{ $school->email }}
            </p>

            <h2 class="mt-4 text-lg font-bold text-gray-900">
                Academic Report
            </h2>

            
        </div>

        <!-- Student Photo -->
        <div class="w-1/5 text-right">
            <img src="{{ asset('storage/'.$student->photo) }}"
                class="h-28 w-24 object-cover border-2 border-gray-400 shadow">
        </div>
    </div>


    <!-- ================= STUDENT INFO ================= -->
    <div class="grid grid-cols-2 gap-6 text-sm mb-8 bg-gray-50 p-6 rounded border border-gray-200">

        <div><span class="font-semibold">Name :</span> {{ $student->name }}</div>
        <div><span class="font-semibold">Roll No :</span> {{ $student->roll_no }}</div>

        <div><span class="font-semibold">Class :</span> {{ $student->grade }}</div>
        <div><span class="font-semibold">Admission No :</span> {{ $student->admission_number }}</div>

        <div><span class="font-semibold">Address :</span> {{ $student->address }}</div>
        <div><span class="font-semibold">Date of Birth :</span>
            {{ \Carbon\Carbon::parse($student->dob)->format('d-m-Y') }}
        </div>

    </div>


    <!-- ================= SUBJECT TABLE ================= -->
    <table class="w-full text-sm border border-gray-300 mb-6">

        <thead class="bg-gray-800 text-white">
            <tr>
                <th class="border px-4 py-3 text-left">Subject</th>
                <th class="border px-4 py-3 text-center">Max Marks</th>
                <th class="border px-4 py-3 text-center">Marks Obtained</th>
                <th class="border px-4 py-3 text-center">Grade</th>
            </tr>
        </thead>

        <tbody class="bg-white">

            @foreach($allAttempts as $attempt)

            @php
            $max = $attempt->exam->total_marks;
            $score = $attempt->score;
            $passMarks = $attempt->exam->pass_marks ?? 33;

            $totalMax += $max;
            $totalObtained += $score;

            $percentage = $max > 0 ? ($score/$max)*100 : 0;

            if ($percentage >= 91) $grade = 'A+';
            elseif ($percentage >= 81) $grade = 'A';
            elseif ($percentage >= 71) $grade = 'B+';
            elseif ($percentage >= 61) $grade = 'B';
            elseif ($percentage >= 51) $grade = 'C+';
            elseif ($percentage >= 41) $grade = 'C';
            elseif ($percentage >= 33) $grade = 'D';
            else $grade = 'E';
            @endphp

            <tr class="hover:bg-gray-50">
                <td class="border px-4 py-2">{{ $attempt->exam->subject }}</td>
                <td class="border px-4 py-2 text-center">{{ $max }}</td>
                <td class="border px-4 py-2 text-center">{{ $score }}</td>
                <td class="border px-4 py-2 text-center font-semibold">{{ $grade }}</td>
            </tr>

            @endforeach

        </tbody>

        @php
        $overallPercentage = $totalMax > 0
        ? round(($totalObtained / $totalMax) * 100, 2)
        : 0;

        if ($overallPercentage >= 91) $overallGrade = 'A+';
        elseif ($overallPercentage >= 81) $overallGrade = 'A';
        elseif ($overallPercentage >= 71) $overallGrade = 'B+';
        elseif ($overallPercentage >= 61) $overallGrade = 'B';
        elseif ($overallPercentage >= 51) $overallGrade = 'C+';
        elseif ($overallPercentage >= 41) $overallGrade = 'C';
        elseif ($overallPercentage >= 33) $overallGrade = 'D';
        else $overallGrade = 'E';

        $finalStatus = $overallPercentage >= 33 ? 'PASS' : 'FAIL';
        @endphp

        <tfoot class="font-semibold">

            <tr class="bg-gray-100">
                <td class="border px-4 py-3">Total</td>
                <td class="border px-4 py-3 text-center">{{ $totalMax }}</td>
                <td class="border px-4 py-3 text-center">{{ $totalObtained }}</td>
                <td class="border px-4 py-3 text-center">{{ $overallGrade }}</td>
            </tr>

            <tr class="bg-yellow-50">
                <td class="border px-4 py-3 font-bold">Result</td>
                <td class="border px-4 py-3 text-center">
                    {{ $overallPercentage }} %
                </td>
                <td class="border px-4 py-3 text-center font-bold">
                    Grade : {{ $overallGrade }}
                </td>
                <td class="border px-4 py-3 text-center font-bold 
                    {{ $finalStatus == 'PASS' ? 'text-green-600' : 'text-red-600' }}">
                    {{ $finalStatus }}
                </td>
            </tr>

        </tfoot>

    </table>


    <!-- ================= FINAL SUMMARY ================= -->
    <div class="mt-10">

        <h3 class="font-bold mb-4 text-center text-gray-800 text-base">
            Grading Scale for Scholastic Areas
        </h3>

        <table class="w-full border border-gray-300 text-sm text-center">

            <tr class="bg-gray-200 font-semibold">
                <td class="border px-3 py-2">Marks Range (%)</td>
                <td class="border px-3 py-2">91-100</td>
                <td class="border px-3 py-2">81-90</td>
                <td class="border px-3 py-2">71-80</td>
                <td class="border px-3 py-2">61-70</td>
                <td class="border px-3 py-2">51-60</td>
                <td class="border px-3 py-2">41-50</td>
                <td class="border px-3 py-2">33-40</td>
                <td class="border px-3 py-2">Below 33</td>
            </tr>

            <tr>
                <td class="border px-3 py-2 font-semibold">Grade</td>
                <td class="border px-3 py-2">A+</td>
                <td class="border px-3 py-2">A</td>
                <td class="border px-3 py-2">B+</td>
                <td class="border px-3 py-2">B</td>
                <td class="border px-3 py-2">C+</td>
                <td class="border px-3 py-2">C</td>
                <td class="border px-3 py-2">D</td>
                <td class="border px-3 py-2">E (Fail)</td>
            </tr>

        </table>

    </div>

</div>

@endsection