@extends('layouts.admin')
@section('title','Edit E-Learning')

@section('content')

<div class="max-w-5xl mx-auto">

    <form action="{{ route('admin.elearning.update', $content->id) }}" method="POST">
        @csrf
        @method('PUT')
        @if($content->pdf)
        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <p class="text-sm text-gray-700 mb-2">Current PDF: <a href="{{ $content->pdf }}" target="_blank" class="text-blue-600 hover:underline">View</a></p>
        </div>
        @endif
        <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                Upload PDF
            </label>
            <input type="file" name="pdf" accept=".pdf" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500">
        </div>

        <div class="bg-white rounded-xl shadow-sm border p-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Class -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Class
                    </label>

                    <input type="text"
                        name="class_id"
                        value="{{ $content->class_id }}"
                        class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500">
                </div>

                <!-- Topic -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Topic
                    </label>

                    <input type="text"
                        name="topic"
                        value="{{ $content->topic }}"
                        class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500">
                </div>

                <!-- Sub Topic -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Sub Topic
                    </label>

                    <input type="text"
                        name="sub_topic"
                        value="{{ $content->sub_topic }}"
                        class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500">
                </div>

                <!-- Video Link -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Video Link
                    </label>

                    <input type="text"
                        name="video_link"
                        value="{{ $content->video_link }}"
                        class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500">
                </div>

            </div>

            <!-- Lesson Content Editor -->
            <div class="mt-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Lesson Content
                </label>

                <textarea id="editor" name="content">
                    {!! $content->content !!}
                </textarea>
            </div>

            <!-- Video Preview -->
            @if($content->video_link)
            <div class="mt-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Video Preview
                </label>

                <div class="bg-gray-100 rounded-lg p-4">
                    <a href="{{ $content->video_link }}"
                        target="_blank"
                        class="text-indigo-600 font-medium hover:underline">
                        Watch Video
                    </a>
                </div>
            </div>
            @endif

        </div>

        <!-- Buttons -->
        <div class="flex justify-between items-center mt-6">

            <a href="{{ route('admin.elearning.index') }}"
                class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-100">
                ← Back
            </a>

            <button
                class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow">
                Update Lesson
            </button>

        </div>

    </form>

</div>

@endsection


@push('script')

<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>

<script>
CKEDITOR.replace('editor', {
    height: 300
});
</script>

@endpush