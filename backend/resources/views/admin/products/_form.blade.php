<div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
    <div>
        <label for="name" class="mb-2 block text-sm font-bold text-white">
            Nombre del producto
        </label>

        <input
            type="text"
            id="name"
            name="name"
            value="{{ old('name', $product->name ?? '') }}"
            class="input w-full"
            placeholder="Ej: Leche Entera x 12"
            required
            maxlength="200"
            style="background: #252729; color: #ffffff; border-color: rgba(255, 255, 255, 0.18);"
        >

        @error('name')
            <div class="mt-2 text-sm font-semibold" style="color: #dc2626;">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div>
        <label for="brand_id" class="mb-2 block text-sm font-bold text-white">
            Marca
        </label>

        <select
            id="brand_id"
            name="brand_id"
            class="select w-full"
            required
            style="background: #252729; color: #ffffff; border-color: rgba(255, 255, 255, 0.18);"
        >
            <option value="">Selecciona una marca</option>
            @foreach ($brands as $brand)
                <option
                    value="{{ $brand->id }}"
                    @selected((int) old('brand_id', $product->brand_id ?? 0) === $brand->id)
                >
                    {{ $brand->name }} - {{ $brand->reference }}
                </option>
            @endforeach
        </select>

        @error('brand_id')
            <div class="mt-2 text-sm font-semibold" style="color: #dc2626;">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div>
        <label for="unit_of_measure" class="mb-2 block text-sm font-bold text-white">
            Unidad de medida
        </label>

        <select
            id="unit_of_measure"
            name="unit_of_measure"
            class="select w-full"
            required
            style="background: #252729; color: #ffffff; border-color: rgba(255, 255, 255, 0.18);"
        >
            <option value="">Selecciona una unidad</option>
            @foreach ($units as $unit)
                <option
                    value="{{ $unit }}"
                    @selected(old('unit_of_measure', $product->unit_of_measure ?? '') === $unit)
                >
                    {{ $unit }}
                </option>
            @endforeach
        </select>

        @error('unit_of_measure')
            <div class="mt-2 text-sm font-semibold" style="color: #dc2626;">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div>
        <label for="quantity_in_inventory" class="mb-2 block text-sm font-bold text-white">
            Cantidad en inventario
        </label>

        <input
            type="number"
            id="quantity_in_inventory"
            name="quantity_in_inventory"
            value="{{ old('quantity_in_inventory', $product->quantity_in_inventory ?? 0) }}"
            class="input w-full"
            min="0"
            max="999999"
            step="1"
            required
            style="background: #252729; color: #ffffff; border-color: rgba(255, 255, 255, 0.18);"
        >

        @error('quantity_in_inventory')
            <div class="mt-2 text-sm font-semibold" style="color: #dc2626;">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div>
        <label for="inventory_updated_at" class="mb-2 block text-sm font-bold text-white">
            Fecha de actualizacion
        </label>

        <input
            type="datetime-local"
            id="inventory_updated_at"
            name="inventory_updated_at"
            value="{{ old('inventory_updated_at', isset($product) ? $product->inventory_updated_at?->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}"
            class="input w-full"
            required
            style="background: #252729; color: #ffffff; border-color: rgba(255, 255, 255, 0.18);"
        >

        <div class="mt-3 text-sm leading-relaxed" style="color: #d8dee9;">
            Fecha real de actualizacion del inventario, independiente de la fecha de edicion del registro.
        </div>

        @error('inventory_updated_at')
            <div class="mt-2 text-sm font-semibold" style="color: #dc2626;">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="lg:col-span-2">
        <label for="observations" class="mb-2 block text-sm font-bold text-white">
            Observaciones
        </label>

        <textarea
            id="observations"
            name="observations"
            class="textarea w-full"
            placeholder="Describe condiciones comerciales, logistica, rotacion o notas relevantes del producto."
            required
            maxlength="2000"
            style="background: #252729; color: #ffffff; border-color: rgba(255, 255, 255, 0.18); min-height: 140px;"
        >{{ old('observations', $product->observations ?? '') }}</textarea>

        @error('observations')
            <div class="mt-2 text-sm font-semibold" style="color: #dc2626;">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>

<div class="mt-7 flex flex-wrap items-center gap-3">
    <button type="submit" class="button-brand-outline">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M5 13l4 4L19 7" />
        </svg>
        {{ $submitText }}
    </button>

    <a href="{{ route('admin.products.index') }}" class="button-brand-outline">
        Cancelar
    </a>
</div>
