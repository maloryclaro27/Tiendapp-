<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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
        return $this->quantity_in_inventory > 0;
    }

    public function getInventoryStatusAttribute(): string
    {
        if ($this->quantity_in_inventory <= 0) {
            return 'Sin stock';
        }

        if ($this->quantity_in_inventory <= self::LOW_STOCK_MAX) {
            return 'Bajo stock';
        }

        return 'Stock saludable';
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (blank($search)) {
            return $query;
        }

        return $query->where(function (Builder $query) use ($search): void {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('observations', 'like', "%{$search}%");
        });
    }

    public function scopeByBrand(Builder $query, int|string|null $brandId): Builder
    {
        if (blank($brandId)) {
            return $query;
        }

        return $query->where('brand_id', $brandId);
    }

    public function scopeByUnit(Builder $query, ?string $unit): Builder
    {
        if (blank($unit)) {
            return $query;
        }

        return $query->where('unit_of_measure', $unit);
    }

    public function scopeWithAvailability(Builder $query, ?string $availability): Builder
    {
        return match ($availability) {
            'available', 'in_stock' => $query->where('quantity_in_inventory', '>', 0),
            'healthy_stock' => $query->where('quantity_in_inventory', '>', self::LOW_STOCK_MAX),
            'low_stock' => $query->whereBetween('quantity_in_inventory', [1, self::LOW_STOCK_MAX]),
            'out_of_stock' => $query->where('quantity_in_inventory', '<=', 0),
            default => $query,
        };
    }

    public function scopeSorted(Builder $query, ?string $sort): Builder
    {
        return match ($sort) {
            'name' => $query->orderBy('name'),
            'stock_asc' => $query->orderBy('quantity_in_inventory'),
            'stock_desc' => $query->orderByDesc('quantity_in_inventory'),
            'updated_asc' => $query->orderBy('inventory_updated_at'),
            default => $query->orderByDesc('inventory_updated_at'),
        };
    }
}
