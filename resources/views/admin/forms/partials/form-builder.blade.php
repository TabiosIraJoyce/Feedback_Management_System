@php
    $defaultFields = [];
    if (isset($form)) {
        $defaultFields = $form->fields->map(function ($field) {
            return [
                'label' => $field->label,
                'name' => $field->name,
                'type' => $field->type,
                'placeholder' => $field->placeholder,
                'options' => implode(', ', $field->options ?? []),
                'required' => $field->required,
                'order' => $field->order,
            ];
        })->all();
    }
@endphp

<div x-data="formBuilder({ fields: @json(old('fields', $defaultFields)), defaultBranch: @json(old('branch_id', $form->branch_id ?? null)), defaultName: @json(old('name', $form->name ?? '')), defaultDescription: @json(old('description', $form->description ?? '')), defaultActive: @json(old('is_active', $form->is_active ?? true)) })" x-init="init()" class="space-y-6">
    <div class="grid gap-4 lg:grid-cols-3">
        <div class="lg:col-span-2 space-y-3">
            <x-input-label for="name" value="Form name" />
            <x-text-input id="name" name="name" x-model="name" class="mt-1 block w-full" required />
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm" />
        </div>
        <div class="space-y-3">
            <x-input-label for="branch_id" value="Branch" />
            <select id="branch_id" name="branch_id" x-model="branch_id" class="mt-1 block w-full rounded-xl border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500" required>
                <option value="">Select branch</option>
                @foreach ($branches as $branch)
                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('branch_id')" class="mt-2 text-sm" />
        </div>
    </div>

    <div>
        <x-input-label for="description" value="Description" />
        <textarea id="description" name="description" x-model="description" rows="3" class="mt-1 block w-full rounded-3xl border border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500"></textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2 text-sm" />
    </div>

    <label class="inline-flex items-center gap-3 text-sm font-medium text-slate-700">
        <input type="checkbox" value="1" x-model="is_active" class="rounded border-slate-300 text-cyan-600 focus:ring-cyan-500">
        Publish form for this branch
    </label>

    <div class="space-y-4 rounded-3xl border border-slate-200 bg-slate-50 p-6">
        <div class="flex items-center justify-between gap-3">
            <div>
                <h3 class="text-lg font-semibold text-slate-900">Form fields</h3>
                <p class="text-sm text-slate-500">Add fields and configure the public-facing feedback form behavior.</p>
            </div>
            <button type="button" @click="addField()" class="inline-flex items-center gap-2 rounded-3xl bg-cyan-500 px-4 py-2 text-sm font-semibold text-white hover:bg-cyan-400">
                Add field
            </button>
        </div>

        <template x-if="fields.length === 0">
            <div class="rounded-3xl border border-dashed border-slate-300 bg-white p-8 text-center text-sm text-slate-500">
                Add your first form field to begin building the form.
            </div>
        </template>

        <div class="space-y-4" x-show="fields.length > 0">
            <template x-for="(field, index) in fields" :key="index">
                <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm">
                    <div class="flex items-center justify-between gap-3">
                        <div class="text-sm font-semibold text-slate-900">Field <span x-text="index + 1"></span></div>
                        <button type="button" @click="removeField(index)" class="text-sm font-medium text-rose-600 hover:text-rose-500">Remove</button>
                    </div>

                    <div class="grid gap-4 lg:grid-cols-5">
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-slate-700">Label</label>
                            <input type="text" x-model="field.label" :name="`fields[${index}][label]`" class="mt-2 w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm" required />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700">Name</label>
                            <input type="text" x-model="field.name" :name="`fields[${index}][name]`" class="mt-2 w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm" required />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700">Type</label>
                            <select x-model="field.type" :name="`fields[${index}][type]`" class="mt-2 w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm">
                                <option value="text">Text</option>
                                <option value="textarea">Textarea</option>
                                <option value="email">Email</option>
                                <option value="tel">Phone</option>
                                <option value="number">Number</option>
                                <option value="date">Date</option>
                                <option value="select">Select</option>
                                <option value="radio">Radio</option>
                                <option value="checkbox">Checkbox</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700">Placeholder</label>
                            <input type="text" x-model="field.placeholder" :name="`fields[${index}][placeholder]`" class="mt-2 w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm" />
                        </div>

                        <div class="flex items-end gap-3">
                            <label class="inline-flex items-center gap-2 text-sm text-slate-700">
                                <input type="checkbox" x-model="field.required" :name="`fields[${index}][required]`" class="rounded border-slate-300 text-cyan-600 focus:ring-cyan-500" />
                                Required
                            </label>
                            <input type="hidden" :name="`fields[${index}][order]`" :value="index" />
                        </div>
                    </div>

                    <div x-show="['select', 'radio', 'checkbox'].includes(field.type)" class="mt-4">
                        <label class="block text-sm font-medium text-slate-700">Options</label>
                        <input type="text" x-model="field.options" :name="`fields[${index}][options]`" placeholder="Enter options separated by commas" class="mt-2 w-full rounded-3xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm" />
                        <p class="mt-2 text-sm text-slate-500">Provide choices separated by commas for select, radio, or checkbox fields.</p>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <input type="hidden" name="is_active" x-bind:value="is_active ? 1 : 0" />

    <script>
        function formBuilder(state) {
            return {
                name: state.defaultName,
                description: state.defaultDescription,
                branch_id: state.defaultBranch,
                is_active: state.defaultActive,
                fields: state.fields || [],

                init() {
                    if (this.fields.length === 0) {
                        this.addField();
                    }
                },

                addField() {
                    this.fields.push({
                        label: 'New field',
                        name: `field_${this.fields.length + 1}`,
                        type: 'text',
                        placeholder: '',
                        options: '',
                        required: false,
                        order: this.fields.length,
                    });
                },

                removeField(index) {
                    this.fields.splice(index, 1);
                },
            };
        }
    </script>
</div>
