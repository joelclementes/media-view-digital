<?php

namespace App\Livewire\AsignacionPortales;

use App\Models\CapturistaPortalInternet;
use App\Models\PortalInternet;
use App\Models\User;
use Livewire\Component;

class AsignacionPortales extends Component
{
    public $capturista_id = '';
    public $portales = [];
    public $portalesSeleccionados = [];
    public $asignacionesActuales = [];
    public $portalesAsignadosOtros = [];

    public function mount(): void
    {
        $this->portales = collect();
        $this->asignacionesActuales = collect();
        $this->portalesAsignadosOtros = collect();
    }

    public function updatedCapturistaId($value): void
    {
        $this->cargarPortales($value);
    }

    public function updatedPortalesSeleccionados(): void
    {
        if (empty($this->capturista_id)) {
            return;
        }

        $this->guardarAsignaciones();
    }

    public function cargarPortales($capturistaId): void
    {
        if (empty($capturistaId)) {
            $this->reset([
                'portales',
                'asignacionesActuales',
                'portalesSeleccionados',
                'portalesAsignadosOtros',
            ]);

            return;
        }

        try {
            $this->portalesAsignadosOtros = CapturistaPortalInternet::query()
                ->where('user_id', '!=', $capturistaId)
                ->with(['portal', 'capturista'])
                ->get();

            $portalesAsignadosOtrosIds = $this->portalesAsignadosOtros
                ->pluck('portal_internet_id')
                ->toArray();

            $todosLosPortales = PortalInternet::query()
                ->where('tipo', 'Portal')
                ->orderBy('url')
                ->get();

            $this->portales = $todosLosPortales->map(function ($portal) use ($portalesAsignadosOtrosIds) {
                $portal->asignado_a_otro = in_array($portal->id, $portalesAsignadosOtrosIds);

                return $portal;
            });

            $this->asignacionesActuales = CapturistaPortalInternet::query()
                ->where('user_id', $capturistaId)
                ->with('portal')
                ->get();

            $this->portalesSeleccionados = $this->asignacionesActuales
                ->pluck('portal_internet_id')
                ->map(fn ($id) => (string) $id)
                ->toArray();
        } catch (\Exception $e) {
            session()->flash('error', 'Error al cargar las páginas: ' . $e->getMessage());

            $this->reset([
                'portales',
                'asignacionesActuales',
                'portalesSeleccionados',
                'portalesAsignadosOtros',
            ]);
        }
    }

    public function seleccionarTodos(): void
    {
        if (empty($this->capturista_id)) {
            return;
        }

        $this->portalesSeleccionados = $this->portales
            ->filter(fn ($portal) => ! $portal->asignado_a_otro)
            ->pluck('id')
            ->map(fn ($id) => (string) $id)
            ->toArray();

        $this->guardarAsignaciones();
    }

    public function deseleccionarTodos(): void
    {
        if (empty($this->capturista_id)) {
            return;
        }

        $this->portalesSeleccionados = [];

        $this->guardarAsignaciones();
    }

    protected function guardarAsignaciones(): void
    {
        try {
            $portalesAsignadosOtrosIds = CapturistaPortalInternet::query()
                ->where('user_id', '!=', $this->capturista_id)
                ->pluck('portal_internet_id')
                ->toArray();

            $conflictos = array_intersect($this->portalesSeleccionados, $portalesAsignadosOtrosIds);

            if (! empty($conflictos)) {
                $this->portalesSeleccionados = array_values(
                    array_diff($this->portalesSeleccionados, $conflictos)
                );

                $urlsConflictos = PortalInternet::query()
                    ->whereIn('id', $conflictos)
                    ->pluck('url')
                    ->implode(', ');

                session()->flash(
                    'error',
                    "No se pueden asignar las siguientes páginas porque ya pertenecen a otro capturista: {$urlsConflictos}"
                );

                return;
            }

            CapturistaPortalInternet::query()
                ->where('user_id', $this->capturista_id)
                ->delete();

            foreach ($this->portalesSeleccionados as $portalId) {
                CapturistaPortalInternet::create([
                    'user_id' => $this->capturista_id,
                    'portal_internet_id' => $portalId,
                ]);
            }

            $this->asignacionesActuales = CapturistaPortalInternet::query()
                ->where('user_id', $this->capturista_id)
                ->with('portal')
                ->get();

            $this->cargarPortales($this->capturista_id);

            $this->dispatch('asignacion-guardada');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al guardar las asignaciones: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $capturistas = User::query()
            ->whereHas('roles', function ($query) {
                $query->where('name', 'like', 'cap%');
            })
            ->orderBy('name')
            ->get();

        return view('livewire.asignacion_portales.asignacion-portales', [
            'capturistas' => $capturistas,
        ]);
    }
}