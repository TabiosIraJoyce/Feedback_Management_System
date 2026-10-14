<nav
    x-data="{ open: false }"
    class="border-r border-card-border
           bg-[linear-gradient(180deg,#f8fcfb_0%,#e8f4f2_100%)]
           text-slate-900
           lg:w-80
           dark:border-slate-800
           dark:bg-slate-950
           dark:text-slate-100"
>

    {{-- ========================================================= --}}
    {{-- HEADER / LOGO --}}
    {{-- ========================================================= --}}

    <div class="flex items-center justify-between px-5 py-4 lg:px-6">

        <div class="flex items-center gap-3">

            <x-application-logo
                class="h-12 w-12 rounded-2xl bg-white/80
                       p-1 shadow-soft dark:bg-slate-900/80"
            />

            <div>

                <p
                    class="text-xs font-semibold uppercase
                           tracking-[0.32em]
                           text-slate-700 dark:text-slate-300"
                >
                    GLEDCO
                </p>

                <h1 class="mt-1 text-lg font-semibold">
                    {{ __('Feedback Management') }}
                </h1>

            </div>

        </div>


        {{-- Mobile Menu --}}
        <button
            @click="open = ! open"
            class="rounded-xl border border-card-border
                   bg-white/80 p-2 text-primary-text shadow-sm
                   lg:hidden
                   dark:border-slate-800
                   dark:bg-slate-900
                   dark:text-slate-200"
        >

            <i
                data-lucide="menu"
                class="h-5 w-5"
            ></i>

        </button>

    </div>


    {{-- ========================================================= --}}
    {{-- NAVIGATION CONTENT --}}
    {{-- ========================================================= --}}

    <div
        :class="{ 'block': open, 'hidden': ! open }"
        class="hidden px-4 pb-6 lg:block lg:px-5"
    >

        {{-- ===================================================== --}}
        {{-- LANGUAGE SWITCHER --}}
        {{-- ===================================================== --}}

        <div class="mb-5">

            <x-language-switcher />

        </div>


        {{-- ===================================================== --}}
        {{-- LOGGED-IN USER NAVIGATION --}}
        {{-- ===================================================== --}}

        @auth

            {{-- ================================================= --}}
            {{-- USER INFORMATION --}}
            {{-- ================================================= --}}

            <div
                class="mb-5 hci-panel p-4
                       dark:text-slate-100"
            >

                <p
                    class="text-sm font-semibold
                           text-slate-900
                           dark:text-slate-100"
                >
                    {{ auth()->user()->name }}
                </p>


                <p
                    class="mt-1 text-xs
                           text-slate-700
                           dark:text-slate-400"
                >
                    {{ auth()->user()->getRoleNames()->implode(', ') ?: __('No role assigned') }}
                </p>


                    <p
                        class="mt-2 text-xs
                               text-slate-700
                               dark:text-slate-400"
                    >
                        {{ auth()->user()->branch?->name ?? __('Unassigned department') }}
                    </p>

            </div>


            {{-- ================================================= --}}
            {{-- NAVIGATION --}}
            {{-- ================================================= --}}

            <div class="space-y-6">


                {{-- ================================================= --}}
                {{-- MAIN --}}
                {{-- ================================================= --}}

                <div class="space-y-1">

                    <p
                        class="px-4 text-xs font-semibold uppercase
                               tracking-[0.32em]
                               text-slate-500
                               dark:text-slate-400"
                    >
                        {{ __('Main') }}
                    </p>


                    @if (
                        auth()->user()->can('view dashboard')
                        ||
                        auth()->user()->can('manage complaints')
                    )

                        <x-admin.sidebar-link
                            :href="route('dashboard')"
                            :active="request()->routeIs('dashboard')"
                            icon="layout-dashboard"
                        >
                            {{ __('Dashboard') }}
                        </x-admin.sidebar-link>

                    @endif

                </div>


                {{-- ================================================= --}}
                {{-- FEEDBACK --}}
                {{-- ================================================= --}}

                <div class="space-y-1">

                    <p
                        class="px-4 text-xs font-semibold uppercase
                               tracking-[0.32em]
                               text-slate-500
                               dark:text-slate-400"
                    >
                        {{ __('Feedback') }}
                    </p>


                    {{-- Complaints --}}
                    @if (
                        auth()->user()->can('track complaints')
                        ||
                        auth()->user()->can('manage complaints')
                    )

                        <x-admin.sidebar-link
                            :href="route('complaints.index')"
                            :active="request()->routeIs('complaints.*')"
                            icon="badge-alert"
                        >
                            {{ __('Complaints') }}
                        </x-admin.sidebar-link>

                    @endif

{{-- Feedback --}}
@if (
    auth()->user()->can('submit feedback')
    ||
    auth()->user()->can('manage feedback')
)

    <x-admin.sidebar-link
        :href="route('feedback.index')"
        :active="request()->routeIs('feedback.*')"
        icon="messages-square"
    >
        {{ __('Feedback') }}
    </x-admin.sidebar-link>

    {{-- Employee Feedback --}}
    @if (! auth()->user()->hasRole('Customer'))

        <x-admin.sidebar-link
            :href="route('employee-feedback.index')"
            :active="request()->routeIs('employee-feedback.*')"
            icon="message-square-more"
        >
            {{ __('Employee Feedback') }}
        </x-admin.sidebar-link>

    @endif

@endif

                {{-- ================================================= --}}
                {{-- BRANCH MANAGEMENT --}}
                {{-- ================================================= --}}

                @if (
                    auth()->user()->can('manage branches')
                )

                    <div class="space-y-1">

                        <p
                            class="px-4 text-xs font-semibold uppercase
                                   tracking-[0.32em]
                                   text-slate-500
                                   dark:text-slate-400"
                        >
                            {{ __('Department Management') }}
                        </p>


                        {{-- Branches (now shown as Departments) --}}
                        <x-admin.sidebar-link
                            :href="route('branches.index')"
                            :active="request()->routeIs('branches.*')"
                            icon="building-2"
                        >
                            {{ __('Departments') }}
                        </x-admin.sidebar-link>


                        {{-- Business Types removed --}}


                        {{-- Products --}}
                        <x-admin.sidebar-link
                            :href="route('products.index')"
                            :active="request()->routeIs('products.*')"
                            icon="box"
                        >
                            {{ __('Products') }}
                        </x-admin.sidebar-link>


                        {{-- Original Departments module removed from menu --}}

                    </div>

                @endif


                {{-- ================================================= --}}
                {{-- ADMINISTRATION --}}
                {{-- ================================================= --}}

                @if (
                    auth()->user()->can('manage settings')
                    ||
                    auth()->user()->hasRole('Super Admin')
                )

                    <div class="space-y-1">

                        <p
                            class="px-4 text-xs font-semibold uppercase
                                   tracking-[0.32em]
                                   text-slate-500
                                   dark:text-slate-400"
                        >
                            {{ __('Administration') }}
                        </p>


                        {{-- Permissions --}}
                        @if (
                            auth()->user()->can('manage settings')
                        )

                            <x-admin.sidebar-link
                                :href="route('admin.roles.index')"
                                :active="request()->routeIs('admin.roles.*')"
                                icon="shield-check"
                            >
                                {{ __('Permissions') }}
                            </x-admin.sidebar-link>


                            {{-- System Settings --}}
                            <x-admin.sidebar-link
                                :href="route('admin.settings.index')"
                                :active="request()->routeIs('admin.settings.*')"
                                icon="settings"
                            >
                                {{ __('System Settings') }}
                            </x-admin.sidebar-link>

                        @endif


                        {{-- QR Codes --}}
                        @if (
                            auth()->user()->hasRole('Super Admin')
                        )

                            <x-admin.sidebar-link
                                :href="route('admin.qr.index')"
                                :active="request()->routeIs('admin.qr.*')"
                                icon="qr-code"
                            >
                                {{ __('Generate QR Codes') }}
                            </x-admin.sidebar-link>

                        @endif

                    </div>

                @endif


                {{-- ================================================= --}}
                {{-- UTILITIES --}}
                {{-- ================================================= --}}

                <div class="space-y-1">

                    <p
                        class="px-4 text-xs font-semibold uppercase
                               tracking-[0.32em]
                               text-slate-500
                               dark:text-slate-400"
                    >
                        {{ __('Utilities') }}
                    </p>


                    {{-- Reports --}}
                    @if (
                        auth()->user()->can('manage reports')
                    )

                        <x-admin.sidebar-link
                            :href="route('reports.index')"
                            :active="request()->routeIs('reports.*')"
                            icon="file-bar-chart"
                        >
                            {{ __('Reports & Export') }}
                        </x-admin.sidebar-link>

                    @endif


                    {{-- User Management --}}
                    @if (
                        auth()->user()->can('manage users')
                    )

                        <x-admin.sidebar-link
                            :href="route('admin.users.index')"
                            :active="request()->routeIs('admin.users.*')"
                            icon="users-round"
                        >
                            {{ __('User Management') }}
                        </x-admin.sidebar-link>

                    @endif


                    {{-- Form Builder --}}
                    @if (
                        auth()->user()->can('manage forms')
                    )

                        <x-admin.sidebar-link
                            :href="route('admin.forms.index')"
                            :active="request()->routeIs('admin.forms.*')"
                            icon="layout-grid"
                        >
                            {{ __('Form Builder') }}
                        </x-admin.sidebar-link>

                    @endif


                    {{-- Profile --}}
                    <x-admin.sidebar-link
                        :href="route('profile.edit')"
                        :active="request()->routeIs('profile.*')"
                        icon="user-cog"
                    >
                        {{ __('Profile') }}
                    </x-admin.sidebar-link>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- LOGOUT --}}
            {{-- ================================================= --}}

            <form
                method="POST"
                action="{{ route('logout') }}"
                class="mt-6"
            >

                @csrf

                <button
                    type="submit"
                    class="flex w-full items-center gap-3
                           rounded-2xl border border-rose-200
                           bg-white/80 px-4 py-3
                           text-sm font-semibold
                           text-rose-700 shadow-sm
                           transition hover:bg-rose-50
                           dark:border-rose-900/40
                           dark:bg-rose-950/30
                           dark:text-rose-200"
                >

                    <i
                        data-lucide="log-out"
                        class="h-4 w-4"
                    ></i>

                    {{ __('Log Out') }}

                </button>

            </form>


        @else


            {{-- ================================================= --}}
            {{-- GUEST / PUBLIC CUSTOMER --}}
            {{-- ================================================= --}}

            <div
                class="hci-panel p-5
                       dark:text-slate-100"
            >

                <div
                    class="flex items-center gap-3"
                >

                    <div
                        class="flex h-10 w-10 items-center
                               justify-center rounded-xl
                               bg-white shadow-sm
                               dark:bg-slate-900"
                    >

                        <i
                            data-lucide="message-square-heart"
                            class="h-5 w-5 text-primary"
                        ></i>

                    </div>


                    <div>

                        <p
                            class="text-sm font-semibold
                                   text-slate-900
                                   dark:text-slate-100"
                        >
                            {{ __('Public Feedback') }}
                        </p>

                        <p
                            class="mt-1 text-xs
                                   text-slate-600
                                   dark:text-slate-400"
                        >
                            {{ __('We value your feedback.') }}
                        </p>

                    </div>

                </div>


                <div
                    x-data="{
                        items: null,
                        loading: false,
                        error: null,
                        async load() {
                            this.error = null; this.items = null;

                            // Only auto-load on public feedback pages
                            if (! window.location.pathname.includes('/feedback')) {
                                return;
                            }

                            // Try saved references first
                            var refs = JSON.parse(localStorage.getItem('fms-customer-refs') || '[]');
                            if (refs.length === 0) {
                                // Fallback to saved phone lookup
                                var phone = (localStorage.getItem('fms-customer-phone') || '').trim();
                                if (! phone) {
                                    return;
                                }

                                this.loading = true;
                                try {
                                    const res = await fetch('{{ route('feedback.track') }}', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        },
                                        body: JSON.stringify({ query: phone })
                                    });

                                    const data = await res.json();
                                    if (! res.ok) {
                                        this.error = data.error || 'No submitted requests found.';
                                    } else {
                                        this.items = data.matches || [];
                                    }
                                } catch (e) {
                                    this.error = 'Unable to contact server.';
                                } finally {
                                    this.loading = false;
                                }

                                return;
                            }

                            // Load details for saved references
                            this.loading = true;
                            try {
                                const promises = refs.slice(0, 5).map(r => fetch('{{ route('feedback.track') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({ reference: r.reference_no })
                                }).then(res => res.ok ? res.json() : null).catch(() => null));

                                const results = await Promise.all(promises);
                                this.items = results.filter(Boolean).map(d => ({
                                    reference_no: d.reference_no,
                                    status: d.status,
                                    branch: d.branch,
                                    department: d.department,
                                    created_at: d.created_at
                                }));
                            } catch (e) {
                                this.error = 'Unable to contact server.';
                            } finally {
                                this.loading = false;
                            }
                        },
                        reviewSubmission(item) {
    window.location.href =
        '{{ route('feedback.submission.show', ['reference' => '__REFERENCE__']) }}'
            .replace(
                '__REFERENCE__',
                encodeURIComponent(item.reference_no)
            );
},

