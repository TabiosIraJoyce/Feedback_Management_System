<x-guest-layout>

    <div class="min-h-screen bg-gray-50 py-6 sm:py-8">

        <div class="mx-auto max-w-xl px-4 sm:px-6">

            {{-- Header --}}
            <div class="mb-6 text-center sm:mb-8">

                <img
                    src="{{ asset('images/feedback-logo.png') }}"
                    alt="GLEDCO Feedback Management System Logo"
                    class="mx-auto mb-4 h-20 w-20 object-contain"
                >

                <h1 class="text-2xl font-bold text-gray-900">
                    {{ $branch->name }}
                </h1>

                @if($department)
                    <p class="mt-1 text-sm text-gray-500">
                        {{ $department->name }}
                    </p>
                @endif

                <p class="mt-3 text-gray-600">
                    We value your feedback!
                </p>

            </div>


            {{-- Success Message --}}
            @if(session('status') && ! session()->has('feedback_success'))
                <div class="mb-6 rounded-xl bg-green-50 border border-green-200
                            px-4 py-3 text-sm text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Public tracker: auto-run when sidebar button sets ?show_my_requests=1 (no phone input) --}}
            <div class="mb-6" x-data="{
                        matches: null,
                        error: null,
                        loading: false,
                        async trackFromStored() {
                            this.error = null; this.matches = null;
                            const p = (localStorage.getItem('fms-customer-phone') || '').trim();
                            if (! p) { this.error = 'No phone stored. Submit feedback once to register your phone.'; return; }

                            this.loading = true;
                            try {
                                const res = await fetch('{{ route('feedback.track') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({ query: p })
                                });

                                const data = await res.json();

                                if (! res.ok) {
                                    this.error = data.error || 'No submitted requests found for your phone.';
                                } else {
                                    this.matches = data.matches || [];
                                }
                            } catch (e) {
                                this.error = 'Unable to contact server.';
                            } finally {
                                this.loading = false;
                            }
                        }
                    }"
                    x-init="(() => {
                        // Auto-run when link/QR contains show_my_requests=1 or when a saved phone exists
                        const params = new URLSearchParams(window.location.search);
                        const saved = (localStorage.getItem('fms-customer-phone') || '').trim();
                        if (params.get('show_my_requests') === '1' || saved) {
                            $nextTick(() => trackFromStored());
                        }
                    })()">

                <div x-show="error || matches" x-cloak class="mb-3 rounded-xl border bg-white p-4">
                    <template x-if="error">
                        <p class="text-sm text-gray-500" x-text="error"></p>
                    </template>

                    <template x-if="matches">
                        <div class="mt-3 border-t pt-3 space-y-2">
                            <div class="text-sm text-gray-700">Your submitted requests:</div>
                            <template x-if="matches.length === 0">
                                <div class="text-sm text-gray-500 mt-2">No submitted requests found for your phone number.</div>
                            </template>
                            <template x-for="m in matches" :key="m.reference_no">
                                <div class="flex items-center justify-between rounded-md p-2 bg-gray-50">
                                    <div>
                                        <div class="text-sm font-medium" x-text="m.reference_no"></div>
                                        <div class="text-xs text-gray-500" x-text="m.customer_name + ' • ' + (m.created_at ? new Date(m.created_at).toLocaleString() : '')"></div>
                                    </div>
                                    <div class="text-sm text-gray-600" x-text="m.status"></div>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>

            </div>


            {{-- Feedback Form --}}
            <form
                action="{{ route('feedback.public.store', [
                    'branch' => $branch->slug,
                    'department' => $department?->slug,
                ]) }}"
                method="POST"
                enctype="multipart/form-data"
                class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6"
                x-data="{
                    showConfirm: false,
                    showSuccess: {{ session()->has('feedback_success') ? 'true' : 'false' }},
                    success: @json(session('feedback_success') ?? null),
                    satisfaction: @js(old('satisfaction', '')),
                    rating: {{ (int) old('rating', 0) }},
                    showSatisfactionError: false,
                    showRatingError: false,
                    photoName: '',
                    validateAndConfirm() {
                        if (! this.$refs.feedbackForm.reportValidity()) return;

                        this.showSatisfactionError = ! this.$refs.satisfactionInput.value;
                        this.showRatingError = Number(this.$refs.ratingInput.value) < 1;

                        if (this.showSatisfactionError || this.showRatingError) {
                            return;
                        }

                        this.showConfirm = true;
                    }
                }"
                x-ref="feedbackForm"
                @submit.prevent="validateAndConfirm()"
            >

                @csrf

                @if ($errors->any())
                    <div class="mb-6 rounded-xl border border-red-300 bg-red-50 p-4 text-red-900" role="alert" aria-labelledby="feedback-error-title">
                        <h2 id="feedback-error-title" class="text-base font-semibold">Please review your answers</h2>
                        <ul class="mt-2 list-inside list-disc space-y-1 text-sm leading-6">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


                {{-- Personal Information --}}
                <div class="mb-8 space-y-5">

                    <h2 class="text-xl font-semibold text-gray-900">
                        Your Information
                    </h2>


                    {{-- Name --}}
                    <div id="feedback-customer-name">
                        <label for="customer_name" class="mb-1 block text-base font-medium text-gray-800">
                            Name
                            <span class="ml-0.5 font-semibold text-red-600" aria-hidden="true">*</span><span class="sr-only"> (required)</span>
                        </label>

                        <input
                            type="text"
                            id="customer_name"
                            name="customer_name"
                            value="{{ old('customer_name') }}"
                            placeholder="Enter your name"
                            autocomplete="name"
                            class="min-h-12 w-full rounded-lg border-gray-300 px-3 text-base focus:border-blue-500 focus:ring-blue-500"
                            required
                        >
                        <x-input-error :messages="$errors->get('customer_name')" class="mt-2 text-sm" />
                    </div>


                    {{-- Contact Number --}}
                    <div id="feedback-customer-phone">
                        <label for="customer_phone" class="mb-1 block text-base font-medium text-gray-800">
                            Contact Number
                            <span class="ml-0.5 font-semibold text-red-600" aria-hidden="true">*</span><span class="sr-only"> (required)</span>
                        </label>

                        <input
                            type="tel"
                            id="customer_phone"
                            name="customer_phone"
                            value="{{ old('customer_phone') }}"
                            placeholder="09XXXXXXXXX or +63 9XX XXX XXXX"
                            inputmode="tel"
                            autocomplete="tel"
                            pattern="(?=(?:\D*\d){7})[0-9+().\s-]{7,20}"
                            title="Enter at least 7 digits. You may include +, spaces, parentheses, dots, or hyphens."
                            class="min-h-12 w-full rounded-lg border-gray-300 px-3 text-base focus:border-blue-500 focus:ring-blue-500"
                            required
                        >
                        <x-input-error :messages="$errors->get('customer_phone')" class="mt-2 text-sm" />
                    </div>


                    {{-- Email --}}
                    <div id="feedback-customer-email">
                        <label for="customer_email" class="mb-1 block text-base font-medium text-gray-800">
                            Email
                            <span class="ml-0.5 font-semibold text-red-600" aria-hidden="true">*</span><span class="sr-only"> (required)</span>
                        </label>

                        <input
                            type="email"
                            id="customer_email"
                            name="customer_email"
                            value="{{ old('customer_email') }}"
                            placeholder="example@email.com"
                            autocomplete="email"
                            class="min-h-12 w-full rounded-lg border-gray-300 px-3 text-base focus:border-blue-500 focus:ring-blue-500"
                            required
                        >
                        <x-input-error :messages="$errors->get('customer_email')" class="mt-2 text-sm" />
                    </div>


                    {{-- Age --}}
                    <div id="feedback-age">
                        <label for="age" class="mb-1 block text-base font-medium text-gray-800">
                            Age
                            <span class="ml-0.5 font-semibold text-red-600" aria-hidden="true">*</span><span class="sr-only"> (required)</span>
                        </label>

                        <input
                            type="number"
                            id="age"
                            name="age"
                            value="{{ old('age') }}"
                            min="1"
                            max="120"
                            placeholder="Enter your age"
                            inputmode="numeric"
                            class="min-h-12 w-full rounded-lg border-gray-300 px-3 text-base focus:border-blue-500 focus:ring-blue-500"
                            required
                        >
                        <x-input-error :messages="$errors->get('age')" class="mt-2 text-sm" />
                    </div>

                    {{-- Description --}}
                    <div id="feedback-description" class="mt-1">
                        <label for="description" class="mb-1 block text-base font-medium text-gray-800">
                            Description
                            <span class="ml-0.5 font-semibold text-red-600" aria-hidden="true">*</span><span class="sr-only"> (required)</span>
                        </label>

                        <textarea
                            id="description"
                            name="description"
                            rows="4"
                            placeholder="Tell us a bit about your experience"
                            maxlength="5000"
                            class="w-full rounded-lg border-gray-300 px-3 py-2 text-base leading-6 focus:border-blue-500 focus:ring-blue-500"
                            required
                        >{{ old('description') }}</textarea>

                        <x-input-error :messages="$errors->get('description')" class="mt-2 text-sm" />
                    </div>

                </div>


                {{-- Thumbs --}}
                <div id="feedback-satisfaction" class="mb-8">

                    <h2 class="mb-2 text-center text-xl font-semibold text-gray-900">
                        Overall experience
                        <span class="ml-0.5 font-semibold text-red-600" aria-hidden="true">*</span><span class="sr-only"> (required)</span>
                    </h2>

                    <p class="mb-4 text-center text-sm leading-6 text-gray-600">Choose whether your experience was positive or needs improvement.</p>

                    <div class="flex justify-center gap-5">

                        {{-- Thumbs Up --}}
                        <button
                            type="button"
                            @click="satisfaction = 'like'; showSatisfactionError = false"
                            :aria-pressed="satisfaction === 'like'"
                            aria-label="Positive experience"
                            :class="satisfaction === 'like'
                                ? 'border-green-500 bg-green-50'
                                : 'border-gray-200 bg-white'"
                            class="min-h-24 w-28 rounded-xl border-2 sm:w-32
                                   flex flex-col items-center justify-center
                                   text-base transition focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            <span class="text-4xl">👍</span>

                            <span class="mt-1 text-base font-medium text-gray-700">
                                Good
                            </span>
                        </button>


                        {{-- Thumbs Down --}}
                        <button
                            type="button"
                            @click="satisfaction = 'dislike'; showSatisfactionError = false"
                            :aria-pressed="satisfaction === 'dislike'"
                            aria-label="Experience needs improvement"
                            :class="satisfaction === 'dislike'
                                ? 'border-red-500 bg-red-50'
                                : 'border-gray-200 bg-white'"
                            class="min-h-24 w-28 rounded-xl border-2 sm:w-32
                                   flex flex-col items-center justify-center
                                   text-base transition focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            <span class="text-4xl">👎</span>

                            <span class="mt-1 text-base font-medium text-gray-700">
                                Needs improvement
                            </span>
                        </button>

                    </div>

                    <input
                        type="hidden"
                        name="satisfaction"
                        :value="satisfaction"
                        x-ref="satisfactionInput"
                    >

                    <x-input-error
                        :messages="$errors->get('satisfaction')"
                        class="mt-3 text-center"
                    />
                    <p x-show="showSatisfactionError" class="mt-2 text-center text-sm text-red-600" role="alert">
                        Choose Good or Needs improvement to describe your overall experience.
                    </p>

                </div>


                {{-- Star Rating --}}
                <div
                    id="feedback-rating"
                    class="mb-8 text-center"
                >

                    <h2 class="text-xl font-semibold text-gray-900">
                        Satisfaction rating
                        <span class="ml-0.5 font-semibold text-red-600" aria-hidden="true">*</span><span class="sr-only"> (required)</span>
                    </h2>

                    <p class="mx-auto mb-4 mt-1 max-w-md text-sm leading-6 text-gray-600">
                        Choose one score to show how satisfied you were: 1 is Very Poor and 5 is Excellent.
                    </p>


                    @php($ratingLabels = [1 => 'Very Poor', 2 => 'Poor', 3 => 'Fair', 4 => 'Good', 5 => 'Excellent'])
                    <div class="mx-auto grid max-w-md grid-cols-5 gap-2">

                        @for($i = 1; $i <= 5; $i++)
                            @php($ratingLabel = $ratingLabels[$i])

                            <button
                                type="button"
                                @click="rating = {{ $i }}; showRatingError = false"
                                aria-label="{{ $i }} {{ $i === 1 ? 'star' : 'stars' }}: {{ $ratingLabel }}"
                                title="{{ $i }} {{ $i === 1 ? 'star' : 'stars' }}: {{ $ratingLabel }}"
                                :aria-pressed="rating === {{ $i }}"
                                class="flex min-h-24 min-w-0 flex-col items-center justify-center rounded-lg px-1 py-2 transition hover:bg-yellow-50 focus:outline-none focus:ring-2 focus:ring-yellow-400"
                                :class="rating >= {{ $i }}
                                    ? 'text-yellow-400'
                                    : 'text-gray-300'"
                            >
                                <span class="text-3xl leading-none">★</span>
                                <span class="mt-2 text-sm leading-5 text-gray-700">{{ $i }} {{ $i === 1 ? 'Star' : 'Stars' }}</span>
                                <span class="text-sm leading-5 text-gray-600">{{ $ratingLabel }}</span>
                            </button>

                        @endfor

                    </div>


                    <p
                        class="text-sm text-gray-500 mt-2"
                        x-text="rating > 0
                            ? rating + ' out of 5'
                            : 'Please select a rating'"
                    ></p>


                    <input
                        type="hidden"
                        name="rating"
                        x-model="rating"
                        x-ref="ratingInput"
                    >
                    <x-input-error :messages="$errors->get('rating')" class="mt-2" />
                    <p x-show="showRatingError" class="mt-2 text-sm text-red-600" role="alert">
                        Please select a star rating.
                    </p>

                </div>


                {{-- Photo --}}
                <div id="feedback-photo" class="mb-8">

                    <h2 class="text-lg font-semibold text-gray-900 mb-1">
                        Photo
                        <span class="text-sm font-normal text-gray-600">
                            (Optional)
                        </span>
                    </h2>

                    <p class="mb-3 text-sm leading-6 text-gray-600">
                        A photo can help explain your feedback, but you can submit without one. JPG, PNG, or WEBP up to 4 MB.
                    </p>


                    <label
                        class="flex flex-col items-center justify-center
                               min-h-36 w-full rounded-xl border-2 border-dashed p-4
                               border-gray-300 cursor-pointer
                               hover:bg-gray-50 transition"
                    >

                        <span class="text-3xl mb-2">
                            📷
                        </span>

                        <span class="text-base font-medium text-gray-700">
                            Choose a photo (optional)
                        </span>

                        <span class="mt-1 text-sm text-gray-600" x-text="photoName || 'No photo selected'">
                        </span>

                        <input
                            type="file"
                            name="photos[]"
                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                            capture="environment"
                            class="sr-only"
                            @change="photoName = $event.target.files[0]?.name || ''"
                        >

                    </label>
                    <x-input-error :messages="$errors->get('photos')" class="mt-2" />
                    <x-input-error :messages="$errors->get('photos.0')" class="mt-2" />

                </div>


                {{-- Submit --}}
                <button
                    type="submit"
                    class="min-h-14 w-full rounded-xl bg-blue-600
                           px-4 py-3 text-base text-white font-semibold
                           hover:bg-blue-700 transition"
                >
                    Submit Feedback
                </button>

                {{-- Confirmation Modal --}}
                <div
                    x-show="showConfirm"
                    x-cloak
                    class="fixed inset-0 z-40 flex items-center justify-center bg-black/40 p-4"
                    role="dialog"
                    aria-modal="true"
                    aria-labelledby="feedback-confirm-title"
                >
                    <div class="w-full max-w-md rounded-2xl bg-white p-5 shadow-xl sm:p-6">
                        <h3 id="feedback-confirm-title" class="text-xl font-semibold text-gray-900">Confirm submission</h3>
                        <p class="mt-2 text-base leading-6 text-gray-700">Ready to send your feedback? You will not be able to edit it after submission.</p>

                        <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                            <button type="button" @click="showConfirm = false" class="min-h-12 rounded-lg border border-gray-300 px-5 text-base font-medium text-gray-800">Go back</button>
                            <button type="button" @click="$refs.feedbackForm.submit()" class="min-h-12 rounded-lg bg-blue-600 px-5 text-base font-semibold text-white hover:bg-blue-700">Submit feedback</button>
                        </div>
                    </div>
                </div>

                <script>
                    document.addEventListener('submit', function (e) {
                        // Save customer phone to localStorage when public feedback form is submitted
                        try {
                            var form = e.target;
                            if (form && form.querySelector && form.querySelector('input[name="customer_phone"]')) {
                                var phone = form.querySelector('input[name="customer_phone"]').value || '';
                                if (phone.trim() !== '') {
                                    localStorage.setItem('fms-customer-phone', phone.trim());
                                }
                            }
                        } catch (err) {
                            // ignore
                        }
                    }, true);
                </script>

                <script>
                    // When a ticket was just created, persist its reference locally
                    (function () {
                        try {
                            var success = @json(session('feedback_success') ?? null);
                            if (success && success.reference_no) {
                                var refs = JSON.parse(localStorage.getItem('fms-customer-refs') || '[]');
                                // prepend newest
                                refs.unshift({ reference_no: success.reference_no, created_at: new Date().toISOString() });
                                // unique by reference_no
                                var seen = {};
                                refs = refs.filter(function (r) {
                                    if (seen[r.reference_no]) return false;
                                    seen[r.reference_no] = true;
                                    return true;
                                }).slice(0, 10);
                                localStorage.setItem('fms-customer-refs', JSON.stringify(refs));
                            }
                        } catch (err) {
                            // ignore
                        }
                    })();
                </script>

                {{-- Clear submission confirmation --}}
                <div
                    x-show="showSuccess"
                    x-cloak
                    class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 p-4"
                    role="dialog"
                    aria-modal="true"
                    aria-labelledby="feedback-success-title"
                >
                    <div class="w-full max-w-lg rounded-2xl border border-green-200 bg-white p-6 shadow-xl sm:p-8">
                        <div class="flex h-14 w-14 items-center justify-center rounded-full bg-green-100 text-3xl font-bold text-green-700" aria-hidden="true">✓</div>
                        <h3 id="feedback-success-title" class="mt-4 text-2xl font-bold text-gray-900">Thank you. Your feedback was received.</h3>
                        <p class="mt-2 text-base leading-6 text-gray-700">Your feedback has been submitted successfully. Keep this reference number if you want to follow up.</p>

                        <div class="mt-5 rounded-xl bg-gray-50 p-4">
                            <p class="text-sm font-medium text-gray-700">Reference number</p>
                            <p class="mt-1 break-all text-xl font-bold tracking-wide text-cyan-800" x-text="success?.reference_no ?? 'Not available'"></p>
                        </div>

                        <button type="button" @click="showSuccess = false" class="mt-6 min-h-12 w-full rounded-xl bg-blue-600 px-5 text-base font-semibold text-white hover:bg-blue-700">Done</button>
                    </div>
                </div>

            </form>


            <p class="text-center text-xs text-gray-400 mt-6">
                Thank you for helping us improve our service.
            </p>

        </div>

    </div>

</x-guest-layout>
