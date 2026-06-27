<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'unit_of_measure' => $this->unit_of_measure,
            'observations' => $this->observations,
            'quantity_in_inventory' => $this->quantity_in_inventory,
            'is_available' => $this->quantity_in_inventory > 0,
            'inventory_status' => $this->inventoryStatus(),
            'inventory_updated_at' => $this->inventory_updated_at?->toISOString(),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),

            'brand' => new BrandResource($this->whenLoaded('brand')),
        ];
    }

    private function inventoryStatus(): string
    {
        if ($this->quantity_in_inventory === 0) {
            return 'Sin stock';
        }

        if ($this->quantity_in_inventory <= 10) {
            return 'Bajo stock';
        }

        return 'Disponible';
    }
}