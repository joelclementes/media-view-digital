<div class="mb-6 p-5 border rounded-lg bg-white shadow-sm">
    <h3 class="text-lg font-semibold mb-4 text-gray-800">
        Datos del autor
    </h3>

    <div class="grid grid-cols-1 gap-4">
        <div>
            <x-label for="genero_sujeto_id" value="Género del autor" />

            <select
                id="genero_sujeto_id"
                wire:model.live="genero_sujeto_id"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
            >
                <option value="">Seleccionar género</option>
                @foreach ($generos_sujeto as $genero)
                    <option value="{{ $genero->id }}">{{ $genero->nombre }}</option>
                @endforeach
            </select>

            @error('genero_sujeto_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <x-label for="nombre_autor" value="Nombre del autor" />

            <x-input
                id="nombre_autor"
                type="text"
                wire:model.live.debounce.500ms="nombre_autor"
                placeholder="Nombre del autor, si aplica"
                class="w-full"
            />

            @error('nombre_autor')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
