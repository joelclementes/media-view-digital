<div class="mb-6 p-5 border rounded-lg bg-white shadow-sm">
    <h3 class="text-lg font-semibold mb-4 text-gray-800">
        Medio impreso
    </h3>

    <x-label for="medio_prensa_id" value="Medio de prensa" />

    <select id="medio_prensa_id" wire:model.live="medio_prensa_id"
        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
        <option value="">Seleccionar medio impreso</option>

        @foreach ($medios_prensa as $medio)
            <option value="{{ $medio->id }}">
                {{ $medio->nombre }}
                @if(isset($medio->ciudad))
                    - {{ $medio->ciudad }}
                @endif
            </option>
        @endforeach
    </select>

    @error('medio_prensa_id')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>