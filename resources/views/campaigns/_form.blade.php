@php
    $channelLabels = ['email' => 'Email', 'social' => 'Ijtimoiy tarmoq', 'seo' => 'SEO', 'ppc' => 'PPC reklama', 'sms' => 'SMS', 'event' => 'Tadbir'];
    $statusLabels = ['draft' => 'Qoralama', 'active' => 'Faol', 'paused' => 'To\'xtatilgan', 'completed' => 'Tugatilgan'];
    $c = $campaign ?? null;
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div class="md:col-span-2">
        <label for="name" class="block text-sm font-semibold text-[var(--text-secondary)] mb-2">
            Kampaniya nomi <span class="text-[var(--accent)]">*</span>
        </label>
        <input type="text" id="name" name="name" value="{{ old('name', $c->name ?? '') }}"
               class="input @error('name') border-[var(--accent)] @enderror"
               placeholder="Masalan: Yozgi chegirma kampaniyasi" required>
        @error('name')
            <p class="text-[var(--accent)] text-xs mt-2 flex items-center gap-1"><x-lucide-alert-circle class="w-3.5 h-3.5 flex-shrink-0" />{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="channel" class="block text-sm font-semibold text-[var(--text-secondary)] mb-2">
            Kanal <span class="text-[var(--accent)]">*</span>
        </label>
        <select name="channel" id="channel" class="input cursor-pointer @error('channel') border-[var(--accent)] @enderror" required>
            @foreach($channels as $ch)
            <option value="{{ $ch }}" {{ old('channel', $c->channel ?? 'email') === $ch ? 'selected' : '' }}>{{ $channelLabels[$ch] ?? $ch }}</option>
            @endforeach
        </select>
        @error('channel')<p class="text-[var(--accent)] text-xs mt-2 flex items-center gap-1"><x-lucide-alert-circle class="w-3.5 h-3.5 flex-shrink-0" />{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="status" class="block text-sm font-semibold text-[var(--text-secondary)] mb-2">
            Holat <span class="text-[var(--accent)]">*</span>
        </label>
        <select name="status" id="status" class="input cursor-pointer @error('status') border-[var(--accent)] @enderror" required>
            @foreach($statuses as $st)
            <option value="{{ $st }}" {{ old('status', $c->status ?? 'draft') === $st ? 'selected' : '' }}>{{ $statusLabels[$st] ?? $st }}</option>
            @endforeach
        </select>
        @error('status')<p class="text-[var(--accent)] text-xs mt-2 flex items-center gap-1"><x-lucide-alert-circle class="w-3.5 h-3.5 flex-shrink-0" />{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="budget" class="block text-sm font-semibold text-[var(--text-secondary)] mb-2">
            Byudjet (so'm) <span class="text-[var(--accent)]">*</span>
        </label>
        <input type="number" step="0.01" min="0" id="budget" name="budget" value="{{ old('budget', $c->budget ?? '0') }}"
               class="input @error('budget') border-[var(--accent)] @enderror" required>
        @error('budget')<p class="text-[var(--accent)] text-xs mt-2 flex items-center gap-1"><x-lucide-alert-circle class="w-3.5 h-3.5 flex-shrink-0" />{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="spent" class="block text-sm font-semibold text-[var(--text-secondary)] mb-2">Sarflangan (so'm)</label>
        <input type="number" step="0.01" min="0" id="spent" name="spent" value="{{ old('spent', $c->spent ?? '0') }}"
               class="input @error('spent') border-[var(--accent)] @enderror">
        @error('spent')<p class="text-[var(--accent)] text-xs mt-2 flex items-center gap-1"><x-lucide-alert-circle class="w-3.5 h-3.5 flex-shrink-0" />{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="start_date" class="block text-sm font-semibold text-[var(--text-secondary)] mb-2">Boshlanish sanasi</label>
        <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $c?->start_date?->format('Y-m-d')) }}" class="input">
    </div>

    <div>
        <label for="end_date" class="block text-sm font-semibold text-[var(--text-secondary)] mb-2">Tugash sanasi</label>
        <input type="date" id="end_date" name="end_date" value="{{ old('end_date', $c?->end_date?->format('Y-m-d')) }}"
               class="input @error('end_date') border-[var(--accent)] @enderror">
        @error('end_date')<p class="text-[var(--accent)] text-xs mt-2 flex items-center gap-1"><x-lucide-alert-circle class="w-3.5 h-3.5 flex-shrink-0" />{{ $message }}</p>@enderror
    </div>
</div>

<div class="mb-8">
    <label for="description" class="block text-sm font-semibold text-[var(--text-secondary)] mb-2">Tavsif</label>
    <textarea id="description" name="description" rows="3" class="input" placeholder="Kampaniya haqida qisqacha...">{{ old('description', $c->description ?? '') }}</textarea>
</div>