clear() {
    localStorage.removeItem('fms-customer-refs');
    localStorage.removeItem('fms-customer-phone');
    this.items = null;
    this.error = 'Saved data cleared.';
}
                    }"
                    x-init="load()"
                    class="mt-4 rounded-xl bg-white/70 p-4 dark:bg-slate-900/60"
                >

                    <template x-if="! items && ! loading">
                        <p class="text-xs leading-5 text-slate-600 dark:text-slate-400">
                            {{ __('Please complete the feedback form to share your experience, suggestions, or concerns with GLEDCO.') }}
                        </p>
                    </template>

                    <template x-if="loading">
                        <p class="text-xs text-gray-500">Loading your submissions…</p>
                    </template>

                    <template x-if="error">
                        <p class="text-xs text-rose-600" x-text="error"></p>
                    </template>

                    <div class="space-y-2">

    <div x-data="{ showSubmissions: false }" class="space-y-2">

    {{-- CLICKABLE MY SUBMISSIONS --}}
    <button
        type="button"
        @click="
            showSubmissions = !showSubmissions;
            if (showSubmissions && !items && !loading) {
                load();
            }
        "
        class="w-full flex items-center justify-between text-left
               text-sm font-medium text-slate-800
               hover:text-primary transition"
    >
        <span>Your recent submissions</span>

        <i
            data-lucide="chevron-right"
            class="h-4 w-4 transition-transform duration-200"
            :class="{ 'rotate-90': showSubmissions }"
        ></i>
    </button>

    {{-- SUBMISSIONS LIST --}}
    <div
        x-show="showSubmissions"
        x-transition
        class="mt-2 space-y-2"
    >

        {{-- Loading --}}
        <template x-if="loading">
            <div class="text-xs text-gray-500">
                Loading your submissions...
            </div>
        </template>

        {{-- Results --}}
        <template x-if="items">
            <div class="space-y-2">

                <template x-if="items.length === 0">
                    <div class="text-xs text-gray-500">
                        No submissions found.
                    </div>
                </template>

                <template x-for="it in items" :key="it.reference_no">

                    {{-- CLICKABLE SUBMISSION --}}
                    <button
                        type="button"
                        @click="reviewSubmission(it)"
                        class="w-full text-left rounded-md bg-slate-50
                               hover:bg-slate-100 p-2 text-xs
                               transition"
                    >
                        <div class="flex items-center justify-between">

                            <div>
                                <div
                                    class="font-medium text-slate-800"
                                    x-text="it.reference_no"
                                ></div>

                                <div
                                    class="text-gray-500"
                                    x-text="
                                        (it.branch || '') +
                                        (it.department ? ' • ' + it.department : '') +
                                        (it.created_at
                                            ? ' • ' + new Date(it.created_at).toLocaleString()
                                            : '')
                                    "
                                ></div>
                            </div>

                            <div
                                class="text-sm text-gray-600"
                                x-text="it.status"
                            ></div>

                        </div>
                    </button>

                </template>

                <div class="pt-2">
                    <button
                        type="button"
                        @click="clear()"
                        class="text-xs text-rose-600 hover:underline"
                    >
                        Clear saved data
                    </button>
                </div>

            </div>
        </template>

    </div>
</div>
                </div>

            </div>

        @endauth

    </div>

</nav>