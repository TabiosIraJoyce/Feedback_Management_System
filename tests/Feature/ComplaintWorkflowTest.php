<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Complaint;
use App\Models\Department;
use App\Models\FeedbackTicket;
use App\Models\Feedback;
use App\Models\User;
use App\Services\ComplaintWorkflowService;
use App\Services\SettingsService;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ComplaintWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
        Notification::fake();
        Cache::flush();
    }

    public function test_new_complaint_is_assigned_to_the_supervisor_for_its_department(): void
    {
        [$branch, $department] = $this->makeBranchAndDepartment();
        $supervisor = $this->makeHandler('Supervisor', $branch, $department);
        $complaint = $this->makeComplaint($branch, $department);

        $assigned = app(ComplaintWorkflowService::class)->assignOnSubmission($complaint);

        $this->assertSame($supervisor->id, $assigned?->id);
        $this->assertSame($supervisor->id, $complaint->fresh()->assigned_to_user_id);
        $this->assertSame(0, $complaint->fresh()->escalation_level);
        $this->assertDatabaseHas('complaint_status_histories', [
            'complaint_id' => $complaint->id,
            'to_assigned_user_id' => $supervisor->id,
            'action' => 'assigned',
            'visible_to_public' => false,
        ]);

    }

    public function test_complaint_moves_through_department_head_to_designated_upper_management(): void
    {
        [$branch, $department] = $this->makeBranchAndDepartment();
        $supervisor = $this->makeHandler('Supervisor', $branch, $department);
        $departmentHead = $this->makeHandler('Department Head', $branch, $department);
        $upperManagement = $this->makeHandler('Upper Management', null, null);
        SettingsService::set('upper_management_user_id', (string) $upperManagement->id, 'string', 'complaint_routing');

        $complaint = $this->makeComplaint($branch, $department);
        $workflow = app(ComplaintWorkflowService::class);
        $workflow->assignOnSubmission($complaint);
        $workflow->escalateToDepartmentHead($complaint, $supervisor, 'The branch investigation did not resolve the service issue.', 'The supervisor reviewed the service logs and contacted the branch team.');

        $this->assertSame($departmentHead->id, $complaint->fresh()->assigned_to_user_id);
        $this->assertSame(1, $complaint->fresh()->escalation_level);
        $workflow->escalateToUpperManagement($complaint, $departmentHead, 'A policy decision is required to complete this resolution.');

        $complaint->refresh();
        $this->assertSame($upperManagement->id, $complaint->assigned_to_user_id);
        $this->assertSame(2, $complaint->escalation_level);
        $this->assertSame('escalated', $complaint->status);
        $this->assertDatabaseHas('complaint_status_histories', [
            'complaint_id' => $complaint->id,
            'actor_user_id' => $departmentHead->id,
            'to_assigned_user_id' => $upperManagement->id,
            'action' => 'escalated_to_upper_management',
            'previous_status' => 'escalated',
            'new_status' => 'escalated',
            'visible_to_public' => false,
        ]);

        $this->actingAs($upperManagement)
            ->getJson(route('dashboard.metrics'))
            ->assertOk()
            ->assertJsonPath('stats.total_feedback', 1)
            ->assertJsonPath('analytics.feedback_distribution', [])
            ->assertJsonPath('analytics.department_highlights', []);
    }

    public function test_only_the_assigned_supervisor_can_escalate_and_notes_remain_private(): void
    {
        [$branch, $department] = $this->makeBranchAndDepartment();
        $supervisor = $this->makeHandler('Supervisor', $branch, $department);
        $otherSupervisor = $this->makeHandler('Supervisor', $branch, $department);
        $this->makeHandler('Department Head', $branch, $department);
        $complaint = $this->makeComplaint($branch, $department);
        $workflow = app(ComplaintWorkflowService::class);
        $workflow->assignOnSubmission($complaint);

        try {
            $workflow->escalateToDepartmentHead($complaint, $otherSupervisor, 'This should be rejected as an unassigned actor.', 'No authorized review was performed.');
            $this->fail('Expected the unassigned supervisor to be denied.');
        } catch (AuthorizationException) {
            $this->assertSame($supervisor->id, $complaint->fresh()->assigned_to_user_id);
        }

        $employee = User::factory()->create(['branch_id' => $branch->id, 'department_id' => $department->id]);
        $employee->assignRole('Employee');
        $this->assertFalse($employee->can('view', $complaint->fresh()));

        $workflow->addInternalNote($complaint, $supervisor, 'Private investigation note for staff only.');
        $this->assertDatabaseHas('complaint_status_histories', [
            'complaint_id' => $complaint->id,
            'action' => 'internal_note',
            'visible_to_public' => false,
            'note' => 'Private investigation note for staff only.',
        ]);
    }

    public function test_customer_can_view_own_complaint_without_internal_notes(): void
    {
        [$branch, $department] = $this->makeBranchAndDepartment();
        $supervisor = $this->makeHandler('Supervisor', $branch, $department);
        $customer = $this->makeHandler('Customer', $branch, $department);
        $complaint = $this->makeComplaint($branch, $department);
        $complaint->update(['submitted_by_user_id' => $customer->id]);

        $workflow = app(ComplaintWorkflowService::class);
        $workflow->assignOnSubmission($complaint);
        $workflow->addInternalNote($complaint, $supervisor, 'Internal-only investigation details.');

        $this->actingAs($customer)
            ->get(route('complaints.show', $complaint))
            ->assertOk()
            ->assertDontSee('Internal-only investigation details.');
    }

    public function test_admin_dashboard_renders_and_reports_department_positive_feedback(): void
    {
        [$branch, $department] = $this->makeBranchAndDepartment();
        FeedbackTicket::create([
            'branch_id' => $branch->id,
            'department_id' => $department->id,
            'reference_no' => 'FBK-TEST-001',
            'satisfaction' => 'like',
            'category' => 'feedback',
            'comment' => 'Helpful service from the department team.',
        ]);
        $this->assertDatabaseHas('feedback_tickets', [
            'department_id' => $department->id,
            'satisfaction' => 'like',
        ]);

        $admin = $this->makeHandler('Super Admin', null, null);
        $this->actingAs($admin)->get(route('dashboard'))->assertOk();
        $this->actingAs($admin)->getJson(route('dashboard.metrics'))
            ->assertOk()
            ->assertJsonPath('analytics.department_highlights.most_positive_feedback.name', $department->name)
            ->assertJsonPath('analytics.department_highlights.most_positive_feedback.count', 1);
    }

    public function test_admin_accounts_cannot_create_complaints_or_feedback(): void
    {
        $admin = $this->makeHandler('Admin', null, null);

        $this->assertFalse($admin->can('create', Complaint::class));
        $this->assertFalse($admin->can('create', Feedback::class));
    }

    private function makeBranchAndDepartment(): array
    {
        $branch = Branch::create(['name' => 'Test Branch', 'code' => 'TB', 'slug' => 'test-branch']);
        $department = Department::create([
            'branch_id' => $branch->id,
            'name' => 'Customer Service',
            'code' => 'CS',
            'qr_token' => 'test-customer-service',
            'status' => 'active',
        ]);

        return [$branch, $department];
    }

    private function makeHandler(string $role, ?Branch $branch, ?Department $department): User
    {
        $user = User::factory()->create([
            'branch_id' => $branch?->id,
            'department_id' => $department?->id,
        ]);
        $user->assignRole($role);

        return $user;
    }

    private function makeComplaint(Branch $branch, Department $department): Complaint
    {
        return Complaint::create([
            'branch_id' => $branch->id,
            'department_id' => $department->id,
            'customer_name' => 'Test Customer',
            'customer_email' => 'customer@example.com',
            'subject' => 'Test complaint',
            'category' => 'service',
            'priority' => 'normal',
            'status' => 'open',
            'message' => 'This complaint needs a complete review by the assigned team.',
        ]);
    }
}
