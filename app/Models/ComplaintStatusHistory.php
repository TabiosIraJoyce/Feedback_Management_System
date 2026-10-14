<?php

namespace App\Models;

use Database\Factories\ComplaintStatusHistoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComplaintStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'complaint_id',
        'status',
        'title',
        'note',
        'visible_to_public',
    ];

    public function complaint(): BelongsTo
    {
        return $this->belongsTo(Complaint::class);
    }
}
