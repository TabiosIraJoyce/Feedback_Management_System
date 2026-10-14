<?php

namespace App\Http\Controllers;

use App\Http\Requests\PublicComplaintReopenRequest;
use App\Http\Requests\PublicComplaintSurveyRequest;
use App\Http\Requests\TrackComplaintRequest;
use App\Services\PublicComplaintTrackingService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TrackComplaintController extends Controller
{
    public function __construct(private PublicComplaintTrackingService $tracking)
    {
    }

    public function index(): View
    {
        return view('track-complaint.index');
    }

    public function search(TrackComplaintRequest $request): RedirectResponse
    {
        $trackingNumber = strtoupper($request->validated('tracking_number'));

        $complaint = \App\Models\Complaint::where('reference_no', $trackingNumber)->first();

        if (! $complaint) {
            return back()->withErrors(['tracking_number' => __('Complaint not found. Please check the tracking number and try again.')]);
        }

        $this->tracking->searchAudit($trackingNumber, $request);

        return redirect()->route('track-complaint.show', ['tracking_number' => $trackingNumber]);
    }

    public function show(string $tracking_number): View
    {
        $trackingNumber = strtoupper($tracking_number);

        $complaint = $this->tracking->findByTrackingNumber($trackingNumber);

        return view('track-complaint.show', [
            'complaint' => $complaint,
            'timelineSteps' => $this->tracking->timeline($complaint),
            'historyEntries' => $this->tracking->publicHistory($complaint),
            'sla' => $this->tracking->slaSummary($complaint),
            'escalation' => $this->tracking->escalationSummary($complaint),
            'estimatedResolution' => $this->tracking->estimatedResolution($complaint),
            'trackingSnapshot' => $this->tracking->snapshot($complaint),
            'canSubmitSurvey' => $this->tracking->canSubmitSurvey($complaint),
            'canPublicReopen' => $this->tracking->canPublicReopen($complaint),
        ]);
    }

    public function statusFeed(string $tracking_number): JsonResponse
    {
        $complaint = $this->tracking->findByTrackingNumber(strtoupper($tracking_number));

        return response()->json($this->tracking->snapshot($complaint));
    }

    public function resolutionSummary(string $tracking_number): View
    {
        $complaint = $this->tracking->findByTrackingNumber(strtoupper($tracking_number));

        return view('track-complaint.resolution-summary', [
            'complaint' => $complaint,
            'sla' => $this->tracking->slaSummary($complaint),
            'escalation' => $this->tracking->escalationSummary($complaint),
        ]);
    }

    public function survey(string $tracking_number): View|RedirectResponse
    {
        $complaint = $this->tracking->findByTrackingNumber(strtoupper($tracking_number));

        if (! $this->tracking->canSubmitSurvey($complaint)) {
            return redirect()->route('track-complaint.show', $complaint->reference_no)
                ->with('status', __('The satisfaction survey is not available for this complaint.'));
        }

        return view('track-complaint.survey', [
            'complaint' => $complaint,
        ]);
    }

    public function storeSurvey(PublicComplaintSurveyRequest $request, string $tracking_number): RedirectResponse
    {
        $complaint = $this->tracking->findByTrackingNumber(strtoupper($tracking_number));

        abort_unless($this->tracking->canSubmitSurvey($complaint), 422);

        $this->tracking->submitSurvey($complaint, $request->validated());

        return redirect()->route('track-complaint.show', $complaint->reference_no)
            ->with('status', __('Thank you for rating your complaint experience.'));
    }

    public function reopen(PublicComplaintReopenRequest $request, string $tracking_number): RedirectResponse
    {
        $complaint = $this->tracking->findByTrackingNumber(strtoupper($tracking_number));

        abort_unless($this->tracking->canPublicReopen($complaint), 422);

        $this->tracking->publicReopen($complaint, $request->validated());

        return redirect()->route('track-complaint.show', $complaint->reference_no)
            ->with('status', __('Your complaint has been reopened for further review.'));
    }
}
