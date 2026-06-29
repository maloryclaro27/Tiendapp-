<div class="form-grid">
    <div class="form-group">
        <label for="name" class="label">Nombre de la marca</label>
        <input
            type="text"
            id="name"
            name="name"
            value="{{ old('name', $brand->name ?? '') }}"
            class="input"
            placeholder="Ej: Alpina"
            required
            maxlength="150"
        >

        @error('name')
            <div class="error-text">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="reference" class="label">Referencia</label>
        <input
            type="text"
            id="reference"
            name="reference"
            value="{{ old('reference', $brand->reference ?? '') }}"
            class="input"
            placeholder="Ej: ALPINA-001"
            required
            maxlength="50"
        >

        <div class="help">
            Identificador alfanumerico unico. Solo letras, numeros y guiones.
        </div>

        @error('reference')
            <div class="error-text">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="actions" style="margin-top: 22px;">
    <button type="submit" class="button button-primary">
        {{ $submitText }}
    </button>

    <a href="{{ route('admin.brands.index') }}" class="button button-secondary">
        Cancelar
    </a>
</div>