<div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
    <div>
        <label for="name" class="mb-2 block text-sm font-bold text-white">
            Nombre de la marca
        </label>

        <input
            type="text"
            id="name"
            name="name"
            value="{{ old('name', $brand->name ?? '') }}"
            class="input w-full"
            placeholder="Ej: Alpina"
            required
            maxlength="150"
            style="background: #252729; color: #ffffff; border-color: rgba(255, 255, 255, 0.18);"
        >

        @error('name')
            <div class="mt-2 text-sm font-semibold" style="color: #dc2626;">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div>
        <label for="reference" class="mb-2 block text-sm font-bold text-white">
            Referencia
        </label>

        <input
            type="text"
            id="reference"
            name="reference"
            value="{{ old('reference', $brand->reference ?? '') }}"
            class="input w-full"
            placeholder="Ej: ALPINA-001"
            required
            maxlength="50"
            style="background: #252729; color: #ffffff; border-color: rgba(255, 255, 255, 0.18);"
        >

        <div class="mt-3 text-sm leading-relaxed" style="color: #d8dee9;">
            Identificador alfanumerico unico. Solo letras, numeros y guiones.
        </div>

        @error('reference')
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

    <a href="{{ route('admin.brands.index') }}" class="button-brand-outline">
        Cancelar
    </a>
</div>
