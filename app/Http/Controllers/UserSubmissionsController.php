<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Feedback;
use App\Models\FormSubmission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserSubmissionsController extends Controller
{
    public function recent(Request $request): JsonResponse
    {
        $user = $request->user();

        $complaints = Complaint::query()
            ->where('submitted_by_user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get(['reference_no', 'status', 'created_at', 'subject'])
            ->map(fn($c) => [
                'type' => 'complaint',
                'reference_no' => $c->reference_no,
                'status' => $c->status,
                'created_at' => $c->created_at?->toIso8601String(),
                'subject' => $c->subject,
            ]);

        $feedbacks = Feedback::query()
            ->where('submitted_by_user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get(['reference_no', 'status', 'created_at', 'subject'])
            ->map(fn($f) => [
                'type' => 'feedback',
                'reference_no' => $f->reference_no ?? null,
                'status' => $f->status,
                'created_at' => $f->created_at?->toIso8601String(),
                'subject' => $f->subject,
            ]);

        $forms = FormSubmission::query()
            ->where('submitted_by_user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get(['id', 'status', 'created_at'])
            ->map(fn($s) => [
                'type' => 'form',
                'id' => $s->id,
                'status' => $s->status,
                'created_at' => $s->created_at?->toIso8601String(),
            ]);

        $items = $complaints->concat($feedbacks)->concat($forms)
            ->sortByDesc('created_at')
            ->values()
            ->take(5);

        return response()->json(['items' => $items]);
    }
}
