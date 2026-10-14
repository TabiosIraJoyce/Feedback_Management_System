@props([
    'complaint' => null,
    'branches',
    'departments' => collect(),
    'assignableUsers',
    'submitLabel' => 'Save Complaint',
])

@php
    $attachmentRules = app(\App\Services\AttachmentValidationService::class);
    $selectedBranchId = (string) old('branch_id', $complaint?->branch_id ?? auth()->user()->branch_id);
    $selectedDepartmentId = (string) old('department_id', $complaint?->department_id);
    $departmentOptions = \Illuminate\Support\Js::from(
        $departments->map(fn ($department) => [
            'id' => (string) $department->id,
            'name' => $department->name,
            'branch_id' => (string) $department->branch_id,
            'branch_name' => $department->branch?->name,
        ])->values()
    );
    $isCustomer = auth()->user()->hasRole('Customer');
@endphp

@if ($isCustomer)
    <div class="space-y-5">
        <div>
            <x-input-label for="branch_id" value="1. BRANCH" />
            <select id="branch_id" name="branch_id" class="mt-1 block w-full rounded-xl border-slate-300 bg-white text-slate-900 shadow-sm focus:border-cyan-500 focus:ring-cyan-500" required>
                @foreach ($branches as $branch)
                    <option value="{{ $branch->id }}" @selected(old('branch_id', $complaint?->branch_id ?? auth()->user()->branch_id) == $branch->id)>{{ $branch->name }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('branch_id')" class="mt-2" />
        </div>

        <input type="hidden" name="customer_name" value="{{ old('customer_name', $complaint?->customer_name ?? auth()->user()->name) }}" />
        <input type="hidden" name="customer_email" value="{{ old('customer_email', $complaint?->customer_email ?? auth()->user()->email) }}" />
        <input type="hidden" name="priority" value="normal" />

        <div>
            <x-input-label for="category" value="2. WHAT IS THIS ABOUT?" />
            <select id="category" name="category" class="mt-1 block w-full rounded border-slate-400 bg-white text-slate-900 shadow-sm focus:border-blue-600 focus:ring-blue-600" required>
                @foreach (['general' => 'Service quality', 'product' => 'Product', 'staff' => 'Staff', 'facility' => 'Facility', 'other' => 'Other'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('category', $complaint?->category ?? 'general') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <input type="hidden" name="subject" value="{{ old('subject', $complaint?->subject ?? 'Customer complaint') }}" />
            <x-input-error :messages="$errors->get('subject')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="message" value="3. YOUR MESSAGE *" />
            <textarea id="message" name="message" rows="5" class="mt-1 block w-full rounded border-slate-400 bg-white text-slate-900 placeholder:text-slate-400 shadow-sm focus:border-blue-600 focus:ring-blue-600" required placeholder="Please share what happened or how we can improve.">{{ old('message', $complaint?->message) }}</textarea>
            <x-input-error :messages="$errors->get('message')" class="mt-2" />
        </div>

        <div>
            <p class="text-sm font-medium text-slate-700">How was your experience?</p>
            <div class="mt-2 flex gap-3">
                <label class="cursor-pointer">
                    <input type="radio" name="sentiment" value="positive" class="peer sr-only" @checked(old('sentiment', $complaint?->sentiment) === 'positive') />
                    <span class="inline-flex items-center gap-2 rounded-lg border border-slate-300 px-4 py-2 text-sm peer-checked:border-cyan-600 peer-checked:bg-cyan-50">👍 Good</span>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="sentiment" value="negative" class="peer sr-only" @checked(old('sentiment', $complaint?->sentiment) === 'negative') />
                    <span class="inline-flex items-center gap-2 rounded-lg border border-slate-300 px-4 py-2 text-sm peer-checked:border-cyan-600 peer-checked:bg-cyan-50">👎 Needs improvement</span>
                </label>
            </div>
            <x-input-error :messages="$errors->get('sentiment')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="customer_phone" value="YOUR CONTACT (OPTIONAL)" />
            <x-text-input id="customer_phone" name="customer_phone" class="mt-1 block w-full" :value="old('customer_phone', $complaint?->customer_phone)" placeholder="Mobile number" />
        </div>

        <div>
            <x-input-label for="attachments" value="PHOTO OR FILE (OPTIONAL)" />
            <input id="attachments" name="attachments[]" type="file" multiple accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx" class="mt-1 block w-full text-sm" />
            <x-input-error :messages="$errors->get('attachments')" class="mt-2" />
            <x-input-error :messages="$errors->get('attachments.*')" class="mt-2" />
        </div>

        <div class="flex items-center gap-3">
            <x-primary-button>{{ $submitLabel }}</x-primary-button>
            <a href="{{ route('complaints.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-700">Cancel</a>
        </div>
    </div>
@else
<div
    x-data="complaintForm({
        branchId: {{ \Illuminate\Support\Js::from($selectedBranchId) }},
        departmentId: {{ \Illuminate\Support\Js::from($selectedDepartmentId) }},
        departments: {{ $departmentOptions }}
    })"
    x-init="init()"
    class="grid gap-4 md:grid-cols-2"
>
    <div>
        <x-input-label for="branch_id" value="Branch" />
        <select id="branch_id" name="branch_id" x-model="branchId" @change="syncDepartment()" class="mt-1 block w-full rounded-xl border-slate-300 bg-white text-slate-900 shadow-sm focus:border-cyan-500 focus:ring-cyan-500" required>
            @foreach ($branches as $branch)
                <option value="{{ $branch->id }}" @selected(old('branch_id', $complaint?->branch_id ?? auth()->user()->branch_id) == $branch->id)>{{ $branch->name }}</option>
            @endforeach
        </select>
    </div>

    @can('assign complaints')
        <div>
            <x-input-label for="assigned_to_user_id" value="Assign To" />
            <select id="assigned_to_user_id" name="assigned_to_user_id" class="mt-1 block w-full rounded-xl border-slate-300 bg-white text-slate-900 shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
                <option value="">Unassigned</option>
                @foreach ($assignableUsers as $assignableUser)
                    <option value="{{ $assignableUser->id }}" @selected(old('assigned_to_user_id', $complaint?->assigned_to_user_id) == $assignableUser->id)>{{ $assignableUser->name }}</option>
                @endforeach
            </select>
        </div>
    @endcan

    <div>
        <x-input-label for="customer_name" value="Customer / Submitter Name" />
        <x-text-input id="customer_name" name="customer_name" class="mt-1 block w-full" :value="old('customer_name', $complaint?->customer_name ?? auth()->user()->name)" required />
    </div>

    <div>
        <x-input-label for="customer_email" value="Email" />
        <x-text-input id="customer_email" name="customer_email" type="email" class="mt-1 block w-full" :value="old('customer_email', $complaint?->customer_email ?? auth()->user()->email)" />
    </div>

    <div>
        <x-input-label for="customer_phone" value="Phone" />
        <x-text-input id="customer_phone" name="customer_phone" class="mt-1 block w-full" :value="old('customer_phone', $complaint?->customer_phone)" />
    </div>

    <div>
        <x-input-label for="subject" value="Subject" />
        <x-text-input id="subject" name="subject" class="mt-1 block w-full" :value="old('subject', $complaint?->subject)" required />
    </div>

    <div>
        <x-input-label for="department_id" value="Assigned Department" />
        <select id="department_id" name="department_id" x-model="departmentId" class="mt-1 block w-full rounded-xl border-slate-300 bg-white text-slate-900 shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
            <option value="">No department</option>
            <template x-for="department in filteredDepartments" :key="department.id">
                <option :value="department.id" x-text="department.branch_name ? `${department.name} | ${department.branch_name}` : department.name"></option>
            </template>
        </select>
    </div>

    <div>
        <x-input-label for="category" value="Category" />
        <x-text-input id="category" name="category" class="mt-1 block w-full" :value="old('category', $complaint?->category ?? 'general')" required />
    </div>

    <div>
        <x-input-label for="priority" value="Priority" />
        <select id="priority" name="priority" class="mt-1 block w-full rounded-xl border-slate-300 bg-white text-slate-900 shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
            @foreach (['low', 'normal', 'high', 'urgent'] as $priority)
                <option value="{{ $priority }}" @selected(old('priority', $complaint?->priority ?? 'normal') === $priority)>{{ ucfirst($priority) }}</option>
            @endforeach
        </select>
    </div>

    @if ($complaint && auth()->user()->can('manage complaints'))
        <div>
            <x-input-label for="status" value="Status" />
            <select id="status" name="status" class="mt-1 block w-full rounded-xl border-slate-300 bg-white text-slate-900 shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
                @foreach (['open', 'in_progress', 'resolved', 'closed', 'reopened'] as $status)
                    <option value="{{ $status }}" @selected(old('status', $complaint->status) === $status)>{{ ucwords(str_replace('_', ' ', $status)) }}</option>
                @endforeach
            </select>
        </div>
    @endif

    <div class="md:col-span-2">
        <x-input-label for="message" value="Complaint Details" />
        <textarea id="message" name="message" rows="6" class="mt-1 block w-full rounded-xl border-slate-300 bg-white text-slate-900 placeholder:text-slate-400 shadow-sm focus:border-cyan-500 focus:ring-cyan-500" required>{{ old('message', $complaint?->message) }}</textarea>
    </div>

    <div class="md:col-span-2">
        <x-input-label for="due_at" value="Target Resolution Date" />
        <x-text-input id="due_at" name="due_at" type="datetime-local" class="mt-1 block w-full" :value="old('due_at', optional($complaint?->due_at)->format('Y-m-d\TH:i'))" />
    </div>

    <div class="md:col-span-2">
        <x-input-label for="resolution_summary" value="Public Resolution Summary" />
        <textarea id="resolution_summary" name="resolution_summary" rows="4" class="mt-1 block w-full rounded-xl border-slate-300 bg-white text-slate-900 placeholder:text-slate-400 shadow-sm focus:border-cyan-500 focus:ring-cyan-500">{{ old('resolution_summary', $complaint?->resolution_summary) }}</textarea>
        <p class="mt-2 text-xs text-slate-500">Visible in the public tracking portal after resolution or closure.</p>
    </div>

    <div class="md:col-span-2">
        <label class="inline-flex items-center gap-3 text-sm text-slate-700">
            <input type="checkbox" name="allow_public_reopen" value="1" class="rounded border-slate-300 text-cyan-600 shadow-sm focus:ring-cyan-500" @checked(old('allow_public_reopen', $complaint?->allow_public_reopen ?? true)) />
            <span>Allow customers to request reopen from the public tracking portal</span>
        </label>
    </div>

    <div class="md:col-span-2">
        <x-input-label for="attachments" value="Attachments" />
        <input id="attachments" name="attachments[]" type="file" multiple accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx" class="mt-1 block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100" />
        <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">{{ $attachmentRules->uploadHelpText() }}</p>
        <x-input-error :messages="$errors->get('attachments')" class="mt-2" />
        <x-input-error :messages="$errors->get('attachments.*')" class="mt-2" />
    </div>
</div>

<div class="mt-6 flex items-center gap-3">
    <x-primary-button>{{ $submitLabel }}</x-primary-button>
    <a href="{{ route('complaints.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-700">Cancel</a>
</div>

@endif

@once
    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('complaintForm', (config) => ({
                    branchId: config.branchId ?? '',
                    departmentId: config.departmentId ?? '',
                    departments: config.departments ?? [],
                    get filteredDepartments() {
                        return this.departments.filter((department) => department.branch_id === this.branchId);
                    },
                    syncDepartment() {
                        if (!this.filteredDepartments.some((department) => department.id === this.departmentId)) {
                            this.departmentId = '';
                        }
                    },
                    init() {
                        this.syncDepartment();
                    },
                }));
            });
        </script>
    @endpush
@endonce
