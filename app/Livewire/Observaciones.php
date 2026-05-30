<?php

namespace App\Livewire;

use Livewire\Component;

class Observaciones extends Component
{
    public $observaciones = '';

    public function mount(): void
    {
        $this->dispatch('updatedObservaciones', payload: $this->getData());
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'observaciones' => 'nullable|string|max:255',
        ]);

        $this->dispatch('updatedObservaciones', payload: $this->getData());
    }

    public function updatingObservaciones($value)
    {
        if (strlen($value) > 255) {
            $this->addError('observaciones', 'No se permiten más de 255 caracteres');
            return substr($value, 0, 255);
        }

        return $value;
    }

    public function getData()
    {
        return [
            'observaciones' => $this->observaciones,
        ];
    }

    public function limpiar()
    {
        $this->observaciones = '';
        $this->dispatch('updatedObservaciones', payload: $this->getData());
    }

    public function render()
    {
        return view('livewire.observaciones');
    }
}
