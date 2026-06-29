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

    public const LOW_STOCK_MAX = 10;

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
        return $this->quantity_in_inventory > self::LOW_STOCK_MAX;
    }

    public function getInventoryStatusAttribute(): string
    {
        if ($this->quantity_in_inventory === 0) {
            return 'Sin stock';
        }

        if ($this->quantity_in_inventory <= self::LOW_STOCK_MAX) {
            return 'Bajo stock';
        }

        return 'Disponible';
    }
}
