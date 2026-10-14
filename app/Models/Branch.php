<?php

namespace App\Models;

// BusinessType model removed — association deprecated
use App\Models\EmployeeFeedback;
use App\Models\Form;
use App\Models\FormSubmission;
use App\Models\Product;
use Database\Factories\BranchFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'slug',
        'description',
        // 'business_type_id' removed
        'address',
        'contact_number',
        'email',
        'logo',
        'qr_token',
        'status',
        'is_active',
        'public_form_id',
    ];

    protected static function booted(): void
    {
        static::saving(function (Branch $branch): void {
            $slug = Str::slug($branch->slug ?: $branch->name);
            $originalSlug = $slug;
            $counter = 1;

            while (Branch::where('slug', $slug)
                ->when($branch->exists, fn ($query) => $query->where('id', '!=', $branch->id))
                ->exists()
            ) {
                $slug = "{$originalSlug}-{$counter}";
                $counter++;
            }

            $branch->slug = $slug;

            if (! $branch->qr_token) {
                do {
                    $token = Str::upper(Str::random(24));
                } while (Branch::where('qr_token', $token)->exists());

                $branch->qr_token = $token;
            }

            $branch->is_active = $branch->status === 'active';
        });

        static::deleting(function (Branch $branch): void {
            if ($branch->logo) {
                Storage::disk('public')->delete($branch->logo);
            }
        });
    }

    public function getFeedbackUrlAttribute(): string
    {
        return route('feedback.public.show', ['branch' => $this->slug]);
    }

    public function getQrCodeUrlAttribute(): string
    {
        return $this->qrCode?->svg_preview_url
            ?? 'https://api.qrserver.com/v1/create-qr-code/?size=260x260&data='.urlencode($this->feedback_url).'&format=svg';
    }

    // public function businessType(): BelongsTo
    // {
    //     return $this->belongsTo(BusinessType::class);
    // }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function publicForm(): BelongsTo
    {
        return $this->belongsTo(Form::class, 'public_form_id');
    }

    public function publicFormSubmissions(): HasMany
    {
        return $this->hasMany(FormSubmission::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class);
    }

    public function qrCode(): HasOne
    {
        return $this->hasOne(QrCode::class)->where('type', 'branch');
    }

    public function feedbackEntries(): HasMany
    {
        return $this->hasMany(Feedback::class);
    }

    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }

    public function employeeFeedback(): HasMany
    {
        return $this->hasMany(EmployeeFeedback::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'branch_products')
            ->withPivot('status')
            ->withTimestamps();
    }

    public function activeProducts(): BelongsToMany
    {
        return $this->products()->wherePivot('status', 'active');
    }
}
