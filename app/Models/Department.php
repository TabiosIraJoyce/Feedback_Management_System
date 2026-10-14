<?php

namespace App\Models;

use App\Models\EmployeeFeedback;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'slug',
        'description',
        'branch_id',
        'qr_token',
        'status',
        'is_active',
        'logo',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Department $department): void {
            $department->code = Str::upper(trim($department->code));
            $department->slug = static::generateUniqueSlug($department);
            $department->is_active = $department->status === 'active';

            if (! $department->qr_token) {
                $department->qr_token = static::generateUniqueQrToken($department);
            }
        });

        static::deleting(function (Department $department): void {
            if ($department->logo) {
                Storage::disk('public')->delete($department->logo);
            }
        });
    }

    public function getFeedbackUrlAttribute(): string
    {
        $branchSlug = $this->branch?->slug ?? $this->branch()->value('slug');

        return route('feedback.public.show', ['branch' => $branchSlug, 'department' => $this->slug]);
    }

    public function getQrCodeUrlAttribute(): string
    {
        return $this->qrCode?->svg_preview_url
            ?? 'https://api.qrserver.com/v1/create-qr-code/?size=260x260&data='.urlencode($this->feedback_url).'&format=svg';
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function feedbackTickets(): HasMany
    {
        return $this->hasMany(FeedbackTicket::class);
    }

    public function formSubmissions(): HasMany
    {
        return $this->hasMany(FormSubmission::class);
    }

    public function employeeFeedback(): HasMany
    {
        return $this->hasMany(EmployeeFeedback::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class);
    }

    public function qrCode(): HasOne
    {
        return $this->hasOne(QrCode::class)->where('type', 'department');
    }

    private static function generateUniqueQrToken(Department $department): string
    {
        $branch = $department->branch ?? Branch::find($department->branch_id);
        $branchBase = Str::slug((string) ($branch?->name ?? 'branch'));
        $branchBase = preg_replace('/-branch$/', '', $branchBase) ?: 'branch';
        $departmentBase = Str::slug((string) ($department->code ?: $department->name));
        $token = trim($branchBase.'-'.$departmentBase, '-');
        $originalToken = $token;
        $counter = 1;

        while (static::query()
            ->where('qr_token', $token)
            ->when($department->exists, fn ($query) => $query->whereKeyNot($department->id))
            ->exists()
        ) {
            $token = "{$originalToken}-{$counter}";
            $counter++;
        }

        return $token;
    }

    private static function generateUniqueSlug(Department $department): string
    {
        $base = Str::slug((string) ($department->code ?: $department->name));
        $base = $base !== '' ? $base : 'department';
        $slug = $base;
        $counter = 1;

        while (static::query()
            ->where('branch_id', $department->branch_id)
            ->where('slug', $slug)
            ->when($department->exists, fn ($query) => $query->whereKeyNot($department->id))
            ->exists()
        ) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
