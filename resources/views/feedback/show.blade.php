<x-app-layout>

    <x-slot name="header">

        <x-admin.page-header
            :title="$feedback->subject"
            :description="ucfirst($feedback->type) . ' feedback'"
            icon="message-square-more"
        >
            <x-admin.status-badge
                :value="$feedback->status"
            />
        </x-admin.page-header>

    </x-slot>


    <div class="grid gap-6 xl:grid-cols-[1.3fr,0.7fr]">

        {{-- ========================================================= --}}
        {{-- FEEDBACK DETAILS --}}
        {{-- ========================================================= --}}

        <section
            class="rounded-3xl border border-slate-200
                   bg-white p-6 shadow-sm"
        >

            <h3 class="text-lg font-semibold text-slate-950">
                Feedback Details
            </h3>


            <dl class="mt-5 grid gap-4 md:grid-cols-2">

                {{-- Name --}}
                <div>
                    <dt class="text-sm font-medium text-slate-500">
                        Name
                    </dt>

                    <dd class="mt-1 text-sm text-slate-900">
                        {{ $feedback->name ?: 'Anonymous' }}
                    </dd>
                </div>


                {{-- Email --}}
                <div>
                    <dt class="text-sm font-medium text-slate-500">
                        Email
                    </dt>

                    <dd class="mt-1 text-sm text-slate-900">
                        {{ $feedback->email ?? 'N/A' }}
                    </dd>
                </div>


                {{-- Contact Number --}}
                <div>
                    <dt class="text-sm font-medium text-slate-500">
                        Contact Number
                    </dt>

                    <dd class="mt-1 text-sm text-slate-900">
                        {{ $feedback->phone ?? 'N/A' }}
                    </dd>
                </div>


                {{-- Age --}}
                <div>
                    <dt class="text-sm font-medium text-slate-500">
                        Age
                    </dt>

                    <dd class="mt-1 text-sm text-slate-900">
                        {{ $feedback->age ?? 'N/A' }}
                    </dd>
                </div>


                {{-- Type --}}
                <div>
                    <dt class="text-sm font-medium text-slate-500">
                        Type
                    </dt>

                    <dd class="mt-1 text-sm font-semibold text-slate-900">
                        {{ ucfirst($feedback->type) }}
                    </dd>
                </div>


                {{-- Branch --}}
                <div>
                    <dt class="text-sm font-medium text-slate-500">
                        Branch
                    </dt>

                    <dd class="mt-1 text-sm text-slate-900">
                        {{ $feedback->branch?->name ?? 'No branch' }}
                    </dd>
                </div>


                {{-- Department --}}
                <div>
                    <dt class="text-sm font-medium text-slate-500">
                        Department
                    </dt>

                    <dd class="mt-1 text-sm text-slate-900">
                        {{ $feedback->department?->name ?? 'N/A' }}
                    </dd>
                </div>


                {{-- Category --}}
                <div>
                    <dt class="text-sm font-medium text-slate-500">
                        Category
                    </dt>

                    <dd class="mt-1 text-sm text-slate-900">
                        {{ $feedback->category
                            ? ucfirst($feedback->category)
                            : 'N/A'
                        }}
                    </dd>
                </div>


                {{-- Author --}}
                <div>
                    <dt class="text-sm font-medium text-slate-500">
                        Submitted By
                    </dt>

                    <dd class="mt-1 text-sm text-slate-900">
                        {{ $feedback->user?->name ?? 'Anonymous' }}
                    </dd>
                </div>

            </dl>


            {{-- ===================================================== --}}
            {{-- CUSTOMER SATISFACTION --}}
            {{-- ===================================================== --}}

            @if ($feedback->type === 'customer')

                <div class="mt-8 border-t border-slate-200 pt-6">

                    <h4 class="text-lg font-semibold text-slate-950">
                        Customer Satisfaction
                    </h4>


                    {{-- Thumbs --}}
                    <div class="mt-5">

                        <p class="text-sm font-medium text-slate-500">
                            Experience
                        </p>

                        <div class="mt-2">

                            @if ($feedback->sentiment === 'positive')

                                <span
                                    class="inline-flex items-center gap-2
                                           rounded-xl bg-green-50
                                           px-4 py-2 text-sm font-semibold
                                           text-green-700"
                                >
                                    👍 Good
                                </span>

                            @elseif ($feedback->sentiment === 'negative')

                                <span
                                    class="inline-flex items-center gap-2
                                           rounded-xl bg-red-50
                                           px-4 py-2 text-sm font-semibold
                                           text-red-700"
                                >
                                    👎 Needs Improvement
                                </span>

                            @else

                                <span class="text-sm text-slate-400">
                                    No response
                                </span>

                            @endif

                        </div>

                    </div>


                    {{-- Star Rating --}}
                    <div class="mt-5">

                        <p class="text-sm font-medium text-slate-500">
                            Satisfaction Rating
                        </p>


                        @if ($feedback->rating)

                            <div class="mt-2 flex items-center gap-1">

                                @for ($i = 1; $i <= 5; $i++)

                                    <span
                                        class="text-2xl
                                        {{ $i <= $feedback->rating
                                            ? 'text-yellow-400'
                                            : 'text-slate-300'
                                        }}"
                                    >
                                        ★
                                    </span>

                                @endfor

                                <span class="ml-2 text-sm text-slate-500">
                                    {{ $feedback->rating }}/5
                                </span>

                            </div>

                        @else

                            <p class="mt-2 text-sm text-slate-400">
                                No rating provided
                            </p>

                        @endif

                    </div>

                </div>

            @endif


            {{-- ===================================================== --}}
            {{-- MESSAGE / DESCRIPTION --}}
            {{-- ===================================================== --}}

            <div class="mt-8">

                <h4 class="text-lg font-semibold text-slate-950">

                    @if ($feedback->type === 'customer')
                        Feedback
                    @else
                        Description
                    @endif

                </h4>


                <div
                    class="mt-4 rounded-2xl bg-slate-50
                           p-5 text-sm leading-7 text-slate-700"
                >
                    {{ $feedback->message }}
                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- ATTACHMENTS --}}
            {{-- ===================================================== --}}

            <div class="mt-8">

                <h4
                    class="text-lg font-semibold
                           text-slate-950 dark:text-slate-100"
                >
                    Attachments
                </h4>

                <div class="mt-4">

                    <x-attachments.list
                        :attachments="$feedback->attachments"
                        :can-delete="auth()->user()->can(
                            'update',
                            $feedback
                        )"
                    />

                </div>

            </div>

        </section>


        {{-- ========================================================= --}}
        {{-- ACTIONS --}}
        {{-- ========================================================= --}}

        <aside
            class="rounded-3xl border border-slate-200
                   bg-white p-6 shadow-sm"
        >

            <h3 class="text-lg font-semibold text-slate-950">
                Actions
            </h3>


            <div class="mt-4 flex flex-wrap gap-3">


                {{-- ================================================= --}}
                {{-- EDIT --}}
                {{-- ================================================= --}}

                @can('update', $feedback)

                    <a
                        href="{{ route(
                            'feedback.edit',
                            ['feedback' => $feedback->id ?? request()->route('feedback')]
                        ) }}"
                        class="rounded-xl border border-cyan-200
                               bg-cyan-50 px-4 py-2
                               text-sm font-medium text-cyan-700
                               transition hover:bg-cyan-100"
                    >
                        Edit
                    </a>

                @endcan


                {{-- ================================================= --}}
                {{-- ARCHIVE --}}
                {{-- ================================================= --}}

                @can('delete', $feedback)

                    <form
                        method="POST"
                        action="{{ route(
                            'feedback.destroy',
                            ['feedback' => $feedback->id ?? request()->route('feedback')]
                        ) }}"
                        onsubmit="return confirm(
                            'Archive this feedback?'
                        )"
                    >

                        @csrf

                        @method('DELETE')

                        <x-danger-button>
                            Archive
                        </x-danger-button>

                    </form>

                @endcan

            </div>

        </aside>

    </div>

</x-app-layout>