@extends('layouts.dashboard')

@section('title', __('campaigns.new'))
@section('breadcrumb', __('nav.marketing'))
@section('header_title', __('campaigns.new'))

@section('content')
<div class="max-w-3xl mx-auto">

    <a href="{{ route('campaigns.index') }}"
       class="inline-flex items-center gap-1.5 text-sm font-medium text-[var(--text-muted)] hover:text-white transition-colors mb-6">
        <x-lucide-arrow-left class="w-4 h-4" />
        {{ __('campaigns.back') }}
    </a>

    <div class="card p-6 sm:p-8">
        <div class="mb-8 pb-6 border-b border-[var(--border-subtle)]">
            <h2 class="text-xl font-bold text-white tracking-tight">{{ __('campaigns.create_title') }}</h2>
            <p class="text-sm text-[var(--text-muted)] mt-1">{{ __('campaigns.create_sub') }}</p>
        </div>

        <form action="{{ route('campaigns.store') }}" method="POST" novalidate>
            @csrf
            @include('campaigns._form', ['campaign' => null])

            <div class="flex items-center justify-end gap-3 pt-6 border-t border-[var(--border-subtle)]">
                <a href="{{ route('campaigns.index') }}" class="btn-secondary">{{ __('common.cancel') }}</a>
                <button type="submit" class="btn-primary">
                    <x-lucide-plus class="w-4 h-4" />
                    {{ __('campaigns.create_btn') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
