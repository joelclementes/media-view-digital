<?php

namespace App\Livewire\Catalogos\TamanosPublicacion;

use App\Models\TamanoPublicacion;
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

    public ?int $tamanoPublicacionId = null;
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
                Rule::unique('tamanos_publicacion', 'nombre')->ignore($this->tamanoPublicacionId),
            ],
        ];
    }

    protected array $messages = [
        'nombre.required' => 'El nombre es obligatorio.',
        'nombre.max' => 'El nombre no debe exceder 255 caracteres.',
        'nombre.unique' => 'Este tamaño de publicación ya existe.',
    ];

    public function guardar(): void
    {
        $this->validate();

        TamanoPublicacion::create([
            'nombre' => trim($this->nombre),
        ]);

        $this->limpiarFormulario();

        session()->flash('success', 'Tamaño de publicación guardado correctamente.');
    }

    public function editar(int $id): void
    {
        $tamanoPublicacion = TamanoPublicacion::findOrFail($id);

        $this->tamanoPublicacionId = $tamanoPublicacion->id;
        $this->nombre = $tamanoPublicacion->nombre;

        $this->resetValidation();
    }

    public function actualizar(): void
    {
        $this->validate();

        $tamanoPublicacion = TamanoPublicacion::findOrFail($this->tamanoPublicacionId);

        $tamanoPublicacion->update([
            'nombre' => trim($this->nombre),
        ]);

        $this->limpiarFormulario();

        session()->flash('success', 'Tamaño de publicación actualizado correctamente.');
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

        if ($this->tamanoPublicacionTieneRelaciones($this->confirmingDeleteId)) {
            $this->confirmingDeleteId = null;

            session()->flash('error', 'No se puede borrar porque este tamaño de publicación ya está relacionado con registros de monitoreo.');
            return;
        }

        TamanoPublicacion::findOrFail($this->confirmingDeleteId)->delete();

        if ($this->tamanoPublicacionId === $this->confirmingDeleteId) {
            $this->limpiarFormulario();
        }

        $this->confirmingDeleteId = null;

        session()->flash('success', 'Tamaño de publicación eliminado correctamente.');
    }

    private function tamanoPublicacionTieneRelaciones(int $tamanoPublicacionId): bool
    {
        $relaciones = [
            [
                'tabla' => 'monitoreo_medios_electronicos',
                'columna' => 'tamano_id',
            ],
            [
                'tabla' => 'monitoreo_medios_impresos',
                'columna' => 'publicacion_tamano_id',
            ],
        ];

        foreach ($relaciones as $relacion) {
            if (
                Schema::hasTable($relacion['tabla']) &&
                Schema::hasColumn($relacion['tabla'], $relacion['columna']) &&
                DB::table($relacion['tabla'])
                    ->where($relacion['columna'], $tamanoPublicacionId)
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
            'tamanoPublicacionId',
        ]);

        $this->resetValidation();
    }

    public function render()
    {
        $tamanosPublicacion = TamanoPublicacion::query()
            ->when($this->buscar, function ($query) {
                $query->where('nombre', 'like', '%' . $this->buscar . '%');
            })
            ->orderBy('nombre')
            ->paginate($this->perPage);

        return view('livewire.catalogos.tamanos-publicacion.formulario', [
            'tamanosPublicacion' => $tamanosPublicacion,
        ])->layout('layouts.app');
    }
}