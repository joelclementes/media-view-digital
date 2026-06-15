<?php

namespace App\Livewire\Medios\Cine;

use App\Models\Cine;
use App\Models\Distrito;
use App\Models\Localidad;
use App\Models\MonitoreoMedioCine;
use App\Models\Municipio;
use App\Models\Partido;
use App\Models\Periodo;
use App\Models\Sujeto;
use App\Models\TipoEleccion;
use App\Models\ViolenciaTema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Formulario extends Component
{
    use WithFileUploads;
    use WithPagination;

    public string $tipo_medio = 'medios-cine';

    public string $fecha_inicio_registro = '';
    public string $fecha_fin_registro = '';
    public string $busqueda_tabla = '';
    public string $filtro_tipo_eleccion_id = '';
    public string $filtro_municipio_id = '';
    public int $cantidad_por_pagina = 10;
    public bool $mostrar_filtros_tabla = false;

    protected $paginationTheme = 'tailwind';

    public bool $mostrar_modal_eliminar = false;
    public ?int $registro_eliminar_id = null;
    public string $registro_eliminar_referencia = '';

    public string $busqueda_sujeto = '';
    public array $resultados_sujetos = [];
    public $sujeto_seleccionado = null;

    public ?int $sujeto_id = null;
    public ?int $organizacion_politica_id = null;
    public ?int $periodo_id = null;
    public ?string $etapa_sujeto = null;
    public ?int $tipo_eleccion_id = null;

    public array $etapas_sujeto = [
        'candidatura' => 'Candidatura',
        'precandidatura' => 'Precandidatura',
        'candidatura_independiente' => 'Candidatura independiente',
    ];

    public ?int $medio_cine_id = null;
    public ?int $medio_distrito_id = null;
    public ?int $medio_municipio_id = null;
    public ?int $medio_localidad_id = null;
    public string $medio_sala = '';

    public string $publicacion_fecha = '';
    public string $publicacion_hora = '';
    public ?int $publicacion_tiempo = null;

    public string $referencia = '';
    public string $observaciones = '';

    public array $archivos = [];
    public ?int $registro_editando_id = null;
    public array $archivos_existentes = [];
    public array $archivos_eliminados = [];

    public bool $mostrar_panel_cualitativo = false;
    public ?int $registro_cualitativo_id = null;
    public array $registro_cualitativo = [];
    public array $videos_cualitativos = [];

    public ?string $cuali_valoracion = null;
    public ?string $cuali_lenguaje_inclusivo = null;
    public ?string $cuali_estereotipo = null;
    public ?int $cuali_violencia_temas_id = null;
    public ?string $cuali_resumen = null;
    public ?string $cuali_criterio_evaluacion = null;

    public $partidos;
    public $periodos;
    public $tipos_eleccion;
    public $municipios;
    public $localidades;
    public $distritos;
    public $cines;
    public $violencia_temas;

    public array $valoraciones = ['Positiva', 'Negativa', 'Neutral'];
    public array $estereotipos = [
        'NA',
        'Personas indígenas',
        'Creencias religiosas de las personas',
        'Personas afroamericanas',
        'Personas de la diversidad sexual o de género',
        'Personas jóvenes',
        'Personas mayores',
        'Personas con discapacidad',
        'Personas que viven con VIH',
        'Víctimas del delito',
    ];
    public array $criterios_evaluacion = [
        'Presentación directa',
        'Cita y voz',
        'Cita y audio',
        'Solo cita',
        'Voz de las y los ciudadanos',
    ];

    public function mount(): void
    {
        $this->publicacion_fecha = now()->format('Y-m-d');
        $this->fecha_inicio_registro = now()->startOfMonth()->format('Y-m-d');
        $this->fecha_fin_registro = now()->format('Y-m-d');
        $this->cargarCatalogos();
    }

    public function render()
    {
        return view('livewire.medios.cine.formulario', [
            'registros' => $this->consultarRegistros(),
        ]);
    }

    public function cargarCatalogos(): void
    {
        $this->partidos = Partido::orderBy('nombre')->get();
        $this->periodos = Periodo::orderBy('nombre')->get();
        $this->tipos_eleccion = TipoEleccion::orderBy('nombre')->get();
        $this->municipios = Municipio::orderBy('nombre')->get();
        $this->localidades = collect();
        $this->distritos = Distrito::orderBy('nombre')->get();
        $this->cines = Cine::orderBy('nombre')->get();
        $this->violencia_temas = ViolenciaTema::orderBy('nombre')->get();
    }

    public function updatedMedioMunicipioId($value): void
    {
        $this->medio_localidad_id = null;
        $this->cargarLocalidadesPorMunicipio($value ? (int) $value : null);
    }

    private function cargarLocalidadesPorMunicipio(?int $municipio_id): void
    {
        $this->localidades = $municipio_id
            ? Localidad::where('municipio_id', $municipio_id)->orderBy('nombre')->get()
            : collect();
    }

    public function updatedBusquedaSujeto(): void
    {
        if (mb_strlen($this->busqueda_sujeto) < 2) {
            $this->resultados_sujetos = [];
            return;
        }

        $this->resultados_sujetos = Sujeto::where('nombre', 'like', '%' . $this->busqueda_sujeto . '%')
            ->orderBy('nombre')
            ->limit(10)
            ->get(['id', 'nombre'])
            ->toArray();
    }

    public function seleccionarSujeto(int $sujeto_id): void
    {
        $sujeto = Sujeto::with('partido')->find($sujeto_id);

        if (! $sujeto) {
            return;
        }

        $this->sujeto_seleccionado = $sujeto;
        $this->sujeto_id = $sujeto->id;
        $this->busqueda_sujeto = $sujeto->nombre;
        $this->resultados_sujetos = [];

        if ($sujeto->partido_id) {
            $this->organizacion_politica_id = $sujeto->partido_id;
        }
    }

    public function limpiarSujeto(): void
    {
        $this->reset([
            'busqueda_sujeto',
            'resultados_sujetos',
            'sujeto_seleccionado',
            'sujeto_id',
            'organizacion_politica_id',
            'periodo_id',
            'etapa_sujeto',
            'tipo_eleccion_id',
        ]);
    }

    protected function rules(): array
    {
        return [
            'sujeto_id' => 'required|exists:sujetos,id',
            'organizacion_politica_id' => 'nullable|exists:partidos,id',
            'periodo_id' => 'nullable|exists:periodos,id',
            'etapa_sujeto' => 'nullable|in:candidatura,precandidatura,candidatura_independiente',
            'tipo_eleccion_id' => 'nullable|exists:tipos_eleccion,id',

            'medio_cine_id' => 'required|exists:cines,id',
            'medio_distrito_id' => 'nullable|exists:distritos,id',
            'medio_municipio_id' => 'required|exists:municipios,id',
            'medio_localidad_id' => 'nullable|exists:localidades,id',
            'medio_sala' => 'nullable|string|max:255',

            'publicacion_fecha' => 'required|date',
            'publicacion_hora' => 'nullable|date_format:H:i',
            'publicacion_tiempo' => 'nullable|integer|min:1',

            'referencia' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string|max:1000',

            'archivos' => $this->registro_editando_id ? 'nullable|array' : 'required|array|min:1',
            'archivos.*' => 'file|max:51200|mimes:mp4,mov,avi,wmv,mkv',
        ];
    }

    public function guardar(): void
    {
        $datos = $this->validate();
        $datos['organizacion_id'] = $datos['organizacion_politica_id'] ?? null;
        unset($datos['organizacion_politica_id']);

        DB::transaction(function () use ($datos) {
            $registro = $this->registro_editando_id
                ? MonitoreoMedioCine::findOrFail($this->registro_editando_id)
                : MonitoreoMedioCine::create(array_merge($datos, [
                    'tipo_medio' => $this->tipo_medio,
                    'archivos' => null,
                ]));

            foreach ($this->archivos_eliminados as $ruta) {
                Storage::disk('public')->delete($ruta);
            }

            $rutas_nuevas = $this->guardarArchivosDelRegistro(
                $registro->id,
                count($this->archivos_existentes) + 1
            );

            $rutas_archivos = array_values(array_merge($this->archivos_existentes, $rutas_nuevas));

            $registro->update(array_merge($datos, [
                'tipo_medio' => $this->tipo_medio,
                'archivos' => $rutas_archivos,
            ]));
        });

        $mensaje = $this->registro_editando_id
            ? 'Registro actualizado correctamente.'
            : 'Registro de cine guardado correctamente.';

        $this->dispatch('cine-registro-guardado', datos: $this->datosParaRecuperar());
        $this->limpiarFormulario();
        session()->flash('success', $mensaje);
    }

    private function guardarArchivosDelRegistro(int $registro_id, int $consecutivo_inicial = 1): array
    {
        $rutas = [];

        foreach ($this->archivos as $indice => $archivo) {
            $consecutivo = str_pad((string) ($consecutivo_inicial + $indice), 2, '0', STR_PAD_LEFT);
            $extension = strtolower($archivo->getClientOriginalExtension() ?: 'mp4');
            $nombre = "cine_{$registro_id}_{$consecutivo}.{$extension}";
            $ruta = "medios/cine/{$nombre}";

            Storage::disk('public')->put($ruta, file_get_contents($archivo->getRealPath()));
            $rutas[] = $ruta;
        }

        return $rutas;
    }

    public function editar(int $id): void
    {
        $registro = MonitoreoMedioCine::findOrFail($id);

        $this->registro_editando_id = $registro->id;
        $this->sujeto_id = $registro->sujeto_id;
        $this->sujeto_seleccionado = Sujeto::find($registro->sujeto_id);
        $this->busqueda_sujeto = $this->sujeto_seleccionado?->nombre ?? '';
        $this->organizacion_politica_id = $registro->organizacion_id;
        $this->periodo_id = $registro->periodo_id;
        $this->etapa_sujeto = $registro->etapa_sujeto;
        $this->tipo_eleccion_id = $registro->tipo_eleccion_id;
        $this->medio_cine_id = $registro->medio_cine_id;
        $this->medio_distrito_id = $registro->medio_distrito_id;
        $this->medio_municipio_id = $registro->medio_municipio_id;
        $this->cargarLocalidadesPorMunicipio($registro->medio_municipio_id);
        $this->medio_localidad_id = $registro->medio_localidad_id;
        $this->medio_sala = $registro->medio_sala ?? '';
        $this->publicacion_fecha = optional($registro->publicacion_fecha)->format('Y-m-d') ?? '';
        $this->publicacion_hora = $registro->publicacion_hora ? date('H:i', strtotime((string) $registro->publicacion_hora)) : '';
        $this->publicacion_tiempo = $registro->publicacion_tiempo;
        $this->referencia = $registro->referencia ?? '';
        $this->observaciones = $registro->observaciones ?? '';
        $this->archivos_existentes = is_array($registro->archivos) ? $registro->archivos : [];
        $this->archivos = [];
        $this->archivos_eliminados = [];
        $this->mostrar_panel_cualitativo = false;

        $this->resetValidation();
    }

    public function limpiarFormulario(): void
    {
        $this->reset([
            'busqueda_sujeto',
            'resultados_sujetos',
            'sujeto_seleccionado',
            'sujeto_id',
            'organizacion_politica_id',
            'periodo_id',
            'etapa_sujeto',
            'tipo_eleccion_id',
            'medio_cine_id',
            'medio_distrito_id',
            'medio_municipio_id',
            'medio_localidad_id',
            'medio_sala',
            'publicacion_hora',
            'publicacion_tiempo',
            'referencia',
            'observaciones',
            'archivos',
            'registro_editando_id',
            'archivos_existentes',
            'archivos_eliminados',
        ]);

        $this->localidades = collect();
        $this->publicacion_fecha = now()->format('Y-m-d');
        $this->resetValidation();
    }

    public function eliminarArchivo(int $indice): void
    {
        unset($this->archivos[$indice]);
        $this->archivos = array_values($this->archivos);
    }

    public function eliminarArchivoExistente(int $indice): void
    {
        if (! isset($this->archivos_existentes[$indice])) {
            return;
        }

        $this->archivos_eliminados[] = $this->archivos_existentes[$indice];
        unset($this->archivos_existentes[$indice]);
        $this->archivos_existentes = array_values($this->archivos_existentes);
    }

    public function abrirCualitativos(int $id): void
    {
        $registro = MonitoreoMedioCine::query()
            ->leftJoin('sujetos', 'monitoreo_cine.sujeto_id', '=', 'sujetos.id')
            ->leftJoin('generos_sujetos', 'sujetos.genero_id', '=', 'generos_sujetos.id')
            ->leftJoin('partidos', 'monitoreo_cine.organizacion_id', '=', 'partidos.id')
            ->leftJoin('periodos', 'monitoreo_cine.periodo_id', '=', 'periodos.id')
            ->leftJoin('tipos_eleccion', 'monitoreo_cine.tipo_eleccion_id', '=', 'tipos_eleccion.id')
            ->leftJoin('cines', 'monitoreo_cine.medio_cine_id', '=', 'cines.id')
            ->leftJoin('distritos', 'monitoreo_cine.medio_distrito_id', '=', 'distritos.id')
            ->leftJoin('municipios', 'monitoreo_cine.medio_municipio_id', '=', 'municipios.id')
            ->leftJoin('localidades', 'monitoreo_cine.medio_localidad_id', '=', 'localidades.id')
            ->where('monitoreo_cine.id', $id)
            ->select([
                'monitoreo_cine.*',
                'sujetos.nombre as sujeto_nombre',
                'generos_sujetos.nombre as sujeto_genero',
                'partidos.nombre as organizacion_nombre',
                'periodos.nombre as periodo_nombre',
                'tipos_eleccion.nombre as tipo_eleccion_nombre',
                'cines.nombre as cine_nombre',
                'cines.nombre_cine as cine_nombre_comercial',
                'distritos.nombre as distrito_nombre',
                'municipios.nombre as municipio_nombre',
                'localidades.nombre as localidad_nombre',
            ])
            ->firstOrFail();

        $this->registro_cualitativo_id = $registro->id;
        $this->registro_cualitativo = $registro->toArray();
        $this->videos_cualitativos = is_array($registro->archivos) ? $registro->archivos : [];
        $this->cuali_valoracion = $registro->cuali_valoracion;
        $this->cuali_lenguaje_inclusivo = $registro->cuali_lenguaje_inclusivo;
        $this->cuali_estereotipo = $registro->cuali_estereotipo;
        $this->cuali_violencia_temas_id = $registro->cuali_violencia_temas_id;
        $this->cuali_resumen = $registro->cuali_resumen;
        $this->cuali_criterio_evaluacion = $registro->cuali_criterio_evaluacion;
        $this->mostrar_panel_cualitativo = true;
        $this->resetValidation();
    }

    public function guardarCualitativos(): void
    {
        $datos = $this->validate([
            'cuali_valoracion' => 'nullable|in:Positiva,Negativa,Neutral',
            'cuali_lenguaje_inclusivo' => 'nullable|in:Si,No',
            'cuali_estereotipo' => 'nullable|in:' . implode(',', $this->estereotipos),
            'cuali_violencia_temas_id' => 'nullable|exists:violencia_temas,id',
            'cuali_resumen' => 'nullable|string|max:255',
            'cuali_criterio_evaluacion' => 'nullable|in:' . implode(',', $this->criterios_evaluacion),
        ]);

        MonitoreoMedioCine::findOrFail($this->registro_cualitativo_id)->update($datos);
        $this->dispatch('cine-cualitativos-guardados', datos: $datos);
        $this->cerrarCualitativos();
        session()->flash('success', 'Datos cualitativos guardados correctamente.');
    }

    public function cerrarCualitativos(): void
    {
        $this->mostrar_panel_cualitativo = false;
        $this->registro_cualitativo_id = null;
        $this->registro_cualitativo = [];
        $this->videos_cualitativos = [];
    }

    public function confirmarEliminacion(int $id): void
    {
        $registro = MonitoreoMedioCine::findOrFail($id);
        $this->registro_eliminar_id = $registro->id;
        $this->registro_eliminar_referencia = 'Registro de cine #' . $registro->id;
        $this->mostrar_modal_eliminar = true;
    }

    public function cancelarEliminacion(): void
    {
        $this->mostrar_modal_eliminar = false;
        $this->registro_eliminar_id = null;
        $this->registro_eliminar_referencia = '';
    }

    public function eliminar(): void
    {
        $registro = MonitoreoMedioCine::findOrFail($this->registro_eliminar_id);

        foreach (($registro->archivos ?? []) as $ruta) {
            Storage::disk('public')->delete($ruta);
        }

        $registro->delete();
        $this->cancelarEliminacion();
        session()->flash('success', 'Registro eliminado correctamente.');
    }

    public function updatedFechaInicioRegistro(): void
    {
        $this->resetPage();
    }
    public function updatedFechaFinRegistro(): void
    {
        $this->resetPage();
    }
    public function updatedBusquedaTabla(): void
    {
        $this->resetPage();
    }
    public function updatedCantidadPorPagina(): void
    {
        $this->resetPage();
    }
    public function updatedFiltroTipoEleccionId(): void
    {
        $this->resetPage();
    }
    public function updatedFiltroMunicipioId(): void
    {
        $this->resetPage();
    }

    public function alternarFiltrosTabla(): void
    {
        $this->mostrar_filtros_tabla = ! $this->mostrar_filtros_tabla;
    }

    public function limpiarFiltrosTabla(): void
    {
        $this->fecha_inicio_registro = now()->startOfMonth()->format('Y-m-d');
        $this->fecha_fin_registro = now()->format('Y-m-d');
        $this->filtro_tipo_eleccion_id = '';
        $this->filtro_municipio_id = '';
        $this->busqueda_tabla = '';
        $this->cantidad_por_pagina = 10;
        $this->resetPage();
    }

    private function consultarRegistros()
    {
        return MonitoreoMedioCine::query()
            ->leftJoin('sujetos', 'monitoreo_cine.sujeto_id', '=', 'sujetos.id')
            ->leftJoin('partidos', 'monitoreo_cine.organizacion_id', '=', 'partidos.id')
            ->leftJoin('tipos_eleccion', 'monitoreo_cine.tipo_eleccion_id', '=', 'tipos_eleccion.id')
            ->leftJoin('cines', 'monitoreo_cine.medio_cine_id', '=', 'cines.id')
            ->leftJoin('municipios', 'monitoreo_cine.medio_municipio_id', '=', 'municipios.id')
            ->leftJoin('localidades', 'monitoreo_cine.medio_localidad_id', '=', 'localidades.id')
            ->when($this->fecha_inicio_registro, fn($query) => $query->whereDate('monitoreo_cine.publicacion_fecha', '>=', $this->fecha_inicio_registro))
            ->when($this->fecha_fin_registro, fn($query) => $query->whereDate('monitoreo_cine.publicacion_fecha', '<=', $this->fecha_fin_registro))
            ->when($this->filtro_tipo_eleccion_id, fn($query) => $query->where('monitoreo_cine.tipo_eleccion_id', $this->filtro_tipo_eleccion_id))
            ->when($this->filtro_municipio_id, fn($query) => $query->where('monitoreo_cine.medio_municipio_id', $this->filtro_municipio_id))
            ->when($this->busqueda_tabla, function ($query) {
                $busqueda = '%' . $this->busqueda_tabla . '%';
                $query->where(function ($subquery) use ($busqueda) {
                    $subquery->where('sujetos.nombre', 'like', $busqueda)
                        ->orWhere('partidos.nombre', 'like', $busqueda)
                        ->orWhere('cines.nombre', 'like', $busqueda)
                        ->orWhere('cines.nombre_cine', 'like', $busqueda)
                        ->orWhere('municipios.nombre', 'like', $busqueda)
                        ->orWhere('localidades.nombre', 'like', $busqueda)
                        ->orWhere('monitoreo_cine.medio_sala', 'like', $busqueda)
                        ->orWhere('monitoreo_cine.referencia', 'like', $busqueda);
                });
            })
            ->where('monitoreo_cine.tipo_medio', $this->tipo_medio)
            ->select([
                'monitoreo_cine.*',
                'sujetos.nombre as sujeto_nombre',
                'partidos.nombre as organizacion_nombre',
                'tipos_eleccion.nombre as tipo_eleccion_nombre',
                'cines.nombre as cine_nombre',
                'cines.nombre_cine as cine_nombre_comercial',
                'municipios.nombre as municipio_nombre',
                'localidades.nombre as localidad_nombre',
            ])
            ->orderByDesc('monitoreo_cine.publicacion_fecha')
            ->orderByDesc('monitoreo_cine.id')
            ->paginate($this->cantidad_por_pagina);
    }

    public function recuperarInfoAnterior(array $datos): void
    {
        $this->sujeto_id = ! empty($datos['sujeto_id']) ? (int) $datos['sujeto_id'] : null;

        $sujeto = $this->sujeto_id ? Sujeto::find($this->sujeto_id) : null;

        $this->sujeto_seleccionado = $sujeto;
        $this->busqueda_sujeto = $sujeto?->nombre ?? '';
        $this->resultados_sujetos = [];

        $this->organizacion_politica_id = ! empty($datos['organizacion_politica_id'])
            ? (int) $datos['organizacion_politica_id']
            : (! empty($datos['organizacion_id']) ? (int) $datos['organizacion_id'] : null);

        $this->periodo_id = ! empty($datos['periodo_id']) ? (int) $datos['periodo_id'] : null;
        $this->etapa_sujeto = $datos['etapa_sujeto'] ?? null;
        $this->tipo_eleccion_id = ! empty($datos['tipo_eleccion_id']) ? (int) $datos['tipo_eleccion_id'] : null;

        $this->medio_cine_id = ! empty($datos['medio_cine_id']) ? (int) $datos['medio_cine_id'] : null;
        $this->medio_distrito_id = ! empty($datos['medio_distrito_id']) ? (int) $datos['medio_distrito_id'] : null;
        $this->medio_municipio_id = ! empty($datos['medio_municipio_id']) ? (int) $datos['medio_municipio_id'] : null;

        $this->cargarLocalidadesPorMunicipio($this->medio_municipio_id);

        $this->medio_localidad_id = ! empty($datos['medio_localidad_id'])
            ? (int) $datos['medio_localidad_id']
            : null;

        $this->medio_sala = $datos['medio_sala'] ?? '';

        $this->publicacion_fecha = $datos['publicacion_fecha'] ?? now()->format('Y-m-d');
        $this->publicacion_hora = $datos['publicacion_hora'] ?? '';
        $this->publicacion_tiempo = ! empty($datos['publicacion_tiempo'])
            ? (int) $datos['publicacion_tiempo']
            : null;

        $this->referencia = $datos['referencia'] ?? '';
        $this->observaciones = $datos['observaciones'] ?? '';

        $this->archivos = [];
        $this->archivos_existentes = [];
        $this->archivos_eliminados = [];
        $this->registro_editando_id = null;

        $this->resetValidation();

        session()->flash('success', 'Información anterior recuperada. Debes seleccionar nuevamente el archivo.');
    }

    private function datosParaRecuperar(): array
    {
        return [
            'sujeto_id' => $this->sujeto_id,
            'organizacion_politica_id' => $this->organizacion_politica_id,
            'periodo_id' => $this->periodo_id,
            'etapa_sujeto' => $this->etapa_sujeto,
            'tipo_eleccion_id' => $this->tipo_eleccion_id,

            'medio_cine_id' => $this->medio_cine_id,
            'medio_distrito_id' => $this->medio_distrito_id,
            'medio_municipio_id' => $this->medio_municipio_id,
            'medio_localidad_id' => $this->medio_localidad_id,
            'medio_sala' => $this->medio_sala,

            'publicacion_fecha' => $this->publicacion_fecha,
            'publicacion_hora' => $this->publicacion_hora,
            'publicacion_tiempo' => $this->publicacion_tiempo,

            'referencia' => $this->referencia,
            'observaciones' => $this->observaciones,
        ];
    }

    public function recuperarDatosCualitativos(array $datos): void
    {
        $permitidos = [
            'cuali_valoracion',
            'cuali_lenguaje_inclusivo',
            'cuali_estereotipo',
            'cuali_violencia_temas_id',
            'cuali_resumen',
            'cuali_criterio_evaluacion',
        ];

        foreach ($permitidos as $campo) {
            if (array_key_exists($campo, $datos)) {
                $this->{$campo} = $datos[$campo];
            }
        }

        session()->flash('success', 'Datos cualitativos anteriores recuperados.');
    }
}
