<?php

namespace App\Models;

use Database\Factories\FormFieldFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormField extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id',
        'label',
        'name',
        'type',
        'placeholder',
        'options',
        'required',
        'order',
    ];

    protected $casts = [
        'options' => 'array',
        'required' => 'boolean',
    ];

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }
}
