@extends('layouts.student')

@section('title', 'E-Learning Content')

@section('content')
@php
    // Define modules and their video links here
    $modules = [
        'Module 1: Introduction to Courier Executive - Operations' => [
            ['title' => 'Components of Supply Chain', 'url' => 'https://www.youtube.com/watch?v=Lpp9bHtPAN0'],
            ['title' => 'Sub-sectors & Opportunities', 'url' => ' '],
            ['title' => 'Material Handling Equipment (MHE)', 'url' => 'https://www.youtube.com/watch?v=3anQoW3mT7Q&t=47s'],
            ['title' => 'AWB Explained', 'url' => 'https://www.youtube.com/watch?v=GqKJv4t_pjQ&t=7s'],  
        ],
        'Module 2: ERP Data Analysis in Courier Hub' => [
            ['title' => 'ERP Basics', 'url' => 'https://www.youtube.com/watch?v=10JeksrGVjI'],
            ['title' => 'Shipment Tracking & Status', 'url' => 'https://www.youtube.com/watch?v=vws4ytd_tsU'],
            ['title' => 'Trend Analysis in Operations', 'url' => 'https://www.youtube.com/watch?v=mKX-q4SSHy0 '],
        ],
        'Module 3: Institutional Business Developmen    t' => [
            ['title' => 'Sales Cycle Basics', 'url' => 'https://www.youtube.com/results?search_query=Institutional+Sales+Cycle+Explained '],
            ['title' => 'Sales Cycle & B2B Sales', 'url' => 'https://www.youtube.com/watch?v=PTfhwWKvnWU '],
            ['title' => 'Negotiation Skills', 'url' => ' https://www.youtube.com/watch?v=S-_4O0FT-XE'],
        ],
        'Module 4: Branch Sales' => [
            ['title' => 'Introduction to Branch Sales', 'url' => 'https://www.youtube.com/watch?v=S_e1j54TRns&list=PLszYngDGf2tmBSl-unLj0y6hmgAsa3l-E '],
            ['title' => 'ERP Tracking', 'url' => 'https://www.youtube.com/watch?v=DRiaeh08baA '],
        ],
        'Module 5: Shipment Classification & Customs Clearance' => [
            ['title' => 'Introduction to Customs & International Shipments', 'url' => 'https://www.youtube.com/watch?v=o4sd7lHOqVI '],
            ['title' => 'Pre-Clearance Checkpoints', 'url' => ' https://www.youtube.com/watch?v=sUWgUqJyx7w&list=PLsh2FvSr3n7eUcjDAa5VESrDpg6Vllttg'],
            ['title' => 'HSN Code Structure', 'url' => 'https://www.youtube.com/watch?v=gEfd_dE8EE4 '],
        ],
        'Module 6: Customer Service Management' => [
            ['title' => 'Importance of Communication Skills', 'url' => 'https://www.youtube.com/watch?v=QGHBq5OEsBM '],
            ['title' => 'Difficult Customer Handling', 'url' => 'https://www.youtube.com/watch?v=0eSmZxdDZGI '],
        ],
        
        'Module 7: Health, Safety & Security Compliance' => [
            ['title' => 'Introduction to Health & Safety', 'url' => 'https://www.youtube.com/watch?v=W--iI196xjA '],
            ['title' => 'Warehouse Safety Overview', 'url' => 'https://www.youtube.com/watch?v=pADOS7ofCtQ '],
            ['title' => '5S Concept', 'url' => ' https://www.youtube.com/watch?v=_PiuiOGRyaI'],
            ['title' => 'Fire Safety Training', 'url' => ' https://www.youtube.com/watch?v=ReGcUEk47zk&t=36s'],
        ],
        'Module 8: Verify GST Application' => [
            ['title' => 'Introduction to GST', 'url' => 'https://www.youtube.com/watch?v=76UUB7Vv8s8 '],
            ['title' => 'Types of GST', 'url' => ' https://www.youtube.com/watch?v=46Q6i6giItg'],
            ['title' => 'GST Calculation Workshop', 'url' => ' https://www.youtube.com/watch?v=bSDGrRtNcdk&list=PLVevUWkXP6J3COA_vVbiKk9GOxLeCn4Fs'],
        ],
        'Module 9: Employability Skills' => [
            ['title' => 'Introduction to Employability Skills', 'url' => 'https://www.youtube.com/watch?v=Jjc0EHuByqo&list=PLFXJRLJ1IaWmA3BWnt-c-RfZlrmx6tHeZ '],

            ['title' => 'Communication Skills', 'url' => 'https://www.youtube.com/watch?v=RfFbA5uHqqw '],
        ],
    ];
@endphp

<div class="max-w-7xl mx-auto px-2 sm:px-4">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">E-Learning Content</h1>
        <p class="text-gray-500 text-sm">Access your study materials and video lectures organized by module.</p>
    </div>

    <div class="space-y-8">
        @foreach($modules as $moduleName => $videos)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h2 class="text-lg font-bold text-gray-800">{{ $moduleName }}</h2>
                @if(count($videos) > 0)
                <span class="text-xs font-medium bg-indigo-100 text-indigo-700 px-2 py-1 rounded-full">{{ count($videos) }} Videos</span>
                @endif
            </div>
            <div class="p-6">
                @if(count($videos) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($videos as $video)
                    <a href="{{ $video['url'] }}" target="_blank" class="group block bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-all">
                        <div class="aspect-video bg-gray-100 relative flex items-center justify-center group-hover:bg-gray-50 h-48">
                            <i class="bi bi-youtube text-5xl text-red-600 group-hover:scale-110 transition-transform z-10"></i>
                            <div class="absolute inset-0 bg-black/5 group-hover:bg-transparent transition-colors"></div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-800 text-sm line-clamp-2 group-hover:text-indigo-600 transition-colors">{{ $video['title'] }}</h3>
                            <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
                                <i class="bi bi-play-btn"></i> Watch on YouTube
                            </p>
                        </div>
                    </a>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 text-gray-400 mb-3">
                        <i class="bi bi-camera-video-off text-xl"></i>
                    </div>
                    <p class="text-gray-500 text-sm">No videos uploaded for this module yet.</p>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
