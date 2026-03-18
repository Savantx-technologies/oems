@extends('layouts.superadmin')

@section('title', $title ?? 'Coming Soon')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-blue-900 px-8 py-10 text-white">
                <span class="inline-flex items-center rounded-full bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em]">
                    Super Admin
                </span>
                <h1 class="mt-4 text-3xl font-bold">{{ $feature ?? 'This module' }}</h1>
                <p class="mt-3 max-w-2xl text-sm text-slate-200">
                    {{ $description ?? 'This section is planned and will be available soon.' }}
                </p>
            </div>

            <div class="px-8 py-10">
                <div class="rounded-2xl border border-dashed border-blue-200 bg-blue-50 px-6 py-8">
                    <h2 class="text-xl font-semibold text-slate-900">Coming soon</h2>
                    <p class="mt-3 text-sm leading-7 text-slate-600">
                        The {{ strtolower($feature ?? 'selected feature') }} page is not built yet, but the navigation is now connected so your superadmin panel no longer lands on a dead link.
                    </p>
                    <div class="mt-6">
                        <a href="{{ route('superadmin.dashboard') }}"
                            class="inline-flex items-center rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
                            Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
