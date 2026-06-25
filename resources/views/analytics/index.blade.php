@extends('layouts.dashboard')

@section('title', __('analytics.title'))
@section('breadcrumb', __('nav.marketing'))
@section('header_title', __('analytics.title'))

@section('content')
@php
    $statusLabelList  = array_map(fn($k) => __('leads.status_options.'.$k), array_keys($leadsByStatus));
    $sourceLabelList  = array_map(fn($k) => __('leads.source_options.'.$k), array_keys($leadsBySource));
    $channelLabelList = array_map(fn($k) => __('campaigns.channel_options.'.$k), array_keys($budgetByChannel));

    $funnelLabels = [
        'new'       => __('analytics.funnel_new'),
        'contacted' => __('analytics.funnel_contacted'),
        'qualified' => __('analytics.funnel_qualified'),
        'won'       => __('analytics.funnel_won'),
    ];
    $funnelMax = max(1, $funnel['new']);
@endphp

<div class="space-y-6">

    {{-- ====================== KPI CARDS ====================== --}}
    <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
        @php
            $kpis = [
                [__('analytics.kpi_total_leads'), number_format($totalLeads, 0, '.', ' '), __('analytics.won_suffix', ['count' => $wonLeads]), 'contact', 'var(--accent)'],
                [__('analytics.kpi_conversion'), $conversionRate.'%', __('analytics.conv_sub'), 'trending-up', 'var(--success)'],
                [__('analytics.kpi_roi'), $roi.'%', __('analytics.roi_sub'), 'percent', 'var(--accent-alt)'],
                [__('analytics.kpi_pipeline'), number_format($pipelineValue, 0, '.', ' '), __('analytics.pipeline_sub'), 'git-branch', 'var(--info)'],
                [__('analytics.kpi_campaigns'), $totalCampaigns, __('analytics.active_suffix', ['count' => $activeCampaigns]), 'megaphone', 'var(--accent)'],
                [__('analytics.kpi_budget'), $budgetUsage.'%', __('analytics.budget_sub', ['amount' => number_format($totalSpent, 0, '.', ' ')]), 'wallet', 'var(--warning)'],
            ];
        @endphp
        @foreach($kpis as [$label, $value, $sub, $icon, $color])
        <div class="card p-4 sm:p-5 card-hover">
            <div class="flex items-start justify-between gap-2">
                <p class="text-[0.65rem] font-bold uppercase tracking-widest text-[var(--text-muted)] leading-tight">{{ $label }}</p>
                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background: color-mix(in srgb, {{ $color }} 12%, transparent); border: 1px solid color-mix(in srgb, {{ $color }} 30%, transparent);">
                    @svg('lucide-'.$icon, 'w-4 h-4', ['style' => 'color: '.$color])
                </div>
            </div>
            <p class="mt-2 text-2xl font-extrabold text-white tracking-tight">{{ $value }}</p>
            <p class="mt-1 text-[0.7rem] text-[var(--text-muted)] truncate">{{ $sub }}</p>
        </div>
        @endforeach
    </div>

    {{-- ====================== TREND (LINE) ====================== --}}
    <div class="card p-6">
        <div class="flex items-center justify-between mb-1">
            <div>
                <h3 class="text-sm font-bold text-white uppercase tracking-wider">{{ __('analytics.trend_title') }}</h3>
                <p class="text-xs text-[var(--text-muted)] mt-0.5">{{ __('analytics.trend_sub') }}</p>
            </div>
            <div class="flex items-center gap-4 text-xs">
                <span class="inline-flex items-center gap-1.5"><span class="w-3 h-3 rounded-full" style="background: var(--accent);"></span><span class="text-[var(--text-secondary)]">{{ __('analytics.legend_new') }}</span></span>
                <span class="inline-flex items-center gap-1.5"><span class="w-3 h-3 rounded-full" style="background: var(--success);"></span><span class="text-[var(--text-secondary)]">{{ __('analytics.legend_won') }}</span></span>
            </div>
        </div>
        <div class="relative" style="height: 300px;"><canvas id="chartTrend"></canvas></div>
    </div>

    {{-- ====================== DOUGHNUTS ====================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card p-6">
            <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-4">{{ __('analytics.status_title') }}</h3>
            <div class="relative" style="height: 280px;"><canvas id="chartStatus"></canvas></div>
        </div>
        <div class="card p-6">
            <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-4">{{ __('analytics.source_title') }}</h3>
            <div class="relative" style="height: 280px;"><canvas id="chartSource"></canvas></div>
        </div>
    </div>

    {{-- ====================== FUNNEL + CHANNEL BAR ====================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Funnel --}}
        <div class="card p-6">
            <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-5">{{ __('analytics.funnel_title') }}</h3>
            <div class="space-y-3">
                @foreach($funnel as $stage => $count)
                @php
                    $pct = round($count / $funnelMax * 100);
                    $conv = $loop->first ? 100 : ($funnel['new'] > 0 ? round($count / $funnel['new'] * 100) : 0);
                @endphp
                <div>
                    <div class="flex items-center justify-between text-sm mb-1.5">
                        <span class="text-[var(--text-secondary)] font-medium">{{ $funnelLabels[$stage] }}</span>
                        <span class="text-white font-bold">{{ number_format($count, 0, '.', ' ') }} <span class="text-[var(--text-muted)] font-normal text-xs">({{ $conv }}%)</span></span>
                    </div>
                    <div class="h-7 rounded-[var(--radius-md)] bg-white/5 overflow-hidden">
                        <div class="h-full rounded-[var(--radius-md)] flex items-center justify-end pr-2 transition-all duration-700"
                             style="width: {{ max(4, $pct) }}%; background: var(--accent-gradient);">
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-5 pt-4 border-t border-[var(--border-subtle)] flex items-center justify-between text-xs">
                <span class="text-[var(--text-muted)]">{{ __('analytics.total_conversion') }}</span>
                <span class="badge badge-success">{{ $conversionRate }}%</span>
            </div>
        </div>

        {{-- Budget vs Spent by channel --}}
        <div class="card p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-white uppercase tracking-wider">{{ __('analytics.channel_title') }}</h3>
                <div class="flex items-center gap-4 text-xs">
                    <span class="inline-flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm" style="background: var(--accent-alt);"></span><span class="text-[var(--text-secondary)]">{{ __('analytics.legend_budget') }}</span></span>
                    <span class="inline-flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm" style="background: var(--accent);"></span><span class="text-[var(--text-secondary)]">{{ __('analytics.legend_spent') }}</span></span>
                </div>
            </div>
            <div class="relative" style="height: 280px;"><canvas id="chartChannel"></canvas></div>
        </div>
    </div>

    {{-- ====================== TOP CAMPAIGNS ====================== --}}
    <div class="card overflow-hidden">
        <div class="px-6 py-4 border-b border-[var(--border-subtle)]">
            <h3 class="text-sm font-bold text-white uppercase tracking-wider">{{ __('analytics.top_title') }}</h3>
        </div>
        <div class="overflow-x-auto scroll-area">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-[var(--border-subtle)]" style="background: rgba(255,255,255,0.01);">
                        <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider text-[var(--text-muted)]">{{ __('analytics.col_campaign') }}</th>
                        <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider text-[var(--text-muted)]">{{ __('analytics.col_total') }}</th>
                        <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider text-[var(--text-muted)]">{{ __('analytics.col_won') }}</th>
                        <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider text-[var(--text-muted)] w-1/3">{{ __('analytics.col_efficiency') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border-subtle)] text-sm">
                    @forelse($topCampaigns as $campaign)
                    @php $eff = $campaign->leads_count > 0 ? round($campaign->won_leads_count / $campaign->leads_count * 100) : 0; @endphp
                    <tr class="hover:bg-white/[0.02] transition-colors">
                        <td class="px-6 py-3.5 text-white font-semibold">{{ $campaign->name }}</td>
                        <td class="px-6 py-3.5 text-[var(--text-secondary)]">{{ $campaign->leads_count }}</td>
                        <td class="px-6 py-3.5"><span class="badge badge-success">{{ $campaign->won_leads_count }}</span></td>
                        <td class="px-6 py-3.5">
                            <div class="flex items-center gap-2">
                                <div class="flex-1 h-2 rounded-full bg-white/10 overflow-hidden">
                                    <div class="h-full rounded-full" style="width: {{ $eff }}%; background: var(--accent-gradient);"></div>
                                </div>
                                <span class="text-xs text-[var(--text-muted)] font-mono w-9 text-right">{{ $eff }}%</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-6 py-12 text-center text-sm text-[var(--text-muted)]">{{ __('analytics.top_empty') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    (function () {
        if (typeof Chart === 'undefined') return;

        const css = getComputedStyle(document.documentElement);
        const v = (name, fallback) => (css.getPropertyValue(name).trim() || fallback);

        const accent  = v('--accent', '#f8704f');
        const accentA = v('--accent-alt', '#f59e0b');
        const success = v('--success', '#22c55e');
        const palette = ['#fb7185', '#f8704f', '#f59e0b', '#facc15', '#fb923c', '#e879f9'];

        const textColor = v('--text-secondary', '#c9b0a3');
        const gridColor = 'rgba(148, 120, 100, 0.14)';

        Chart.defaults.color = textColor;
        Chart.defaults.font.family = "'Inter', sans-serif";
        Chart.defaults.font.size = 12;

        const fmt = (n) => new Intl.NumberFormat('{{ app()->getLocale() === 'en' ? 'en-US' : 'uz-UZ' }}').format(n);

        // helper: vertical gradient
        function grad(ctx, area, from, to) {
            const g = ctx.createLinearGradient(0, area.top, 0, area.bottom);
            g.addColorStop(0, from); g.addColorStop(1, to);
            return g;
        }

        // ── Trend (line/area) ──
        const trendEl = document.getElementById('chartTrend');
        if (trendEl) new Chart(trendEl, {
            type: 'line',
            data: {
                labels: @json($months),
                datasets: [
                    {
                        label: @json(__('analytics.legend_new')), data: @json($monthlyLeads),
                        borderColor: accent, tension: 0.4, borderWidth: 2.5,
                        pointBackgroundColor: accent, pointRadius: 3, pointHoverRadius: 5, fill: true,
                        backgroundColor: (c) => { const {ctx, chartArea} = c.chart; return chartArea ? grad(ctx, chartArea, accent + '55', accent + '00') : accent + '20'; },
                    },
                    {
                        label: @json(__('analytics.legend_won')), data: @json($monthlyWon),
                        borderColor: success, tension: 0.4, borderWidth: 2.5,
                        pointBackgroundColor: success, pointRadius: 3, pointHoverRadius: 5, fill: true,
                        backgroundColor: (c) => { const {ctx, chartArea} = c.chart; return chartArea ? grad(ctx, chartArea, success + '40', success + '00') : success + '20'; },
                    },
                ],
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { color: gridColor, drawBorder: false } },
                    y: { grid: { color: gridColor, drawBorder: false }, beginAtZero: true, ticks: { precision: 0 } },
                },
            },
        });

        // ── Doughnut helper ──
        function doughnut(id, labels, data) {
            const el = document.getElementById(id);
            if (!el) return;
            new Chart(el, {
                type: 'doughnut',
                data: { labels, datasets: [{ data, backgroundColor: palette, borderColor: v('--bg-raised', '#271b14'), borderWidth: 3, hoverOffset: 6 }] },
                options: {
                    responsive: true, maintainAspectRatio: false, cutout: '64%',
                    plugins: {
                        legend: { position: 'right', labels: { boxWidth: 10, boxHeight: 10, padding: 14, usePointStyle: true } },
                        tooltip: { callbacks: { label: (c) => ' ' + c.label + ': ' + fmt(c.raw) } },
                    },
                },
            });
        }
        doughnut('chartStatus', @json($statusLabelList), @json(array_values($leadsByStatus)));
        doughnut('chartSource', @json($sourceLabelList), @json(array_values($leadsBySource)));

        // ── Channel grouped bar ──
        const chEl = document.getElementById('chartChannel');
        if (chEl) new Chart(chEl, {
            type: 'bar',
            data: {
                labels: @json($channelLabelList),
                datasets: [
                    { label: @json(__('analytics.legend_budget')), data: @json(array_values($budgetByChannel)), backgroundColor: accentA, borderRadius: 6, maxBarThickness: 22 },
                    { label: @json(__('analytics.legend_spent')), data: @json(array_values($spentByChannel)), backgroundColor: accent, borderRadius: 6, maxBarThickness: 22 },
                ],
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { callbacks: { label: (c) => ' ' + c.dataset.label + ': ' + fmt(c.raw) } } },
                scales: {
                    x: { grid: { display: false } },
                    y: { grid: { color: gridColor, drawBorder: false }, beginAtZero: true, ticks: { callback: (val) => fmt(val) } },
                },
            },
        });
    })();
</script>
@endpush
@endsection
