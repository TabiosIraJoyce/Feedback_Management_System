<x-app-layout>
    <x-slot name="header">
        <x-admin.page-header title="Product Management" description="Manage products, assign them to branches, and keep branch availability up to date." icon="box" />
    </x-slot>

    <div class="space-y-6">
        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-slate-950">Product Catalog</h3>
                    <p class="mt-1 text-sm text-slate-500">Search by product name, filter by business type, and manage product activation states.</p>
                </div>

                <form method="GET" action="{{ route('products.index') }}" class="grid gap-3 sm:grid-cols-[minmax(0,1fr),auto,auto] lg:w-[720px]">
                    <x-text-input type="search" name="search" placeholder="Search products" class="w-full" value="{{ request('search') }}" />

                    <input type="hidden" name="business_type" value="" />

                    <select name="status" class="rounded-xl border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
                        <option value="">All statuses</option>
                        <option value="active" @selected(request('status') === 'active')>Active</option>
                        <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                    </select>
                </form>
            </div>
        </section>

        <div class="grid gap-6 xl:grid-cols-[0.95fr,1.05fr]">
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-slate-950">Create New Product</h3>

                <form method="POST" action="{{ route('products.store') }}" class="mt-5 space-y-4">
                    @csrf

                    <div>
                        <x-input-label for="name" value="Product Name" />
                        <x-text-input id="name" name="name" class="mt-1 block w-full" value="{{ old('name') }}" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="business_type_id" value="Business Type" />
                            <select id="business_type_id" name="business_type_id" class="mt-1 block w-full rounded-xl border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500">
                                <option value="">Not assigned</option>
                            </select>
                        <x-input-error :messages="$errors->get('business_type_id')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="icon" value="Product Icon" />
                        <x-text-input id="icon" name="icon" class="mt-1 block w-full" value="{{ old('icon') }}" placeholder="e.g. droplet, gas-station" />
                        <x-input-error :messages="$errors->get('icon')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="status" value="Status" />
                        <select id="status" name="status" class="mt-1 block w-full rounded-xl border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500" required>
                            <option value="active" @selected(old('status') === 'active')>Active</option>
                            <option value="inactive" @selected(old('status') === 'inactive')>Inactive</option>
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="description" value="Description" />
                        <textarea id="description" name="description" rows="4" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500">{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <x-primary-button>Create Product</x-primary-button>
                </form>
            </section>

            <section class="space-y-4">
                @forelse ($products as $product)
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                            <div class="space-y-3">
                                <div class="flex flex-wrap items-center gap-3">
                                    <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-cyan-500/10 text-cyan-700">
                                        <i data-lucide="{{ $product->icon ?? 'package' }}" class="h-5 w-5"></i>
                                    </span>
                                    <div>
                                        <h3 class="text-lg font-semibold text-slate-950">{{ $product->name }}</h3>
                                        <p class="text-sm text-slate-500">Unassigned type</p>
                                    </div>
                                </div>
                                <div class="flex flex-wrap items-center gap-3 text-sm text-slate-600">
                                    <x-admin.status-badge :value="$product->status" />
                                    <span>{{ $product->branches->count() }} branch assignments</span>
                                </div>
                                @if ($product->description)
                                    <p class="text-sm text-slate-500">{{ $product->description }}</p>
                                @endif
                            </div>

                            <div class="flex flex-wrap items-center gap-3">
                                <form method="POST" action="{{ route('products.toggle-status', $product) }}">
                                    @csrf
                                    @method('PATCH')
                                    <x-secondary-button type="submit">{{ $product->status === 'active' ? 'Deactivate' : 'Activate' }}</x-secondary-button>
                                </form>

                                <form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('Delete this product?')">
                                    @csrf
                                    @method('DELETE')
                                    <x-danger-button>Delete</x-danger-button>
                                </form>
                            </div>
                        </div>

                        <div class="mt-6 grid gap-4 lg:grid-cols-[0.9fr,1.1fr]">
                            <div class="space-y-4 rounded-3xl bg-slate-50 p-5">
                                <div class="flex items-center justify-between gap-3">
                                    <h4 class="text-sm font-semibold text-slate-900">Assigned Branches</h4>
                                    <span class="text-xs uppercase tracking-[0.24em] text-slate-400">{{ $product->branches->count() }} total</span>
                                </div>

                                @forelse ($product->branches as $branch)
                                    <div class="rounded-3xl border border-slate-200 bg-white p-4">
                                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                            <div>
                                                <p class="font-semibold text-slate-950">{{ $branch->name }}</p>
                                                <p class="text-sm text-slate-500">{{ $branch->code }} • {{ $branch->businessType?->name ?? 'No type' }}</p>
                                            </div>

                                            <div class="flex flex-wrap items-center gap-2">
                                                <x-admin.status-badge :value="$branch->pivot->status" />

                                                <form method="POST" action="{{ route('products.branches.toggle-status', ['product' => $product, 'branch' => $branch]) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <x-secondary-button type="submit">{{ $branch->pivot->status === 'active' ? 'Deactivate' : 'Activate' }}</x-secondary-button>
                                                </form>
                                                <form method="POST" action="{{ route('products.branches.destroy', ['product' => $product, 'branch' => $branch]) }}" onsubmit="return confirm('Remove product from this branch?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <x-danger-button>Remove</x-danger-button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="rounded-3xl border border-slate-200 bg-white p-4 text-sm text-slate-600">No branch assignments yet.</div>
                                @endforelse
                            </div>

                            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                                <h4 class="text-sm font-semibold text-slate-950">Assign to Branch</h4>
                                <p class="mt-1 text-sm text-slate-500">Pick a branch using the business type for this product.</p>

                                @php
                                    $availableBranches = $branches
                                        ->where('business_type_id', $product->business_type_id)
                                        ->whereNotIn('id', $product->branches->pluck('id'));
                                @endphp

                                <form method="POST" action="{{ route('products.assign-branch', $product) }}" class="mt-4 space-y-4">
                                    @csrf

                                    <div>
                                        <x-input-label for="branch_id_{{ $product->id }}" value="Branch" />
                                        <select id="branch_id_{{ $product->id }}" name="branch_id" class="mt-1 block w-full rounded-xl border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500" required>
                                            <option value="">Select branch</option>
                                            @foreach ($availableBranches as $branch)
                                                <option value="{{ $branch->id }}">{{ $branch->name }} ({{ $branch->code }})</option>
                                            @endforeach
                                        </select>
                                        <x-input-error :messages="$errors->get('branch_id')" class="mt-2" />
                                    </div>

                                    <x-primary-button type="submit" class="w-full">Assign Branch</x-primary-button>
                                </form>
                            </div>
                        </div>

                        <div class="mt-6 rounded-3xl border border-slate-200 bg-slate-50 p-5">
                            <h4 class="text-sm font-semibold text-slate-950">Edit Product Details</h4>
                            <form method="POST" action="{{ route('products.update', $product) }}" class="mt-4 grid gap-4">
                                @csrf
                                @method('PUT')

                                <div>
                                    <x-input-label :for="'product-name-'.$product->id" value="Name" />
                                    <x-text-input :id="'product-name-'.$product->id" name="name" class="mt-1 block w-full" :value="$product->name" required />
                                </div>

                                <div>
                                    <x-input-label :for="'product-business-type-'.$product->id" value="Business Type" />
                                    <select :id="'product-business-type-'.$product->id" name="business_type_id" class="mt-1 block w-full rounded-xl border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500" required>
                                            <option value="">Not assigned</option>
                                        </select>
                                </div>

                                <div>
                                    <x-input-label :for="'product-icon-'.$product->id" value="Icon" />
                                    <x-text-input :id="'product-icon-'.$product->id" name="icon" class="mt-1 block w-full" :value="$product->icon" placeholder="e.g. droplet, gas-station" />
                                </div>

                                <div>
                                    <x-input-label :for="'product-status-'.$product->id" value="Status" />
                                    <select :id="'product-status-'.$product->id" name="status" class="mt-1 block w-full rounded-xl border-slate-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500" required>
                                        <option value="active" @selected($product->status === 'active')>Active</option>
                                        <option value="inactive" @selected($product->status === 'inactive')>Inactive</option>
                                    </select>
                                </div>

                                <div>
                                    <x-input-label :for="'product-description-'.$product->id" value="Description" />
                                    <textarea :id="'product-description-'.$product->id" name="description" rows="3" class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-cyan-500 focus:ring-cyan-500">{{ $product->description }}</textarea>
                                </div>

                                <x-primary-button>Save Product</x-primary-button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 text-center text-slate-600">
                        No products found. Create one to begin assigning branch availability.
                    </div>
                @endforelse
            </section>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            {{ $products->withQueryString()->links() }}
        </div>
    </div>
</x-app-layout>
