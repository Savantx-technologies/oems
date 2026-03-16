@extends('layouts.admin')
@section('title','Manual Exam Checking')

@section('content')

<div class="max-w-7xl mx-auto space-y-10">

    {{-- ================================
    CLASS CARD VIEW
    ================================ --}}
    @if(!isset($class))

    <div class="bg-white border rounded-xl shadow-sm p-6">

        <h2 class="text-lg font-semibold text-gray-800 mb-6 text-center">
            Select Class For Manual Checking
        </h2>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">

            @forelse($classes as $className => $classAttempts)

            <a href="{{ route('admin.results.attempts',['class'=>$className]) }}"
                class="bg-indigo-50 border border-indigo-200 rounded-xl p-6 text-center hover:shadow-md transition">

                <div class="text-xl font-bold text-indigo-600">
                    Class {{ $className }}
                </div>

                <div class="text-sm text-gray-500 mt-2">
                    {{ $classAttempts->count() }} Attempts
                </div>

            </a>

            @empty

            <div class="col-span-full text-center text-gray-500">
                No attempts found.
            </div>

            @endforelse

        </div>

    </div>

    {{-- ================================
    STUDENT ATTEMPTS VIEW
    ================================ --}}
    @else

    <div class="flex justify-between items-center mb-6">

        <h2 class="text-xl font-semibold text-gray-800">
            Class {{ $class }} Student Attempts
        </h2>

        <a href="{{ route('admin.results.attempts') }}" class="text-sm text-indigo-600 hover:underline">

            ← Back to Classes

        </a>

    </div>


    <div class="bg-white shadow-lg rounded-xl border border-gray-100 overflow-hidden">

        <div class="overflow-x-auto">

            <table class="min-w-full text-sm">

                <thead class="bg-gray-50 text-gray-600 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-6 py-4 text-left">Student</th>
                        <th class="px-6 py-4 text-left">Exam</th>
                        <th class="px-6 py-4 text-left">Status</th>
                        <th class="px-6 py-4 text-right">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">

                    @forelse($students as $studentAttempts)

                    @foreach($studentAttempts as $attempt)

                    <tr class="hover:bg-gray-50 transition">

                        <!-- Student -->
                        <td class="px-6 py-4 font-medium text-gray-800">
                            {{ $attempt->user->name }}
                        </td>

                        <!-- Exam -->
                        <td class="px-6 py-4 text-gray-600">
                            {{ $attempt->exam->title }}
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4">

                            @if($attempt->approval_status == 'pending')
                            <span class="px-3 py-1 text-xs bg-yellow-100 text-yellow-700 rounded-full">
                                Pending
                            </span>

                            @elseif($attempt->approval_status == 'approved')
                            <span class="px-3 py-1 text-xs bg-green-100 text-green-700 rounded-full">
                                Approved
                            </span>

                            @else
                            <span class="px-3 py-1 text-xs bg-red-100 text-red-700 rounded-full">
                                Rejected
                            </span>
                            @endif

                        </td>

                        <!-- Action -->
                        <td class="px-6 py-4 text-right">

                            <a href="{{ route('admin.results.viewAttempt',$attempt->id) }}"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg shadow hover:bg-blue-700 transition">

                                Check Paper

                            </a>

                        </td>

                    </tr>

                    @endforeach

                    @empty

                    <tr>
                        <td colspan="4" class="text-center py-6 text-gray-500">
                            No attempts found for this class.
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    @endif

</div>

@endsection