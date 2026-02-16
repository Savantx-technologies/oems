@extends('layouts.admin')

@section('title','Practice Exams')

@section('content')

<div class="bg-white rounded-xl shadow p-6">
{{-- <a href="{{ route('admin.exams.solution', $exam->id) }}"
    class="px-3 py-1 text-xs bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100">
    View Solution
</a> --}}

    <table class="min-w-full text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left">Title</th>
                <th class="px-4 py-2 text-left">Class</th>
                <th class="px-4 py-2 text-left">Subject</th>
                <th class="px-4 py-2 text-right">Action</th>
            </tr>
        </thead>

        <tbody>
            @foreach($exams as $exam)
            <tr class="border-t">
                <td class="px-4 py-3">{{ $exam->title }}</td>
                <td class="px-4 py-3">{{ $exam->class }}</td>
                <td class="px-4 py-3">{{ $exam->subject }}</td>
                <td class="px-4 py-3 text-right">
                    <a href="{{ route('admin.exams.solution',$exam->id) }}"
                        class="px-3 py-1 text-xs bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100">
                        View Solution
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $exams->links() }}

</div>

@endsection
