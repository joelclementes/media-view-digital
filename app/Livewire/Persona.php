<?php

namespace App\Livewire;

use App\Models\Partido;
use App\Models\Periodo;
use App\Models\Sujeto;
use App\Models\TipoEleccion;
use Livewire\Component;

class Persona extends Component
{
    public $search = '';
    public $resultados = [];
    public $selectedSujeto = null;

    public $sujeto_id = null;
    public $organizacion_politica_id = null;
    public $periodo_id = null;
    public $etapa_sujeto = null;
    public $tipo_eleccion_id = null;

    public $partidos = [];
    public $periodos = [];
    public $tiposEleccion = [];
    public $etapasSujeto = [
        'candidatura' => 'Candidatura',
        'precandidatura' => 'Precandidatura',
        'candidatura_independiente' => 'Candidatura independiente',
    ];

    public $mostrarFormulario = false;

    public function mount()
    {
        $this->partidos = Partido::orderBy('nombre')->get();
        $this->periodos = Periodo::orderBy('nombre')->get();
        $this->tiposEleccion = TipoEleccion::orderBy('nombre')->get();

        $this->dispatch('updatedPersona', payload: $this->getData());
    }

    public function updatedSearch()
    {
        if (strlen($this->search) >= 2) {
            $this->resultados = Sujeto::where('nombre', 'LIKE', '%' . $this->search . '%')
                ->limit(10)
                ->get(['id', 'nombre'])
                ->toArray();
        } else {
            $this->resultados = [];
        }
    }

    public function selectSujeto($id)
    {
        $this->selectedSujeto = Sujeto::with(['partido'])->find($id);

        if ($this->selectedSujeto) {
            $this->sujeto_id = $id;
            $this->search = $this->selectedSujeto->nombre;
            $this->resultados = [];
            $this->mostrarFormulario = true;

            if ($this->selectedSujeto->partido_id) {
                $this->organizacion_politica_id = $this->selectedSujeto->partido_id;
            }

            $this->dispatch('sujetoSeleccionado', sujetoId: $id);
            $this->dispatch('updatedPersona', payload: $this->getData());
        }
    }

    public function limpiarSujeto()
    {
        $this->reset([
            'search',
            'selectedSujeto',
            'sujeto_id',
            'resultados',
            'mostrarFormulario',
            'organizacion_politica_id',
            'periodo_id',
            'etapa_sujeto',
            'tipo_eleccion_id',
        ]);

        $this->dispatch('sujetoLimpiado');
        $this->dispatch('updatedPersona', payload: $this->getData());
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'sujeto_id' => 'required|exists:sujetos,id',
            'organizacion_politica_id' => 'nullable|exists:partidos,id',
            'periodo_id' => 'nullable|exists:periodos,id',
            'etapa_sujeto' => 'nullable|in:candidatura,precandidatura,candidatura_independiente',
            'tipo_eleccion_id' => 'nullable|exists:tipos_eleccion,id',
        ]);

        $this->dispatch('updatedPersona', payload: $this->getData());
    }

    public function getData()
    {
        return [
            'sujeto_id' => $this->sujeto_id,
            'organizacion_politica_id' => $this->organizacion_politica_id,
            'periodo_id' => $this->periodo_id,
            'etapa_sujeto' => $this->etapa_sujeto,
            'tipo_eleccion_id' => $this->tipo_eleccion_id,
        ];
    }

    public function render()
    {
        return view('livewire.persona');
    }
}
