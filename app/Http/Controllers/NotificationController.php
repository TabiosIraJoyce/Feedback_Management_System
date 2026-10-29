<?php

namespace App\Http\Controllers;

use App\Services\SettingsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class NotificationController extends Controller
{
    public function read(Request $request, string $notification): RedirectResponse
    {
        $record = $request->user()->notifications()->findOrFail($notification);

        if ($record->read_at === null) {
            $record->markAsRead();
            Cache::forget("user_notifications_{$request->user()->id}");
            Cache::forget("user_unread_notifications_{$request->user()->id}");

            activity()
                ->causedBy($request->user())
                ->performedOn($record)
                ->withProperties(['notification_id' => $notification])
                ->log('Notification marked as read.');
        }

        return redirect()->to($this->resolveRedirectTarget($request, $record->data['url'] ?? null));
    }

    public function readAll(Request $request): RedirectResponse
    {
        $request->user()->unreadNotifications->markAsRead();
        Cache::forget("user_notifications_{$request->user()->id}");
        Cache::forget("user_unread_notifications_{$request->user()->id}");

        activity()
            ->causedBy($request->user())
            ->withProperties(['mark_all_read' => true])
            ->log('All notifications marked as read.');

        return back()->with('status', 'Notifications marked as read.');
    }

    public function feed(Request $request): JsonResponse
    {
        $user = $request->user();
        $enabled = filter_var(SettingsService::get('enable_notifications', true), FILTER_VALIDATE_BOOLEAN);

        if (! $enabled) {
            return response()->json([
                'enabled' => false,
                'unread_count' => 0,
                'items' => [],
            ]);
        }

        $notifications = Cache::remember("user_notifications_{$user->id}", 15, fn () => $user->notifications()
            ->latest()
            ->limit(8)
            ->get());

        return response()->json([
            'enabled' => true,
            'unread_count' => Cache::remember("user_unread_notifications_{$user->id}", 15, fn () => $user->unreadNotifications()->count()),
            'items' => $notifications->map(fn ($notification) => $this->transformNotification($notification))->values(),
        ]);
    }

    private function resolveRedirectTarget(Request $request, ?string $fallback = null): string
    {
        $redirectTo = (string) $request->input('redirect_to', '');

        if ($redirectTo !== '' && str_starts_with($redirectTo, url('/'))) {
            return $redirectTo;
        }

        return $fallback ?: route('dashboard');
    }

    private function transformNotification($notification): array
    {
        $data = $notification->data;
        $title = __('Notification');
        $message = $data['message'] ?? $data['note'] ?? __('You have a new update.');
        $targetUrl = $data['url'] ?? route('dashboard');

        if (isset($data['complaint_id'])) {
            $title = __('Complaint Update');
        }

        return [
            'id' => $notification->id,
            'title' => $title,
            'message' => $message,
            'url' => $targetUrl,
            'is_unread' => $notification->read_at === null,
            'created_human' => $notification->created_at->diffForHumans(),
        ];
    }
}
