<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFormRequest;
use App\Http\Requests\UpdateFormRequest;
use App\Models\Branch;
use App\Models\Form;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class FormBuilderController extends Controller
{
    public function index(): View
    {
        return view('admin.forms.index', [
            'forms' => Form::with(['branch', 'fields'])->orderByDesc('updated_at')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.forms.create', [
            'branches' => Branch::active()->orderBy('name')->get(),
        ]);
    }

    public function store(StoreFormRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $form = Form::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'branch_id' => $validated['branch_id'],
            'is_active' => $validated['is_active'] ?? false,
        ]);

        $this->saveFields($form, $validated['fields']);

        Branch::where('id', $form->branch_id)->update(['public_form_id' => $form->id]);

        return redirect()->route('admin.forms.index')->with('status', "Form '{$form->name}' created successfully.");
    }

    public function edit(Form $form): View
    {
        return view('admin.forms.edit', [
            'form' => $form->load('fields'),
            'branches' => Branch::active()->orderBy('name')->get(),
        ]);
    }

    public function update(UpdateFormRequest $request, Form $form): RedirectResponse
    {
        $validated = $request->validated();

        $previousBranchId = $form->branch_id;

        $form->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'branch_id' => $validated['branch_id'],
            'is_active' => $validated['is_active'] ?? false,
        ]);

        $this->saveFields($form, $validated['fields']);

        if ($previousBranchId !== $form->branch_id) {
            Branch::where('public_form_id', $form->id)->update(['public_form_id' => null]);
        }

        Branch::where('id', $form->branch_id)->update(['public_form_id' => $form->id]);

        return redirect()->route('admin.forms.index')->with('status', "Form '{$form->name}' updated successfully.");
    }

    public function destroy(Form $form): RedirectResponse
    {
        Branch::where('public_form_id', $form->id)->update(['public_form_id' => null]);
        $form->delete();

        return back()->with('status', 'Form deleted successfully.');
    }

    protected function saveFields(Form $form, array $fields): void
    {
        $form->fields()->delete();

        foreach ($fields as $index => $field) {
            $form->fields()->create([
                'label' => $field['label'],
                'name' => $field['name'],
                'type' => $field['type'],
                'placeholder' => $field['placeholder'] ?? null,
                'options' => $field['options'] ?? null,
                'required' => ! empty($field['required']),
                'order' => $field['order'] ?? $index,
            ]);
        }
    }
}
