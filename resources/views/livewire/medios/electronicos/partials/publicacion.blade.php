<div class="mb-6 p-5 border rounded-lg bg-white shadow-sm">
    <h3 class="text-lg font-semibold mb-4 text-gray-800">
        Datos de la publicación
    </h3>

    <div class="grid grid-cols-1 gap-4">
        <div>
            <x-label for="fecha" value="Fecha de publicación" />

            <x-input
                id="fecha"
                type="date"
                wire:model.live="fecha"
                class="w-full"
                max="{{ now()->format('Y-m-d') }}"
            />

            @error('fecha')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <x-label for="tamano_id" value="Tamaño de publicación" />

            <select
                id="tamano_id"
                wire:model.live="tamano_id"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
            >
                <option value="">Seleccionar tamaño</option>
                @foreach ($tamanos as $tamano)
                    <option value="{{ $tamano->id }}">{{ $tamano->nombre }}</option>
                @endforeach
            </select>

            @error('tamano_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <x-label for="genero_id" value="Género periodístico" />

            <select
                id="genero_id"
                wire:model.live="genero_id"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
            >
                <option value="">Seleccionar género</option>
                @foreach ($generos as $genero)
                    <option value="{{ $genero->id }}">{{ $genero->nombre }}</option>
                @endforeach
            </select>

            @error('genero_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
