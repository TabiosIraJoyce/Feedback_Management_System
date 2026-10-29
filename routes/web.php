<?php

use App\Http\Controllers\Admin\FormBuilderController;
use App\Http\Controllers\Admin\QrManagementController;
use App\Http\Controllers\Admin\RoleManagementController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\BranchController;
// BusinessTypeController removed
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ComplaintWorkflowController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\GlobalSearchController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicFeedbackController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TrackComplaintController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::middleware(['auth', 'verified', 'force.ssl', 'permission:manage feedback|submit feedback'])->group(function () {
    // Register the explicit management routes before the public feedback slug routes
    // so `/feedback/manage/*` never gets mistaken for a public branch path.
    Route::resource('feedback/manage', FeedbackController::class)
    ->parameters(['manage' => 'feedback'])
    ->names('feedback');
});

Route::middleware('throttle:10,1')->group(function () {
    Route::get('track-complaint', [TrackComplaintController::class, 'index'])->name('track-complaint.index');
    Route::post('track-complaint', [TrackComplaintController::class, 'search'])->name('track-complaint.search');
    Route::get('track-complaint/{tracking_number}', [TrackComplaintController::class, 'show'])->name('track-complaint.show');
    Route::get('track-complaint/{tracking_number}/status-feed', [TrackComplaintController::class, 'statusFeed'])->name('track-complaint.status-feed');
    Route::get('track-complaint/{tracking_number}/resolution-summary', [TrackComplaintController::class, 'resolutionSummary'])->name('track-complaint.summary');
    Route::get('track-complaint/{tracking_number}/survey', [TrackComplaintController::class, 'survey'])->name('track-complaint.survey');
    Route::post('track-complaint/{tracking_number}/survey', [TrackComplaintController::class, 'storeSurvey'])->name('track-complaint.survey.store');
    Route::post('track-complaint/{tracking_number}/reopen', [TrackComplaintController::class, 'reopen'])->name('track-complaint.reopen');
});
Route::get('feedback/{branch}/{department?}', [PublicFeedbackController::class, 'show'])
    ->where('branch', '^(?!manage$)[A-Za-z0-9-]+$')
    ->where('department', '[A-Za-z0-9-]+')
    ->middleware('throttle:5,1')
    ->name('feedback.public.show');

Route::get('feedback/submission/{reference}', [PublicFeedbackController::class, 'showSubmission'])
    ->name('feedback.submission.show');
Route::get('feedback', [PublicFeedbackController::class, 'index'])
    ->middleware('throttle:5,1')
    ->name('feedback.public.index');

// Public feedback tracker
Route::post('feedback/track', [PublicFeedbackController::class, 'track'])
    ->middleware('throttle:10,1')
    ->name('feedback.track');


Route::post('feedback/{branch}/{department?}', [PublicFeedbackController::class, 'store'])
    ->where('branch', '^(?!manage$)[A-Za-z0-9-]+$')
    ->where('department', '[A-Za-z0-9-]+')
    ->middleware('throttle:5,1')
    ->name('feedback.public.store');

