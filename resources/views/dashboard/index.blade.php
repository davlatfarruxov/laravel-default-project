@extends('layouts.dashboard')

@section('title', 'Boshqaruv paneli')
@section('breadcrumb', 'MarketingPro')
@section('header_title', 'Boshqaruv paneli')

@section('content')
<div class="space-y-8">

    {{-- Welcome card --}}
    <div class="card p-6 sm:p-8 flex flex-col sm:flex-row items-start sm:items-center gap-5">
        <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-white font-bold text-xl flex-shrink-0 shadow-lg"
             style="background: linear-gradient(135deg, var(--accent-hover), var(--accent-alt));">
            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
        </div>
        <div class="flex-1 min-w-0">
            <h2 class="text-xl font-bold text-white leading-tight">
                Xush kelibsiz, {{ auth()->user()->name ?? 'Admin' }}
            </h2>
            <p class="mt-1 text-sm text-[var(--text-secondary)]">
                {{ auth()->user()->email ?? '' }}
                @if(auth()->user()->getRoleNames()->isNotEmpty())
                <span class="ml-2 badge badge-accent">{{ auth()->user()->getRoleNames()->first() }}</span>
                @endif
            </p>
        </div>
        <div class="text-right flex-shrink-0">
            <p class="text-xs text-[var(--text-muted)] font-mono">{{ now()->format('d M Y') }}</p>
            <p class="text-xs text-[var(--text-muted)] font-mono mt-0.5">{{ now()->format('H:i') }}</p>
        </div>
    </div>

    {{-- Marketing stats --}}
    @canany(['leads.view', 'campaigns.view', 'analytics.view'])
    <div>
        <h3 class="text-xs font-bold text-[var(--text-muted)] uppercase tracking-widest mb-3">Marketing</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

            @can('leads.view')
            <div class="card card-hover p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-widest text-[var(--text-muted)]">Mijozlar</p>
                        <p class="mt-2 text-3xl font-extrabold text-white">{{ \App\Models\Lead::count() }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: var(--accent-soft); border: 1px solid var(--accent-border);">
                        <x-lucide-contact class="w-5 h-5" style="color: var(--accent-hover);" />
                    </div>
                </div>
                <a href="{{ route('leads.index') }}" class="mt-4 inline-flex items-center gap-1.5 text-xs font-semibold text-[var(--accent-hover)] hover:text-[var(--accent-alt)] transition-colors">
                    Barchasini ko'rish <x-lucide-arrow-right class="w-3.5 h-3.5" />
                </a>
            </div>
            @endcan

            @can('campaigns.view')
            <div class="card card-hover p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-widest text-[var(--text-muted)]">Kampaniyalar</p>
                        <p class="mt-2 text-3xl font-extrabold text-white">{{ \App\Models\Campaign::count() }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(245, 158, 11,0.10); border: 1px solid rgba(245, 158, 11,0.25);">
                        <x-lucide-megaphone class="w-5 h-5" style="color: var(--accent-alt);" />
                    </div>
                </div>
                <a href="{{ route('campaigns.index') }}" class="mt-4 inline-flex items-center gap-1.5 text-xs font-semibold text-[var(--accent-hover)] hover:text-[var(--accent-alt)] transition-colors">
                    Barchasini ko'rish <x-lucide-arrow-right class="w-3.5 h-3.5" />
                </a>
            </div>
            @endcan

            @can('analytics.view')
            <div class="card card-hover p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-widest text-[var(--text-muted)]">Konversiya</p>
                        @php $won = \App\Models\Lead::where('status', 'won')->count(); $all = \App\Models\Lead::count(); @endphp
                        <p class="mt-2 text-3xl font-extrabold text-white">{{ $all > 0 ? round($won / $all * 100, 1) : 0 }}%</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(52,211,153,0.10); border: 1px solid rgba(52,211,153,0.25);">
                        <x-lucide-trending-up class="w-5 h-5 text-emerald-400" />
                    </div>
                </div>
                <a href="{{ route('analytics.index') }}" class="mt-4 inline-flex items-center gap-1.5 text-xs font-semibold text-[var(--accent-hover)] hover:text-[var(--accent-alt)] transition-colors">
                    Tahlilni ko'rish <x-lucide-arrow-right class="w-3.5 h-3.5" />
                </a>
            </div>
            @endcan
        </div>

        {{-- Marketing quick actions --}}
        <div class="card p-6 mt-4">
            <h3 class="text-sm font-bold text-white mb-4 uppercase tracking-wider">Tezkor amallar</h3>
            <div class="flex flex-wrap gap-3">
                @can('leads.create')
                <a href="{{ route('leads.create') }}" class="btn-primary">
                    <x-lucide-user-plus class="w-4 h-4" /> Mijoz +
                </a>
                @endcan
                @can('campaigns.create')
                <a href="{{ route('campaigns.create') }}" class="btn-secondary">
                    <x-lucide-plus class="w-4 h-4" /> Kampaniya +
                </a>
                @endcan
                @can('analytics.view')
                <a href="{{ route('analytics.index') }}" class="btn-ghost">
                    <x-lucide-bar-chart-3 class="w-4 h-4" /> Tahlil
                </a>
                @endcan
            </div>
        </div>
    </div>
    @endcanany

    {{-- Platform stats --}}
    @canany(['users.view', 'roles.view'])
    <div>
        <h3 class="text-xs font-bold text-[var(--text-muted)] uppercase tracking-widest mb-3">Statistika</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

            @can('users.view')
            <div class="card card-hover p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-widest text-[var(--text-muted)]">Foydalanuvchilar</p>
                        <p class="mt-2 text-3xl font-extrabold text-white">{{ \App\Models\User::count() }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: var(--accent-soft); border: 1px solid var(--accent-border);">
                        <x-lucide-user-cog class="w-5 h-5" style="color: var(--accent-hover);" />
                    </div>
                </div>
                <a href="{{ route('users.index') }}" class="mt-4 inline-flex items-center gap-1.5 text-xs font-semibold text-[var(--accent-hover)] hover:text-[var(--accent-alt)] transition-colors">
                    Barchasini ko'rish <x-lucide-arrow-right class="w-3.5 h-3.5" />
                </a>
            </div>
            @endcan

            @can('roles.view')
            <div class="card card-hover p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-widest text-[var(--text-muted)]">Rollar</p>
                        <p class="mt-2 text-3xl font-extrabold text-white">{{ \Spatie\Permission\Models\Role::count() }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(245, 158, 11,0.10); border: 1px solid rgba(245, 158, 11,0.25);">
                        <x-lucide-shield-check class="w-5 h-5" style="color: var(--accent-alt);" />
                    </div>
                </div>
                <a href="{{ route('roles.index') }}" class="mt-4 inline-flex items-center gap-1.5 text-xs font-semibold text-[var(--accent-hover)] hover:text-[var(--accent-alt)] transition-colors">
                    Barchasini ko'rish <x-lucide-arrow-right class="w-3.5 h-3.5" />
                </a>
            </div>
            @endcan

            <div class="card p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-widest text-[var(--text-muted)]">MarketingPro</p>
                        <p class="mt-2 text-lg font-extrabold text-white">v3.0</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center overflow-hidden border border-[var(--border-strong)] bg-white/5 p-1">
                        <img src="{{ asset('/images/logo.png') }}" alt="MarketingPro" class="w-full h-full object-contain">
                    </div>
                </div>
                <p class="mt-4 text-xs text-[var(--text-muted)]">Laravel {{ app()->version() }}</p>
            </div>
        </div>

        {{-- Quick actions --}}
        <div class="card p-6 mt-4">
            <h3 class="text-sm font-bold text-white mb-4 uppercase tracking-wider">Boshqaruv amallari</h3>
            <div class="flex flex-wrap gap-3">
                @can('users.create')
                <a href="{{ route('users.create') }}" class="btn-primary">
                    <x-lucide-user-plus class="w-4 h-4" /> Foydalanuvchi +
                </a>
                @endcan
                @can('roles.create')
                <a href="{{ route('roles.create') }}" class="btn-secondary">
                    <x-lucide-shield-plus class="w-4 h-4" /> Rol +
                </a>
                @endcan
                @can('roles.view')
                <a href="{{ route('roles.index') }}" class="btn-ghost">
                    <x-lucide-shield-check class="w-4 h-4" /> Rollar
                </a>
                @endcan
            </div>
        </div>
    </div>
    @endcanany

</div>
@endsection
