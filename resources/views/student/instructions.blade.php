@extends('layouts.student')

@section('title', 'Exam Instructions')

@section('content')
<div class="max-w-7xl mx-auto px-2 sm:px-4">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Exam Instructions & Guidelines</h1>
        <p class="text-gray-500 text-sm">Please read the following instructions carefully before appearing for any exam.</p>
    </div>

    <div class="space-y-6">
        <!-- Super Admin / General Instructions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-indigo-50 px-6 py-4 border-b border-indigo-100 flex items-center gap-3">
                <div class="bg-indigo-100 p-2 rounded-full text-indigo-600">
                    <i class="bi bi-shield-check text-xl"></i>
                </div>
                <h2 class="text-lg font-bold text-indigo-900">General Examination Guidelines</h2>
            </div>
            <div class="p-6 text-gray-700 space-y-4">
                @if(!empty($generalInstructions))
                    <div class="prose max-w-none text-sm text-gray-600">
                        {!! $generalInstructions !!}
                    </div>
                @else
                    <p class="text-sm text-gray-600 italic">No general instructions available at this time.</p>
                @endif
            </div>
        </div>

        <!-- School Admin / School Specific Instructions -->
        @php
            $school = auth()->user()->school;
        @endphp
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
             <div class="bg-blue-50 px-6 py-4 border-b border-blue-100 flex items-center gap-3">
                <div class="bg-blue-100 p-2 rounded-full text-blue-600">
                    <i class="bi bi-building text-xl"></i>
                </div>
                <h2 class="text-lg font-bold text-blue-900">
                    {{ $school ? $school->name : 'School' }} Instructions
                </h2>
            </div>
            <div class="p-6 text-gray-700 space-y-4">
                 @if(!empty($schoolInstructions))
                    <div class="prose max-w-none text-sm text-gray-600">
                        {!! $schoolInstructions !!}
                    </div>
                 @else
                    <p class="text-sm text-gray-600 italic">No specific instructions provided by the school.</p>
                 @endif
            </div>
        </div>
    </div>
</div>
@endsection