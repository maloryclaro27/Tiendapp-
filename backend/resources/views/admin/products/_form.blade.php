<div class="form-grid">
    <div class="form-group">
        <label for="name" class="label">Nombre del producto</label>
        <input
            type="text"
            id="name"
            name="name"
            value="{{ old('name', $product->name ?? '') }}"
            class="input"
            placeholder="Ej: Leche Entera x 12"
            required
            maxlength="200"
        >

        @error('name')
            <div class="error-text">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="brand_id" class="label">Marca</label>
        <select id="brand_id" name="brand_id" class="select" required>
            <option value="">Selecciona una marca</option>
            @foreach ($brands as $brand)
                <option
                    value="{{ $brand->id }}"
                    @selected((int) old('brand_id', $product->brand_id ?? 0) === $brand->id)
                >
                    {{ $brand->name }} · {{ $brand->reference }}
                </option>
            @endforeach
        </select>

        @error('brand_id')
            <div class="error-text">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="unit_of_measure" class="label">Unidad de medida</label>
        <select id="unit_of_measure" name="unit_of_measure" class="select" required>
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
            <div class="error-text">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="quantity_in_inventory" class="label">Cantidad en inventario</label>
        <input
            type="number"
            id="quantity_in_inventory"
            name="quantity_in_inventory"
            value="{{ old('quantity_in_inventory', $product->quantity_in_inventory ?? 0) }}"
            class="input"
            min="0"
            max="999999"
            step="1"
            required
        >

        @error('quantity_in_inventory')
            <div class="error-text">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="inventory_updated_at" class="label">Fecha de actualizacion</label>
        <input
            type="datetime-local"
            id="inventory_updated_at"
            name="inventory_updated_at"
            value="{{ old('inventory_updated_at', isset($product) ? $product->inventory_updated_at?->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}"
            class="input"
            required
        >

        <div class="help">
            Fecha real de actualizacion del inventario, independiente de la fecha de edicion del registro.
        </div>

        @error('inventory_updated_at')
            <div class="error-text">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group full">
        <label for="observations" class="label">Observaciones</label>
        <textarea
            id="observations"
            name="observations"
            class="textarea"
            placeholder="Describe condiciones comerciales, logistica, rotacion o notas relevantes del producto."
            required
            maxlength="2000"
        >{{ old('observations', $product->observations ?? '') }}</textarea>

        @error('observations')
            <div class="error-text">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="actions" style="margin-top: 22px;">
    <button type="submit" class="button button-primary">
        {{ $submitText }}
    </button>

    <a href="{{ route('admin.products.index') }}" class="button button-secondary">
        Cancelar
    </a>
</div>