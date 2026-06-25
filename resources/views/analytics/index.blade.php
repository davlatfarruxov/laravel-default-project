@extends('layouts.dashboard')

@section('title', 'Tahlil')
@section('breadcrumb', 'Marketing')
@section('header_title', 'Tahlil')

@section('content')
@php
    $statusLabels = ['new' => 'Yangi', 'contacted' => 'Aloqada', 'qualified' => 'Saralangan', 'won' => 'Yutilgan', 'lost' => 'Yo\'qotilgan'];
    $sourceLabels = ['website' => 'Website', 'social' => 'Ijtimoiy', 'referral' => 'Tavsiya', 'ads' => 'Reklama', 'event' => 'Tadbir', 'other' => 'Boshqa'];
    $channelLabels = ['email' => 'Email', 'social' => 'Ijtimoiy', 'seo' => 'SEO', 'ppc' => 'PPC', 'sms' => 'SMS', 'event' => 'Tadbir'];
    $maxStatus = max(1, ...(array_values($leadsByStatus) ?: [1]));
    $maxSource = max(1, ...(array_values($leadsBySource) ?: [1]));
    $maxBudget = max(1, ...(array_values($budgetByChannel) ?: [1]));
@endphp
<div class="space-y-8">

    {{-- KPI cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="card p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-[var(--text-muted)]">Jami mijozlar</p>
                    <p class="mt-2 text-3xl font-extrabold text-white">{{ number_format($totalLeads, 0, '.', ' ') }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: var(--accent-soft); border: 1px solid var(--accent-border);">
                    <x-lucide-contact class="w-5 h-5" style="color: var(--accent-hover);" />
                </div>
            </div>
            <p class="mt-3 text-xs text-emerald-400 font-semibold">{{ $wonLeads }} yutilgan</p>
        </div>

        <div class="card p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-[var(--text-muted)]">Konversiya</p>
                    <p class="mt-2 text-3xl font-extrabold text-white">{{ $conversionRate }}%</p>
                </div>
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(52,211,153,0.10); border: 1px solid rgba(52,211,153,0.25);">
                    <x-lucide-trending-up class="w-5 h-5 text-emerald-400" />
                </div>
            </div>
            <p class="mt-3 text-xs text-[var(--text-muted)]">won / jami mijozlar</p>
        </div>

        <div class="card p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-[var(--text-muted)]">Kampaniyalar</p>
                    <p class="mt-2 text-3xl font-extrabold text-white">{{ $totalCampaigns }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(129,140,248,0.10); border: 1px solid rgba(129,140,248,0.25);">
                    <x-lucide-megaphone class="w-5 h-5 text-indigo-400" />
                </div>
            </div>
            <p class="mt-3 text-xs text-emerald-400 font-semibold">{{ $activeCampaigns }} faol</p>
        </div>

        <div class="card p-5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-[var(--text-muted)]">Byudjet</p>
                    <p class="mt-2 text-2xl font-extrabold text-white">{{ number_format($totalBudget, 0, '.', ' ') }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(245,158,11,0.10); border: 1px solid rgba(245,158,11,0.25);">
                    <x-lucide-wallet class="w-5 h-5 text-amber-400" />
                </div>
            </div>
            <p class="mt-3 text-xs text-[var(--text-muted)]">{{ number_format($totalSpent, 0, '.', ' ') }} sarflandi</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Leads by status --}}
        <div class="card p-6">
            <h3 class="text-sm font-bold text-white mb-5 uppercase tracking-wider">Mijozlar holati bo'yicha</h3>
            <div class="space-y-4">
                @forelse($leadsByStatus as $status => $count)
                <div>
                    <div class="flex items-center justify-between text-sm mb-1.5">
                        <span class="text-[var(--text-secondary)] font-medium">{{ $statusLabels[$status] ?? $status }}</span>
                        <span class="text-white font-semibold">{{ $count }}</span>
                    </div>
                    <div class="h-2 rounded-full bg-white/10 overflow-hidden">
                        <div class="h-full rounded-full" style="width: {{ round($count / $maxStatus * 100) }}%; background: linear-gradient(90deg, var(--accent-hover), var(--accent-alt));"></div>
                    </div>
                </div>
                @empty
                <p class="text-sm text-[var(--text-muted)] text-center py-6">Ma'lumot yo'q</p>
                @endforelse
            </div>
        </div>

        {{-- Leads by source --}}
        <div class="card p-6">
            <h3 class="text-sm font-bold text-white mb-5 uppercase tracking-wider">Mijozlar manbasi bo'yicha</h3>
            <div class="space-y-4">
                @forelse($leadsBySource as $source => $count)
                <div>
                    <div class="flex items-center justify-between text-sm mb-1.5">
                        <span class="text-[var(--text-secondary)] font-medium">{{ $sourceLabels[$source] ?? $source }}</span>
                        <span class="text-white font-semibold">{{ $count }}</span>
                    </div>
                    <div class="h-2 rounded-full bg-white/10 overflow-hidden">
                        <div class="h-full rounded-full" style="width: {{ round($count / $maxSource * 100) }}%; background: linear-gradient(90deg, #34d399, #60a5fa);"></div>
                    </div>
                </div>
                @empty
                <p class="text-sm text-[var(--text-muted)] text-center py-6">Ma'lumot yo'q</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Budget by channel --}}
    <div class="card p-6">
        <h3 class="text-sm font-bold text-white mb-5 uppercase tracking-wider">Kanal bo'yicha byudjet</h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 items-end" style="min-height: 140px;">
            @forelse($budgetByChannel as $channel => $total)
            <div class="flex flex-col items-center justify-end gap-2 h-full">
                <span class="text-xs text-white font-semibold">{{ number_format($total, 0, '.', ' ') }}</span>
                <div class="w-full rounded-t-lg" style="height: {{ max(6, round($total / $maxBudget * 100)) }}%; background: linear-gradient(180deg, var(--accent-alt), var(--accent-hover)); min-height: 6px;"></div>
                <span class="text-[0.68rem] text-[var(--text-muted)] uppercase tracking-wide">{{ $channelLabels[$channel] ?? $channel }}</span>
            </div>
            @empty
            <p class="text-sm text-[var(--text-muted)] col-span-full text-center py-6">Ma'lumot yo'q</p>
            @endforelse
        </div>
    </div>

    {{-- Top campaigns --}}
    <div class="card overflow-hidden">
        <div class="px-6 py-4 border-b border-[var(--border-subtle)]">
            <h3 class="text-sm font-bold text-white uppercase tracking-wider">Eng samarali kampaniyalar</h3>
        </div>
        <div class="overflow-x-auto scroll-area">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-[var(--border-subtle)]" style="background: rgba(255,255,255,0.01);">
                        <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider text-[var(--text-muted)]">Kampaniya</th>
                        <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider text-[var(--text-muted)]">Jami mijoz</th>
                        <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider text-[var(--text-muted)]">Yutilgan</th>
                        <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider text-[var(--text-muted)]">Byudjet</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border-subtle)] text-sm">
                    @forelse($topCampaigns as $campaign)
                    <tr class="hover:bg-white/[0.02] transition-colors">
                        <td class="px-6 py-3.5 text-white font-semibold">{{ $campaign->name }}</td>
                        <td class="px-6 py-3.5 text-[var(--text-secondary)]">{{ $campaign->leads_count }}</td>
                        <td class="px-6 py-3.5"><span class="badge badge-success">{{ $campaign->won_leads_count }}</span></td>
                        <td class="px-6 py-3.5 text-[var(--text-secondary)] font-mono">{{ number_format($campaign->budget, 0, '.', ' ') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-6 py-10 text-center text-sm text-[var(--text-muted)]">Kampaniyalar yo'q</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
