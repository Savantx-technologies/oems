@extends('layouts.admin')
@section('title','Elearning')

@section('content')

{{-- ===============================
CLASS CARD VIEW
=============================== --}}
@if(!request()->filled('class'))

<div class="p-6 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">

    @forelse($classes as $class)

    <a href="{{ route('admin.elearning.index', ['class' => $class]) }}"
        class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition p-6 text-center">

        <div class="text-xl font-bold text-indigo-600">
            Class {{ $class }}
        </div>

        <div class="text-sm text-gray-500 mt-2">
            View E-Learning
        </div>

    </a>

    @empty

    <div class="col-span-full text-center text-gray-500">
        No classes available.
    </div>

    @endforelse

</div>

{{-- ===============================
CONTENT TABLE VIEW
=============================== --}}
@else

<!-- Sub Header -->
<div class="px-6 py-4 border-b bg-gray-50 flex justify-between items-center">

    <h2 class="text-lg font-semibold text-gray-800">
        Class {{ request('class') }} E-Learning
    </h2>

    <a href="{{ route('admin.elearning.index') }}" class="text-sm text-indigo-600 hover:underline">
        ← Back to Classes
    </a>

</div>

<!-- Table Section -->
<div class="overflow-x-auto">

    <table class="min-w-full text-sm">

        <thead class="bg-gray-100 text-xs uppercase text-gray-600">
            <tr>
                <th class="px-4 py-3 text-left">#</th>
                <th class="px-4 py-3 text-left">Topic</th>
                <th class="px-4 py-3 text-left">Sub Topic</th>
                <th class="px-4 py-3 text-left">Content</th>
                <th class="px-4 py-3 text-left">link</th>
                <th class="px-4 py-3 text-left">Videos</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>

        <tbody class="divide-y">

            @forelse($contents as $content)

            <tr class="hover:bg-gray-50 transition">

                <td class="px-4 py-3">
                    {{ $loop->iteration + ($contents->currentPage() - 1) * $contents->perPage() }}
                </td>

                <td class="px-4 py-3 font-medium">
                    {{ $content->topic }}
                </td>

                <td class="px-4 py-3">
                    {{ $content->sub_topic }}
                </td>

                <td class="px-4 py-3">
                    <button
                        class="viewLessonBtn px-3 py-1.5 bg-indigo-600 text-white rounded text-xs hover:bg-indigo-700"
                        data-content="{!! htmlspecialchars($content->content) !!}">
                        View Lesson
                    </button>
                </td>
                <!-- Video Link -->
                <td class="px-4 py-3">
                    @if($content->video_link)
                    <a href="{{ Str::startsWith($content->video_link, ['http://','https://']) ? $content->video_link : 'https://' . $content->video_link }}"
                        target="_blank" class="text-blue-600 hover:underline">
                        Open Link
                    </a>
                    @else
                    -
                    @endif
                </td>

                <!-- Uploaded Video File -->
                <td class="px-4 py-3">
                    @if($content->video_file)
                    <a href="{{ Storage::url($content->video_file) }}" target="_blank"
                        class="text-green-600 hover:underline">
                        Watch Video
                    </a>
                    @else
                    No File
                    @endif
                </td>

                <td class="px-4 py-3 text-right">

                    <div class="inline-flex gap-2">

                        <a href="{{ route('admin.elearning.edit', $content->id) }}"
                            class="px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 text-xs font-medium hover:bg-blue-100">
                            Edit
                        </a>

                        <form method="POST" action="{{ route('admin.elearning.destroy', $content->id) }}"
                            onsubmit="return confirm('Delete this content?')">
                            @csrf
                            @method('DELETE')

                            <button
                                class="px-3 py-1.5 rounded-lg bg-red-50 text-red-700 text-xs font-medium hover:bg-red-100">
                                Delete
                            </button>
                        </form>

                    </div>

                </td>

            </tr>

            @empty

            <tr>
                <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                    No E-Learning content found.
                </td>
            </tr>

            @endforelse

        </tbody>

    </table>
    <!-- Lesson Modal -->
    <div id="lessonModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

        <div class="bg-white w-full max-w-3xl rounded-xl shadow-lg">

            <!-- Header -->
            <div class="flex justify-between items-center border-b px-6 py-4">
                <h3 class="text-lg font-semibold">Lesson Preview</h3>

                <button onclick="closeLessonModal()" class="text-gray-500 hover:text-red-500 text-xl">
                    ✕
                </button>
            </div>

            <!-- Content -->
            <div id="lessonContent" class="p-6 max-h-[500px] overflow-y-auto prose max-w-none">
            </div>

            <!-- Footer -->
            <div class="border-t px-6 py-3 text-right">
                <button onclick="closeLessonModal()" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                    Close
                </button>
            </div>

        </div>
    </div>
</div>

{{-- Pagination --}}
@if($contents->hasPages())
<div class="px-6 py-4 border-t bg-gray-50">
    {{ $contents->appends(request()->query())->links() }}
</div>
@endif

@endif

@endsection
@push('script')
<script>
    document.querySelectorAll('.viewLessonBtn').forEach(button => {
    button.addEventListener('click', function () {
        let content = this.getAttribute('data-content');
        document.getElementById('lessonContent').innerHTML = content;

        document.getElementById('lessonModal').classList.remove('hidden');
        document.getElementById('lessonModal').classList.add('flex');
    });
});

function closeLessonModal() {
    document.getElementById('lessonModal').classList.add('hidden');
}
</script>

@endpush