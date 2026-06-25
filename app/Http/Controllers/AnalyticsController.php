<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Lead;
use Illuminate\Support\Facades\Auth;

class AnalyticsController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        abort_unless($user->hasPermissionTo('analytics.view'), 403);

        $totalLeads     = Lead::count();
        $wonLeads       = Lead::where('status', 'won')->count();
        $totalCampaigns = Campaign::count();
        $activeCampaigns = Campaign::where('status', 'active')->count();

        $totalBudget = (float) Campaign::sum('budget');
        $totalSpent  = (float) Campaign::sum('spent');

        $conversionRate = $totalLeads > 0 ? round(($wonLeads / $totalLeads) * 100, 1) : 0;

        // Mijozlar holati bo'yicha taqsimot
        $leadsByStatus = Lead::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')->pluck('total', 'status')->toArray();

        // Manba bo'yicha taqsimot
        $leadsBySource = Lead::selectRaw('source, COUNT(*) as total')
            ->groupBy('source')->pluck('total', 'source')->toArray();

        // Kanal bo'yicha byudjet
        $budgetByChannel = Campaign::selectRaw('channel, SUM(budget) as total')
            ->groupBy('channel')->pluck('total', 'channel')->toArray();

        // Eng samarali kampaniyalar (won mijozlar soni bo'yicha)
        $topCampaigns = Campaign::withCount([
            'leads',
            'leads as won_leads_count' => fn($q) => $q->where('status', 'won'),
        ])->orderByDesc('won_leads_count')->take(5)->get();

        return view('analytics.index', compact(
            'totalLeads', 'wonLeads', 'totalCampaigns', 'activeCampaigns',
            'totalBudget', 'totalSpent', 'conversionRate',
            'leadsByStatus', 'leadsBySource', 'budgetByChannel', 'topCampaigns'
        ));
    }
}
