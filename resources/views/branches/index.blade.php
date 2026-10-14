<x-app-layout>
        <x-slot name="header">
        <x-admin.page-header title="Department Management" description="Manage all departments and keep status up to date." icon="building-2" />
    </x-slot>

    <div class="space-y-6">
        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-slate-950">Department Directory</h3>
                    <p class="mt-1 text-sm text-slate-500">Search across departments and manage department settings.</p>
                </div>
                <form method="GET" action="{{ route('branches.index') }}" class="grid gap-3 sm:grid-cols-[minmax(0,1fr),auto] lg:w-[720px]">
                    <x-text-input type="search" name="search" placeholder="Search branch name or code" class="w-full" value="{{ request('search') }}" />

                    <select name="status" class="rounded-xl border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
                        <option value="">All statuses</option>
                        <option value="active" @selected(request('status') === 'active')>Active</option>
                        <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                    </select>
                </form>
            </div>
        </section>

        <div class="grid gap-6 xl:grid-cols-[0.95fr,1.05fr]">
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-950">Add Department</h3>

                <form method="POST" action="{{ route('branches.store') }}" enctype="multipart/form-data" class="mt-5 space-y-4">
                    @csrf

                    <div class="grid gap-4 lg:grid-cols-2">
                        <div>
                            <x-input-label for="name" value="Department Name" />
                            <x-text-input id="name" name="name" class="mt-1 block w-full" value="{{ old('name') }}" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="code" value="Code" />
                            <x-text-input id="code" name="code" class="mt-1 block w-full" value="{{ old('code') }}" required />
                            <x-input-error :messages="$errors->get('code')" class="mt-2" />
                        </div>

                        

                        <div>
                            <x-input-label for="status" value="Status" />
                            <select id="status" name="status" class="mt-1 block w-full rounded-xl border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500" required>
                                <option value="active" @selected(old('status') === 'active')>Active</option>
                                <option value="inactive" @selected(old('status') === 'inactive')>Inactive</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>
                    </div>

                    <div class="grid gap-4 lg:grid-cols-2">
                        <div>
                            <x-input-label for="address" value="Address" />
                            <x-text-input id="address" name="address" class="mt-1 block w-full" value="{{ old('address') }}" />
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="contact_number" value="Contact Number" />
                            <x-text-input id="contact_number" name="contact_number" class="mt-1 block w-full" value="{{ old('contact_number') }}" />
                            <x-input-error :messages="$errors->get('contact_number')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="email" value="Email" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" value="{{ old('email') }}" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="logo" value="Logo" />
                            <input id="logo" name="logo" type="file" accept="image/*" class="mt-1 block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500" />
                            <x-input-error :messages="$errors->get('logo')" class="mt-2" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="description" value="Description" />
                        <textarea id="description" name="description" rows="4" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500">{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <x-primary-button>Create Department</x-primary-button>
                </form>
            </section>

            <section class="space-y-4">
                @forelse ($branches as $branch)
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="mb-4 flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                            <div class="space-y-2">
                                <div class="flex flex-wrap items-center gap-3">
                                    <h3 class="text-lg font-semibold text-slate-950">{{ $branch->name }}</h3>
                                    <x-admin.status-badge :value="$branch->status" />
                                </div>
                                <p class="text-sm text-slate-500">{{ $branch->code }}</p>
                                <p class="text-sm text-slate-500">{{ $branch->address }}</p>
                                <div class="flex flex-wrap gap-3 text-sm text-slate-500">
                                    @if ($branch->contact_number)
                                        <span>{{ $branch->contact_number }}</span>
                                    @endif
                                    @if ($branch->email)
                                        <span>{{ $branch->email }}</span>
                                    @endif
                                </div>

                                <div class="mt-3 rounded-3xl border border-slate-200 bg-slate-50 p-3 text-sm text-slate-600">
                                    <div class="font-semibold text-slate-900">Public feedback link</div>
                                    <a href="{{ $branch->feedback_url }}" class="break-all text-cyan-600 hover:text-cyan-400">{{ $branch->feedback_url }}</a>
                                </div>
                                <div class="mt-3 flex items-center gap-3 rounded-3xl border border-slate-200 bg-slate-50 p-3">
                                    <img src="{{ $branch->qr_code_url }}" alt="{{ $branch->name }} QR code" class="h-20 w-20 rounded-2xl bg-white p-2" />
                                    <div class="text-sm text-slate-600">Scan or share this QR code to open the feedback page for the branch automatically.</div>
                                </div>
                            </div>

                            <div class="flex flex-wrap items-center gap-3">
                                <form method="POST" action="{{ route('branches.toggle-status', $branch) }}">
                                    @csrf
                                    @method('PATCH')
                                    <x-secondary-button type="submit">{{ $branch->status === 'active' ? 'Deactivate' : 'Activate' }}</x-secondary-button>
                                </form>
                                <form method="POST" action="{{ route('branches.destroy', $branch) }}" onsubmit="return confirm('Delete this branch?')">
                                    @csrf
                                    @method('DELETE')
                                    <x-danger-button>Delete</x-danger-button>
                                </form>
                            </div>
                        </div>

                        <div class="mb-6 grid gap-4 sm:grid-cols-3">
                            <!--<div class="rounded-2xl bg-slate-50 p-4 text-sm text-slate-600">Business type: <span class="font-semibold text-slate-900">—</span></div>-->
                            <div class="rounded-2xl bg-slate-50 p-4 text-sm text-slate-600">Users: <span class="font-semibold text-slate-900">{{ $branch->users_count }}</span></div>
                            <div class="rounded-2xl bg-slate-50 p-4 text-sm text-slate-600">Complaints: <span class="font-semibold text-slate-900">{{ $branch->complaints_count }}</span></div>
                        </div>

                        <form method="POST" action="{{ route('branches.update', $branch) }}" enctype="multipart/form-data" class="grid gap-4 lg:grid-cols-2">
                            @csrf
                            @method('PUT')

                            <div>
                                <x-input-label :for="'branch-name-'.$branch->id" value="Branch Name" />
                                <x-text-input :id="'branch-name-'.$branch->id" name="name" class="mt-1 block w-full" :value="$branch->name" required />
                            </div>

                            <div>
                                <x-input-label :for="'branch-code-'.$branch->id" value="Code" />
                                <x-text-input :id="'branch-code-'.$branch->id" name="code" class="mt-1 block w-full" :value="$branch->code" required />
                            </div>

                            <!-- Business Type field removed -->

                            <div>
                                <x-input-label :for="'branch-status-'.$branch->id" value="Status" />
                                <select :id="'branch-status-'.$branch->id" name="status" class="mt-1 block w-full rounded-xl border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500" required>
                                    <option value="active" @selected($branch->status === 'active')>Active</option>
                                    <option value="inactive" @selected($branch->status === 'inactive')>Inactive</option>
                                </select>
                            </div>

                            <div>
                                <x-input-label :for="'branch-contact-'.$branch->id" value="Contact Number" />
                                <x-text-input :id="'branch-contact-'.$branch->id" name="contact_number" class="mt-1 block w-full" :value="$branch->contact_number" />
                            </div>

                            <div>
                                <x-input-label :for="'branch-email-'.$branch->id" value="Email" />
                                <x-text-input :id="'branch-email-'.$branch->id" name="email" type="email" class="mt-1 block w-full" :value="$branch->email" />
                            </div>

                            <div class="lg:col-span-2">
                                <x-input-label :for="'branch-address-'.$branch->id" value="Address" />
                                <textarea :id="'branch-address-'.$branch->id" name="address" rows="3" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500">{{ $branch->address }}</textarea>
                            </div>

                            <div>
                                <x-input-label :for="'branch-logo-'.$branch->id" value="Logo" />
                                <input :id="'branch-logo-'.$branch->id" name="logo" type="file" accept="image/*" class="mt-1 block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500" />
                            </div>

                            <div class="lg:col-span-2">
                                <x-input-label :for="'branch-description-'.$branch->id" value="Description" />
                                <textarea :id="'branch-description-'.$branch->id" name="description" rows="3" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500">{{ $branch->description }}</textarea>
                            </div>

                            <div class="lg:col-span-2 flex flex-wrap gap-3">
                                <x-primary-button>Save Changes</x-primary-button>
                                @if ($branch->logo)
                                    <div class="flex items-center gap-3 rounded-2xl bg-slate-50 p-3 text-sm text-slate-600">
                                        <img src="{{ Storage::disk('public')->url($branch->logo) }}" alt="Branch logo" class="h-10 w-10 rounded-xl object-cover" />
                                        <span>Current logo</span>
                                    </div>
                                @endif
                            </div>
                        </form>
                    </div>
                @empty
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 text-center text-slate-600">
                        No branches found. Add one to get started.
                    </div>
                @endforelse
            </section>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            {{ $branches->withQueryString()->links() }}
        </div>
    </div>
</x-app-layout>
