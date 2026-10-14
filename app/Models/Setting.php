<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value', 'type', 'group', 'description'];

    protected $casts = [
        'value' => 'string',
    ];

    public static function getValue(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();

        return $setting ? $setting->value : $default;
    }

    public static function setValue(string $key, $value, string $type = 'string', string $group = null): self
    {
        return static::updateOrCreate(['key' => $key], ['value' => $value, 'type' => $type, 'group' => $group]);
    }
}
