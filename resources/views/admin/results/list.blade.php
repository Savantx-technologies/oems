@extends('layouts.admin')
@section('title','Final Results')

@section('content')

<div class="max-w-7xl mx-auto space-y-10">
    @if(!isset($class))
        <div class="rounded-3xl border bg-white p-4 shadow-sm sm:p-6">
            <h2 class="mb-6 text-center text-lg font-semibold text-gray-800">Select Class For View Result</h2>

            <div class="grid grid-cols-2 gap-4 md:grid-cols-4 lg:grid-cols-6">
                @forelse($classes as $className => $classAttempts)
                    <a href="{{ route('admin.results.list',['class'=>$className]) }}"
                        class="rounded-2xl border border-indigo-200 bg-indigo-50 p-5 text-center transition hover:shadow-md">
                        <div class="text-xl font-bold text-indigo-600">Class {{ $className }}</div>
                        <div class="mt-2 text-sm text-gray-500">View Results</div>
                    </a>
                @empty
                    <div class="col-span-full text-center text-gray-500">No approved results found.</div>
                @endforelse
            </div>
        </div>
    @else
        <div class="mb-6 flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Class {{ $class }} Final Results</h2>
            <a href="{{ route('admin.results.list') }}" class="text-sm text-indigo-600 hover:underline">Back to Classes</a>
        </div>

        @forelse($students as $studentId => $studentAttempts)
            @php
                $student = $studentAttempts->first()->user;
                $totalMax = 0;
                $totalObtained = 0;
            @endphp

            <div class="rounded-3xl border bg-white p-4 shadow-sm sm:p-8">
                <div class="mb-6 border-b pb-4 text-center">
                    <h2 class="text-lg font-bold text-gray-800">Final Examination Result</h2>
                </div>

                <div class="mb-6 flex flex-col gap-2 sm:flex-row sm:justify-between">
                    <div>
                        <p><strong>Name:</strong> {{ $student->name }}</p>
                        <p><strong>Roll No:</strong> {{ $student->roll_no ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="space-y-4 sm:hidden">
                    @foreach($studentAttempts as $attempt)
                        @php
                            $max = $attempt->exam->total_marks;
                            $score = $attempt->score;
                            $totalMax += $max;
                            $totalObtained += $score;
                            $percentage = $max > 0 ? ($score / $max) * 100 : 0;

                            if ($percentage >= 90) { $grade = 'A+'; $color = 'bg-green-100 text-green-700'; }
                            elseif ($percentage >= 75) { $grade = 'A'; $color = 'bg-blue-100 text-blue-700'; }
                            elseif ($percentage >= 60) { $grade = 'B'; $color = 'bg-indigo-100 text-indigo-700'; }
                            elseif ($percentage >= 45) { $grade = 'C'; $color = 'bg-yellow-100 text-yellow-700'; }
                            elseif ($percentage >= 33) { $grade = 'D'; $color = 'bg-orange-100 text-orange-700'; }
                            else { $grade = 'F'; $color = 'bg-red-100 text-red-700'; }
                        @endphp

                        <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="text-sm font-semibold text-gray-800">{{ $attempt->exam->subject }}</div>
                                <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $color }}">{{ $grade }}</span>
                            </div>
                            <div class="mt-4 grid grid-cols-2 gap-3 text-sm text-gray-700">
                                <div>
                                    <div class="text-[11px] uppercase tracking-wide text-gray-400">Max Marks</div>
                                    <div class="mt-1">{{ $max }}</div>
                                </div>
                                <div>
                                    <div class="text-[11px] uppercase tracking-wide text-gray-400">Obtained</div>
                                    <div class="mt-1">{{ $score }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="hidden overflow-x-auto sm:block">
                    <table class="min-w-full border text-sm">
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

                                    if ($percentage >= 90) { $grade = 'A+'; $color = 'bg-green-100 text-green-700'; }
                                    elseif ($percentage >= 75) { $grade = 'A'; $color = 'bg-blue-100 text-blue-700'; }
                                    elseif ($percentage >= 60) { $grade = 'B'; $color = 'bg-indigo-100 text-indigo-700'; }
                                    elseif ($percentage >= 45) { $grade = 'C'; $color = 'bg-yellow-100 text-yellow-700'; }
                                    elseif ($percentage >= 33) { $grade = 'D'; $color = 'bg-orange-100 text-orange-700'; }
                                    else { $grade = 'F'; $color = 'bg-red-100 text-red-700'; }
                                @endphp
                                <tr>
                                    <td class="border px-4 py-2">{{ $attempt->exam->subject }}</td>
                                    <td class="border px-4 py-2">{{ $max }}</td>
                                    <td class="border px-4 py-2">{{ $score }}</td>
                                    <td class="border px-4 py-2">
                                        <span class="rounded px-3 py-1 text-xs font-semibold {{ $color }}">{{ $grade }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @php
                    $percentage = $totalMax > 0 ? round(($totalObtained / $totalMax) * 100, 2) : 0;
                    if ($percentage >= 90) { $finalGrade = 'A+'; $gradeColor = 'text-green-600'; }
                    elseif ($percentage >= 75) { $finalGrade = 'A'; $gradeColor = 'text-blue-600'; }
                    elseif ($percentage >= 60) { $finalGrade = 'B'; $gradeColor = 'text-indigo-600'; }
                    elseif ($percentage >= 45) { $finalGrade = 'C'; $gradeColor = 'text-yellow-600'; }
                    elseif ($percentage >= 33) { $finalGrade = 'D'; $gradeColor = 'text-orange-600'; }
                    else { $finalGrade = 'F'; $gradeColor = 'text-red-600'; }
                    $finalStatus = $percentage >= 33 ? 'Pass' : 'Fail';
                @endphp

                <div class="mt-6 space-y-1 text-left sm:text-right">
                    <p><strong>Total Marks:</strong> {{ $totalMax }}</p>
                    <p><strong>Total Obtained:</strong> {{ $totalObtained }}</p>
                    <p><strong>Percentage:</strong> {{ $percentage }}%</p>
                    <p class="font-semibold {{ $gradeColor }}">Overall Grade: {{ $finalGrade }}</p>
                    <p class="mt-3 text-lg font-semibold {{ $finalStatus == 'Pass' ? 'text-green-600' : 'text-red-600' }}">Final Result: {{ $finalStatus }}</p>
                </div>
            </div>
        @empty
            <div class="py-10 text-center text-gray-500">No results found for this class.</div>
        @endforelse
    @endif
</div>

@endsection
