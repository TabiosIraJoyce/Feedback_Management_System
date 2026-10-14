@props([
    'feedback' => null,
    'branches',
    'departments' => collect(),
    'submitLabel' => 'Submit Feedback',
])

@php
    $attachmentRules = app(\App\Services\AttachmentValidationService::class);
    $showStatusField = $feedback && auth()->user()->can('manage feedback');
    $isCustomer = auth()->user()->hasRole('Customer');
@endphp


@if ($isCustomer)

    {{-- ========================================================= --}}
    {{-- CUSTOMER FEEDBACK FORM --}}
    {{-- ========================================================= --}}

    <div
        x-data="{
            sentiment: '{{ old('sentiment', $feedback?->sentiment) }}',
            rating: {{ old('rating', $feedback?->rating ?? 0) }}
        }"
        class="space-y-6"
    >

        {{-- Header --}}
        <div class="text-center">
            <h2 class="text-2xl font-bold text-primary-text dark:text-white">
                Customer Feedback
            </h2>

            <p class="mt-2 text-sm text-secondary-text dark:text-slate-400">
                We value your feedback. Please tell us about your experience.
            </p>
        </div>


        {{-- Branch --}}
        <div class="hci-panel p-6">

            <x-input-label for="branch_id" value="Branch" />

            <select
                id="branch_id"
                name="branch_id"
                class="hci-form-control mt-2 block w-full px-4 py-3 text-sm focus:ring-0"
                required
            >
                <option value="">Choose a branch</option>

                @foreach ($branches as $branch)
                    <option
                        value="{{ $branch->id }}"
                        @selected(
                            old(
                                'branch_id',
                                $feedback?->branch_id ?? auth()->user()->branch_id
                            ) == $branch->id
                        )
                    >
                        {{ $branch->name }}
                    </option>
                @endforeach
            </select>

            <x-input-error
                :messages="$errors->get('branch_id')"
                class="mt-2"
            />

            {{-- Customer type --}}
            <input type="hidden" name="type" value="customer">
            <input type="hidden" name="subject" value="{{ old('subject', $feedback?->subject ?? 'Customer feedback submission') }}">

            

        </div>


        {{-- Personal Information --}}
        <div class="hci-panel p-6">

            <h3 class="text-lg font-semibold text-primary-text dark:text-white">
                Your Information
            </h3>

            <p class="mt-1 text-sm text-secondary-text dark:text-slate-400">
                These details are optional.
            </p>


            <div class="mt-5 grid gap-4 md:grid-cols-2">

                {{-- Name --}}
                <div>
                    <x-input-label for="name" value="Name (Optional)" />

                    <x-text-input
                        id="name"
                        name="name"
                        class="mt-2 block w-full"
                        :value="old(
                            'name',
                            $feedback?->name ?? (
                                auth()->user()->name ?? ''
                            )
                        )"
                        placeholder="Enter your name"
                    />

                    <x-input-error
                        :messages="$errors->get('name')"
                        class="mt-2"
                    />
                </div>


                {{-- Contact Number --}}
                <div>
                    <x-input-label
                        for="phone"
                        value="Contact Number (Optional)"
                    />

                    <x-text-input
                        id="phone"
                        name="phone"
                        type="tel"
                        class="mt-2 block w-full"
                        :value="old('phone', $feedback?->phone)"
                        placeholder="09XXXXXXXXX"
                    />

                    <x-input-error
                        :messages="$errors->get('phone')"
                        class="mt-2"
                    />
                </div>


                {{-- Email --}}
                <div>
                    <x-input-label
                        for="email"
                        value="Email (Optional)"
                    />

                    <x-text-input
                        id="email"
                        name="email"
                        type="email"
                        class="mt-2 block w-full"
                        :value="old(
                            'email',
                            $feedback?->email ?? (
                                auth()->user()->email ?? ''
                            )
                        )"
                        placeholder="name@example.com"
                    />

                    <x-input-error
                        :messages="$errors->get('email')"
                        class="mt-2"
                    />
                </div>


                {{-- Age --}}
                <div>
                    <x-input-label
                        for="age"
                        value="Age (Optional)"
                    />

                    <x-text-input
                        id="age"
                        name="age"
                        type="number"
                        min="1"
                        max="120"
                        class="mt-2 block w-full"
                        :value="old('age', $feedback?->age)"
                        placeholder="Enter your age"
                    />

                    <x-input-error
                        :messages="$errors->get('age')"
                        class="mt-2"
                    />
                </div>

                {{-- Description --}}
                <div>
                    <x-input-label for="description" value="Description (Optional)" />

                    <textarea id="description" name="description" rows="4" class="mt-2 block w-full rounded-xl border-slate-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500" placeholder="Tell us a bit about your experience">{{ old('description', $feedback?->message ?? '') }}</textarea>

                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

            </div>

        </div>


        {{-- Thumbs Up / Down --}}
        <div class="hci-panel p-6">

            <div class="text-center">

                <h3 class="text-lg font-semibold text-primary-text dark:text-white">
                    How was your experience?
                </h3>

                <p class="mt-1 text-sm text-secondary-text dark:text-slate-400">
                    Choose one.
                </p>


                <div class="mt-5 flex justify-center gap-5">

                    {{-- Thumbs Up --}}
                    <button
                        type="button"
                        @click="sentiment = 'positive'"
                        :class="sentiment === 'positive'
                            ? 'border-green-500 bg-green-50 text-green-600'
                            : 'border-card-border bg-white text-slate-500'"
                        class="flex h-24 w-32 flex-col items-center
                               justify-center rounded-2xl border-2
                               transition hover:bg-green-50"
                    >
                        <span class="text-4xl">👍</span>

                        <span class="mt-1 text-sm font-semibold">
                            Good
                        </span>
                    </button>


                    {{-- Thumbs Down --}}
                    <button
                        type="button"
                        @click="sentiment = 'negative'"
                        :class="sentiment === 'negative'
                            ? 'border-red-500 bg-red-50 text-red-600'
                            : 'border-card-border bg-white text-slate-500'"
                        class="flex h-24 w-32 flex-col items-center
                               justify-center rounded-2xl border-2
                               transition hover:bg-red-50"
                    >
                        <span class="text-4xl">👎</span>

                        <span class="mt-1 text-sm font-semibold">
                            Needs Improvement
                        </span>
                    </button>

                </div>


                <input
                    type="hidden"
                    name="sentiment"
                    x-model="sentiment"
                />

                <x-input-error
                    :messages="$errors->get('sentiment')"
                    class="mt-2"
                />

            </div>

        </div>


        {{-- Star Rating --}}
        <div class="hci-panel p-6">

            <div class="text-center">

                <h3 class="text-lg font-semibold text-primary-text dark:text-white">
                    Satisfaction Rating
                </h3>

                <p class="mt-1 text-sm text-secondary-text dark:text-slate-400">
                    How satisfied are you with our service?
                </p>


                <div class="mt-5 flex justify-center gap-2">

                    @for ($i = 1; $i <= 5; $i++)

                        <button
                            type="button"
                            @click="rating = {{ $i }}"
                            class="text-4xl leading-none transition"
                            :class="rating >= {{ $i }}
                                ? 'text-yellow-400'
                                : 'text-slate-300'"
                        >
                            ★
                        </button>

                    @endfor

                </div>


                <p
                    class="mt-3 text-sm text-secondary-text dark:text-slate-400"
                    x-text="
                        rating > 0
                            ? rating + ' out of 5 stars'
                            : 'Select your rating'
                    "
                ></p>


                <input
                    type="hidden"
                    name="rating"
                    x-model="rating"
                />

                <x-input-error
                    :messages="$errors->get('rating')"
                    class="mt-2"
                />

            </div>

        </div>


        {{-- Photo --}}
        <div class="hci-panel p-6">

            <h3 class="text-lg font-semibold text-primary-text dark:text-white">
                Photo
                <span class="text-sm font-normal text-slate-400">
                    (Optional)
                </span>
            </h3>

            <p class="mt-1 text-sm text-secondary-text dark:text-slate-400">
                Upload a photo or take a photo using your camera.
            </p>


            <label
                for="attachments"
                class="mt-4 flex min-h-32 cursor-pointer
                       flex-col items-center justify-center
                       rounded-2xl border-2 border-dashed
                       border-card-border bg-white/60
                       transition hover:bg-slate-50"
            >

                <span class="text-4xl">
                    📷
                </span>

                <span class="mt-2 text-sm font-semibold text-slate-600">
                    Upload or Take Photo
                </span>

                <span class="mt-1 text-xs text-slate-400">
                    JPG, PNG, WEBP
                </span>

                <input
                    id="attachments"
                    name="attachments[]"
                    type="file"
                    accept="image/*"
                    capture="environment"
                    class="hidden"
                />

            </label>


            <x-input-error
                :messages="$errors->get('attachments')"
                class="mt-2"
            />

            <x-input-error
                :messages="$errors->get('attachments.*')"
                class="mt-2"
            />

        </div>


        {{-- Submit --}}
        <div class="flex justify-end">

            <x-primary-button>
                {{ __($submitLabel) }}
            </x-primary-button>

        </div>

    </div>


