@extends('layouts.dashboard')

@section('title', 'Mijozni tahrirlash')
@section('breadcrumb', 'Marketing')
@section('header_title', 'Mijozni tahrirlash')

@section('content')
<div class="max-w-3xl mx-auto">

    <a href="{{ route('leads.index') }}"
       class="inline-flex items-center gap-1.5 text-sm font-medium text-[var(--text-muted)] hover:text-white transition-colors mb-6">
        <x-lucide-arrow-left class="w-4 h-4" />
        Mijozlarga qaytish
    </a>

    <div class="card p-6 sm:p-8">
        <div class="mb-8 pb-6 border-b border-[var(--border-subtle)]">
            <h2 class="text-xl font-bold text-white tracking-tight">{{ $lead->name }}</h2>
            <p class="text-sm text-[var(--text-muted)] mt-1">Mijoz ma'lumotlarini yangilang</p>
        </div>

        <form action="{{ route('leads.update', $lead) }}" method="POST" novalidate>
            @csrf
            @method('PUT')
            @include('leads._form', ['lead' => $lead])

            <div class="flex items-center justify-end gap-3 pt-6 border-t border-[var(--border-subtle)]">
                <a href="{{ route('leads.index') }}" class="btn-secondary">Bekor qilish</a>
                <button type="submit" class="btn-primary">
                    <x-lucide-save class="w-4 h-4" />
                    Saqlash
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
