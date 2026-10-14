<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlaEscalation extends Model
{
    use HasFactory;

    protected $fillable = ['complaint_id', 'old_status', 'new_status', 'note', 'notified'];

    public function complaint()
    {
        return $this->belongsTo(Complaint::class);
    }
}
