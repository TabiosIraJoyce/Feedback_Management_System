<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignBranchProductRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Branch;
// BusinessType removed — feature deprecated
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::with(['branches'])
            ->when(request('search'), fn ($query, $search) => $query->where('name', 'like', "%{$search}%"))
            ->when(request('business_type'), fn ($query, $businessType) => $query->where('business_type_id', $businessType))
            ->when(request('status'), fn ($query, $status) => $query->where('status', $status))
            ->orderBy('name')
            ->paginate(15);

        return view('products.index', [
            'products' => $products,
            'businessTypes' => [],
            'branches' => Branch::orderBy('name')->get(),
        ]);
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        Product::create($request->validated());

        return back()->with('status', 'Product created successfully.');
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $product->update($request->validated());

        return back()->with('status', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->branches()->detach();
        $product->delete();

        return back()->with('status', 'Product deleted successfully.');
    }

    public function toggleStatus(Product $product): RedirectResponse
    {
        $product->update([
            'status' => $product->status === 'active' ? 'inactive' : 'active',
        ]);

        return back()->with('status', 'Product status updated successfully.');
    }

    public function assignBranch(AssignBranchProductRequest $request, Product $product): RedirectResponse
    {
        $branchId = $request->validated()['branch_id'];

        $product->branches()->syncWithoutDetaching([
            $branchId => ['status' => 'active'],
        ]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($product)
            ->withProperties(['branch_id' => $branchId])
            ->log('Product was assigned to a branch.');

        return back()->with('status', 'Branch assigned to product successfully.');
    }

    public function toggleBranchStatus(Product $product, Branch $branch): RedirectResponse
    {
        if (! $product->branches()->where('branch_id', $branch->id)->exists()) {
            return back()->with('status', 'This branch is not assigned to the selected product.');
        }

        $currentStatus = $product->branches()
            ->where('branch_id', $branch->id)
            ->first()
            ->pivot
            ->status;

        $newStatus = $currentStatus === 'active' ? 'inactive' : 'active';

        $product->branches()->updateExistingPivot($branch->id, ['status' => $newStatus]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($product)
            ->withProperties(['branch_id' => $branch->id, 'status' => $newStatus])
            ->log('Branch product status was updated.');

        return back()->with('status', 'Branch product status updated successfully.');
    }

    public function removeBranch(Product $product, Branch $branch): RedirectResponse
    {
        $product->branches()->detach($branch->id);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($product)
            ->withProperties(['branch_id' => $branch->id])
            ->log('Branch was removed from product.');

        return back()->with('status', 'Branch assignment removed successfully.');
    }
}
