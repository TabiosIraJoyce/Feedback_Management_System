@props([
    'feedback' => null,
    'departments' => collect(),
    'categories' => [],
    'priorities' => [],
])

<div class="grid gap-4 lg:grid-cols-2">
    <div>
        <x-input-label for="department_id" value="Department" />
        <select id="department_id" name="department_id" class="mt-1 block w-full rounded-xl border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100" required>
            <option value="">Select department</option>
            @foreach ($departments as $department)
                <option value="{{ $department->id }}" @selected(old('department_id', $feedback?->department_id) == $department->id)>{{ $department->name }} @if($department->branch) | {{ $department->branch->name }} @endif</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('department_id')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="priority" value="Priority" />
        <select id="priority" name="priority" class="mt-1 block w-full rounded-xl border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100" required>
            @foreach ($priorities as $value => $label)
                <option value="{{ $value }}" @selected(old('priority', $feedback?->priority ?? 'medium') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('priority')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="employee_name" value="Employee Name" />
        <x-text-input id="employee_name" name="employee_name" class="mt-1 block w-full" :value="old('employee_name', $feedback?->employee_name)" />
        <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Leave blank if you want your account name used automatically.</p>
        <x-input-error :messages="$errors->get('employee_name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="category" value="Category" />
        <select id="category" name="category" class="mt-1 block w-full rounded-xl border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100" required>
            @foreach ($categories as $value => $label)
                <option value="{{ $value }}" @selected(old('category', $feedback?->category) === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('category')" class="mt-2" />
    </div>

    <div class="lg:col-span-2">
        <x-input-label for="subject" value="Subject" />
        <x-text-input id="subject" name="subject" class="mt-1 block w-full" :value="old('subject', $feedback?->subject)" required />
        <x-input-error :messages="$errors->get('subject')" class="mt-2" />
    </div>

    <div class="lg:col-span-2">
        <x-input-label for="description" value="Description" />
        <textarea id="description" name="description" rows="6" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100" required>{{ old('description', $feedback?->description) }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>

    <div class="lg:col-span-2">
        <x-input-label for="attachment" value="Attachment / Photo" />
        <input id="attachment" name="attachment" type="file" accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx" class="mt-1 block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100" />
        <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Supports photos and documents up to 5MB.</p>
        <x-input-error :messages="$errors->get('attachment')" class="mt-2" />
    </div>

    <label class="lg:col-span-2 flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4 text-sm font-medium text-slate-700 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200">
        <input type="checkbox" name="is_anonymous" value="1" @checked(old('is_anonymous', $feedback?->is_anonymous)) class="rounded border-slate-300 text-cyan-600 focus:ring-cyan-500">
        Submit anonymously to reviewers while keeping the report linked to your account for tracking
    </label>
</div>
