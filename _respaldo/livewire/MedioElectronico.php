<?php

namespace App\Livewire;

use App\Models\PortalInternet;
use Livewire\Component;

class MedioElectronico extends Component
{
    public $portal_internet_id = null;
    public $url_pagina = '';

    public $portales = [];

    public $mostrarOtroPortal = false;
    public $nuevoPortal = [
        'nombre' => '',
        'url' => '',
        'ciudad' => '',
        'tipo' => '',
    ];

    public function mount()
    {
        $this->cargarPortales();
        $this->dispatch('updatedMedioElectronico', payload: $this->getData());
    }

    public function cargarPortales()
    {
        $this->portales = PortalInternet::orderBy('nombre')->get();
    }

    public function updatedPortalInternetId($value)
    {
        if ($value === 'otro') {
            $this->mostrarOtroPortal = true;
            $this->portal_internet_id = null;
        } else {
            $this->mostrarOtroPortal = false;
            $portal = PortalInternet::find($value);
            if ($portal) {
                $this->url_pagina = $portal->url;
            }
        }

        $this->dispatch('updatedMedioElectronico', payload: $this->getData());
    }

    public function guardarNuevoPortal()
    {
        $this->validate([
            'nuevoPortal.nombre' => 'required|min:3',
            'nuevoPortal.url' => 'url',
            'nuevoPortal.ciudad' => 'required',
            'nuevoPortal.tipo' => 'required',
        ]);

        try {
            $portal = PortalInternet::create([
                'nombre' => $this->nuevoPortal['nombre'],
                'url' => $this->nuevoPortal['url'],
                'ciudad' => $this->nuevoPortal['ciudad'],
                'tipo' => $this->nuevoPortal['tipo'],
            ]);

            $this->cargarPortales();

            $this->portal_internet_id = $portal->id;
            $this->url_pagina = $portal->url;

            $this->reset('nuevoPortal', 'mostrarOtroPortal');

            $this->dispatch('portalGuardado', portalId: $portal->id);
            $this->dispatch('updatedMedioElectronico', payload: $this->getData());
        } catch (\Exception $e) {
            session()->flash('error', 'Error al guardar el portal: ' . $e->getMessage());
        }
    }

    public function cancelarNuevoPortal()
    {
        $this->reset('nuevoPortal', 'mostrarOtroPortal');
        $this->dispatch('updatedMedioElectronico', payload: $this->getData());
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'portal_internet_id' => 'nullable|exists:portales_internet,id',
            'url_pagina' => 'required|url',
        ]);

        $this->dispatch('updatedMedioElectronico', payload: $this->getData());
    }

    public function getData()
    {
        return [
            'portal_internet_id' => $this->portal_internet_id,
            'url_pagina' => $this->url_pagina,
        ];
    }

    public function render()
    {
        return view('livewire.m-electronicos.medio-electronico');
    }
}
