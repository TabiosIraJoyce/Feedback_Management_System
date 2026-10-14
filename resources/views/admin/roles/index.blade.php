<x-app-layout>
    <x-slot name="header">
        <x-admin.page-header title="Role Permission Matrix" description="Review and fine-tune which permissions belong to each system role." icon="shield-check" />
    </x-slot>

    <div class="grid gap-6 xl:grid-cols-2">
        @foreach ($roles as $role)
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-5 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-950">{{ $role->name }}</h3>
                        <p class="text-sm text-slate-500">{{ $role->permissions->count() }} permissions assigned</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.roles.update', $role) }}">
                    @csrf
                    @method('PATCH')
                    <div class="grid gap-3 sm:grid-cols-2">
                        @foreach ($permissions as $permission)
                            <label class="flex items-center gap-3 rounded-2xl border border-slate-200 px-4 py-3 text-sm font-medium text-slate-700">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" @checked($role->permissions->contains('name', $permission->name)) class="rounded border-slate-300 text-cyan-600 focus:ring-cyan-500">
                                <span>{{ $permission->name }}</span>
                            </label>
                        @endforeach
                    </div>

                    <x-primary-button class="mt-5">Save Permissions</x-primary-button>
                </form>
            </section>
        @endforeach
    </div>
</x-app-layout>
