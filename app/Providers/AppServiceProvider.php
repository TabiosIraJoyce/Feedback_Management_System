<?php

namespace App\Providers;

use App\Models\Complaint;
use App\Models\EmployeeFeedback;
use App\Models\Feedback;
use App\Services\SettingsService;
use App\Policies\ComplaintPolicy;
use App\Policies\EmployeeFeedbackPolicy;
use App\Policies\FeedbackPolicy;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\PermissionRegistrar;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Complaint::class, ComplaintPolicy::class);
        Gate::policy(Feedback::class, FeedbackPolicy::class);
        Gate::policy(EmployeeFeedback::class, EmployeeFeedbackPolicy::class);

        View::composer(['layouts.app', 'layouts.guest'], function ($view): void {
            $view->with([
                'hciConfig' => [
                    'locale' => app()->getLocale(),
                    'available_locales' => [
                        ['value' => 'en', 'label' => 'English'],
                        ['value' => 'fil', 'label' => 'Filipino'],
                        ['value' => 'ilo', 'label' => 'Ilocano'],
                    ],
                    'dark_mode_enabled' => filter_var(SettingsService::get('enable_dark_mode', true), FILTER_VALIDATE_BOOLEAN),
                    'default_language' => SettingsService::get('default_language', 'en'),
                ],
            ]);
        });

        View::composer('layouts.app', function ($view): void {
            $user = auth()->user();

            if (! $user) {
                $view->with([
                    'notificationItems' => collect(),
                    'unreadNotificationCount' => 0,
                ]);

                return;
            }

            $view->with([
                'notificationItems' => Cache::remember("user_notifications_{$user->id}", 30, fn () => $user->notifications()
                    ->latest()
                    ->limit(8)
                    ->get()),
                'unreadNotificationCount' => Cache::remember("user_unread_notifications_{$user->id}", 30, fn () => $user->unreadNotifications()->count()),
            ]);
        });

        Gate::before(function ($user, string $ability): ?bool {
            return method_exists($user, 'hasRole') && $user->hasRole('Super Admin') ? true : null;
        });

        if (Schema::hasTable('cache') && Schema::hasTable('permissions')) {
            app(PermissionRegistrar::class)->forgetCachedPermissions();
        }
    }
}
