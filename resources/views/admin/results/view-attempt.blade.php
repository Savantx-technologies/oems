@extends('layouts.admin')
@section('title', 'Manual Exam Checking')

@section('content')

<div class="max-w-5xl mx-auto">

    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Manual Exam Checking</h1>
    </div>

    <!-- Student Info -->
    <div class="bg-white shadow rounded-xl p-6 mb-6 border">

        <div class="grid grid-cols-3 gap-6">

            <div>
                <p class="text-xs text-gray-500 uppercase">Student</p>
                <p class="text-lg font-semibold text-gray-800">{{ $attempt->user->name }}</p>
            </div>

            <div>
                <p class="text-xs text-gray-500 uppercase">Exam</p>
                <p class="text-lg font-semibold text-gray-800">{{ $attempt->exam->title }}</p>
            </div>

            <div>
                <p class="text-xs text-gray-500 uppercase">Status</p>

                @if ($attempt->approval_status == 'pending')
                <span class="px-3 py-1 text-xs bg-yellow-100 text-yellow-700 rounded-full">
                    Pending Review
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

            </div>

        </div>
    </div>


    @foreach ($questions as $index => $question)
    @php
    $studentAnswer = $attempt->answers->where('question_id', $question->id)->first();
    $selectedOption = $studentAnswer->selected_option ?? null;
    $correctOption = $question->correct_option;

    $isCorrectAnswer = $selectedOption == $correctOption;
    @endphp


    <div class="bg-white shadow rounded-xl p-6 mb-6 border">

        <!-- Question Header -->
        <div class="flex justify-between items-center mb-4">

            <h3 class="font-semibold text-gray-800">
                Q{{ $index + 1 }}. {{ $question->question_text }}
            </h3>

            <div class="flex items-center gap-3">

                <span class="text-sm text-gray-400">
                    Marks: {{ $question->marks }}
                </span>

                @if ($studentAnswer)

                <div class="check-buttons flex gap-2">

                    @if ($studentAnswer && $studentAnswer->admin_checked == 0)

                    <button class="mark-correct px-3 py-1 bg-green-600 text-white rounded text-xs"
                        data-id="{{ $studentAnswer->id }}">
                        ✓ Correct
                    </button>

                    <button class="mark-wrong px-3 py-1 bg-red-600 text-white rounded text-xs"
                        data-id="{{ $studentAnswer->id }}">
                        ✗ Wrong
                    </button>

                    @elseif ($studentAnswer && $studentAnswer->is_correct == 1)

                    <span class="px-3 py-1 bg-green-600 text-white text-xs rounded">
                        ✔ Verified Correct
                    </span>

                    @else

                    <span class="px-3 py-1 bg-red-600 text-white text-xs rounded">
                        ✖ Verified Wrong
                    </span>

                    @endif

                </div>

                @endif
            </div>

        </div>


        <!-- Options -->
        <div class="grid gap-3">

            @foreach (['A', 'B', 'C', 'D'] as $option)
            @php
            $optionText = $question->{'option_' . strtolower($option)};

            $isStudent = $selectedOption == $option;
            $isCorrect = $correctOption == $option;
            @endphp

            @if ($optionText)
            <div class="p-3 rounded-lg border

                                @if ($isStudent && $isCorrect) bg-green-100 border-green-500

                                @elseif($isStudent && !$isCorrect)
                                bg-red-100 border-red-500

                                @elseif(!$isStudent && $isCorrect && !$isCorrectAnswer)
                                bg-green-50 border-green-300

                                @else
                                bg-gray-50 border-gray-200 @endif
                                ">

                <span class="font-medium text-gray-700">
                    {{ $option }}. {{ $optionText }}
                </span>

            </div>
            @endif
            @endforeach

        </div>

    </div>
    @endforeach


    <div class="flex gap-3 mt-8">

        <form action="{{ route('admin.results.approve', $attempt->id) }}" method="POST">
            @csrf
            <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-lg shadow hover:bg-green-700">
                Approve Result
            </button>
        </form>

        <form action="{{ route('admin.results.reject', $attempt->id) }}" method="POST">
            @csrf
            <button type="submit" class="px-6 py-3 bg-red-600 text-white rounded-lg shadow hover:bg-red-700">
                Reject Result
            </button>
        </form>

    </div>

</div>

@endsection

@push('script')
<script>
    document.addEventListener("DOMContentLoaded", function () {

document.querySelectorAll('.mark-correct').forEach(btn => {

btn.addEventListener('click', function() {

let id = this.dataset.id;
let wrapper = this.closest('.check-buttons');

fetch(`/admin/answer/${id}/correct`, {
method: 'POST',
headers: {
'X-CSRF-TOKEN': '{{ csrf_token() }}',
'Content-Type': 'application/json',
'Accept': 'application/json'
}
})
.then(res => res.json())
.then(data => {

wrapper.innerHTML =
'<span class="px-3 py-1 bg-green-600 text-white text-xs rounded">✔ Verified Correct</span>';

alert('Question Checked Successfully');

})
.catch(error => console.log(error));

});

});


document.querySelectorAll('.mark-wrong').forEach(btn => {

btn.addEventListener('click', function() {

let id = this.dataset.id;
let wrapper = this.closest('.check-buttons');

fetch(`/admin/answer/${id}/wrong`, {
method: 'POST',
headers: {
'X-CSRF-TOKEN': '{{ csrf_token() }}',
'Content-Type': 'application/json',
'Accept': 'application/json'
}
})
.then(res => res.json())
.then(data => {

wrapper.innerHTML =
'<span class="px-3 py-1 bg-red-600 text-white text-xs rounded">✖ Verified Wrong</span>';

alert('Question Checked Successfully');

})
.catch(error => console.log(error));

});

});

});
</script>
@endpush