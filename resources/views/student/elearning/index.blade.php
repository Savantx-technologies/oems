@extends('layouts.student')

@section('title', 'E-Learning Content')

@section('content')

<div class="max-w-6xl mx-auto p-6">

    <h1 class="text-2xl font-bold text-gray-800 mb-6">My Classes</h1>

    @if(!auth()->user()->grade || trim(auth()->user()->grade) === '')
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg" role="alert">
            <strong class="font-bold">Action Required!</strong>
            <p class="block sm:inline">Your class has not been assigned to your profile. Please contact your school administrator.</p>
        </div>
    @else
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @forelse($classes as $class)
                <a href="{{ route('student.elearning.class', $class->class_id) }}"
                    class="bg-white shadow rounded-xl p-6 text-center hover:shadow-lg transition">

                    <div class="text-xl font-bold text-indigo-600">
                        Class {{ $class->class_id }}
                    </div>

                    <p class="text-gray-500 text-sm mt-2">
                        View Lessons
                    </p>
                </a>
            @empty
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center col-span-full">
                    <p class="text-gray-600">No e-learning content has been assigned to your class yet.</p>
                </div>
            @endforelse
        </div>
    @endif

</div>

@endsection