<div class="mb-6 p-5 border rounded-lg bg-white shadow-sm">
    <h3 class="text-lg font-semibold mb-4 text-gray-800">Referencia</h3>

    <div class="grid grid-cols-1 gap-4">
        <div>
            <x-label for="referencia" value="Referencia" />
            <x-input id="referencia" type="text" wire:model.live.debounce.500ms="referencia" class="w-full" />
            <x-input-error for="referencia" class="mt-1" />
        </div>

        <div>
            <x-label for="referencia_domiciliaria" value="Referencia domiciliaria" />
            <textarea id="referencia_domiciliaria" wire:model.live.debounce.500ms="referencia_domiciliaria" maxlength="255" rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"></textarea>
            <div class="mt-1 flex justify-between text-xs text-gray-500">
                <span>Máximo 255 caracteres.</span>
                <span>{{ strlen($referencia_domiciliaria) }}/255</span>
            </div>
            <x-input-error for="referencia_domiciliaria" class="mt-1" />
        </div>
    </div>
</div>
