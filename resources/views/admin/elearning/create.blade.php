@extends('layouts.admin')

@section('title','Elearning Content')

@section('content')

<div class="max-w-6xl mx-auto">

    <form method="POST" action="{{ route('admin.elearning.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Class -->
            <div class="bg-white p-5 rounded-xl shadow-sm border">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Select Class
                </label>

                <select name="class_id"
                       class="w-full border border-gray-300 rounded-lg p-2 bg-white">
                    <option value="">Choose Class</option>
                    @foreach($classes as $class)
                    <option value="{{ $class->grade }}">
                        Class {{ $class->grade }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Topic -->
            <div class="bg-white p-5 rounded-xl shadow-sm border">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Topic
                </label>
                <input type="text"
                    name="topic"
                    placeholder="Enter topic name"
                    class="w-full border border-gray-300 rounded-lg p-2 bg-white">
            </div>

            <!-- Sub Topic -->
            <div class="bg-white p-5 rounded-xl shadow-sm border">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Sub Topic
                </label>
                <input type="text"
                    name="sub_topic"
                    placeholder="Enter sub topic"
                    class="w-full border border-gray-300 rounded-lg p-2 bg-white">
            </div>

            <!-- Video Upload -->
            <div class="bg-white p-5 rounded-xl shadow-sm border">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Upload Video
                </label>
                <input type="file"
                    name="video_file"
                    class="w-full border border-gray-300 rounded-lg p-2 bg-white">
                <p class="text-xs text-gray-400 mt-2">
                    Upload MP4 or educational video.
                </p>
            </div>

            <!-- PDF Notes Upload -->
            <div class="bg-white p-5 rounded-xl shadow-sm border">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Upload PDF Notes
                </label>
                <input type="file"
                    name="pdf_file"
                    accept=".pdf"
                    class="w-full border border-gray-300 rounded-lg p-2 bg-white">
                <p class="text-xs text-gray-400 mt-2">
                    Upload PDF file for student notes.
                </p>
            </div>

            <!-- Video Link -->
            <div class="bg-white p-5 rounded-xl shadow-sm border">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Video Link (YouTube / Google Drive)
                </label>
                <input type="text"
                    name="video_link"
                    placeholder="https://youtube.com/..."
                    class="w-full border border-gray-300 rounded-lg p-2 bg-white">
            </div>

        </div>

        <!-- Content Editor -->
        <div class="bg-white p-6 rounded-xl shadow-sm border mt-6">
            <label class="block text-sm font-semibold text-gray-700 mb-3">
                Lesson Content
            </label>

            <textarea id="editor" name="content"></textarea>
        </div>

        <!-- Button Section -->
        <div class="flex justify-between items-center mt-6">

            <a href="{{ route('admin.elearning.index') }}"
                class="px-5 py-2.5 rounded-lg border text-gray-600 hover:bg-gray-100">
                Cancel
            </a>

            <button
                class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow">
                Upload Content
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