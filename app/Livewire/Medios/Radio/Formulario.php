<?php

namespace App\Livewire\Medios\Radio;

use App\Models\GeneroSujeto;
use App\Models\MonitoreoMedioRadio;
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
use Illuminate\Support\Facades\Auth;

class Formulario extends Component
{
    use WithFileUploads;
    use WithPagination;

    public string $tipo_medio = 'medios-radio';

    public string $fecha_inicio_registro = '';
    public string $fecha_fin_registro = '';
    public string $busqueda_tabla = '';
    public string $filtro_tipo_eleccion_id = '';
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

    public string $medio_nombre = '';
    public string $medio_siglas = '';
    public string $medio_banda = '';
    public string $medio_grupo_radiofonico = '';
    public ?int $medio_municipio_id = null;
    public string $medio_cobertura = '';

    public string $publicacion_fecha = '';
    public string $publicacion_hora = '';
    public ?int $publicacion_tiempo = null;
    public string $publicacion_tipo = '';
    public string $publicacion_ubicacion = '';
    public string $publicacion_modalidad = '';

    public ?int $genero_autor_id = null;
    public string $nombre_autor = '';
    public string $observaciones = '';

    public array $archivos = [];
    public ?int $registro_editando_id = null;
    public array $archivos_existentes = [];
    public array $archivos_eliminados = [];

    public bool $mostrar_panel_cualitativo = false;
    public ?int $registro_cualitativo_id = null;
    public array $registro_cualitativo = [];
    public array $audios_cualitativos = [];

    public ?string $cuali_valoracion = null;
    public ?string $cuali_lenguaje_inclusivo = null;
    public ?string $cuali_estereotipo = null;
    public ?int $cuali_violencia_temas_id = null;
    public ?int $cuali_tipos_eleccion_id = null;
    public ?string $cuali_resumen = null;
    public ?string $cuali_criterio_evaluacion = null;

    public $partidos;
    public $periodos;
    public $tipos_eleccion;
    public $municipios;
    public $generos_sujeto;
    public $violencia_temas;

