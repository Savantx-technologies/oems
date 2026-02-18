@extends('layouts.admin')

@section('title', 'Bulk Upload Questions')

@section('content')
<div class="max-w-3xl mx-auto space-y-8">

    <div class="pt-2">
        <h1 class="text-2xl font-bold text-gray-900">Bulk Upload Questions</h1>
        <p class="text-sm text-gray-500 mt-1">
            Upload multiple MCQ questions by providing a CSV file in the specified format below.
        </p>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl shadow">
        <div class="p-8">

            @if (session('error'))
            <div class="mb-6 p-3 bg-red-50 border border-red-200 rounded text-sm text-red-700">
                {{ session('error') }}
            </div>
            @endif
            @if (session('success'))
            <div class="mb-6 p-3 bg-green-50 border border-green-200 rounded text-sm text-green-700">
                {{ session('success') }}
            </div>
            @endif
            @if ($errors->any())
            <div class="mb-6 p-3 bg-red-50 border border-red-200 rounded text-sm text-red-700">
                <ul class="ml-3 list-disc">
                    @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('admin.questions.bulk.upload') }}" enctype="multipart/form-data"
                class="space-y-8">
                @csrf

                <div>
                    <label for="file" class="block text-sm font-medium text-gray-700 mb-1">
                        Upload CSV file
                    </label>
                    <input id="file" name="file" type="file" accept=".csv" required
                        class="block w-full text-sm border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 file:bg-indigo-50 file:border-none file:px-3 file:py-2 file:rounded-l-md file:text-indigo-700">
                    <p class="mt-1 text-xs text-gray-500">Only CSV files are supported (max 2MB).</p>
                </div>

                <div class="bg-gray-50 border border-gray-200 rounded-lg p-5 text-sm text-gray-800">
                    <div class="flex flex-wrap justify-between items-center mb-2">
                        <span class="font-semibold">CSV Format Example</span>
                        <a href="{{ route('admin.questions.bulk.sample') }}"
                            class="text-indigo-600 hover:underline text-xs font-semibold">
                            Download Sample CSV
                        </a>
                    </div>
                    <pre
                        class="bg-gray-100 border rounded-md px-2 py-2 text-xs text-gray-800 overflow-x-auto whitespace-pre">
class,subject,question_text,marks,option_a,option_b,option_c,option_d,correct_option
8,Science,What is photosynthesis?,2,Process in plants,Animal breathing,Water cycle,Soil erosion,2
</pre>
                   
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.questions.index') }}"
                        class="px-5 py-2 rounded-lg border border-gray-300 bg-white text-gray-700 text-sm hover:bg-gray-100 transition">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-6 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Upload Questions
                    </button>
                </div>
            </form>
            <div id="uploadProgressBox" class="hidden mt-4">
                <div class="text-sm text-gray-600 mb-1">
                    Uploading file…
                </div>

                <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                    <div id="uploadProgressBar" class="bg-indigo-600 h-2.5 rounded-full transition-all"
                        style="width:0%">
                    </div>
                </div>

                <div class="text-xs text-gray-500 mt-1" id="uploadProgressText">
                    0%
                </div>
            </div>

        </div>
    </div>

</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {

    const form   = document.querySelector('form[action="{{ route('admin.questions.bulk.upload') }}"]');
    const box    = document.getElementById('uploadProgressBox');
    const bar    = document.getElementById('uploadProgressBar');
    const text   = document.getElementById('uploadProgressText');

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(form);

        const xhr = new XMLHttpRequest();
        xhr.open('POST', form.action, true);

        xhr.setRequestHeader(
            'X-CSRF-TOKEN',
            document.querySelector('input[name="_token"]').value
        );

        box.classList.remove('hidden');

        xhr.upload.onprogress = function (e) {
            if (e.lengthComputable) {
                const percent = Math.round((e.loaded / e.total) * 100);
                bar.style.width = percent + '%';
                text.innerText = percent + '%';
            }
        };

        xhr.onload = function () {
            if (xhr.status === 200) {
                bar.style.width = '100%';
                text.innerText = 'Completed';

                // redirect after upload
                window.location.href = "{{ route('admin.questions.index') }}";
            } else {
                alert('Upload failed. Please try again.');
            }
        };

        xhr.send(formData);
    });

});
</script>

@endsection