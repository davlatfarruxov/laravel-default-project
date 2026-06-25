<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CampaignController extends Controller
{
    private const CHANNELS = ['email', 'social', 'seo', 'ppc', 'sms', 'event'];
    private const STATUSES = ['draft', 'active', 'paused', 'completed'];

    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        abort_unless($user->hasPermissionTo('campaigns.view'), 403);

        $search    = $request->input('search');
        $status    = in_array($request->input('status'), self::STATUSES) ? $request->input('status') : null;
        $sort      = in_array($request->input('sort'), ['id', 'name', 'budget', 'spent']) ? $request->input('sort') : 'id';
        $direction = $request->input('direction') === 'desc' ? 'desc' : 'asc';

        $campaigns = Campaign::withCount('leads')
            ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%"))
            ->when($status, fn($q) => $q->where('status', $status))
            ->orderBy($sort, $direction)
            ->paginate(10)
            ->withQueryString();

        return view('campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        abort_unless($user->hasPermissionTo('campaigns.create'), 403);

        return view('campaigns.create', [
            'channels' => self::CHANNELS,
            'statuses' => self::STATUSES,
        ]);
    }

    public function store(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        abort_unless($user->hasPermissionTo('campaigns.create'), 403);

        $data = $this->validateData($request);

        $campaign = Campaign::create($data);

        return redirect()->route('campaigns.edit', $campaign)
            ->with('success', __('campaigns.created', ['name' => $campaign->name]));
    }

    public function edit(Campaign $campaign)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        abort_unless($user->hasPermissionTo('campaigns.edit'), 403);

        return view('campaigns.edit', [
            'campaign' => $campaign,
            'channels' => self::CHANNELS,
            'statuses' => self::STATUSES,
        ]);
    }

    public function update(Request $request, Campaign $campaign)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        abort_unless($user->hasPermissionTo('campaigns.edit'), 403);

        $campaign->update($this->validateData($request));

        return redirect()->route('campaigns.edit', $campaign)
            ->with('success', __('campaigns.updated', ['name' => $campaign->name]));
    }

    public function destroy(Campaign $campaign)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        abort_unless($user->hasPermissionTo('campaigns.delete'), 403);

        $name = $campaign->name;
        $campaign->delete();

        return redirect()->route('campaigns.index')
            ->with('success', __('campaigns.deleted', ['name' => $name]));
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'channel'     => ['required', 'string', 'in:' . implode(',', self::CHANNELS)],
            'status'      => ['required', 'string', 'in:' . implode(',', self::STATUSES)],
            'budget'      => ['required', 'numeric', 'min:0', 'max:9999999999'],
            'spent'       => ['nullable', 'numeric', 'min:0', 'max:9999999999'],
            'start_date'  => ['nullable', 'date'],
            'end_date'    => ['nullable', 'date', 'after_or_equal:start_date'],
            'description' => ['nullable', 'string', 'max:2000'],
        ], [
            'name.required'         => 'Kampaniya nomi majburiy.',
            'channel.required'      => 'Kanalni tanlang.',
            'status.required'       => 'Holatni tanlang.',
            'budget.required'       => 'Byudjetni kiriting.',
            'budget.numeric'        => 'Byudjet raqam bo\'lishi kerak.',
            'end_date.after_or_equal' => 'Tugash sanasi boshlanish sanasidan keyin bo\'lishi kerak.',
        ]);
    }
}