@else

    {{-- ========================================================= --}}
    {{-- EMPLOYEE / INTERNAL FEEDBACK FORM --}}
    {{-- ========================================================= --}}

    <div class="space-y-6">

        {{-- Header --}}
        <div class="hci-panel p-6">

            <p class="hci-eyebrow">
                Internal Feedback
            </p>

            <h2 class="mt-2 hci-section-title">
                Submit Internal Feedback
            </h2>

            <p class="mt-2 hci-section-copy">
                Select the appropriate department and category,
                then provide a clear description of your feedback.
            </p>

        </div>


        {{-- Routing --}}
        <section class="hci-panel p-6">

            <h3 class="hci-section-title">
                Feedback Details
            </h3>

            <div class="mt-5 grid gap-4 md:grid-cols-2">


                {{-- Branch --}}
                <div>

                    <x-input-label
                        for="branch_id"
                        value="Branch"
                    />

                    <select
                        id="branch_id"
                        name="branch_id"
                        class="hci-form-control mt-2 block w-full px-4 py-3 text-sm"
                        required
                    >

                        <option value="">
                            Select Department
                        </option>

                        @foreach ($branches as $branch)

                            <option
                                value="{{ $branch->id }}"
                                @selected(
                                    old(
                                        'branch_id',
                                        $feedback?->branch_id ??
                                        auth()->user()->branch_id
                                    ) == $branch->id
                                )
                            >
                                {{ $branch->name }}
                            </option>

                        @endforeach

                    </select>

                    <x-input-error
                        :messages="$errors->get('branch_id')"
                        class="mt-2"
                    />

                </div>

                    <x-input-error
                        :messages="$errors->get('department_id')"
                        class="mt-2"
                    />

                </div>


                {{-- Category --}}
                <div>

                    <x-input-label
                        for="category"
                        value="Category"
                    />

                    <select
                        id="category"
                        name="category"
                        class="hci-form-control mt-2 block w-full px-4 py-3 text-sm"
                        required
                    >

                        <option value="">
                            Select Category
                        </option>

                        @foreach ([
                            'complaint' => 'Complaint',
                            'suggestion' => 'Suggestion',
                            'service' => 'Service',
                            'process' => 'Process',
                            'employee' => 'Employee',
                            'facility' => 'Facility',
                            'other' => 'Other',
                        ] as $value => $label)

                            <option
                                value="{{ $value }}"
                                @selected(
                                    old(
                                        'category',
                                        $feedback?->category
                                    ) === $value
                                )
                            >
                                {{ $label }}
                            </option>

                        @endforeach

                    </select>

                    <x-input-error
                        :messages="$errors->get('category')"
                        class="mt-2"
                    />

                </div>


                {{-- Type --}}
                <input
                    type="hidden"
                    name="type"
                    value="internal"
                />

            </div>

        </section>


        {{-- Subject and Description --}}
        <section class="hci-panel p-6">

            <h3 class="hci-section-title">
                Describe Your Feedback
            </h3>


            {{-- Subject --}}
            <div class="mt-5">

                <x-input-label
                    for="subject"
                    value="Subject"
                />

                <x-text-input
                    id="subject"
                    name="subject"
                    class="mt-2 block w-full"
                    :value="old('subject', $feedback?->subject)"
                    required
                    placeholder="Write a short summary"
                />

                <x-input-error
                    :messages="$errors->get('subject')"
                    class="mt-2"
                />

            </div>


            {{-- Description --}}
            <div class="mt-5">

                <x-input-label
                    for="message"
                    value="Description"
                />

                <textarea
                    id="message"
                    name="message"
                    rows="7"
                    required
                    class="hci-form-control mt-2 block w-full px-4 py-3 text-sm"
                    placeholder="Describe your feedback, concern, or suggestion..."
                >{{ old('message', $feedback?->message) }}</textarea>

                <x-input-error
                    :messages="$errors->get('message')"
                    class="mt-2"
                />

            </div>

        </section>


        {{-- Attachment --}}
        <section class="hci-panel p-6">

            <h3 class="hci-section-title">
                Attachment
                <span class="text-sm font-normal text-slate-400">
                    (Optional)
                </span>
            </h3>

            <p class="mt-1 hci-field-hint">
                Add a photo or file if it helps explain your feedback.
            </p>


            <input
                id="attachments"
                name="attachments[]"
                type="file"
                multiple
                accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx"
                class="hci-form-control mt-4 block w-full px-4 py-3 text-sm"
            />

            <p class="mt-2 hci-field-hint">
                {{ $attachmentRules->uploadHelpText() }}
            </p>

            <x-input-error
                :messages="$errors->get('attachments')"
                class="mt-2"
            />

            <x-input-error
                :messages="$errors->get('attachments.*')"
                class="mt-2"
            />

        </section>


        {{-- Status --}}
        @if ($showStatusField)

            <section class="hci-panel p-6">

                <x-input-label
                    for="status"
                    value="Status"
                />

                <select
                    id="status"
                    name="status"
                    class="hci-form-control mt-2 block w-full px-4 py-3 text-sm"
                >

                    @foreach ([
                        'submitted',
                        'reviewed',
                        'archived'
                    ] as $status)

                        <option
                            value="{{ $status }}"
                            @selected(
                                old(
                                    'status',
                                    $feedback->status
                                ) === $status
                            )
                        >
                            {{ __(ucfirst($status)) }}
                        </option>

                    @endforeach

                </select>

                <x-input-error
                    :messages="$errors->get('status')"
                    class="mt-2"
                />

            </section>

        @endif


        {{-- Submit --}}
        <div class="flex items-center justify-end gap-3">

            <a
                href="{{ route('feedback.index') }}"
                class="inline-flex items-center justify-center
                       rounded-2xl border border-card-border
                       bg-white/90 px-5 py-3 text-sm font-semibold
                       text-secondary-text shadow-sm
                       transition hover:border-lavender"
            >
                Cancel
            </a>

            <x-primary-button>
                {{ __($submitLabel) }}
            </x-primary-button>

        </div>

    </div>

@endif
