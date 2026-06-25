@extends('layouts.dashboard')

@section('title', __('info.faq_title'))
@section('breadcrumb', __('info.section'))
@section('header_title', __('info.faq_title'))

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="card p-6 sm:p-7 flex items-center gap-4 relative overflow-hidden">
        <div class="absolute inset-0 opacity-60" style="background: var(--accent-gradient-soft);"></div>
        <div class="relative w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 text-white" style="background: var(--accent-gradient);">
            <x-lucide-help-circle class="w-6 h-6" />
        </div>
        <div class="relative">
            <h2 class="text-lg font-bold text-white">{{ __('info.faq_title') }}</h2>
            <p class="text-sm text-[var(--text-secondary)] mt-0.5">{{ __('info.faq_sub') }}</p>
        </div>
    </div>

    <div class="space-y-3">
        @foreach($faqs as $i => $faq)
        <div class="card overflow-hidden" x-data="{ open: {{ $i === 0 ? 'true' : 'false' }} }">
            <button @click="open = !open" type="button"
                class="w-full flex items-center justify-between gap-4 px-5 py-4 text-left cursor-pointer hover:bg-white/[0.02] transition-colors">
                <span class="text-sm font-semibold text-white">{{ $faq['q'] }}</span>
                <x-lucide-chevron-down class="w-4 h-4 text-[var(--text-muted)] flex-shrink-0 transition-transform duration-300" ::class="open ? 'rotate-180' : ''" />
            </button>
            <div x-show="open" x-collapse>
                <p class="px-5 pb-4 text-sm text-[var(--text-secondary)] leading-relaxed">{{ $faq['a'] }}</p>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
