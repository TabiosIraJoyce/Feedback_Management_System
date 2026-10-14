<?php

namespace App\Models;

use App\Services\QrCodeService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QrCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'department_id',
        'type',
        'code',
        'path',
        'status',
        'scan_count',
        'download_count',
        'last_scanned_at',
        'last_downloaded_at',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'last_scanned_at' => 'datetime',
            'last_downloaded_at' => 'datetime',
            'meta' => 'array',
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

    public function scans(): HasMany
    {
        return $this->hasMany(QrCodeScan::class);
    }

    public function getPublicUrlAttribute(): string
    {
        return app(QrCodeService::class)->publicUrl($this);
    }

    public function getSvgPreviewUrlAttribute(): string
    {
        return app(QrCodeService::class)->remoteImageUrl($this, 'svg', 360);
    }

    public function getPngPreviewUrlAttribute(): string
    {
        return app(QrCodeService::class)->remoteImageUrl($this, 'png', 480);
    }
}
