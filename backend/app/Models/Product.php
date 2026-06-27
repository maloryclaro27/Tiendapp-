<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'brand_id',
        'name',
        'unit_of_measure',
        'observations',
        'quantity_in_inventory',
        'inventory_updated_at',
    ];

    protected $casts = [
        'quantity_in_inventory' => 'integer',
        'inventory_updated_at' => 'datetime',
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function getIsAvailableAttribute(): bool
    {
        return $this->quantity_in_inventory > 0;
    }
}