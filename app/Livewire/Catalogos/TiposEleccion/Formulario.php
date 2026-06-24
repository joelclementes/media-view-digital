<?php

namespace App\Livewire\Catalogos\TiposEleccion;

use App\Models\TipoEleccion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class Formulario extends Component
{
    use WithPagination;

    public string $nombre = '';
    public string $buscar = '';
    public int $perPage = 10;

    public ?int $tipoEleccionId = null;
    public ?int $confirmingDeleteId = null;

    protected string $paginationTheme = 'tailwind';

    public function updatedBuscar(): void
    {
        $this->resetPage();
    }

    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    protected function rules(): array
    {
        return [
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tipos_eleccion', 'nombre')->ignore($this->tipoEleccionId),
            ],
        ];
    }

    protected array $messages = [
        'nombre.required' => 'El nombre es obligatorio.',
        'nombre.max' => 'El nombre no debe exceder 255 caracteres.',
        'nombre.unique' => 'Este tipo de elección ya existe.',
    ];

    public function guardar(): void
    {
        $this->validate();

        TipoEleccion::create([
            'nombre' => trim($this->nombre),
        ]);

        $this->limpiarFormulario();

        session()->flash('success', 'Tipo de elección guardado correctamente.');
    }

    public function editar(int $id): void
    {
        $tipoEleccion = TipoEleccion::findOrFail($id);

        $this->tipoEleccionId = $tipoEleccion->id;
        $this->nombre = $tipoEleccion->nombre;

        $this->resetValidation();
    }

    public function actualizar(): void
    {
        $this->validate();

        $tipoEleccion = TipoEleccion::findOrFail($this->tipoEleccionId);

        $tipoEleccion->update([
            'nombre' => trim($this->nombre),
        ]);

        $this->limpiarFormulario();

        session()->flash('success', 'Tipo de elección actualizado correctamente.');
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

        if ($this->tipoEleccionTieneRelaciones($this->confirmingDeleteId)) {
            $this->confirmingDeleteId = null;

            session()->flash('error', 'No se puede borrar porque este tipo de elección ya está relacionado con registros de monitoreo.');
            return;
        }

        TipoEleccion::findOrFail($this->confirmingDeleteId)->delete();

        if ($this->tipoEleccionId === $this->confirmingDeleteId) {
            $this->limpiarFormulario();
        }

        $this->confirmingDeleteId = null;

        session()->flash('success', 'Tipo de elección eliminado correctamente.');
    }

    private function tipoEleccionTieneRelaciones(int $tipoEleccionId): bool
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
                Schema::hasColumn($tabla, 'tipo_eleccion_id') &&
                DB::table($tabla)->where('tipo_eleccion_id', $tipoEleccionId)->exists()
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
            'tipoEleccionId',
        ]);

        $this->resetValidation();
    }

    public function render()
    {
        $tiposEleccion = TipoEleccion::query()
            ->when($this->buscar, function ($query) {
                $query->where('nombre', 'like', '%' . $this->buscar . '%');
            })
            ->orderBy('nombre')
            ->paginate($this->perPage);

        return view('livewire.catalogos.tipos-eleccion.formulario', [
            'tiposEleccion' => $tiposEleccion,
        ])->layout('layouts.app');
    }
}
