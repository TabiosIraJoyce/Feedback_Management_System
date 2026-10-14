<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="languageSwitcher({ locale: '{{ app()->getLocale() }}' })" x-init="init()">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'GLEDCO Feedback Management System') }}</title>

        <script>
            (() => {
                const defaults = {
                    darkMode: @json($hciConfig['dark_mode_enabled'] ?? false),
                    textSize: 'base',
                    highContrast: false,
                    voiceAssistance: false,
                };

                try {
                    const stored = JSON.parse(localStorage.getItem('fms-hci-preferences') || '{}');
                    const preferences = { ...defaults, ...stored };
                    const root = document.documentElement;

                    root.classList.toggle('dark', !!preferences.darkMode);
                    root.classList.toggle('hci-high-contrast', !!preferences.highContrast);
                    root.dataset.textSize = preferences.textSize || 'base';
                    root.dataset.voiceAssistance = preferences.voiceAssistance ? 'on' : 'off';
                } catch (error) {
                    document.documentElement.classList.toggle('dark', !!defaults.darkMode);
                    document.documentElement.dataset.textSize = defaults.textSize;
                }
            })();
        </script>

        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-light-lavender font-sans text-primary-text antialiased transition-colors duration-300 dark:bg-slate-950 dark:text-slate-100">
        @php
            $notificationItems = $notificationItems ?? collect();
            $unreadNotificationCount = $unreadNotificationCount ?? 0;
            $initialNotificationItems = $notificationItems->map(function ($notification) {
                $data = $notification->data;
                $title = __('Notification');
                $message = $data['message'] ?? $data['note'] ?? __('You have a new update.');
                $targetUrl = $data['url'] ?? route('dashboard');

                if (isset($data['complaint_id'])) {
                    $title = __('Complaint Update');
                } elseif (isset($data['employee_feedback_id'])) {
                    $title = __('Employee Feedback Update');
                }

                return [
                    'id' => $notification->id,
                    'title' => $title,
                    'message' => $message,
                    'url' => $targetUrl,
                    'is_unread' => $notification->read_at === null,
                    'created_human' => $notification->created_at->diffForHumans(),
                ];
            })->values();
        @endphp

        <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-[80] focus:rounded-full focus:bg-cyan-500 focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:text-slate-950">
            {{ __('hci.toolbar.skip_to_content') }}
        </a>
        <div id="global-live-region" aria-live="polite" aria-atomic="true" class="sr-only"></div>

        <div class="min-h-screen lg:flex">
            @include('layouts.navigation')

            <div class="flex-1">
                @isset($header)
                    <header class="border-b border-card-border bg-white/75 backdrop-blur-xl dark:border-slate-800 dark:bg-slate-950/90">
                        <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
                            <div class="flex items-center justify-between gap-4">
                                <div class="flex-1">{{ $header }}</div>

                                <div class="flex items-center gap-3">
                                    <form action="{{ route('search') }}" method="GET" class="relative hidden sm:block">
                                        <input
                                            type="search"
                                            name="query"
                                            value="{{ request('query') }}"
                                            placeholder="{{ __('Search complaints, branches...') }}"
                                            class="w-80 rounded-full border border-card-border bg-white/95 px-4 py-2 pr-11 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-400 dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100"
                                        />
                                        <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 text-secondary-text">
                                            <i data-lucide="search" class="h-4 w-4"></i>
                                        </button>
                                    </form>

                                    <x-language-switcher compact />

                                    <div
                                        x-data="notificationCenter({
                                            initialItems: @js($initialNotificationItems),
                                            initialUnreadCount: {{ $unreadNotificationCount }},
                                            feedUrl: '{{ route('notifications.feed') }}',
                                            markAllUrl: '{{ route('notifications.read-all') }}',
                                            readUrlTemplate: '{{ route('notifications.read', ['notification' => '__ID__']) }}',
                                            csrfToken: '{{ csrf_token() }}',
                                            enabled: true,
                                        })"
                                        x-init="init()"
                                        class="relative"
                                    >
                                        <button
                                            type="button"
                                            @click="notificationsOpen = !notificationsOpen"
                                            class="relative inline-flex items-center rounded-full bg-white/90 p-2 shadow-sm dark:bg-slate-950/80"
                                        >
                                            <i data-lucide="bell" class="h-5 w-5 text-primary-text"></i>
                                            <template x-if="unreadCount > 0">
                                                <span class="absolute -right-1 -top-1 inline-flex min-h-4 min-w-4 items-center justify-center rounded-full bg-notification-pink px-1 text-xs font-semibold text-white" x-text="badgeLabel()"></span>
                                            </template>
                                        </button>

                                        <div
                                            x-show="notificationsOpen"
                                            @click.outside="notificationsOpen = false"
                                            x-transition
                                            class="absolute right-0 z-50 mt-3 w-96 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl dark:border-slate-800 dark:bg-slate-950"
                                            style="display: none;"
                                        >
                                            <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3 dark:border-slate-800">
                                                <div>
                                                    <p class="text-sm font-semibold text-slate-950 dark:text-slate-100">{{ __('Notifications') }}</p>
                                                    <p class="text-xs text-slate-500 dark:text-slate-400"><span x-text="unreadCount"></span> {{ __('unread') }}</p>
                                                </div>

                                                <div class="flex items-center gap-3">
                                                    <button type="button" @click="enableBrowserAlerts()" class="text-xs font-semibold text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200">
                                                        {{ __('Browser alerts') }}
                                                    </button>
                                                    <button x-show="unreadCount > 0" type="button" @click="markAllRead()" class="text-xs font-semibold text-cyan-700 hover:text-cyan-800 dark:text-cyan-300">
                                                        {{ __('Mark all as read') }}
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="max-h-96 overflow-y-auto">
                                                <template x-if="items.length === 0">
                                                    <p class="px-4 py-8 text-center text-sm text-slate-500 dark:text-slate-400">{{ __('No notifications yet.') }}</p>
                                                </template>

                                                <template x-for="item in items" :key="item.id">
                                                    <button type="button" @click="openNotification(item)" class="block w-full border-b border-slate-100 px-4 py-3 text-left transition hover:bg-slate-50 dark:border-slate-900 dark:hover:bg-slate-900">
                                                        <div class="flex items-start gap-3">
                                                            <span class="mt-1 h-2.5 w-2.5 rounded-full" :class="item.is_unread ? 'bg-cyan-500' : 'bg-slate-300 dark:bg-slate-700'"></span>
                                                            <div class="min-w-0">
                                                                <p class="text-sm font-semibold text-slate-950 dark:text-slate-100" x-text="item.title"></p>
                                                                <p class="mt-1 text-sm text-slate-600 dark:text-slate-300" x-text="item.message"></p>
                                                                <p class="mt-2 text-xs text-slate-400 dark:text-slate-500" x-text="item.created_human"></p>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </template>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="relative">
                                        <button class="inline-flex items-center gap-2 rounded-full bg-white/90 px-3 py-1.5 shadow-sm dark:bg-slate-950/80 dark:text-slate-100">
                                            <img src="{{ asset('images/feedback-logo.png') }}" alt="GLEDCO Feedback Management System Logo" class="h-7 w-7 rounded-full object-contain" />
                                            <span class="hidden text-sm font-medium text-slate-900 sm:inline-block">{{ auth()->user()->name }}</span>
                                            <i data-lucide="chevron-down" class="h-4 w-4 text-secondary-text dark:text-slate-300"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </header>
                @endisset

                <main id="main-content" class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    @if (session('status'))
                        <div class="mb-6 rounded-3xl border border-emerald-200 bg-emerald-50/95 px-4 py-3 text-sm font-medium text-emerald-700 shadow-sm dark:border-emerald-900/30 dark:bg-emerald-950/30 dark:text-emerald-200">
                            {{ session('status') }}
                        </div>
                    @endif
                    {{ $slot }}
                </main>
            </div>
        </div>

        @include('layouts.partials.hci-toolbar')
        @stack('scripts')
    </body>
</html>
