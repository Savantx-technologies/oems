@extends('layouts.admin')

@section('title','Schedule Exam')

@section('content')

<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Schedule Exam</h1>
            <p class="text-sm text-gray-500">
                {{ $exam->title }} – Class {{ $exam->class }} | {{ $exam->subject }}
            </p>
        </div>

        <a href="{{ route('admin.exams.questions',$exam->id) }}"
            class="inline-flex items-center rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            Back
        </a>
    </div>

    <form method="POST" action="{{ route('admin.exams.schedule.store',$exam->id) }}">
        @csrf

        <!-- Schedule card -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm">

            <div class="px-6 py-4 border-b">
                <h2 class="text-lg font-semibold text-center text-gray-800">Exam Availability</h2>
                <p class="text-sm text-gray-500 text-center">
                    Define when the exam will be available to students
                </p>
                <p class="text-sm text-gray-500 text-center">
                    When you select the start date and time, the end date and time will be automatically calculated and
                    filled based on the exam duration
                </p>
            </div>
            <div class="p-6 grid md:grid-cols-2 gap-5">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Start date & time
                    </label>
                    <input type="datetime-local" name="start_at"
                        value="{{ optional($exam->schedule)->start_at?->format('Y-m-d\TH:i') }}"
                        class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                        required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        End date & time
                    </label>
                    <input type="datetime-local" name="end_at"
                        value="{{ optional($exam->schedule)->end_at?->format('Y-m-d\TH:i') }}"
                        class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                        required>
                </div>

                {{-- <div class="flex items-center gap-3 mt-2">
                    <input type="checkbox" name="late_entry_allowed" value="1"
                        @checked(optional($exam->schedule)->late_entry_allowed)
                    class="rounded border-gray-300 text-indigo-600">
                    <label class="text-sm text-gray-700">
                        Allow late entry
                    </label>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Late entry minutes
                    </label>
                    <input type="number" name="late_entry_minutes" min="0"
                        value="{{ optional($exam->schedule)->late_entry_minutes }}"
                        class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Max attempts
                    </label>
                    <input type="number" name="max_attempts" min="1"
                        value="{{ optional($exam->schedule)->max_attempts ?? 1 }}"
                        class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                </div> --}}

            </div>

            <div class="px-6 py-4 border-t flex justify-end gap-3">

                <a href="{{ route('admin.exams.questions',$exam->id) }}"
                    class="px-5 py-2 rounded-lg border border-gray-300 text-gray-700 text-sm font-medium hover:bg-gray-50">
                    Back
                </a>

                <button type="submit"
                    class="px-6 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">
                    Save & Continue
                </button>

            </div>

        </div>

    </form>

</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const startInput = document.querySelector('input[name="start_at"]');
        const endInput   = document.querySelector('input[name="end_at"]');

        // duration coming from backend
        const durationMinutes = {{ $exam->duration_minutes }};

        startInput.addEventListener('change', function () {

            if (!startInput.value) return;

            const startTime = new Date(startInput.value);

            // add duration
            const endTime = new Date(startTime.getTime() + durationMinutes * 60000);

            const yyyy = endTime.getFullYear();
            const mm   = String(endTime.getMonth() + 1).padStart(2, '0');
            const dd   = String(endTime.getDate()).padStart(2, '0');
            const hh   = String(endTime.getHours()).padStart(2, '0');
            const mi   = String(endTime.getMinutes()).padStart(2, '0');

            endInput.value = `${yyyy}-${mm}-${dd}T${hh}:${mi}`;
        });

    });
</script>

@endsection