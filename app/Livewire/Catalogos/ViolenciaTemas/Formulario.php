<?php

namespace App\Livewire\Catalogos\ViolenciaTemas;

use App\Models\ViolenciaTema;
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

    public ?int $violenciaTemaId = null;
    public ?int $confirmingDeleteId = null;

    protected string $paginationTheme = 'tailwind';

    protected function rules(): array
    {
        return [
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('violencia_temas', 'nombre')->ignore($this->violenciaTemaId),
            ],
        ];
    }

    protected array $messages = [
        'nombre.required' => 'El nombre es obligatorio.',
        'nombre.max' => 'El nombre no debe exceder 255 caracteres.',
        'nombre.unique' => 'Este tema de violencia ya existe.',
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

        ViolenciaTema::create([
            'nombre' => trim($this->nombre),
        ]);

        $this->limpiarFormulario();

        session()->flash('success', 'Tema de violencia guardado correctamente.');
    }

    public function editar(int $id): void
    {
        $violenciaTema = ViolenciaTema::findOrFail($id);

        $this->violenciaTemaId = $violenciaTema->id;
        $this->nombre = $violenciaTema->nombre;

        $this->resetValidation();

        $this->dispatch('scroll-arriba');
    }

    public function actualizar(): void
    {
        $this->validate();

        $violenciaTema = ViolenciaTema::findOrFail($this->violenciaTemaId);

        $violenciaTema->update([
            'nombre' => trim($this->nombre),
        ]);

        $this->limpiarFormulario();

        session()->flash('success', 'Tema de violencia actualizado correctamente.');
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

        if ($this->violenciaTemaTieneRelaciones($this->confirmingDeleteId)) {
            $this->confirmingDeleteId = null;

            session()->flash('error', 'No se puede eliminar el tema de violencia porque ya está relacionado con registros de monitoreo.');
            return;
        }

        ViolenciaTema::findOrFail($this->confirmingDeleteId)->delete();

        if ($this->violenciaTemaId === $this->confirmingDeleteId) {
            $this->limpiarFormulario();
        }

        $this->confirmingDeleteId = null;

        session()->flash('success', 'Tema de violencia eliminado correctamente.');
    }

    private function violenciaTemaTieneRelaciones(int $violenciaTemaId): bool
    {
        $relaciones = [
            'monitoreo_medios_electronicos',
            'monitoreo_medios_impresos',
            'monitoreo_medio_radios',
            'monitoreo_medio_soportes_promocionales',
            'propaganda_movil',
            'medio_television',
            'monitoreo_medio_cine',
        ];

        foreach ($relaciones as $tabla) {
            if (
                Schema::hasTable($tabla) &&
                Schema::hasColumn($tabla, 'cuali_violencia_temas_id') &&
                DB::table($tabla)
                    ->where('cuali_violencia_temas_id', $violenciaTemaId)
                    ->exists()
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
            'violenciaTemaId',
        ]);

        $this->resetValidation();
    }

    public function render()
    {
        $violenciaTemas = ViolenciaTema::query()
            ->when($this->buscar, function ($query) {
                $query->where('nombre', 'like', '%' . $this->buscar . '%');
            })
            ->orderBy('nombre')
            ->paginate($this->perPage);

        return view('livewire.catalogos.violencia-temas.formulario', [
            'violenciaTemas' => $violenciaTemas,
        ])->layout('layouts.app');
    }
}