<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="languageSwitcher({ locale: '{{ app()->getLocale() }}' })" x-init="init()">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

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
        <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-[80] focus:rounded-full focus:bg-cyan-500 focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:text-slate-950">
            {{ __('hci.toolbar.skip_to_content') }}
        </a>
        <div id="global-live-region" aria-live="polite" aria-atomic="true" class="sr-only"></div>

        <div class="fixed right-4 top-4 z-[75]">
            <x-language-switcher compact />
        </div>

        @if (request()->routeIs('track-complaint.*') || request()->routeIs('feedback.public.*'))
            <main id="main-content">
                {{ $slot }}
            </main>
        @else
            <div class="flex min-h-screen flex-col items-center bg-transparent pt-6 sm:justify-center sm:pt-0">
                <div>
                    <a href="/">
                        <x-application-logo class="h-28 w-28" />
                    </a>
                </div>

                <main id="main-content" class="mt-6 w-full overflow-hidden rounded-[1.75rem] border border-card-border bg-white/95 px-6 py-5 shadow-neumorph sm:max-w-md dark:border-slate-800 dark:bg-slate-950 dark:text-slate-100">
                    {{ $slot }}
                </main>
            </div>
        @endif

        @include('layouts.partials.hci-toolbar')
    </body>
</html>
