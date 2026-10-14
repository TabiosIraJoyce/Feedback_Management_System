<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $qrCode->code }} Print Layout</title>
        @vite(['resources/css/app.css'])
    </head>
    <body class="bg-white text-slate-950">
        <main class="mx-auto flex min-h-screen max-w-3xl items-center justify-center p-8">
            <section class="w-full rounded-[2rem] border border-slate-200 bg-white p-10 shadow-sm">
                <div class="text-center">
                    <p class="text-sm font-semibold uppercase tracking-[0.35em] text-cyan-700">GLEDCO Feedback QR</p>
                    <h1 class="mt-4 text-3xl font-bold">{{ $qrCode->department?->name ?? $qrCode->branch?->name }}</h1>
                    <p class="mt-2 text-sm text-slate-500">{{ $qrCode->branch?->name }} @if($qrCode->department) | {{ $qrCode->department->code }} @endif</p>
                </div>

                <div class="mt-8 flex justify-center">
                    <img src="{{ $qrCode->png_preview_url }}" alt="{{ $qrCode->code }} QR code" class="h-80 w-80" />
                </div>

                <div class="mt-8 rounded-3xl bg-slate-50 p-5 text-center">
                    <p class="text-sm font-semibold text-slate-900">{{ $qrCode->code }}</p>
                    <p class="mt-2 break-all text-sm text-cyan-700">{{ $qrCode->public_url }}</p>
                    <p class="mt-4 text-sm text-slate-600">Scan to submit feedback, complaints, suggestions, or concerns.</p>
                </div>
            </section>
        </main>

        <script>
            window.addEventListener('load', () => window.print());
        </script>
    </body>
</html>
