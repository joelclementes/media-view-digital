<?php

namespace App\Livewire\Catalogos\Periodos;

use App\Models\Periodo;
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

    public ?int $periodoId = null;
    public ?int $confirmingDeleteId = null;

    protected string $paginationTheme = 'tailwind';

    protected function rules(): array
    {
        return [
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('periodos', 'nombre')->ignore($this->periodoId),
            ],
        ];
    }

    protected array $messages = [
        'nombre.required' => 'El nombre es obligatorio.',
        'nombre.max' => 'El nombre no debe exceder 255 caracteres.',
        'nombre.unique' => 'Este periodo ya existe.',
    ];

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

        Periodo::create([
            'nombre' => trim($this->nombre),
        ]);

        $this->limpiarFormulario();

        session()->flash('success', 'Periodo guardado correctamente.');
    }

    public function editar(int $id): void
    {
        $periodo = Periodo::findOrFail($id);

        $this->periodoId = $periodo->id;
        $this->nombre = $periodo->nombre;

        $this->resetValidation();

        $this->dispatch('scroll-arriba');
    }

    public function actualizar(): void
    {
        $this->validate();

        $periodo = Periodo::findOrFail($this->periodoId);

        $periodo->update([
            'nombre' => trim($this->nombre),
        ]);

        $this->limpiarFormulario();

        session()->flash('success', 'Periodo actualizado correctamente.');
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
        if (! $this->confirmingDeleteId) {
            return;
        }

        if ($this->periodoTieneRelaciones($this->confirmingDeleteId)) {
            $this->confirmingDeleteId = null;

            session()->flash('error', 'No se puede eliminar el periodo porque ya está relacionado con registros de monitoreo.');
            return;
        }

        Periodo::findOrFail($this->confirmingDeleteId)->delete();

        if ($this->periodoId === $this->confirmingDeleteId) {
            $this->limpiarFormulario();
        }

        $this->confirmingDeleteId = null;

        session()->flash('success', 'Periodo eliminado correctamente.');
    }

    private function periodoTieneRelaciones(int $periodoId): bool
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
                Schema::hasColumn($tabla, 'periodo_id') &&
                DB::table($tabla)->where('periodo_id', $periodoId)->exists()
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
            'periodoId',
        ]);

        $this->resetValidation();
    }

    public function render()
    {
        $periodos = Periodo::query()
            ->when($this->buscar, function ($query) {
                $query->where('nombre', 'like', '%' . $this->buscar . '%');
            })
            ->orderBy('nombre')
            ->paginate($this->perPage);

        return view('livewire.catalogos.periodos.formulario', [
            'periodos' => $periodos,
        ])->layout('layouts.app');
    }
}
