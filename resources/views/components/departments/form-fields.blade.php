@props([
    'branches',
    'department' => null,
    'prefix' => 'department',
    'errorsBag' => $errors,
])

<div data-dept-prefix="{{ $prefix }}" class="grid gap-4 lg:grid-cols-2">
    <div>
        <x-input-label :for="$prefix.'-branch'" value="Branch" />
        <select id="{{ $prefix }}-branch" name="branch_id" class="mt-1 block w-full rounded-xl border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100" required>
            <option value="">Select branch</option>
            @foreach ($branches as $branch)
                <option value="{{ $branch->id }}" @selected(old('branch_id', $department?->branch_id) == $branch->id)>{{ $branch->name }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errorsBag->get('branch_id')" class="mt-2" />
    </div>

    <div>
        <x-input-label :for="$prefix.'-status'" value="Status" />
        <select id="{{ $prefix }}-status" name="status" class="mt-1 block w-full rounded-xl border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100" required>
            <option value="active" @selected(old('status', $department?->status) === 'active')>Active</option>
            <option value="inactive" @selected(old('status', $department?->status) === 'inactive')>Inactive</option>
        </select>
        <x-input-error :messages="$errorsBag->get('status')" class="mt-2" />
    </div>

    <div>
        <x-input-label :for="$prefix.'-name'" value="Department Name" />
        <x-text-input :id="$prefix.'-name'" name="name" data-dept-name="true" class="mt-1 block w-full" :value="old('name', $department?->name)" required />
        <x-input-error :messages="$errorsBag->get('name')" class="mt-2" />
    </div>

    <div>
        <x-input-label :for="$prefix.'-code'" value="Code" />
        <x-text-input :id="$prefix.'-code'" name="code" class="mt-1 block w-full" :value="old('code', $department?->code)" required />
        <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Used to build the QR path, for example `FUEL` becomes `/feedback/solsona/fuel`.</p>
        <x-input-error :messages="$errorsBag->get('code')" class="mt-2" />
    </div>

    <div class="lg:col-span-2">
        <x-input-label :for="$prefix.'-description'" value="Description" />
        <textarea id="{{ $prefix }}-description" name="description" data-dept-description="true" rows="3" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100">{{ old('description', $department?->description) }}</textarea>
        <x-input-error :messages="$errorsBag->get('description')" class="mt-2" />
    </div>

    <div class="lg:col-span-2">
        <x-input-label :for="$prefix.'-logo'" value="Logo" />
        <input id="{{ $prefix }}-logo" name="logo" type="file" accept="image/*" class="mt-1 block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100" />
        <x-input-error :messages="$errorsBag->get('logo')" class="mt-2" />
    </div>
</div>

<script>
    (function () {
        const init = function () {
            try {
                const prefix = @json($prefix);
                console.debug('dept component init, prefix=', prefix);
                const root = document.querySelector('[data-dept-prefix="' + prefix + '"]');
                console.debug('dept root=', root);
                if (!root) return;

                // Try to locate the name/description inputs inside this component instance.
                const nameEl = root.querySelector('[data-dept-name]') || root.querySelector('[name="name"]') || root.querySelector('#' + prefix + '-name');
                const descEl = root.querySelector('[data-dept-description]') || root.querySelector('[name="description"]') || root.querySelector('#' + prefix + '-description');
                console.debug('dept nameEl=', nameEl, 'descEl=', descEl);
                if (!nameEl || !descEl) return;

                let lastGenerated = '';

                function generateDescription(name) {
                    const trimmed = (name || '').trim();
                    if (!trimmed) return '';
                    return `${trimmed} Department handles inquiries, operations, and feedback related to ${trimmed}.`;
                }

                const applyIfEmpty = function () {
                    const generated = generateDescription(nameEl.value);
                    if (descEl.value === '' || descEl.value === lastGenerated) {
                        descEl.value = generated;
                        lastGenerated = generated;
                    }
                };

                nameEl.addEventListener('input', applyIfEmpty);

                // Apply on init in case the name already has a value (editing existing department)
                applyIfEmpty();
            } catch (e) {
                console.error('dept auto-fill error', e);
            }
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            init();
        }
    })();
</script>
