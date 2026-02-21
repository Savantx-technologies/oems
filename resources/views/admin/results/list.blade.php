@extends('layouts.admin')
@section('title','Result Management')

@section('content')

<div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">

    <!-- Header -->
    <div class="flex flex-wrap items-center justify-center gap-3 px-6 py-4 border-b">

        <h2 class="text-lg font-semibold text-gray-800">
            Result Management
        </h2>

    </div>


    {{-- ===============================
    CLASS CARD VIEW
    ================================ --}}
    @if(!request()->filled('class'))

    @php
    $grouped = $attempts
    ->whereIn('approval_status',['approved','rejected'])
    ->groupBy(fn($item) => $item->exam->class);
    @endphp

    <div class="p-6 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">

        @forelse($grouped as $class => $classAttempts)

        <a href="{{ route('admin.results.list', ['class' => $class]) }}"
            class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition p-6 text-center">

            <div class="text-xl font-bold text-indigo-600 bg-body-secondary">
                Class {{ $class }}
            </div>

            <div class="text-sm text-gray-500 mt-2">
                {{ $classAttempts->count() }} Results
            </div>

        </a>

        @empty

        <div class="col-span-full text-center text-gray-500">
            No results available.
        </div>

        @endforelse

    </div>


    {{-- ===============================
    RESULT TABLE VIEW
    ================================ --}}
    @else

    @php
    $class = request('class');

    $classResults = $attempts
    ->whereIn('approval_status',['approved','rejected'])
    ->where('exam.class', $class);
    @endphp


    <!-- Sub Header -->
    <div class="px-6 py-4 border-b bg-gray-50 flex justify-between items-center">

        <h2 class="text-lg font-semibold text-gray-800">
            Class {{ $class }} Results
        </h2>

        <a href="{{ route('admin.results.list') }}" class="text-sm text-indigo-600 hover:underline">
            ← Back to Classes
        </a>

    </div>


    <!-- Table Section -->
    <div class="overflow-x-auto">

        <table class="min-w-full text-sm">

            <thead class="bg-gray-100 text-xs uppercase text-gray-600">
                <tr>
                    <th class="px-4 py-3 text-left">Student</th>
                    <th class="px-4 py-3 text-left">Exam</th>
                    <th class="px-4 py-3 text-left">Score</th>
                    <th class="px-4 py-3 text-left">Status</th>
                </tr>
            </thead>

            <tbody class="divide-y">

                @forelse($classResults as $attempt)

                <tr class="hover:bg-gray-50 transition">

                    <td class="px-4 py-3 font-medium text-gray-800">
                        {{ $attempt->user->name }}
                    </td>

                    <td class="px-4 py-3 text-gray-700">
                        {{ $attempt->exam->title }}
                    </td>

                    <td class="px-4 py-3">
                        <span class="px-2 py-1 text-xs bg-indigo-100 text-indigo-700 rounded-full">
                            {{ $attempt->score }}
                        </span>
                    </td>

                    <td class="px-4 py-3">
                        <span class="px-2 py-1 text-xs rounded-full
                                    {{ $attempt->approval_status === 'approved'
                                        ? 'bg-green-100 text-green-700'
                                        : 'bg-red-100 text-red-700' }}">
                            {{ ucfirst($attempt->approval_status) }}
                        </span>
                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                        No results found.
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    @endif

</div>

@endsection