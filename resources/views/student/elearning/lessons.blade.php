@extends('layouts.student')

@section('title', 'E-Learning')

@section('content')

<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-12">
    <div class="max-w-7xl mx-auto px-4">
    
        @if(!auth()->user()->grade || trim(auth()->user()->grade) === '')
            <div class="bg-gradient-to-r from-red-50 to-orange-50 border-l-4 border-red-500 rounded-lg p-6 shadow-sm">
                <div class="flex items-center gap-3">
                    <span class="text-3xl">⚠️</span>
                    <div>
                        <h3 class="font-bold text-red-900">Action Required</h3>
                        <p class="text-red-800 text-sm">Your class hasn't been assigned. Please contact your school administrator.</p>
                    </div>
                </div>
            </div>
        @else
            <div class="space-y-6">
                @forelse($lessons as $lesson)
                    <div class="group bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-200 hover:border-indigo-300">
                        
                        <!-- Header -->
                        <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 p-6 text-white">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                <div>
                                    <p class="text-indigo-100 text-sm font-semibold uppercase tracking-wide mb-1">Topic</p>
                                    <h3 class="text-2xl font-bold">{{ $lesson->topic }}</h3>
                                </div>
                                <div class="md:text-right">
                                    <p class="text-indigo-100 text-sm font-semibold uppercase tracking-wide mb-1">Sub Topic</p>
                                    <p class="text-lg font-medium">{{ $lesson->sub_topic }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-8 grid md:grid-cols-3 gap-8">
                            
                            <!-- Lesson Content -->
                            <div class="md:col-span-2">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                    <span class="w-1 h-6 bg-indigo-600 rounded"></span>
                                    Lesson Content
                                </h4>
                                <div class="prose prose-sm max-w-none text-gray-700 leading-relaxed">
                                    {!! $lesson->content !!}
                                </div>
                            </div>

                            <!-- Right Sidebar -->
                            <div class="space-y-4">
                                
                                <!-- Video -->
                                @if($lesson->video_file)
                                    <div class="rounded-xl overflow-hidden shadow-lg border border-gray-200">
                                        <video controls class="w-full bg-black">
                                            <source src="{{ Storage::url($lesson->video_file) }}" type="video/mp4">
                                        </video>
                                    </div>
                                @endif

                                <!-- PDF Section -->
                                @if($lesson->pdf_file)
                                    <div class="bg-gradient-to-br from-orange-50 to-amber-50 border border-orange-200 rounded-xl p-4 shadow-sm hover:shadow-md transition">
                                        <div class="flex items-center gap-2 mb-3">
                                            <span class="text-2xl">📄</span>
                                            <span class="font-semibold text-gray-900">Notes</span>
                                        </div>
                                        <div class="flex gap-2">
                                            <a href="{{ Storage::url($lesson->pdf_file) }}" target="_blank" class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-orange-600 text-white text-xs font-semibold rounded-lg hover:bg-orange-700 transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z" /><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.022 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" /></svg>
                                                View
                                            </a>
                                            <a href="{{ Storage::url($lesson->pdf_file) }}" download class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-green-600 text-white text-xs font-semibold rounded-lg hover:bg-green-700 transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                                Download
                                            </a>
                                        </div>
                                    </div>
                                @endif

                                <!-- Video Link -->
                                @if($lesson->video_link)
                                    <div class="bg-gradient-to-br from-blue-50 to-cyan-50 border border-blue-200 rounded-xl p-4 shadow-sm hover:shadow-md transition">
                                        <a href="{{ Str::startsWith($lesson->video_link, ['http://','https://']) ? $lesson->video_link : 'https://' . $lesson->video_link }}" target="_blank" class="flex items-center justify-between gap-3 text-blue-600 font-semibold hover:text-blue-700 group/link">
                                            <div class="flex items-center gap-2">
                                                <span class="text-xl">▶</span>
                                                <span>External Video</span>
                                            </div>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover/link:translate-x-1 transition" viewBox="0 0 20 20" fill="currentColor"><path d="M11 3a1 1 0 100 2h3.586L9.293 9.293a1 1 0 000 1.414l1.414 1.414a1 1 0 001.414 0L17 7.414V11a1 1 0 102 0V4a1 1 0 00-1-1h-7z" /><path d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z" /></svg>
                                        </a>
                                    </div>
                                @endif

                            </div>
                        </div>

                    </div>
                @empty
                    <div class="bg-gradient-to-r from-amber-50 to-orange-50 border-2 border-dashed border-amber-300 rounded-lg p-12 text-center shadow-sm">
                        <span class="text-5xl mb-3 block">📚</span>
                        <p class="text-gray-700 font-semibold text-lg">No lessons available yet</p>
                        <p class="text-gray-600 text-sm mt-1">Check back soon for new content</p>
                    </div>
                @endforelse
            </div>
        @endif

    </div>
</div>

@endsection