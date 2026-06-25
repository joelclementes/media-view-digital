<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Catálogo de periodos
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
            <livewire:catalogos.periodos.formulario />
        </div>
    </div>
</x-app-layout>
