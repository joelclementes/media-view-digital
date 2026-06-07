<div class="mb-6 p-5 border rounded-lg bg-white shadow-sm">
    <h3 class="text-lg font-semibold mb-4 text-gray-800">
        Referencia
    </h3>

    <x-label for="referencia" value="Referencia" />

    <x-input
        id="referencia"
        type="text"
        wire:model.live.debounce.500ms="referencia"
        placeholder="Referencia breve, si aplica"
        class="w-full"
    />

    @error('referencia')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>