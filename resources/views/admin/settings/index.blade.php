<x-app-layout>
    <x-slot name="header">
        <x-admin.page-header title="System Settings" description="Manage branding, notifications, attachment limits, and system defaults." icon="settings" />
    </x-slot>

    <div class="space-y-6">
        @if (session('status'))
            <div class="rounded-3xl border border-cyan-200 bg-cyan-50 p-4 text-sm text-cyan-900 dark:border-cyan-900/30 dark:bg-cyan-950/30 dark:text-cyan-100">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PATCH')

            <div class="grid gap-6 xl:grid-cols-[minmax(0,1.2fr)_360px]">
                <div class="space-y-6">
                    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                        <h3 class="text-lg font-semibold text-slate-950 dark:text-slate-100">Branding</h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Company identity used across the application.</p>

                        <div class="mt-5 grid gap-4 sm:grid-cols-2">
                            <div>
                                <x-input-label for="company_name" value="Company Name" />
                                <x-text-input id="company_name" name="company_name" class="mt-1 block w-full" :value="old('company_name', $settings['company_name'] ?? '')" />
                                <x-input-error :messages="$errors->get('company_name')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="system_logo" value="System Logo" />
                                <input id="system_logo" name="system_logo" type="file" accept=".jpg,.jpeg,.png,.webp" class="mt-1 block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100" />
                                <x-input-error :messages="$errors->get('system_logo')" class="mt-2" />

                                <div class="mt-3 flex items-center gap-3">
                                    <x-application-logo class="h-12 w-12 rounded-xl bg-white p-1 shadow-sm" />
                                    <span class="text-sm text-slate-500 dark:text-slate-400">Current logo</span>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                        <h3 class="text-lg font-semibold text-slate-950 dark:text-slate-100">Attachment Limits</h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Control the maximum file upload size for complaint and feedback attachments.</p>

                        <div class="mt-5 grid gap-4 sm:grid-cols-2">
                            <div>
                                <x-input-label for="max_upload_size_mb" value="Maximum Upload Size (MB)" />
                                <x-text-input id="max_upload_size_mb" name="max_upload_size_mb" type="number" min="1" max="100" class="mt-1 block w-full" :value="old('max_upload_size_mb', $settings['max_upload_size_mb'] ?? 5)" />
                                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Default is 5 MB. This applies to complaint and feedback attachments.</p>
                                <x-input-error :messages="$errors->get('max_upload_size_mb')" class="mt-2" />
                            </div>
                        </div>
                    </section>
                </div>

                <aside class="space-y-6">
                    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                        <h3 class="text-lg font-semibold text-slate-950 dark:text-slate-100">Complaint Routing</h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Select the single authenticated recipient for final escalations.</p>
                        <div class="mt-5">
                            <x-input-label for="upper_management_user_id" value="Upper Management Recipient" />
                            <select id="upper_management_user_id" name="upper_management_user_id" class="mt-1 block w-full rounded-xl border-slate-300 bg-white px-3 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100">
                                <option value="">Not configured</option>
                                @foreach ($upperManagementUsers as $managementUser)
                                    <option value="{{ $managementUser->id }}" @selected(old('upper_management_user_id', $settings['upper_management_user_id'] ?? '') == $managementUser->id)>{{ $managementUser->name }} · {{ $managementUser->email }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('upper_management_user_id')" class="mt-2" />
                            <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Create and activate an account with the Upper Management role in User Access Management first.</p>
                        </div>
                    </section>

                    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                        <h3 class="text-lg font-semibold text-slate-950 dark:text-slate-100">Preferences</h3>
                        <div class="mt-5 space-y-4">
                            <label class="flex items-center justify-between gap-4 rounded-2xl border border-slate-200 p-4 dark:border-slate-800">
                                <div>
                                    <p class="font-semibold text-slate-900 dark:text-slate-100">Enable Notifications</p>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">Keep system notifications active.</p>
                                </div>
                                <input type="checkbox" name="enable_notifications" value="1" class="rounded border-slate-300 text-cyan-600 shadow-sm focus:ring-cyan-500" @checked(old('enable_notifications', filter_var($settings['enable_notifications'] ?? false, FILTER_VALIDATE_BOOLEAN))) />
                            </label>

                            <label class="flex items-center justify-between gap-4 rounded-2xl border border-slate-200 p-4 dark:border-slate-800">
                                <div>
                                    <p class="font-semibold text-slate-900 dark:text-slate-100">Enable Dark Mode</p>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">Allow dark mode preference to remain active.</p>
                                </div>
                                <input type="checkbox" name="enable_dark_mode" value="1" class="rounded border-slate-300 text-cyan-600 shadow-sm focus:ring-cyan-500" @checked(old('enable_dark_mode', filter_var($settings['enable_dark_mode'] ?? false, FILTER_VALIDATE_BOOLEAN))) />
                            </label>
                        </div>
                    </section>

                    <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                        <h3 class="text-lg font-semibold text-slate-950 dark:text-slate-100">Defaults</h3>
                        <div class="mt-5 space-y-4">
                            <div>
                                <x-input-label for="default_sla_hours" value="Default SLA Hours" />
                                <x-text-input id="default_sla_hours" name="default_sla_hours" type="number" min="0" class="mt-1 block w-full" :value="old('default_sla_hours', $settings['default_sla_hours'] ?? 24)" />
                                <x-input-error :messages="$errors->get('default_sla_hours')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="default_language" value="Default Language" />
                                <select id="default_language" name="default_language" class="mt-1 block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100">
                                    <option value="en" @selected(old('default_language', $settings['default_language'] ?? 'en') === 'en')>{{ __('English') }}</option>
                                    <option value="fil" @selected(old('default_language', $settings['default_language'] ?? 'en') === 'fil')>{{ __('Filipino') }}</option>
                                    <option value="ilo" @selected(old('default_language', $settings['default_language'] ?? 'en') === 'ilo')>{{ __('Ilocano') }}</option>
                                </select>
                                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">This becomes the default public interface language until a visitor changes it from the Accessibility panel.</p>
                                <x-input-error :messages="$errors->get('default_language')" class="mt-2" />
                            </div>
                        </div>
                    </section>
                </aside>
            </div>

            <div class="flex justify-end">
                <x-primary-button>Save Settings</x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>
