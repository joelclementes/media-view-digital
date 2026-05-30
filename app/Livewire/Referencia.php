<?php

namespace App\Livewire;

use Livewire\Component;

class Referencia extends Component
{
    public $referencia = '';
    public $referencia_domiciliaria = '';

    public $mostrarDomiciliaria = false;

    public function mount($mostrarDomiciliaria = false)
    {
        $this->mostrarDomiciliaria = $mostrarDomiciliaria;
        $this->dispatch('updatedReferencia', payload: $this->getData());
    }

    public function updated($propertyName)
    {
        $rules = [
            'referencia' => 'nullable|string|max:255',
        ];

        if ($this->mostrarDomiciliaria) {
            $rules['referencia_domiciliaria'] = 'nullable|string|max:500';
        }

        $this->validateOnly($propertyName, $rules);

        $this->dispatch('updatedReferencia', payload: $this->getData());
    }

    public function getData()
    {
        $data = [
            'referencia' => $this->referencia,
        ];

        if ($this->mostrarDomiciliaria) {
            $data['referencia_domiciliaria'] = $this->referencia_domiciliaria;
        }

        return $data;
    }

    public function render()
    {
        return view('livewire.referencia');
    }
}
