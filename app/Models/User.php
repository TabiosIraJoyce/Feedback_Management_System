<?php

namespace App\Models;

use Database\Factories\UserFactory;
use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    protected $fillable = [
        'branch_id',
        'department_id',
        'name',
        'email',
        'job_title',
        'is_active',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'is_active' => 'boolean',
            'password' => 'hashed',
        ];
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function submittedComplaints(): HasMany
    {
        return $this->hasMany(Complaint::class, 'submitted_by_user_id');
    }

    public function assignedComplaints(): HasMany
    {
        return $this->hasMany(Complaint::class, 'assigned_to_user_id');
    }

    public function feedbackEntries(): HasMany
    {
        return $this->hasMany(Feedback::class);
    }

    public function isDepartmentHead(): bool
    {
        return $this->hasRole('Department Head');
    }

    public function isLegacySupervisor(): bool
    {
        return $this->hasRole('Supervisor');
    }

    public function isScopedReviewer(): bool
    {
        return $this->isDepartmentHead() || $this->isLegacySupervisor();
    }

    public function isDepartmentScopedReviewer(): bool
    {
        return $this->isDepartmentHead() && $this->department_id !== null;
    }

    public function isBranchScopedSupervisor(): bool
    {
        return $this->isLegacySupervisor();
    }

    public function managesDepartment(?int $departmentId): bool
    {
        return $this->isDepartmentScopedReviewer()
            && $departmentId !== null
            && (int) $this->department_id === (int) $departmentId;
    }

    public function managesBranch(?int $branchId): bool
    {
        return $this->isScopedReviewer()
            && $this->branch_id !== null
            && $branchId !== null
            && (int) $this->branch_id === (int) $branchId;
    }

    public function canReviewScopedRecord(object $record): bool
    {
        if (! $this->isScopedReviewer()) {
            return false;
        }

        if ($this->isDepartmentScopedReviewer() && isset($record->department_id)) {
            return $this->managesDepartment($record->department_id);
        }

        return isset($record->branch_id) && $this->managesBranch($record->branch_id);
    }

    public static function assignableUsers()
    {
        $roleNames = collect(['Department Head', 'Supervisor', 'Upper Management'])
            ->filter(fn (string $name): bool => Role::where('name', $name)->exists())
            ->all();

        if (empty($roleNames)) {
            return static::query()->whereRaw('0 = 1');
        }

        return static::role($roleNames);
    }
}
