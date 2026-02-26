@extends('layouts.student')
@section('title','Result Status')

@section('content')

<div class="max-w-3xl mx-auto mt-16 p-10 bg-white shadow-xl border rounded-2xl text-center">

    @if($status === 'pending')

    <div class="text-yellow-600 text-2xl font-semibold mb-4">
        Result Under Review
    </div>

    <p class="text-gray-600">
        Your result has been submitted and is currently under administrative review.
        Please check again later.
    </p>

    @elseif($status === 'rejected')

    <div class="text-red-600 text-2xl font-semibold mb-4">
        Result Not Approved
    </div>

    <p class="text-gray-600">
        Your result was not approved by the administrator.
        Please contact your school authority.
    </p>

    @endif

</div>

@endsection