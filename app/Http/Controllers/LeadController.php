<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeadController extends Controller
{
    private const SOURCES  = ['website', 'social', 'referral', 'ads', 'event', 'other'];
    private const STATUSES = ['new', 'contacted', 'qualified', 'won', 'lost'];

    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        abort_unless($user->hasPermissionTo('leads.view'), 403);

        $search    = $request->input('search');
        $status    = in_array($request->input('status'), self::STATUSES) ? $request->input('status') : null;
        $sort      = in_array($request->input('sort'), ['id', 'name', 'value']) ? $request->input('sort') : 'id';
        $direction = $request->input('direction') === 'desc' ? 'desc' : 'asc';

        $leads = Lead::with(['campaign', 'assignee'])
            ->when($search, fn($q) => $q->where(fn($q) => $q
                ->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('company', 'like', "%{$search}%")
            ))
            ->when($status, fn($q) => $q->where('status', $status))
            ->orderBy($sort, $direction)
            ->paginate(15)
            ->withQueryString();

        return view('leads.index', compact('leads'));
    }

    public function create()
    {
        /** @var User $user */
        $user = Auth::user();
        abort_unless($user->hasPermissionTo('leads.create'), 403);

        return view('leads.create', $this->formData());
    }

    public function store(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        abort_unless($user->hasPermissionTo('leads.create'), 403);

        $lead = Lead::create($this->validateData($request));

        return redirect()->route('leads.edit', $lead)
            ->with('success', __('leads.created', ['name' => $lead->name]));
    }

    public function edit(Lead $lead)
    {
        /** @var User $user */
        $user = Auth::user();
        abort_unless($user->hasPermissionTo('leads.edit'), 403);

        return view('leads.edit', array_merge(['lead' => $lead], $this->formData()));
    }

    public function update(Request $request, Lead $lead)
    {
        /** @var User $user */
        $user = Auth::user();
        abort_unless($user->hasPermissionTo('leads.edit'), 403);

        $lead->update($this->validateData($request));

        return redirect()->route('leads.edit', $lead)
            ->with('success', __('leads.updated', ['name' => $lead->name]));
    }

    public function destroy(Lead $lead)
    {
        /** @var User $user */
        $user = Auth::user();
        abort_unless($user->hasPermissionTo('leads.delete'), 403);

        $name = $lead->name;
        $lead->delete();

        return redirect()->route('leads.index')
            ->with('success', __('leads.deleted', ['name' => $name]));
    }

    private function formData(): array
    {
        return [
            'campaigns' => Campaign::orderBy('name')->get(['id', 'name']),
            'users'     => User::orderBy('name')->get(['id', 'name']),
            'sources'   => self::SOURCES,
            'statuses'  => self::STATUSES,
        ];
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['nullable', 'email', 'max:255'],
            'phone'       => ['nullable', 'string', 'max:32'],
            'company'     => ['nullable', 'string', 'max:255'],
            'source'      => ['required', 'string', 'in:' . implode(',', self::SOURCES)],
            'status'      => ['required', 'string', 'in:' . implode(',', self::STATUSES)],
            'value'       => ['nullable', 'numeric', 'min:0', 'max:9999999999'],
            'campaign_id' => ['nullable', 'exists:campaigns,id'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'notes'       => ['nullable', 'string', 'max:2000'],
        ], [
            'name.required'   => 'Mijoz ismi majburiy.',
            'email.email'     => 'To\'g\'ri email kiriting.',
            'source.required' => 'Manbani tanlang.',
            'status.required' => 'Holatni tanlang.',
        ]);
    }
}
