<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Lead;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AnalyticsController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        abort_unless($user->hasPermissionTo('analytics.view'), 403);

        $totalLeads      = Lead::count();
        $wonLeads        = Lead::where('status', 'won')->count();
        $lostLeads       = Lead::where('status', 'lost')->count();
        $totalCampaigns  = Campaign::count();
        $activeCampaigns = Campaign::where('status', 'active')->count();

        $totalBudget = (float) Campaign::sum('budget');
        $totalSpent  = (float) Campaign::sum('spent');
        $pipelineValue = (float) Lead::whereNotIn('status', ['won', 'lost'])->sum('value');
        $wonValue      = (float) Lead::where('status', 'won')->sum('value');

        $conversionRate = $totalLeads > 0 ? round(($wonLeads / $totalLeads) * 100, 1) : 0;
        $budgetUsage    = $totalBudget > 0 ? round(($totalSpent / $totalBudget) * 100, 1) : 0;
        // ROI = (won qiymat - sarflangan) / sarflangan
        $roi = $totalSpent > 0 ? round((($wonValue - $totalSpent) / $totalSpent) * 100, 1) : 0;

        // ── Taqsimotlar (barcha mumkin bo'lgan kalitlar 0 bilan to'ldiriladi) ──
        $statusKeys  = ['new', 'contacted', 'qualified', 'won', 'lost'];
        $sourceKeys  = ['website', 'social', 'referral', 'ads', 'event', 'other'];
        $channelKeys = ['email', 'social', 'seo', 'ppc', 'sms', 'event'];

        $leadsByStatus = $this->fill($statusKeys, Lead::selectRaw('status, COUNT(*) as total')->groupBy('status')->pluck('total', 'status'));
        $leadsBySource = $this->fill($sourceKeys, Lead::selectRaw('source, COUNT(*) as total')->groupBy('source')->pluck('total', 'source'));
        $budgetByChannel = $this->fill($channelKeys, Campaign::selectRaw('channel, SUM(budget) as total')->groupBy('channel')->pluck('total', 'channel'));
        $spentByChannel  = $this->fill($channelKeys, Campaign::selectRaw('channel, SUM(spent) as total')->groupBy('channel')->pluck('total', 'channel'));

        // ── Voronka (funnel): new → contacted → qualified → won ──
        $funnel = [
            'new'       => $leadsByStatus['new'] + $leadsByStatus['contacted'] + $leadsByStatus['qualified'] + $leadsByStatus['won'],
            'contacted' => $leadsByStatus['contacted'] + $leadsByStatus['qualified'] + $leadsByStatus['won'],
            'qualified' => $leadsByStatus['qualified'] + $leadsByStatus['won'],
            'won'       => $leadsByStatus['won'],
        ];

        // ── Oylik trend (so'nggi 6 oy) — DB'dan mustaqil, PHP'da hisoblanadi ──
        $months      = [];
        $monthlyLeads = [];
        $monthlyWon   = [];
        for ($i = 5; $i >= 0; $i--) {
            $start = Carbon::now()->startOfMonth()->subMonths($i);
            $end   = (clone $start)->endOfMonth();
            $months[]       = $start->translatedFormat('M');
            $monthlyLeads[] = Lead::whereBetween('created_at', [$start, $end])->count();
            $monthlyWon[]   = Lead::where('status', 'won')->whereBetween('created_at', [$start, $end])->count();
        }

        // ── Eng samarali kampaniyalar ──
        $topCampaigns = Campaign::withCount([
            'leads',
            'leads as won_leads_count' => fn($q) => $q->where('status', 'won'),
        ])->orderByDesc('won_leads_count')->orderByDesc('leads_count')->take(6)->get();

        return view('analytics.index', compact(
            'totalLeads', 'wonLeads', 'lostLeads', 'totalCampaigns', 'activeCampaigns',
            'totalBudget', 'totalSpent', 'pipelineValue', 'wonValue',
            'conversionRate', 'budgetUsage', 'roi',
            'leadsByStatus', 'leadsBySource', 'budgetByChannel', 'spentByChannel',
            'funnel', 'months', 'monthlyLeads', 'monthlyWon', 'topCampaigns'
        ));
    }

    /** Berilgan kalitlar bo'yicha massivni to'ldiradi (yetishmaganlari 0). */
    private function fill(array $keys, $collection): array
    {
        $result = [];
        foreach ($keys as $key) {
            $result[$key] = (float) ($collection[$key] ?? 0);
        }
        return $result;
    }
}