Route::middleware(['auth', 'verified', 'force.ssl'])->group(function () {
    Route::get('/dashboard', DashboardController::class)
        ->middleware('permission:view dashboard|manage complaints')
        ->name('dashboard');

    Route::get('/dashboard/metrics', [DashboardController::class, 'metrics'])
        ->middleware('permission:view dashboard|manage complaints')
        ->name('dashboard.metrics');

    Route::get('/search', GlobalSearchController::class)->name('search');

    Route::get('/attachments/{attachment}/preview', [AttachmentController::class, 'preview'])->name('attachments.preview');
    Route::get('/attachments/{attachment}/download', [AttachmentController::class, 'download'])->name('attachments.download');
    Route::delete('/attachments/{attachment}', [AttachmentController::class, 'destroy'])->name('attachments.destroy');

    Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])
        ->name('notifications.read-all');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'read'])
        ->name('notifications.read');
    Route::get('/notifications/feed', [NotificationController::class, 'feed'])
        ->name('notifications.feed');

    Route::get('/reports', [ReportController::class, 'index'])
        ->middleware('permission:manage reports')
        ->name('reports.index');
    Route::get('/reports/export/{reportType}/{format}', [ReportController::class, 'export'])
        ->middleware('permission:manage reports')
        ->whereIn('format', ['pdf', 'csv'])
        ->name('reports.export');
    Route::post('/reports/schedules', [ReportController::class, 'storeSchedule'])
        ->middleware('permission:manage reports')
        ->name('reports.schedules.store');
    Route::patch('/reports/schedules/{scheduledReport}', [ReportController::class, 'updateSchedule'])
        ->middleware('permission:manage reports')
        ->name('reports.schedules.update');
    Route::delete('/reports/schedules/{scheduledReport}', [ReportController::class, 'destroySchedule'])
        ->middleware('permission:manage reports')
        ->name('reports.schedules.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('complaints', ComplaintController::class)
        ->middleware('permission:track complaints|manage complaints|submit complaints');
    Route::patch('complaints/{complaint}/assign', [ComplaintController::class, 'assign'])
        ->middleware(['permission:assign complaints', 'branch.access:complaint'])
        ->name('complaints.assign');
    Route::patch('complaints/{complaint}/close', [ComplaintController::class, 'close'])
        ->middleware(['permission:close complaints', 'branch.access:complaint'])
        ->name('complaints.close');
    Route::patch('complaints/{complaint}/start', [ComplaintController::class, 'start'])
        ->middleware('permission:manage complaints')
        ->name('complaints.start');
    Route::patch('complaints/{complaint}/reopen', [ComplaintController::class, 'reopen'])
        ->middleware(['permission:reopen complaints', 'branch.access:complaint'])
        ->name('complaints.reopen');
    Route::post('complaints/{complaint}/escalate/department-head', [ComplaintWorkflowController::class, 'escalateToDepartmentHead'])
        ->middleware('permission:manage complaints')
        ->name('complaints.escalate.department-head');
    Route::post('complaints/{complaint}/escalate/upper-management', [ComplaintWorkflowController::class, 'escalateToUpperManagement'])
        ->middleware('permission:manage complaints')
        ->name('complaints.escalate.upper-management');
    Route::post('complaints/{complaint}/return-to-supervisor', [ComplaintWorkflowController::class, 'returnToSupervisor'])
        ->middleware('permission:manage complaints')
        ->name('complaints.return-to-supervisor');
    Route::post('complaints/{complaint}/resolve', [ComplaintWorkflowController::class, 'resolve'])
        ->middleware('permission:manage complaints')
        ->name('complaints.resolve');
    Route::post('complaints/{complaint}/internal-notes', [ComplaintWorkflowController::class, 'note'])
        ->middleware('permission:manage complaints')
        ->name('complaints.internal-notes.store');

    // Business types removed — feature deprecated.

    Route::resource('branches', BranchController::class)
        ->except(['create', 'show', 'edit'])
        ->middleware('permission:manage branches');

    // Authenticated user's recent submissions (feedback/complaints/forms)
    Route::get('/user/submissions/recent', [\App\Http\Controllers\UserSubmissionsController::class, 'recent'])
        ->name('user.submissions.recent');

    Route::patch('branches/{branch}/toggle-status', [BranchController::class, 'toggleStatus'])
        ->middleware('permission:manage branches')
        ->name('branches.toggle-status');

    Route::resource('departments', DepartmentController::class)
        ->except(['create', 'show', 'edit'])
        ->middleware('permission:manage branches');

    Route::patch('departments/{department}/toggle-status', [DepartmentController::class, 'toggleStatus'])
        ->middleware('permission:manage branches')
        ->name('departments.toggle-status');

    Route::resource('products', ProductController::class)
        ->except(['create', 'show', 'edit'])
        ->middleware('permission:manage branches');

    Route::patch('products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])
        ->middleware('permission:manage branches')
        ->name('products.toggle-status');

    Route::post('products/{product}/assign-branch', [ProductController::class, 'assignBranch'])
        ->middleware('permission:manage branches')
        ->name('products.assign-branch');

    Route::patch('products/{product}/branches/{branch}/toggle-status', [ProductController::class, 'toggleBranchStatus'])
        ->middleware('permission:manage branches')
        ->name('products.branches.toggle-status');

    Route::delete('products/{product}/branches/{branch}', [ProductController::class, 'removeBranch'])
        ->middleware('permission:manage branches')
        ->name('products.branches.destroy');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/roles', [RoleManagementController::class, 'index'])
            ->middleware('permission:manage settings')
            ->name('roles.index');
        Route::patch('/roles/{role}', [RoleManagementController::class, 'update'])
            ->middleware('permission:manage settings')
            ->name('roles.update');

        Route::get('/qr-codes', [QrManagementController::class, 'index'])
            ->middleware('permission:manage settings')
            ->name('qr.index');
        Route::patch('/qr-codes/{qrCode}/toggle-status', [QrManagementController::class, 'toggleStatus'])
            ->middleware('permission:manage settings')
            ->name('qr.toggle-status');
        Route::get('/qr-codes/{qrCode}/download/{format}', [QrManagementController::class, 'download'])
            ->middleware('permission:manage settings')
            ->whereIn('format', ['png', 'svg'])
            ->name('qr.download');
        Route::get('/qr-codes/{qrCode}/print', [QrManagementController::class, 'print'])
            ->middleware('permission:manage settings')
            ->name('qr.print');

        Route::get('/users', [UserManagementController::class, 'index'])
            ->middleware('permission:manage users')
            ->name('users.index');
        Route::post('/users', [UserManagementController::class, 'store'])
            ->middleware('permission:manage users')
            ->name('users.store');
        Route::patch('/users/{user}', [UserManagementController::class, 'update'])
            ->middleware('permission:manage users')
            ->name('users.update');

        Route::get('/settings', [\App\Http\Controllers\Admin\SystemSettingsController::class, 'index'])
            ->middleware('permission:manage settings')
            ->name('settings.index');

        Route::patch('/settings', [\App\Http\Controllers\Admin\SystemSettingsController::class, 'update'])
            ->middleware('permission:manage settings')
            ->name('settings.update');

        Route::resource('forms', FormBuilderController::class)
            ->except(['show'])
            ->middleware('permission:manage forms');
    });
});

require __DIR__.'/auth.php';

if (app()->environment(['local', 'testing']) || config('app.debug')) {
    Route::get('dev-login-superadmin', function () {
        $user = \App\Models\User::where('email', 'iratbios@gmail.com')->first();
        if (! $user) {
            abort(404);
        }

        auth()->login($user);

        return redirect()->route('dashboard');
    })->name('dev.login.superadmin');
}

// SLA management (admin)
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('sla', [\App\Http\Controllers\SlaController::class, 'index'])->middleware('permission:manage settings')->name('sla.index');
    Route::patch('sla/{slaConfig}', [\App\Http\Controllers\SlaController::class, 'update'])->middleware('permission:manage settings')->name('sla.update');
});
