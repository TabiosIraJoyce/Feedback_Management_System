<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        // 'business_type_id' removed
        'status',
        'icon',
    ];
    // BusinessType removed — relationship deprecated

    public function branches(): BelongsToMany
    {
        return $this->belongsToMany(Branch::class, 'branch_products')
            ->withPivot('status')
            ->withTimestamps();
    }

    public function activeBranches(): BelongsToMany
    {
        return $this->branches()->wherePivot('status', 'active');
    }

}
