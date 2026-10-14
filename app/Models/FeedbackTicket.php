<?php

namespace App\Models;

use Database\Factories\FeedbackTicketFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class FeedbackTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'department_id',
        'qr_code_id',
        'reference_no',
        'satisfaction',
        'category',
        'subject',
        'comment',
        'description',
        'rating',
        'customer_name',
        'customer_email',
        'customer_phone',
        'photos',
        'status',
        'is_anonymous',
        'source',
        'hci_metadata',
    ];

    protected $casts = [
        'photos' => 'array',
        'is_anonymous' => 'boolean',
        'hci_metadata' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function (FeedbackTicket $ticket): void {
            if (! $ticket->reference_no) {
                do {
                    $ticket->reference_no = 'FBK-'.date('Y').'-'.Str::upper(Str::random(6));
                } while (static::where('reference_no', $ticket->reference_no)->exists());
            }

            if (! $ticket->description && $ticket->comment) {
                $ticket->description = $ticket->comment;
            }

            if (! $ticket->comment && $ticket->description) {
                $ticket->comment = $ticket->description;
            }
        });
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function qrCode(): BelongsTo
    {
        return $this->belongsTo(QrCode::class);
    }
}
