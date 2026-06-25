@extends('layouts.dashboard')

@section('title', __('leads.title'))
@section('breadcrumb', __('nav.marketing'))
@section('header_title', __('leads.title'))

@section('content')
@php
    $sort = request('sort', 'id');
    $direction = request('direction', 'asc');
    $sortUrl = fn(string $col) => request()->fullUrlWithQuery([
        'sort' => $col,
        'direction' => ($sort === $col && $direction === 'asc') ? 'desc' : 'asc',
        'page' => 1,
    ]);
    $statusStyles = [
        'new'       => 'bg-sky-500/10 text-sky-400 border border-sky-500/25',
        'contacted' => 'bg-amber-500/10 text-amber-400 border border-amber-500/25',
        'qualified' => 'bg-violet-500/10 text-violet-400 border border-violet-500/25',
        'won'       => 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/25',
        'lost'      => 'bg-[var(--accent-soft)] text-[var(--accent-hover)] border border-[var(--accent-border)]',
    ];
@endphp
<div x-data="{ deleteModalOpen: false, deleteUrl: '', deleteName: '' }">

    <div x-show="deleteModalOpen" x-cloak class="fixed inset-0 z-[60]" role="dialog" aria-modal="true" style="display:none;">
        <div x-show="deleteModalOpen"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            @click="deleteModalOpen = false"
            class="fixed inset-0 bg-black/70 backdrop-blur-sm cursor-pointer"></div>

        <div class="flex min-h-screen items-center justify-center p-4 relative z-10">
            <div x-show="deleteModalOpen"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                @click.away="deleteModalOpen = false"
                class="w-full max-w-md rounded-[var(--radius-lg)] bg-[var(--bg-raised)] border border-[var(--border-strong)] shadow-2xl shadow-black/60 p-8">

                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-[var(--accent-soft)] border border-[var(--accent-border)]">
                    <x-lucide-trash-2 class="h-6 w-6 text-[var(--accent)]" />
                </div>

                <div class="mt-5 text-center">
                    <h3 class="text-xl font-bold text-white tracking-tight">{{ __('leads.delete_title') }}</h3>
                    <p class="mt-2 text-sm text-[var(--text-secondary)] leading-relaxed">
                        {{ __('common.are_you_sure') }}
                        <span class="font-semibold text-white" x-text='"\"" + deleteName + "\""'></span>?
                        {{ __('common.delete_warning') }}
                    </p>
                </div>

                <div class="mt-7 flex flex-col sm:flex-row gap-3">
                    <button type="button" @click="deleteModalOpen = false" class="btn-secondary flex-1">{{ __('common.cancel') }}</button>
                    <form :action="deleteUrl" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-5 py-[0.625rem] rounded-[var(--radius-md)] text-sm font-semibold text-white bg-[var(--accent)] hover:bg-[var(--accent-hover)] transition-colors shadow-lg shadow-[var(--accent-glow)] cursor-pointer">
                            <x-lucide-trash-2 class="w-4 h-4" />
                            {{ __('common.delete') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <form method="GET" action="{{ request()->url() }}" class="flex items-center gap-2 flex-wrap">
            <div class="relative">
                <x-lucide-search class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-[var(--text-muted)] pointer-events-none" />
                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('leads.search_ph') }}"
                    class="pl-11 pr-4 py-2 text-sm rounded-[var(--radius-md)] bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-white placeholder-[var(--text-muted)] focus:outline-none focus:border-[var(--accent)] w-56 transition-colors">
            </div>
            <select name="status" onchange="this.form.submit()" class="py-2 px-3 text-sm rounded-[var(--radius-md)] bg-[var(--bg-surface)] border border-[var(--border-subtle)] text-white focus:outline-none focus:border-[var(--accent)] cursor-pointer">
                <option value="">{{ __('common.all_statuses') }}</option>
                @foreach(__('leads.status_options') as $key => $label)
                <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            @if(request('search') || request('status'))
            <a href="{{ request()->url() }}" class="p-2 rounded-lg text-[var(--text-muted)] hover:text-white hover:bg-white/5 transition-colors" title="{{ __('common.clear') }}">
                <x-lucide-x class="w-4 h-4" />
            </a>
            @endif
        </form>

        @can('leads.create')
        <a href="{{ route('leads.create') }}" class="btn-primary self-start sm:self-auto">
            <x-lucide-user-plus class="w-4 h-4" />
            {{ __('leads.new') }}
        </a>
        @endcan
    </div>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto scroll-area">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-[var(--border-subtle)]" style="background: rgba(255,255,255,0.01);">
                        <th class="px-6 py-3.5 text-xs font-bold uppercase tracking-wider text-[var(--text-muted)]">
                            <a href="{{ $sortUrl('name') }}" class="inline-flex items-center gap-1 hover:text-white transition-colors {{ $sort === 'name' ? 'text-white' : '' }}">{{ __('leads.col_client') }}
                                @if($sort === 'name') <x-lucide-chevron-up class="w-3 h-3 {{ $direction === 'desc' ? 'rotate-180' : '' }}" /> @else <x-lucide-chevrons-up-down class="w-3 h-3 opacity-40" /> @endif
                            </a>
                        </th>
                        <th class="px-6 py-3.5 text-xs font-bold uppercase tracking-wider text-[var(--text-muted)]">{{ __('leads.col_source') }}</th>
                        <th class="px-6 py-3.5 text-xs font-bold uppercase tracking-wider text-[var(--text-muted)]">{{ __('leads.col_status') }}</th>
                        <th class="px-6 py-3.5 text-xs font-bold uppercase tracking-wider text-[var(--text-muted)]">{{ __('leads.col_campaign') }}</th>
                        <th class="px-6 py-3.5 text-xs font-bold uppercase tracking-wider text-[var(--text-muted)]">
                            <a href="{{ $sortUrl('value') }}" class="inline-flex items-center gap-1 hover:text-white transition-colors {{ $sort === 'value' ? 'text-white' : '' }}">{{ __('leads.col_value') }}
                                @if($sort === 'value') <x-lucide-chevron-up class="w-3 h-3 {{ $direction === 'desc' ? 'rotate-180' : '' }}" /> @else <x-lucide-chevrons-up-down class="w-3 h-3 opacity-40" /> @endif
                            </a>
                        </th>
                        <th class="px-6 py-3.5 text-xs font-bold uppercase tracking-wider text-[var(--text-muted)] text-right">{{ __('common.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border-subtle)] text-sm">
                    @forelse($leads as $lead)
                    @php
                        $avatarColors = ['bg-rose-500/20 text-rose-400', 'bg-orange-500/20 text-orange-400', 'bg-amber-500/20 text-amber-400', 'bg-pink-500/20 text-pink-400'];
                        $avatarClass = $avatarColors[$lead->id % count($avatarColors)];
                    @endphp
                    <tr class="transition-colors hover:bg-white/[0.02]">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-sm flex-shrink-0 {{ $avatarClass }}">
                                    {{ strtoupper(substr($lead->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-white truncate">{{ $lead->name }}</p>
                                    <p class="text-[0.68rem] text-[var(--text-muted)] truncate">{{ $lead->company ?: $lead->email ?: '—' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-[var(--text-secondary)]">{{ __('leads.source_options.'.$lead->source) }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wide {{ $statusStyles[$lead->status] }}">
                                {{ __('leads.status_options.'.$lead->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-[var(--text-secondary)] text-sm truncate max-w-[12rem]">
                            {{ $lead->campaign?->name ?? '—' }}
                        </td>
                        <td class="px-6 py-4 text-white font-semibold font-mono text-sm">{{ number_format($lead->value, 0, '.', ' ') }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="inline-flex items-center gap-1">
                                @can('leads.edit')
                                <a href="{{ route('leads.edit', $lead) }}"
                                    class="p-2 rounded-lg text-[var(--text-muted)] hover:text-amber-400 hover:bg-amber-500/10 transition-colors" title="{{ __('common.edit') }}">
                                    <x-lucide-pencil class="w-4 h-4" />
                                </a>
                                @endcan
                                @can('leads.delete')
                                <button type="button"
                                    @click="deleteUrl = @js(route('leads.destroy', $lead)); deleteName = @js($lead->name); deleteModalOpen = true"
                                    class="p-2 rounded-lg text-[var(--text-muted)] hover:text-[var(--accent)] hover:bg-[var(--accent-soft)] transition-colors" title="{{ __('common.delete') }}">
                                    <x-lucide-trash-2 class="w-4 h-4" />
                                </button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-12 h-12 rounded-2xl flex items-center justify-center" style="background: rgba(255,255,255,0.04); border: 1px solid var(--border-subtle);">
                                    <x-lucide-contact class="w-6 h-6 text-[var(--text-muted)]" />
                                </div>
                                <p class="text-sm font-semibold text-[var(--text-secondary)]">{{ __('leads.empty_title') }}</p>
                                <p class="text-xs text-[var(--text-muted)]">{{ __('leads.empty_text') }}</p>
                                @can('leads.create')
                                <a href="{{ route('leads.create') }}" class="btn-primary !text-xs !py-2 !px-4 mt-1">
                                    <x-lucide-user-plus class="w-3.5 h-3.5" />
                                    {{ __('leads.new') }}
                                </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($leads->hasPages())
        <div class="px-6 py-4 border-t border-[var(--border-subtle)]">
            {{ $leads->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
