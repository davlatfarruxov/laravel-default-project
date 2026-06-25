@extends('layouts.dashboard')

@section('title', __('info.tips_title'))
@section('breadcrumb', __('info.section'))
@section('header_title', __('info.tips_title'))

@section('content')
<div class="space-y-6">
    <div class="card p-6 sm:p-7 flex items-center gap-4 relative overflow-hidden">
        <div class="absolute inset-0 opacity-60" style="background: var(--accent-gradient-soft);"></div>
        <div class="relative w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 text-white" style="background: var(--accent-gradient);">
            <x-lucide-lightbulb class="w-6 h-6" />
        </div>
        <div class="relative">
            <h2 class="text-lg font-bold text-white">{{ __('info.tips_title') }}</h2>
            <p class="text-sm text-[var(--text-secondary)] mt-0.5">{{ __('info.tips_sub') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($tips as $tip)
        <div class="card card-hover p-5">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-4" style="background: var(--accent-soft); border: 1px solid var(--accent-border);">
                @svg('lucide-'.$tip['icon'], 'w-5 h-5', ['style' => 'color: var(--accent-hover)'])
            </div>
            <h3 class="text-sm font-bold text-white mb-1.5">{{ $tip['title'] }}</h3>
            <p class="text-sm text-[var(--text-secondary)] leading-relaxed">{{ $tip['text'] }}</p>
        </div>
        @endforeach
    </div>
</div>
@endsection
