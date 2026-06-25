<?php

namespace App\Livewire\Catalogos\Generos;

use App\Models\Genero;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class Formulario extends Component
{
    use WithPagination;

    public string $nombre = '';
    public string $medio = '';
    public string $buscar = '';
    public int $perPage = 10;

    public ?int $genero_id = null;
    public ?int $confirmingDeleteId = null;

    protected string $paginationTheme = 'tailwind';

    protected function rules(): array
    {
        return [
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('generos', 'nombre')
                    ->where(fn($query) => $query->where('medio', $this->medio))
                    ->ignore($this->genero_id),
            ],
            'medio' => ['required', Rule::in(['Electrónico', 'Impreso', 'N/A'])],
        ];
    }

    protected array $messages = [
        'nombre.required' => 'El nombre es obligatorio.',
        'nombre.max' => 'El nombre no debe exceder 255 caracteres.',
        'nombre.unique' => 'Este género ya existe para el medio seleccionado.',
        'medio.required' => 'Debes seleccionar el medio.',
        'medio.in' => 'El medio seleccionado no es válido.',
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

        Genero::create([
            'nombre' => trim($this->nombre),
            'medio' => $this->medio,
        ]);

        $this->limpiarFormulario();

        session()->flash('success', 'Género guardado correctamente.');
    }

    public function editar(int $id): void
    {
        $genero = Genero::findOrFail($id);

        $this->genero_id = $genero->id;
        $this->nombre = $genero->nombre;
        $this->medio = $genero->medio;

        $this->resetValidation();

        $this->dispatch('scroll-arriba');
    }

    public function actualizar(): void
    {
        $this->validate();

        $genero = Genero::findOrFail($this->genero_id);

        $genero->update([
            'nombre' => trim($this->nombre),
            'medio' => $this->medio,
        ]);

        $this->limpiarFormulario();

        session()->flash('success', 'Género actualizado correctamente.');
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

        if ($this->generoTieneRelaciones($this->confirmingDeleteId)) {
            $this->confirmingDeleteId = null;

            session()->flash('error', 'No se puede eliminar el género porque ya está relacionado con registros de monitoreo.');
            return;
        }

        Genero::findOrFail($this->confirmingDeleteId)->delete();

        if ($this->genero_id === $this->confirmingDeleteId) {
            $this->limpiarFormulario();
        }

        $this->confirmingDeleteId = null;

        session()->flash('success', 'Género eliminado correctamente.');
    }

    private function generoTieneRelaciones(int $generoId): bool
    {
        $relaciones = [
            [
                'tabla' => 'monitoreo_medios_electronicos',
                'columna' => 'genero_id',
            ],
            [
                'tabla' => 'monitoreo_medios_impresos',
                'columna' => 'publicacion_genero_id',
            ],
        ];

        foreach ($relaciones as $relacion) {
            if (
                Schema::hasTable($relacion['tabla']) &&
                Schema::hasColumn($relacion['tabla'], $relacion['columna']) &&
                DB::table($relacion['tabla'])->where($relacion['columna'], $generoId)->exists()
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
            'medio',
            'genero_id',
        ]);

        $this->resetValidation();
    }

    public function render()
    {
        $generos = Genero::query()
            ->when($this->buscar, function ($query) {
                $query->where(function ($q) {
                    $q->where('nombre', 'like', '%' . $this->buscar . '%')
                        ->orWhere('medio', 'like', '%' . $this->buscar . '%');
                });
            })
            ->orderBy('medio')
            ->orderBy('nombre')
            ->paginate($this->perPage);

        return view('livewire.catalogos.generos.formulario', [
            'generos' => $generos,
        ])->layout('layouts.app');
    }
}
