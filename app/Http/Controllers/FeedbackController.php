<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFeedbackRequest;
use App\Http\Requests\UpdateFeedbackRequest;
use App\Models\Branch;
use App\Models\Complaint;
use App\Models\Department;
use App\Models\Feedback;
use App\Services\AttachmentUploadService;
use App\Services\ComplaintWorkflowService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function __construct(private AttachmentUploadService $attachments, private ComplaintWorkflowService $workflow)
    {
    }

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Feedback::class);
        $user = $request->user();
        $feedback = Feedback::query()->where('type', 'customer')->with(['branch', 'department', 'user', 'attachments']);

        if ($user->isDepartmentScopedReviewer()) {
            $feedback->where('department_id', $user->department_id);
        } elseif ($user->isBranchScopedSupervisor() && $user->branch_id) {
            $feedback->where('branch_id', $user->branch_id);
        } elseif ($user->hasRole('Employee') || $user->hasRole('Customer')) {
            $feedback->where('user_id', $user->id);
        }

        return view('feedback.index', [
            'feedbackEntries' => $feedback->latest()->paginate(10),
            'branches' => $this->branchesForUser($user),
        ]);
    }

    public function create(Request $request): View
    {
        $this->authorize('create', Feedback::class);

        return view('feedback.create', [
            'branches' => $this->branchesForUser($request->user()),
            'departments' => $this->departmentsForUser($request->user()),
            'user' => $request->user(),
        ]);
    }

    public function store(StoreFeedbackRequest $request): RedirectResponse
    {
        $this->authorize('create', Feedback::class);

        $payload = $request->validated();
        $branchId = $this->resolveBranchId($payload);

        if ($request->user()->hasRole('Customer') && ($payload['sentiment'] ?? null) === 'negative') {
            $complaint = Complaint::create([
                'branch_id' => $branchId,
                'department_id' => $payload['department_id'] ?? null,
                'submitted_by_user_id' => $request->user()->id,
                'customer_name' => $payload['name'],
                'customer_email' => $payload['email'] ?? null,
                'customer_phone' => $payload['phone'] ?? null,
                'subject' => 'Customer complaint submission',
                'category' => 'general',
                'priority' => 'normal',
                'status' => 'open',
                'message' => $payload['message'],
                'sentiment' => 'negative',
                'allow_public_reopen' => true,
            ]);
            $this->workflow->assignOnSubmission($complaint);

            $this->attachments->uploadMany($complaint, $request->file('attachments', []), $request->user());

            return redirect()->route('complaints.show', $complaint)
                ->with('status', 'Complaint submitted successfully.');
        }

        $feedback = Feedback::create([
            'branch_id' => $branchId,
            'department_id' => $payload['department_id'] ?? null,
            'user_id' => $request->user()->id,
            'name' => $payload['name'],
            'email' => $payload['email'] ?? null,
            'subject' => $payload['subject'],
            'type' => 'customer',
            'status' => 'submitted',
            'message' => $payload['message'],
            'sentiment' => $payload['sentiment'] ?? null,
        ]);

        $this->attachments->uploadMany($feedback, $request->file('attachments', []), $request->user());

        return redirect()->route('feedback.show', ['feedback' => $feedback->id])
            ->with('status', 'Feedback submitted successfully.');
    }

    public function show(Feedback $feedback): View
    {
        abort_unless($feedback->type === 'customer', 404);
        $this->authorize('view', $feedback);

        $feedback->load(['attachments.uploadedBy']);

        return view('feedback.show', compact('feedback'));
    }

    public function edit(Feedback $feedback): View
    {
        abort_unless($feedback->type === 'customer', 404);
        $this->authorize('update', $feedback);

        return view('feedback.edit', [
            'feedback' => $feedback,
            'branches' => $this->branchesForUser(request()->user()),
            'departments' => $this->departmentsForUser(request()->user()),
        ]);
    }

    public function update(UpdateFeedbackRequest $request, Feedback $feedback): RedirectResponse
    {
        abort_unless($feedback->type === 'customer', 404);
        $this->authorize('update', $feedback);

        $payload = $request->validated();

        if (! $request->user()->can('manage feedback')) {
            $payload['status'] = $feedback->status;
        }

        $payload['branch_id'] = $this->resolveBranchId($payload);
        $payload['type'] = 'customer';

        $feedback->update($payload);
        $this->attachments->uploadMany($feedback, $request->file('attachments', []), $request->user());

        return redirect()->route('feedback.show', ['feedback' => $feedback->id])
            ->with('status', 'Feedback updated successfully.');
    }

    public function destroy(Feedback $feedback): RedirectResponse
    {
        abort_unless($feedback->type === 'customer', 404);
        $this->authorize('delete', $feedback);

        $feedback->delete();

        return redirect()->route('feedback.index')->with('status', 'Feedback archived successfully.');
    }

    private function departmentsForUser($user)
    {
        return Department::with('branch')
            ->when($user->isDepartmentScopedReviewer(), fn ($query) => $query->whereKey($user->department_id))
            ->when($user->isBranchScopedSupervisor() && $user->branch_id, fn ($query) => $query->where('branch_id', $user->branch_id))
            ->when(($user->hasRole('Employee') || $user->hasRole('Customer')) && $user->branch_id, fn ($query) => $query->where('branch_id', $user->branch_id))
            ->where('status', 'active')
            ->orderBy('name')
            ->get();
    }

    private function branchesForUser($user)
    {
        return Branch::query()
            ->when($user->isDepartmentScopedReviewer() && $user->branch_id, fn ($query) => $query->whereKey($user->branch_id))
            ->when($user->isBranchScopedSupervisor() && $user->branch_id, fn ($query) => $query->whereKey($user->branch_id))
            ->when(($user->hasRole('Employee') || $user->hasRole('Customer')) && $user->branch_id, fn ($query) => $query->whereKey($user->branch_id))
            ->orderBy('name')
            ->get();
    }

    private function resolveBranchId(array $payload): ?int
    {
        if (! empty($payload['department_id'])) {
            return Department::query()->whereKey($payload['department_id'])->value('branch_id');
        }

        return $payload['branch_id'] ?? null;
    }
}
