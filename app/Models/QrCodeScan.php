<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QrCodeScan extends Model
{
    use HasFactory;

    protected $fillable = [
        'qr_code_id',
        'ip_address',
        'user_agent',
        'referer',
        'path',
        'scanned_at',
    ];

    protected function casts(): array
    {
        return [
            'scanned_at' => 'datetime',
        ];
    }

    public function qrCode(): BelongsTo
    {
        return $this->belongsTo(QrCode::class);
    }
}
