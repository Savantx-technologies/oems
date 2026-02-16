@extends('layouts.admin')

@section('title','Solution View')

@section('content')

<div class="space-y-6">

    <h2 class="text-xl font-semibold">{{ $exam->title }} - Solution</h2>

    @foreach($questions as $index => $question)

    <div class="bg-white p-6 rounded-xl shadow">

        <p class="font-medium mb-3">
            Q{{ $index+1 }}. {{ $question->question_text }}
        </p>

        <ul class="space-y-3">

            @php
            $options = [
            'A' => $question->option_a,
            'B' => $question->option_b,
            'C' => $question->option_c,
            'D' => $question->option_d,
            ];
            @endphp

            @foreach($options as $key => $option)

            <li class="p-3 rounded-lg border
            {{ $question->correct_option == $key 
                ? 'bg-green-50 border-green-400 text-green-700 font-semibold' 
                : 'bg-gray-50 border-gray-200 text-gray-700' }}">

                <div class="flex justify-between items-center">

                    <span>
                        {{ $key }}. {{ $option }}
                    </span>

                    @if($question->correct_option == $key)
                    <span class="text-green-600 font-bold">
                        ✔ Correct
                    </span>
                    @endif

                </div>

            </li>

            @endforeach

        </ul>


    </div>

    @endforeach

</div>

@endsection