@extends('layouts.dashboard')

@section('title', __('info.news_title'))
@section('breadcrumb', __('info.section'))
@section('header_title', __('info.news_title'))

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="card p-6 sm:p-7 flex items-center gap-4 relative overflow-hidden">
        <div class="absolute inset-0 opacity-60" style="background: var(--accent-gradient-soft);"></div>
        <div class="relative w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 text-white" style="background: var(--accent-gradient);">
            <x-lucide-megaphone class="w-6 h-6" />
        </div>
        <div class="relative">
            <h2 class="text-lg font-bold text-white">{{ __('info.news_title') }}</h2>
            <p class="text-sm text-[var(--text-secondary)] mt-0.5">{{ __('info.news_sub') }}</p>
        </div>
    </div>

    <div class="space-y-4">
        @foreach($news as $item)
        <div class="card card-hover p-5 sm:p-6">
            <div class="flex items-center gap-3 mb-2">
                <span class="badge badge-accent">{{ $item['tag'] }}</span>
                <span class="text-xs text-[var(--text-muted)] font-mono">{{ \Illuminate\Support\Carbon::parse($item['date'])->translatedFormat('d M Y') }}</span>
            </div>
            <h3 class="text-base font-bold text-white mb-1.5">{{ $item['title'] }}</h3>
            <p class="text-sm text-[var(--text-secondary)] leading-relaxed">{{ $item['text'] }}</p>
        </div>
        @endforeach
    </div>
</div>
@endsection
