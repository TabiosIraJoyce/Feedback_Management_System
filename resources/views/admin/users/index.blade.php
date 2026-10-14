<x-app-layout>
    <x-slot name="header">
        <x-admin.page-header title="User Access Management" description="Assign roles, branch ownership, and activation state from a single admin workspace." icon="users-round" />
    </x-slot>

    <div class="space-y-6">
        @if (session('status'))
            <div class="rounded-3xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-3xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-800">
                <p class="font-semibold">We could not save the user account.</p>
                <ul class="mt-2 list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-slate-900">Create new user account</h2>
            <p class="mt-2 text-sm text-slate-500">Add a staff or customer profile and assign their role, department, and activation state.</p>

            <form method="POST" action="{{ route('admin.users.store') }}" class="mt-6 grid gap-4 lg:grid-cols-6">
                @csrf
                <div class="lg:col-span-2">
                    <x-input-label for="new-user-name" value="Name" />
                    <x-text-input id="new-user-name" name="name" class="mt-1 block w-full" :value="old('name')" required />
                    <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm" />
                </div>
                <div class="lg:col-span-2">
                    <x-input-label for="new-user-email" value="Email" />
                    <x-text-input id="new-user-email" name="email" type="email" class="mt-1 block w-full" :value="old('email')" required />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm" />
                </div>
                <div class="lg:col-span-2">
                    <x-input-label for="new-user-password" value="Password" />
                    <x-text-input id="new-user-password" name="password" type="password" class="mt-1 block w-full" required />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm" />
                </div>
                <div>
                    <x-input-label for="new-user-role" value="Role" />
                    <select id="new-user-role" name="role" class="mt-1 block w-full rounded-xl border-slate-300 bg-white text-slate-900 shadow-sm focus:border-cyan-500 focus:ring-cyan-500" required>
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}" @selected(old('role') === $role->name)>{{ $role->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('role')" class="mt-2 text-sm" />
                </div>
                <div>
                    <x-input-label for="new-user-branch" value="Department" />
                    <select id="new-user-branch" name="branch_id" class="mt-1 block w-full rounded-xl border-slate-300 bg-white text-slate-900 shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
                        <option value="">Unassigned</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}" @selected(old('branch_id') == $branch->id)>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('branch_id')" class="mt-2 text-sm" />
                </div>
                <div class="lg:col-span-2">
                    <x-input-label for="new-user-title" value="Job Title" />
                    <x-text-input id="new-user-title" name="job_title" class="mt-1 block w-full" :value="old('job_title')" />
                    <x-input-error :messages="$errors->get('job_title')" class="mt-2 text-sm" />
                </div>
                <label class="flex items-center gap-3 pt-8 text-sm font-medium text-slate-700">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active')) class="rounded border-slate-300 text-cyan-600 focus:ring-cyan-500">
                    Active account
                </label>
                <div class="flex items-end justify-end lg:col-span-3">
                    <x-primary-button>Create User</x-primary-button>
                </div>
            </form>
        </div>

        @foreach ($users as $user)
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <form method="POST" action="{{ route('admin.users.update', $user) }}" class="grid gap-4 lg:grid-cols-6">
                    @csrf
                    @method('PATCH')
                    <div class="lg:col-span-2">
                        <x-input-label :for="'user-name-'.$user->id" value="Name" />
                        <x-text-input :id="'user-name-'.$user->id" name="name" class="mt-1 block w-full" :value="$user->name" required />
                    </div>
                    <div class="lg:col-span-2">
                        <x-input-label :for="'user-email-'.$user->id" value="Email" />
                        <x-text-input :id="'user-email-'.$user->id" name="email" type="email" class="mt-1 block w-full" :value="$user->email" required />
                    </div>
                    <div class="lg:col-span-2">
                        <x-input-label :for="'user-password-'.$user->id" value="New Password" />
                        <x-text-input :id="'user-password-'.$user->id" name="password" type="password" class="mt-1 block w-full" />
                        <p class="mt-2 text-xs text-slate-500">Leave blank to keep the current password.</p>
                    </div>
                    <div>
                        <x-input-label :for="'user-role-'.$user->id" value="Role" />
                        <select id="user-role-{{ $user->id }}" name="role" class="mt-1 block w-full rounded-xl border-slate-300 bg-white text-slate-900 shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}" @selected($user->roles->first()?->name === $role->name)>{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label :for="'user-branch-'.$user->id" value="Department" />
                        <select id="user-branch-{{ $user->id }}" name="branch_id" class="mt-1 block w-full rounded-xl border-slate-300 bg-white text-slate-900 shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
                            <option value="">Unassigned</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" @selected($user->branch_id === $branch->id)>{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="lg:col-span-2">
                        <x-input-label :for="'user-title-'.$user->id" value="Job Title" />
                        <x-text-input :id="'user-title-'.$user->id" name="job_title" class="mt-1 block w-full" :value="$user->job_title" />
                    </div>
                    <label class="flex items-center gap-3 pt-8 text-sm font-medium text-slate-700">
                        <input type="checkbox" name="is_active" value="1" @checked($user->is_active) class="rounded border-slate-300 text-cyan-600 focus:ring-cyan-500">
                        Active account
                    </label>
                    <div class="flex items-end justify-end lg:col-span-3">
                        <x-primary-button>Update User</x-primary-button>
                    </div>
                </form>
            </div>
        @endforeach
    </div>
</x-app-layout>
