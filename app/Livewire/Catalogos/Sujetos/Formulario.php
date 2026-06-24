<?php

namespace App\Livewire\Catalogos\Sujetos;

use App\Models\Distrito;
use App\Models\GeneroSujeto;
use App\Models\Municipio;
use App\Models\Partido;
use App\Models\Sujeto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;
use Livewire\WithPagination;

class Formulario extends Component
{
    use WithPagination;

    public string $nombre = '';
    public ?int $genero_id = null;
    public ?int $distrito_id = null;
    public ?int $municipio_id = null;
    public ?int $partido_id = null;

    public ?int $sujeto_id = null;

    public string $buscar = '';
    public int $perPage = 10;

    public ?int $confirmingDeleteId = null;

    protected string $paginationTheme = 'tailwind';

    protected function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:255'],
            'genero_id' => ['nullable', 'exists:generos_sujetos,id'],
            'municipio_id' => ['nullable', 'exists:municipios,id'],
            'partido_id' => ['nullable', 'exists:partidos,id'],
        ];
    }

    protected array $messages = [
        'nombre.required' => 'El nombre es obligatorio.',
        'nombre.max' => 'El nombre no debe exceder 255 caracteres.',
        'genero_id.exists' => 'El género seleccionado no es válido.',
        'municipio_id.exists' => 'El municipio seleccionado no es válido.',
        'partido_id.exists' => 'El partido o asociación seleccionado no es válido.',
    ];

    public function updatedDistritoId(): void
    {
        $this->municipio_id = null;
    }

    public function updatedBuscar(): void
    {
        $this->resetPage();
    }

    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    public function guardar(): void
    {
        $this->validate();

        Sujeto::create([
            'nombre' => trim($this->nombre),
            'genero_id' => $this->genero_id,
            'municipio_id' => $this->municipio_id,
            'partido_id' => $this->partido_id,
        ]);

        $this->limpiarFormulario();

        session()->flash('success', 'Sujeto guardado correctamente.');
    }

    public function editar(int $id): void
    {
        $sujeto = Sujeto::with('municipio')->findOrFail($id);

        $this->sujeto_id = $sujeto->id;
        $this->nombre = $sujeto->nombre;
        $this->genero_id = $sujeto->genero_id;
        $this->municipio_id = $sujeto->municipio_id;
        $this->partido_id = $sujeto->partido_id;
        $this->distrito_id = $sujeto->municipio?->distrito_id;

        $this->resetValidation();
    }

    public function actualizar(): void
    {
        $this->validate();

        $sujeto = Sujeto::findOrFail($this->sujeto_id);

        $sujeto->update([
            'nombre' => trim($this->nombre),
            'genero_id' => $this->genero_id,
            'municipio_id' => $this->municipio_id,
            'partido_id' => $this->partido_id,
        ]);

        $this->limpiarFormulario();

        session()->flash('success', 'Sujeto actualizado correctamente.');
    }

    public function confirmarEliminar(int $id): void
    {
        $this->confirmingDeleteId = $id;
    }

    public function cancelarEliminar(): void
    {
        $this->confirmingDeleteId = null;
    }

    public function eliminar(): void
    {
        if (!$this->confirmingDeleteId) {
            return;
        }

        if ($this->sujetoTieneRelaciones($this->confirmingDeleteId)) {
            $this->confirmingDeleteId = null;
            session()->flash('error', 'No se puede eliminar el sujeto porque ya está relacionado con registros de monitoreo.');
            return;
        }

        Sujeto::findOrFail($this->confirmingDeleteId)->delete();

        if ($this->sujeto_id === $this->confirmingDeleteId) {
            $this->limpiarFormulario();
        }

        $this->confirmingDeleteId = null;

        session()->flash('success', 'Sujeto eliminado correctamente.');
    }

    private function sujetoTieneRelaciones(int $sujetoId): bool
    {
        $tablas = [
            'monit_soportes_promocionales',
            'monitoreo_cine',
            'monitoreo_medios_electronicos',
            'monitoreo_medios_impresos',
            'monitoreo_propaganda_movil',
            'monitoreo_radio',
            'monitoreo_television',
        ];

        foreach ($tablas as $tabla) {
            if (
                Schema::hasTable($tabla) &&
                Schema::hasColumn($tabla, 'sujeto_id') &&
                DB::table($tabla)->where('sujeto_id', $sujetoId)->exists()
            ) {
                return true;
            }
        }

        return false;
    }

    public function limpiarFormulario(): void
    {
        $this->reset([
            'nombre',
            'genero_id',
            'distrito_id',
            'municipio_id',
            'partido_id',
            'sujeto_id',
        ]);

        $this->resetValidation();
    }

    public function render()
    {
        $municipiosFormulario = collect();

        if ($this->distrito_id) {
            $municipiosFormulario = Municipio::query()
                ->where('distrito_id', $this->distrito_id)
                ->orderBy('nombre')
                ->get();
        }

        $sujetos = Sujeto::query()
            ->with(['municipio', 'partido'])
            ->when($this->buscar, function ($query) {
                $query->where(function ($q) {
                    $q->where('nombre', 'like', '%' . $this->buscar . '%')
                        ->orWhereHas('municipio', function ($municipio) {
                            $municipio->where('nombre', 'like', '%' . $this->buscar . '%');
                        })
                        ->orWhereHas('partido', function ($partido) {
                            $partido->where('nombre', 'like', '%' . $this->buscar . '%')
                                ->orWhere('siglas', 'like', '%' . $this->buscar . '%');
                        });
                });
            })
            ->orderBy('nombre')
            ->paginate($this->perPage);

return view('livewire.catalogos.sujetos.formulario', [
    'generos' => GeneroSujeto::orderBy('nombre')->get(),
    'distritos' => Distrito::orderBy('nombre')->get(),
    'municipiosFormulario' => $municipiosFormulario,
    'partidos' => Partido::whereIn('tipo', ['Partido', 'Asociación'])
        ->orderBy('nombre')
        ->get(),
    'sujetos' => $sujetos,
])->layout('layouts.app');
    }
}
