<?php

namespace App\Livewire;

use App\Models\GeneroSujeto;
use Livewire\Component;

class AutorElectronico extends Component
{
    public $genero_sujeto_id = null;
    public $nombre = '';

    public $generosSujeto = [];

    public function mount()
    {
        $this->cargarGeneros();
        $this->dispatch('updatedAutorElectronico', payload: $this->getData());
    }

    public function cargarGeneros()
    {
        $this->generosSujeto = GeneroSujeto::orderBy('nombre')->get();
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'genero_sujeto_id' => 'required|exists:generos_sujetos,id',
            'nombre' => 'nullable|string|max:255',
        ]);

        $this->dispatch('updatedAutorElectronico', payload: $this->getData());
    }

    public function getData()
    {
        return [
            'genero_sujeto_id' => $this->genero_sujeto_id,
            'nombre_autor' => $this->nombre,
        ];
    }

    public function render()
    {
        return view('livewire.m-electronicos.autor-electronico');
    }
}
