<x-app-layout>

    <div class="min-h-screen bg-gray-50 py-8">

        <div class="max-w-lg mx-auto px-4">

            {{-- Header --}}
            <div class="text-center mb-8">

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
            @if(session('status'))
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

                <div class="bg-white rounded-xl border p-4 mb-3">
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
                class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6"
                x-data="{
                    showConfirm: false,
                    showSuccess: {{ session()->has('feedback_success') ? 'true' : 'false' }},
                    success: @json(session('feedback_success') ?? null)
                }"
                x-ref="feedbackForm"
                @submit.prevent="showConfirm = true"
            >

                @csrf


                {{-- Personal Information --}}
                <div class="mb-8">

                    <h2 class="text-lg font-semibold text-gray-900 mb-4">
                        Your Information
                    </h2>


                    {{-- Name --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Name
                            <span class="text-gray-400">(Optional)</span>
                        </label>

                        <input
                            type="text"
                            name="customer_name"
                            value="{{ old('customer_name') }}"
                            placeholder="Enter your name"
                            class="w-full rounded-lg border-gray-300
                                   focus:border-blue-500 focus:ring-blue-500"
                        >
                    </div>


                    {{-- Contact Number --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Contact Number
                            <span class="text-gray-400">(Optional)</span>
                        </label>

                        <input
                            type="tel"
                            name="customer_phone"
                            value="{{ old('customer_phone') }}"
                            placeholder="09XXXXXXXXX"
                            class="w-full rounded-lg border-gray-300
                                   focus:border-blue-500 focus:ring-blue-500"
                        >
                    </div>


                    {{-- Email --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Email
                            <span class="text-gray-400">(Optional)</span>
                        </label>

                        <input
                            type="email"
                            name="customer_email"
                            value="{{ old('customer_email') }}"
                            placeholder="example@email.com"
                            class="w-full rounded-lg border-gray-300
                                   focus:border-blue-500 focus:ring-blue-500"
                        >
                    </div>


                    {{-- Age --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Age
                            <span class="text-gray-400">(Optional)</span>
                        </label>

                        <input
                            type="number"
                            name="age"
                            value="{{ old('age') }}"
                            min="1"
                            max="120"
                            placeholder="Enter your age"
                            class="w-full rounded-lg border-gray-300
                                   focus:border-blue-500 focus:ring-blue-500"
                        >
                    </div>

                    {{-- Description --}}
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Description
                            <span class="text-gray-400">(Optional)</span>
                        </label>

                        <textarea
                            name="description"
                            rows="4"
                            placeholder="Tell us a bit about your experience"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        >{{ old('description') }}</textarea>

                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                </div>


                {{-- Thumbs --}}
                <div
                    class="mb-8"
                    x-data="{ satisfaction: '{{ old('satisfaction') }}' }"
                >

                    <h2 class="text-lg font-semibold text-gray-900 text-center mb-4">
                        How was your experience?
                    </h2>

                    <div class="flex justify-center gap-5">

                        {{-- Thumbs Up --}}
                        <button
                            type="button"
                            @click="satisfaction = 'like'"
                            :class="satisfaction === 'like'
                                ? 'border-green-500 bg-green-50'
                                : 'border-gray-200 bg-white'"
                            class="w-28 h-24 rounded-xl border-2
                                   flex flex-col items-center justify-center
                                   transition"
                        >
                            <span class="text-4xl">👍</span>

                            <span class="text-sm font-medium text-gray-600 mt-1">
                                Good
                            </span>
                        </button>


                        {{-- Thumbs Down --}}
                        <button
                            type="button"
                            @click="satisfaction = 'dislike'"
                            :class="satisfaction === 'dislike'
                                ? 'border-red-500 bg-red-50'
                                : 'border-gray-200 bg-white'"
                            class="w-28 h-24 rounded-xl border-2
                                   flex flex-col items-center justify-center
                                   transition"
                        >
                            <span class="text-4xl">👎</span>

                            <span class="text-sm font-medium text-gray-600 mt-1">
                                Not Good
                            </span>
                        </button>

                    </div>

                    <input
                        type="hidden"
                        name="satisfaction"
                        :value="satisfaction"
                    >

                    <x-input-error
                        :messages="$errors->get('satisfaction')"
                        class="mt-3 text-center"
                    />

                </div>


                {{-- Star Rating --}}
                <div
                    class="mb-8 text-center"
                    x-data="{ rating: {{ old('rating', 0) }} }"
                >

                    <h2 class="text-lg font-semibold text-gray-900">
                        Satisfaction Rating
                    </h2>

                    <p class="text-sm text-gray-500 mt-1 mb-4">
                        Rate your experience from 1 to 5 stars.
                    </p>


                    <div class="flex justify-center gap-2">

                        @for($i = 1; $i <= 5; $i++)

                            <button
                                type="button"
                                @click="rating = {{ $i }}"
                                class="text-4xl leading-none transition"
                                :class="rating >= {{ $i }}
                                    ? 'text-yellow-400'
                                    : 'text-gray-300'"
                            >
                                ★
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
                    >

                </div>


                {{-- Photo --}}
                <div class="mb-8">

                    <h2 class="text-lg font-semibold text-gray-900 mb-1">
                        Photo
                        <span class="text-sm font-normal text-gray-400">
                            (Optional)
                        </span>
                    </h2>

                    <p class="text-sm text-gray-500 mb-3">
                        Upload a photo or take a photo using your camera.
                    </p>


                    <label
                        class="flex flex-col items-center justify-center
                               w-full h-32 rounded-xl border-2 border-dashed
                               border-gray-300 cursor-pointer
                               hover:bg-gray-50 transition"
                    >

                        <span class="text-3xl mb-2">
                            📷
                        </span>

                        <span class="text-sm font-medium text-gray-600">
                            Upload or Take Photo
                        </span>

                        <span class="text-xs text-gray-400 mt-1">
                            JPG, PNG, WEBP
                        </span>

                        <input
                            type="file"
                            name="photos[]"
                            accept="image/*"
                            capture="environment"
                            class="hidden"
                        >

                    </label>

                </div>


                {{-- Submit --}}
                <button
                    type="submit"
                    class="w-full rounded-xl bg-blue-600
                           px-4 py-3 text-white font-semibold
                           hover:bg-blue-700 transition"
                >
                    Submit Feedback
                </button>

                {{-- Confirmation Modal --}}
                <div
                    x-show="showConfirm"
                    x-cloak
                    class="fixed inset-0 z-40 flex items-center justify-center bg-black/40 p-4"
                >
                    <div class="w-full max-w-md bg-white rounded-lg p-6">
                        <h3 class="text-lg font-semibold">Confirm submission</h3>
                        <p class="mt-2 text-sm text-gray-600">Are you sure you want to submit this feedback? You can’t edit it afterwards.</p>

                        <div class="mt-4 flex justify-end gap-3">
                            <button type="button" @click="showConfirm = false" class="rounded-lg px-4 py-2 border">Cancel</button>
                            <button type="button" @click="$refs.feedbackForm.submit()" class="rounded-lg bg-blue-600 px-4 py-2 text-white">Yes, submit</button>
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

                {{-- Success Popup / Tracker (shows when redirected after submit) --}}
                <div
                    x-show="showSuccess"
                    x-cloak
                    class="fixed inset-0 z-50 flex items-start justify-center p-6 pt-24"
                >
                    <div class="w-full max-w-xl bg-white rounded-xl shadow-lg border p-6">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="text-lg font-semibold">Feedback submitted</h3>
                                <p class="mt-1 text-sm text-gray-600">Thank you! Your feedback was received.</p>
                            </div>

                            <div class="text-right">
                                <button @click="showSuccess = false" class="text-sm text-gray-500">Close</button>
                            </div>
                        </div>

                        <div class="mt-4 border-t pt-4">
                            <p class="text-sm text-gray-700">Reference number:</p>
                            <div class="mt-2 inline-flex items-center gap-3 rounded-md bg-slate-50 px-3 py-2 text-sm font-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/></svg>
                                <span class="text-cyan-700" x-text="success?.reference_no ?? '—'"></span>
                            </div>

                            {{-- Simple Tracker --}}
                            <div class="mt-4">
                                <div class="flex items-center gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3">
                                            <div class="h-3 w-3 rounded-full bg-cyan-600"></div>
                                            <div class="text-sm font-medium">Submitted</div>
                                        </div>
                                    </div>

                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 opacity-40">
                                            <div class="h-3 w-3 rounded-full bg-gray-300"></div>
                                            <div class="text-sm">In progress</div>
                                        </div>
                                    </div>

                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 opacity-40">
                                            <div class="h-3 w-3 rounded-full bg-gray-300"></div>
                                            <div class="text-sm">Resolved</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 flex justify-end">
                                <a href="#" class="text-sm text-cyan-600">View details</a>
                            </div>
                        </div>
                    </div>
                </div>

            </form>


            <p class="text-center text-xs text-gray-400 mt-6">
                Thank you for helping us improve our service.
            </p>

        </div>

    </div>

</x-app-layout>
