@php $l = $lead ?? null; @endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div>
        <label for="name" class="block text-sm font-semibold text-[var(--text-secondary)] mb-2">
            {{ __('leads.name') }} <span class="text-[var(--accent)]">*</span>
        </label>
        <input type="text" id="name" name="name" value="{{ old('name', $l->name ?? '') }}"
               class="input @error('name') border-[var(--accent)] @enderror" placeholder="{{ __('leads.name_ph') }}" required>
        @error('name')<p class="text-[var(--accent)] text-xs mt-2 flex items-center gap-1"><x-lucide-alert-circle class="w-3.5 h-3.5 flex-shrink-0" />{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="company" class="block text-sm font-semibold text-[var(--text-secondary)] mb-2">{{ __('leads.company') }}</label>
        <input type="text" id="company" name="company" value="{{ old('company', $l->company ?? '') }}" class="input" placeholder="{{ __('leads.company_ph') }}">
    </div>

    <div>
        <label for="email" class="block text-sm font-semibold text-[var(--text-secondary)] mb-2">{{ __('leads.email') }}</label>
        <input type="email" id="email" name="email" value="{{ old('email', $l->email ?? '') }}"
               class="input @error('email') border-[var(--accent)] @enderror" placeholder="email@example.com">
        @error('email')<p class="text-[var(--accent)] text-xs mt-2 flex items-center gap-1"><x-lucide-alert-circle class="w-3.5 h-3.5 flex-shrink-0" />{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="phone" class="block text-sm font-semibold text-[var(--text-secondary)] mb-2">{{ __('leads.phone') }}</label>
        <input type="text" id="phone" name="phone" value="{{ old('phone', $l->phone ?? '') }}" class="input" placeholder="+998 90 123 45 67">
    </div>

    <div>
        <label for="source" class="block text-sm font-semibold text-[var(--text-secondary)] mb-2">
            {{ __('leads.source') }} <span class="text-[var(--accent)]">*</span>
        </label>
        <select name="source" id="source" class="input cursor-pointer @error('source') border-[var(--accent)] @enderror" required>
            @foreach($sources as $s)
            <option value="{{ $s }}" {{ old('source', $l->source ?? 'website') === $s ? 'selected' : '' }}>{{ __('leads.source_options.'.$s) }}</option>
            @endforeach
        </select>
        @error('source')<p class="text-[var(--accent)] text-xs mt-2 flex items-center gap-1"><x-lucide-alert-circle class="w-3.5 h-3.5 flex-shrink-0" />{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="status" class="block text-sm font-semibold text-[var(--text-secondary)] mb-2">
            {{ __('leads.status') }} <span class="text-[var(--accent)]">*</span>
        </label>
        <select name="status" id="status" class="input cursor-pointer @error('status') border-[var(--accent)] @enderror" required>
            @foreach($statuses as $st)
            <option value="{{ $st }}" {{ old('status', $l->status ?? 'new') === $st ? 'selected' : '' }}>{{ __('leads.status_options.'.$st) }}</option>
            @endforeach
        </select>
        @error('status')<p class="text-[var(--accent)] text-xs mt-2 flex items-center gap-1"><x-lucide-alert-circle class="w-3.5 h-3.5 flex-shrink-0" />{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="value" class="block text-sm font-semibold text-[var(--text-secondary)] mb-2">{{ __('leads.value') }}</label>
        <input type="number" step="0.01" min="0" id="value" name="value" value="{{ old('value', $l->value ?? '0') }}"
               class="input @error('value') border-[var(--accent)] @enderror">
        @error('value')<p class="text-[var(--accent)] text-xs mt-2 flex items-center gap-1"><x-lucide-alert-circle class="w-3.5 h-3.5 flex-shrink-0" />{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="campaign_id" class="block text-sm font-semibold text-[var(--text-secondary)] mb-2">{{ __('leads.campaign') }}</label>
        <select name="campaign_id" id="campaign_id" class="input cursor-pointer">
            <option value="">{{ __('common.not_selected') }}</option>
            @foreach($campaigns as $campaign)
            <option value="{{ $campaign->id }}" {{ (string) old('campaign_id', $l->campaign_id ?? '') === (string) $campaign->id ? 'selected' : '' }}>{{ $campaign->name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="assigned_to" class="block text-sm font-semibold text-[var(--text-secondary)] mb-2">{{ __('leads.assignee') }}</label>
        <select name="assigned_to" id="assigned_to" class="input cursor-pointer">
            <option value="">{{ __('common.not_selected') }}</option>
            @foreach($users as $u)
            <option value="{{ $u->id }}" {{ (string) old('assigned_to', $l->assigned_to ?? '') === (string) $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="mb-8">
    <label for="notes" class="block text-sm font-semibold text-[var(--text-secondary)] mb-2">{{ __('leads.notes') }}</label>
    <textarea id="notes" name="notes" rows="3" class="input" placeholder="{{ __('leads.notes_ph') }}">{{ old('notes', $l->notes ?? '') }}</textarea>
</div>
