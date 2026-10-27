<x-app-layout>

    <div class="min-h-screen bg-slate-50 dark:bg-slate-950 py-10">

        <div class="max-w-3xl mx-auto px-4">

            {{-- Header --}}
            <div class="mb-6">

                <a
                    href="{{ url()->previous() }}"
                    class="inline-flex items-center gap-2 text-sm
                           text-slate-600 hover:text-primary"
                >
                    <i data-lucide="arrow-left" class="h-4 w-4"></i>
                    Back
                </a>

                <h1 class="mt-4 text-2xl font-bold text-slate-900
                           dark:text-white">
                    Your Submitted Feedback
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    Review the information you submitted to GLEDCO.
                </p>

            </div>


            {{-- Submission Card --}}
            <div class="bg-white dark:bg-slate-900
                        rounded-2xl shadow-sm border
                        border-slate-200 dark:border-slate-800">

                {{-- Reference --}}
                <div class="p-6 border-b border-slate-200
                            dark:border-slate-800">

                    <div class="flex items-center justify-between gap-4">

                        <div>

                            <p class="text-xs text-slate-500 uppercase">
                                Reference Number
                            </p>

                            <p class="mt-1 text-lg font-bold
                                      text-slate-900 dark:text-white">
                                {{ $ticket->reference_no }}
                            </p>

                        </div>


                        <span
                            class="px-3 py-1 rounded-full text-xs
                                   font-medium bg-amber-100
                                   text-amber-700"
                        >
                            {{ ucfirst($ticket->status) }}
                        </span>

                    </div>

                </div>


                {{-- Details --}}
                <div class="p-6 space-y-6">

                    {{-- Branch / Department --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                        <div>

                            <p class="text-xs text-slate-500">
                                Branch
                            </p>

                            <p class="mt-1 font-medium text-slate-900
                                      dark:text-white">
                                {{ $ticket->branch?->name ?? '—' }}
                            </p>

                        </div>


                        <div>

                            <p class="text-xs text-slate-500">
                                Department
                            </p>

                            <p class="mt-1 font-medium text-slate-900
                                      dark:text-white">
                                {{ $ticket->department?->name ?? '—' }}
                            </p>

                        </div>

                    </div>


                    {{-- Date --}}
                    <div>

                        <p class="text-xs text-slate-500">
                            Submitted
                        </p>

                        <p class="mt-1 font-medium text-slate-900
                                  dark:text-white">

                            {{ $ticket->created_at?->format('F d, Y h:i A') }}

                        </p>

                    </div>


                    {{-- Customer Information --}}
                    <div>

                        <h2 class="text-sm font-semibold text-slate-900
                                   dark:text-white mb-4">
                            Your Information
                        </h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                            <div>

                                <p class="text-xs text-slate-500">
                                    Name
                                </p>

                                <p class="mt-1 text-sm">
                                    {{ $ticket->customer_name ?: 'Not provided' }}
                                </p>

                            </div>


                            <div>

                                <p class="text-xs text-slate-500">
                                    Contact Number
                                </p>

                                <p class="mt-1 text-sm">
                                    {{ $ticket->customer_phone ?: 'Not provided' }}
                                </p>

                            </div>


                            <div>

                                <p class="text-xs text-slate-500">
                                    Email
                                </p>

                                <p class="mt-1 text-sm">
                                    {{ $ticket->customer_email ?: 'Not provided' }}
                                </p>

                            </div>

                            <div>

                                <p class="text-xs text-slate-500">
                                    Age
                                </p>

                                <p class="mt-1 text-sm">
                                    {{ $ticket->customer_age ?? 'Not provided' }}
                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- Feedback --}}
                    <div>

                        <h2 class="text-sm font-semibold text-slate-900
                                   dark:text-white mb-4">
                            Feedback Details
                        </h2>


                        <div class="space-y-4">

                            <div>

                                <p class="text-xs text-slate-500">
                                    Subject
                                </p>

                                <p class="mt-1 font-medium">
                                    {{ $ticket->subject }}
                                </p>

                            </div>


                            <div>

                                <p class="text-xs text-slate-500">
                                    Category
                                </p>

                                <p class="mt-1">
                                    {{ ucfirst($ticket->category ?? '—') }}
                                </p>

                            </div>


                            @if($ticket->rating)

                                <div>

                                    <p class="text-xs text-slate-500">
                                        Rating
                                    </p>

                                    <p class="mt-1">
                                        {{ $ticket->rating }} / 5
                                    </p>

                                </div>

                            @endif


                            @if($ticket->satisfaction)

                                <div>

                                    <p class="text-xs text-slate-500">
                                        Satisfaction
                                    </p>

                                    <p class="mt-1">
                                        {{ ucfirst($ticket->satisfaction) }}
                                    </p>

                                </div>

                            @endif


                            <div>

                                <p class="text-xs text-slate-500">
                                    Description
                                </p>

                                <div
                                    class="mt-2 rounded-lg bg-slate-50
                                           dark:bg-slate-800 p-4 text-sm
                                           leading-6"
                                >
                                    {{ $ticket->description ?: $ticket->comment }}
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>
