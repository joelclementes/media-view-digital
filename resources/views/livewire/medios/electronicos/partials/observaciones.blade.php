<div class="mb-6 p-5 border rounded-lg bg-white shadow-sm">
    <h3 class="text-lg font-semibold mb-4 text-gray-800">
        Observaciones
    </h3>

    <x-label for="observaciones" value="Observaciones" />

    <textarea
        id="observaciones"
        wire:model.live.debounce.500ms="observaciones"
        maxlength="255"
        rows="4"
        placeholder="Observaciones del registro, si aplica"
        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
    ></textarea>

    <div class="mt-1 flex justify-between text-xs text-gray-500">
        <span>Máximo 255 caracteres.</span>
        <span>{{ strlen($observaciones) }}/255</span>
    </div>

    @error('observaciones')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>