    public array $coberturas = ['Nacional', 'Regional', 'Local'];
    public array $tipos_publicacion = ['Nota informativa', 'Nota periodística', 'Entrevista', 'Reportaje'];
    public array $ubicaciones_publicacion = ['Al inicio', 'En el desarrollo', 'Al final'];
    public array $modalidades_publicacion = ['Política', 'Electoral'];
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
        return view('livewire.medios.radio.formulario', [
            'registros' => $this->consultarRegistros(),
        ]);
    }

    public function cargarCatalogos(): void
    {
        $this->partidos = Partido::orderBy('nombre')->get();
        $this->periodos = Periodo::orderBy('nombre')->get();
        $this->tipos_eleccion = TipoEleccion::orderBy('nombre')->get();
        $this->municipios = Municipio::orderBy('nombre')->get();
        $this->generos_sujeto = GeneroSujeto::orderBy('nombre')->get();
        $this->violencia_temas = ViolenciaTema::orderBy('nombre')->get();
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

            'medio_nombre' => 'required|string|max:255',
            'medio_siglas' => 'nullable|string|max:255',
            'medio_banda' => 'nullable|string|max:255',
            'medio_grupo_radiofonico' => 'nullable|string|max:255',
            'medio_municipio_id' => 'nullable|exists:municipios,id',
            'medio_cobertura' => 'nullable|in:Nacional,Regional,Local',

            'publicacion_fecha' => 'required|date',
            'publicacion_hora' => 'nullable|date_format:H:i',
            'publicacion_tiempo' => 'nullable|integer|min:1',
            'publicacion_tipo' => 'required|in:Nota informativa,Nota periodística,Entrevista,Reportaje',
            'publicacion_ubicacion' => 'required|in:Al inicio,En el desarrollo,Al final',
            'publicacion_modalidad' => 'required|in:Política,Electoral',

            'genero_autor_id' => 'required|exists:generos_sujetos,id',
            'nombre_autor' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string|max:255',

            'archivos' => $this->registro_editando_id ? 'nullable|array' : 'required|array|min:1',
            'archivos.*' => 'file|max:20480|mimes:mp3',
        ];
    }

    // public function guardar(): void
    // {
    //     $datos = $this->validate();

    //     DB::transaction(function () use ($datos) {
    //         $registro = $this->registro_editando_id
    //             ? MonitoreoMedioRadio::findOrFail($this->registro_editando_id)
    //             : MonitoreoMedioRadio::create(array_merge($datos, [
    //                 'tipo_medio' => $this->tipo_medio,
    //                 'archivos' => null,
    //             ]));

    //         foreach ($this->archivos_eliminados as $ruta) {
    //             Storage::disk('public')->delete($ruta);
    //         }

    //         $rutas_nuevas = $this->guardarArchivosDelRegistro(
    //             $registro->id,
    //             count($this->archivos_existentes) + 1
    //         );

    //         $rutas_archivos = array_values(array_merge(
    //             $this->archivos_existentes,
    //             $rutas_nuevas
    //         ));

    //         $registro->update(array_merge($datos, [
    //             'tipo_medio' => $this->tipo_medio,
    //             'archivos' => $rutas_archivos,
    //         ]));
    //     });

    //     $mensaje = $this->registro_editando_id
    //         ? 'Registro actualizado correctamente.'
    //         : 'Registro de radio guardado correctamente.';

    //     $this->dispatch('radio-registro-guardado', datos: $this->datosParaRecuperar());
    //     $this->limpiarFormulario();
    //     session()->flash('success', $mensaje);
    // }

    public function guardar(): void
    {
        $datos = $this->validate();

        DB::transaction(function () use ($datos) {
            if ($this->registro_editando_id) {
                $registro = MonitoreoMedioRadio::findOrFail($this->registro_editando_id);
            } else {
                $registro = MonitoreoMedioRadio::create(array_merge($datos, [
                    'tipo_medio' => $this->tipo_medio,
                    'usuario1_id' => Auth::id(),
                    'archivos' => null,
                ]));
            }

            foreach ($this->archivos_eliminados as $ruta) {
                Storage::disk('public')->delete($ruta);
            }

            $rutas_nuevas = $this->guardarArchivosDelRegistro(
                $registro->id,
                count($this->archivos_existentes) + 1
            );

            $rutas_archivos = array_values(array_merge(
                $this->archivos_existentes,
                $rutas_nuevas
            ));

            $datosActualizar = array_merge($datos, [
                'tipo_medio' => $this->tipo_medio,
                'archivos' => $rutas_archivos,
            ]);

            if (! $this->registro_editando_id) {
                $datosActualizar['usuario1_id'] = Auth::id();
            }

            $registro->update($datosActualizar);
        });

        $mensaje = $this->registro_editando_id
            ? 'Registro actualizado correctamente.'
            : 'Registro de radio guardado correctamente.';

        $this->dispatch('radio-registro-guardado', datos: $this->datosParaRecuperar());
        $this->limpiarFormulario();
        session()->flash('success', $mensaje);
    }

    private function guardarArchivosDelRegistro(int $registro_id, int $consecutivo_inicial = 1): array
    {
        $rutas = [];

        foreach ($this->archivos as $indice => $archivo) {
            $consecutivo = str_pad((string) ($consecutivo_inicial + $indice), 2, '0', STR_PAD_LEFT);
            $nombre = "radio_{$registro_id}_{$consecutivo}.mp3";
            $ruta = "medios/radio/{$nombre}";

            Storage::disk('public')->put($ruta, file_get_contents($archivo->getRealPath()));
            $rutas[] = $ruta;
        }

        return $rutas;
    }

    public function editar(int $id): void
    {
        $registro = MonitoreoMedioRadio::findOrFail($id);

        $this->registro_editando_id = $registro->id;
        $this->sujeto_id = $registro->sujeto_id;
        $this->sujeto_seleccionado = Sujeto::find($registro->sujeto_id);
        $this->busqueda_sujeto = $this->sujeto_seleccionado?->nombre ?? '';
        $this->organizacion_politica_id = $registro->organizacion_politica_id;
        $this->periodo_id = $registro->periodo_id;
        $this->etapa_sujeto = $registro->etapa_sujeto;
        $this->tipo_eleccion_id = $registro->tipo_eleccion_id;
        $this->medio_nombre = $registro->medio_nombre ?? '';
        $this->medio_siglas = $registro->medio_siglas ?? '';
        $this->medio_banda = $registro->medio_banda ?? '';
        $this->medio_grupo_radiofonico = $registro->medio_grupo_radiofonico ?? '';
        $this->medio_municipio_id = $registro->medio_municipio_id;
        $this->medio_cobertura = $registro->medio_cobertura ?? '';
        $this->publicacion_fecha = optional($registro->publicacion_fecha)->format('Y-m-d') ?? '';
        $this->publicacion_hora = $registro->publicacion_hora ? date('H:i', strtotime((string) $registro->publicacion_hora)) : '';
        $this->publicacion_tiempo = $registro->publicacion_tiempo;
        $this->publicacion_tipo = $registro->publicacion_tipo ?? '';
        $this->publicacion_ubicacion = $registro->publicacion_ubicacion ?? '';
        $this->publicacion_modalidad = $registro->publicacion_modalidad ?? '';
        $this->genero_autor_id = $registro->genero_autor_id;
        $this->nombre_autor = $registro->nombre_autor ?? '';
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
            'medio_nombre',
            'medio_siglas',
            'medio_banda',
            'medio_grupo_radiofonico',
            'medio_municipio_id',
            'medio_cobertura',
            'publicacion_hora',
            'publicacion_tiempo',
            'publicacion_tipo',
            'publicacion_ubicacion',
            'publicacion_modalidad',
            'genero_autor_id',
            'nombre_autor',
            'observaciones',
            'archivos',
            'registro_editando_id',
            'archivos_existentes',
            'archivos_eliminados',
        ]);

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
        $registro = MonitoreoMedioRadio::query()
            ->leftJoin('sujetos', 'monitoreo_radio.sujeto_id', '=', 'sujetos.id')
            ->leftJoin('generos_sujetos', 'sujetos.genero_id', '=', 'generos_sujetos.id')
            ->leftJoin('partidos', 'monitoreo_radio.organizacion_politica_id', '=', 'partidos.id')
            ->leftJoin('periodos', 'monitoreo_radio.periodo_id', '=', 'periodos.id')
            ->leftJoin('tipos_eleccion', 'monitoreo_radio.tipo_eleccion_id', '=', 'tipos_eleccion.id')
            ->leftJoin('municipios', 'monitoreo_radio.medio_municipio_id', '=', 'municipios.id')
            ->where('monitoreo_radio.id', $id)
            ->select([
                'monitoreo_radio.*',
                'sujetos.nombre as sujeto_nombre',
                'generos_sujetos.nombre as sujeto_genero',
                'partidos.nombre as organizacion_nombre',
                'periodos.nombre as periodo_nombre',
                'tipos_eleccion.nombre as tipo_eleccion_nombre',
                'municipios.nombre as municipio_nombre',
            ])
            ->firstOrFail();

        $this->registro_cualitativo_id = $registro->id;
        $this->registro_cualitativo = $registro->toArray();
        $this->audios_cualitativos = is_array($registro->archivos) ? $registro->archivos : [];
        $this->cuali_valoracion = $registro->cuali_valoracion;
        $this->cuali_lenguaje_inclusivo = $registro->cuali_lenguaje_inclusivo;
        $this->cuali_estereotipo = $registro->cuali_estereotipo;
        $this->cuali_violencia_temas_id = $registro->cuali_violencia_temas_id;
        $this->cuali_tipos_eleccion_id = $registro->cuali_tipos_eleccion_id;
        $this->cuali_resumen = $registro->cuali_resumen;
        $this->cuali_criterio_evaluacion = $registro->cuali_criterio_evaluacion;
        $this->mostrar_panel_cualitativo = true;
        $this->resetValidation();
    }

    // public function guardarCualitativos(): void
    // {
    //     $datos = $this->validate([
    //         'cuali_valoracion' => 'nullable|in:Positiva,Negativa,Neutral',
    //         'cuali_lenguaje_inclusivo' => 'nullable|in:Si,No',
    //         'cuali_estereotipo' => 'nullable|in:' . implode(',', $this->estereotipos),
    //         'cuali_violencia_temas_id' => 'nullable|exists:violencia_temas,id',
    //         'cuali_tipos_eleccion_id' => 'nullable|exists:tipos_eleccion,id',
    //         'cuali_resumen' => 'nullable|string|max:65535',
    //         'cuali_criterio_evaluacion' => 'nullable|in:' . implode(',', $this->criterios_evaluacion),
    //     ]);

    //     MonitoreoMedioRadio::findOrFail($this->registro_cualitativo_id)->update($datos);
    //     $this->dispatch('radio-cualitativos-guardados', datos: $datos);
    //     $this->cerrarCualitativos();
    //     session()->flash('success', 'Datos cualitativos guardados correctamente.');
    // }

    public function guardarCualitativos(): void
    {
        $datos = $this->validate([
            'cuali_valoracion' => 'nullable|in:Positiva,Negativa,Neutral',
            'cuali_lenguaje_inclusivo' => 'nullable|in:Si,No',
            'cuali_estereotipo' => 'nullable|in:' . implode(',', $this->estereotipos),
            'cuali_violencia_temas_id' => 'nullable|exists:violencia_temas,id',
            'cuali_tipos_eleccion_id' => 'nullable|exists:tipos_eleccion,id',
            'cuali_resumen' => 'nullable|string|max:65535',
            'cuali_criterio_evaluacion' => 'nullable|in:' . implode(',', $this->criterios_evaluacion),
        ]);

        $datos['usuario2_id'] = Auth::id();

        MonitoreoMedioRadio::findOrFail($this->registro_cualitativo_id)
            ->update($datos);

        $this->dispatch('radio-cualitativos-guardados', datos: $datos);
        $this->cerrarCualitativos();

        session()->flash('success', 'Datos cualitativos guardados correctamente.');
    }

    public function cerrarCualitativos(): void
    {
        $this->mostrar_panel_cualitativo = false;
        $this->registro_cualitativo_id = null;
        $this->registro_cualitativo = [];
        $this->audios_cualitativos = [];
    }

    public function confirmarEliminacion(int $id): void
    {
        $registro = MonitoreoMedioRadio::findOrFail($id);
        $this->registro_eliminar_id = $registro->id;
        $this->registro_eliminar_referencia = $registro->medio_nombre ?: 'Registro #' . $registro->id;
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
        $registro = MonitoreoMedioRadio::findOrFail($this->registro_eliminar_id);

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

    public function alternarFiltrosTabla(): void
    {
        $this->mostrar_filtros_tabla = ! $this->mostrar_filtros_tabla;
    }

    public function limpiarFiltrosTabla(): void
    {
        $this->fecha_inicio_registro = now()->startOfMonth()->format('Y-m-d');
        $this->fecha_fin_registro = now()->format('Y-m-d');
        $this->filtro_tipo_eleccion_id = '';
        $this->busqueda_tabla = '';
        $this->cantidad_por_pagina = 10;
        $this->resetPage();
    }

    // private function consultarRegistros()
    // {
    //     return MonitoreoMedioRadio::query()
    //         ->leftJoin('sujetos', 'monitoreo_radio.sujeto_id', '=', 'sujetos.id')
    //         ->leftJoin('partidos', 'monitoreo_radio.organizacion_politica_id', '=', 'partidos.id')
    //         ->leftJoin('municipios', 'monitoreo_radio.medio_municipio_id', '=', 'municipios.id')
    //         ->select([
    //             'monitoreo_radio.id',
    //             'monitoreo_radio.medio_nombre',
    //             'monitoreo_radio.medio_siglas',
    //             'monitoreo_radio.publicacion_fecha',
    //             'monitoreo_radio.publicacion_hora',
    //             'monitoreo_radio.publicacion_tipo',
    //             'monitoreo_radio.archivos',
    //             'monitoreo_radio.created_at',
    //             'sujetos.nombre as sujeto_nombre',
    //             'partidos.nombre as organizacion_nombre',
    //             'municipios.nombre as municipio_nombre',
    //         ])
    //         ->where('monitoreo_radio.tipo_medio', $this->tipo_medio)
    //         ->when($this->fecha_inicio_registro, fn($q) => $q->whereDate('monitoreo_radio.created_at', '>=', $this->fecha_inicio_registro))
    //         ->when($this->fecha_fin_registro, fn($q) => $q->whereDate('monitoreo_radio.created_at', '<=', $this->fecha_fin_registro))
    //         ->when($this->filtro_tipo_eleccion_id !== '', fn($q) => $q->where('monitoreo_radio.tipo_eleccion_id', $this->filtro_tipo_eleccion_id))
    //         ->when($this->busqueda_tabla, function ($query) {
    //             $busqueda = '%' . trim($this->busqueda_tabla) . '%';

    //             $query->where(function ($q) use ($busqueda) {
    //                 $q->where('monitoreo_radio.id', 'like', $busqueda)
    //                     ->orWhere('monitoreo_radio.observaciones', 'like', $busqueda)
    //                     ->orWhere('monitoreo_radio.medio_nombre', 'like', $busqueda)
    //                     ->orWhere('monitoreo_radio.medio_siglas', 'like', $busqueda)
    //                     ->orWhere('monitoreo_radio.publicacion_tipo', 'like', $busqueda)
    //                     ->orWhere('sujetos.nombre', 'like', $busqueda)
    //                     ->orWhere('partidos.nombre', 'like', $busqueda)
    //                     ->orWhere('municipios.nombre', 'like', $busqueda);
    //             });
    //         })
    //         ->orderByDesc('monitoreo_radio.id')
    //         ->paginate($this->cantidad_por_pagina);
    // }

    private function consultarRegistros()
    {
        return MonitoreoMedioRadio::query()
            ->with([
                'capturista:id,name',
                'analista:id,name',
            ])
            ->leftJoin('sujetos', 'monitoreo_radio.sujeto_id', '=', 'sujetos.id')
            ->leftJoin('partidos', 'monitoreo_radio.organizacion_politica_id', '=', 'partidos.id')
            ->leftJoin('municipios', 'monitoreo_radio.medio_municipio_id', '=', 'municipios.id')
            ->select([
                'monitoreo_radio.*',
                'sujetos.nombre as sujeto_nombre',
                'partidos.nombre as organizacion_nombre',
                'municipios.nombre as municipio_nombre',
            ])
            ->where('monitoreo_radio.tipo_medio', $this->tipo_medio)

            ->when(! $this->usuarioPuedeVerTodo(), function ($query) {
                $query->where('monitoreo_radio.usuario1_id', Auth::id());
            })

            ->when($this->fecha_inicio_registro, function ($query) {
                $query->whereDate('monitoreo_radio.created_at', '>=', $this->fecha_inicio_registro);
            })
            ->when($this->fecha_fin_registro, function ($query) {
                $query->whereDate('monitoreo_radio.created_at', '<=', $this->fecha_fin_registro);
            })
            ->when($this->filtro_tipo_eleccion_id !== '', function ($query) {
                $query->where('monitoreo_radio.tipo_eleccion_id', $this->filtro_tipo_eleccion_id);
            })
            ->when($this->busqueda_tabla, function ($query) {
                $texto_busqueda = trim($this->busqueda_tabla);
                $busqueda = '%' . $texto_busqueda . '%';

                $query->where(function ($q) use ($texto_busqueda, $busqueda) {
                    if (is_numeric($texto_busqueda)) {
                        $q->where('monitoreo_radio.id', (int) $texto_busqueda);
                    }

                    $q->orWhere('monitoreo_radio.observaciones', 'like', $busqueda)
                        ->orWhere('monitoreo_radio.medio_nombre', 'like', $busqueda)
                        ->orWhere('monitoreo_radio.medio_siglas', 'like', $busqueda)
                        ->orWhere('monitoreo_radio.publicacion_tipo', 'like', $busqueda)
                        ->orWhere('sujetos.nombre', 'like', $busqueda)
                        ->orWhere('partidos.nombre', 'like', $busqueda)
                        ->orWhere('municipios.nombre', 'like', $busqueda);
                });
            })
            ->orderByDesc('monitoreo_radio.id')
            ->paginate($this->cantidad_por_pagina);
    }

    public function recuperarInfoAnterior(array $datos): void
    {
        $this->sujeto_id = ! empty($datos['sujeto_id']) ? (int) $datos['sujeto_id'] : null;
        $sujeto = $this->sujeto_id ? Sujeto::find($this->sujeto_id) : null;
        $this->sujeto_seleccionado = $sujeto;
        $this->busqueda_sujeto = $sujeto?->nombre ?? '';
        $this->organizacion_politica_id = ! empty($datos['organizacion_politica_id']) ? (int) $datos['organizacion_politica_id'] : null;
        $this->periodo_id = ! empty($datos['periodo_id']) ? (int) $datos['periodo_id'] : null;
        $this->etapa_sujeto = $datos['etapa_sujeto'] ?? null;
        $this->tipo_eleccion_id = ! empty($datos['tipo_eleccion_id']) ? (int) $datos['tipo_eleccion_id'] : null;
        $this->medio_nombre = $datos['medio_nombre'] ?? '';
        $this->medio_siglas = $datos['medio_siglas'] ?? '';
        $this->medio_banda = $datos['medio_banda'] ?? '';
        $this->medio_grupo_radiofonico = $datos['medio_grupo_radiofonico'] ?? '';
        $this->medio_municipio_id = ! empty($datos['medio_municipio_id']) ? (int) $datos['medio_municipio_id'] : null;
        $this->medio_cobertura = $datos['medio_cobertura'] ?? '';
        $this->publicacion_fecha = $datos['publicacion_fecha'] ?? now()->format('Y-m-d');
        $this->publicacion_hora = $datos['publicacion_hora'] ?? '';
        $this->publicacion_tiempo = ! empty($datos['publicacion_tiempo']) ? (int) $datos['publicacion_tiempo'] : null;
        $this->publicacion_tipo = $datos['publicacion_tipo'] ?? '';
        $this->publicacion_ubicacion = $datos['publicacion_ubicacion'] ?? '';
        $this->publicacion_modalidad = $datos['publicacion_modalidad'] ?? '';
        $this->genero_autor_id = ! empty($datos['genero_autor_id']) ? (int) $datos['genero_autor_id'] : null;
        $this->nombre_autor = $datos['nombre_autor'] ?? '';
        $this->observaciones = $datos['observaciones'] ?? '';
        $this->archivos = [];
        $this->archivos_existentes = [];
        $this->archivos_eliminados = [];
        $this->registro_editando_id = null;
        $this->resetValidation();
        session()->flash('success', 'Información anterior recuperada. Debes seleccionar nuevamente el audio MP3.');
    }

    public function recuperarDatosCualitativos(array $datos): void
    {
        $this->cuali_valoracion = $datos['cuali_valoracion'] ?? null;
        $this->cuali_lenguaje_inclusivo = $datos['cuali_lenguaje_inclusivo'] ?? null;
        $this->cuali_estereotipo = $datos['cuali_estereotipo'] ?? null;
        $this->cuali_violencia_temas_id = ! empty($datos['cuali_violencia_temas_id']) ? (int) $datos['cuali_violencia_temas_id'] : null;
        $this->cuali_tipos_eleccion_id = ! empty($datos['cuali_tipos_eleccion_id']) ? (int) $datos['cuali_tipos_eleccion_id'] : null;
        $this->cuali_resumen = $datos['cuali_resumen'] ?? null;
        $this->cuali_criterio_evaluacion = $datos['cuali_criterio_evaluacion'] ?? null;
        $this->resetValidation();
        session()->flash('success', 'Datos cualitativos recuperados.');
    }

    private function datosParaRecuperar(): array
    {
        return [
            'sujeto_id' => $this->sujeto_id,
            'organizacion_politica_id' => $this->organizacion_politica_id,
            'periodo_id' => $this->periodo_id,
            'etapa_sujeto' => $this->etapa_sujeto,
            'tipo_eleccion_id' => $this->tipo_eleccion_id,
            'medio_nombre' => $this->medio_nombre,
            'medio_siglas' => $this->medio_siglas,
            'medio_banda' => $this->medio_banda,
            'medio_grupo_radiofonico' => $this->medio_grupo_radiofonico,
            'medio_municipio_id' => $this->medio_municipio_id,
            'medio_cobertura' => $this->medio_cobertura,
            'publicacion_fecha' => $this->publicacion_fecha,
            'publicacion_hora' => $this->publicacion_hora,
            'publicacion_tiempo' => $this->publicacion_tiempo,
            'publicacion_tipo' => $this->publicacion_tipo,
            'publicacion_ubicacion' => $this->publicacion_ubicacion,
            'publicacion_modalidad' => $this->publicacion_modalidad,
            'genero_autor_id' => $this->genero_autor_id,
            'nombre_autor' => $this->nombre_autor,
            'observaciones' => $this->observaciones,
        ];
    }

    private function usuarioPuedeVerTodo(): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        return $user->hasAnyRole([
            'Administrador',
            'Super Usuario',
            'Super usuario',
            'Consultor',
        ]);
    }
}
