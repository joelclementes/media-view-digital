<div class="mb-6 p-5 border rounded-lg bg-white shadow-sm">
    <h3 class="text-lg font-semibold mb-4 text-gray-800">Datos de la publicación</h3>

    <div class="grid grid-cols-1 gap-4">
        <div>
            <x-label for="publicacion_fecha" value="Fecha de publicación" />
            <x-input id="publicacion_fecha" type="date" wire:model.live="publicacion_fecha" class="w-full" max="{{ now()->format('Y-m-d') }}" />
            <x-input-error for="publicacion_fecha" class="mt-1" />
        </div>

        <div>
            <x-label for="publicacion_hora" value="Hora de publicación" />
            <x-input id="publicacion_hora" type="time" wire:model.live="publicacion_hora" class="w-full" />
            <x-input-error for="publicacion_hora" class="mt-1" />
        </div>

        <div>
            <x-label for="publicacion_tiempo" value="Tiempo en segundos" />
            <x-input id="publicacion_tiempo" type="number" min="1" wire:model.live="publicacion_tiempo" class="w-full" />
            <x-input-error for="publicacion_tiempo" class="mt-1" />
        </div>

        <div>
            <x-label for="referencia" value="Referencia" />
            <x-input id="referencia" type="text" wire:model.live.debounce.500ms="referencia" class="w-full" placeholder="Referencia del registro" />
            <x-input-error for="referencia" class="mt-1" />
        </div>
    </div>
</div>
