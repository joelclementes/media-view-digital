<?php

namespace App\Livewire;

use App\Models\Genero;
use App\Models\TamanoPublicacion;
use Carbon\Carbon;
use Livewire\Component;

class PublicacionElectronico extends Component
{
    public $fecha = '';
    public $tamano_id = null;
    public $genero_id = null;

    public $tamanos = [];
    public $generos = [];

    public $medio = 'electronico';

    public function mount()
    {
        $this->cargarTamanos();
        $this->cargarGeneros();

        if (empty($this->fecha)) {
            $this->fecha = Carbon::now()->format('Y-m-d');
        }

        $this->dispatch('updatedPublicacionElectronico', payload: $this->getData());
    }

    public function cargarTamanos()
    {
        $this->tamanos = TamanoPublicacion::orderBy('nombre')->get();
    }

    public function cargarGeneros()
    {
        $this->generos = Genero::porMedio($this->medio)
            ->orderBy('nombre')
            ->get();
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'fecha' => 'required|date',
            'tamano_id' => 'required|exists:tamanos_publicacion,id',
            'genero_id' => 'required|exists:generos,id',
        ]);

        $this->dispatch('updatedPublicacionElectronico', payload: $this->getData());
    }

    public function getData()
    {
        return [
            'fecha' => $this->fecha,
            'tamano_id' => $this->tamano_id,
            'genero_id' => $this->genero_id,
        ];
    }

    public function render()
    {
        return view('livewire.m-electronicos.publicacion-electronico');
    }
}
