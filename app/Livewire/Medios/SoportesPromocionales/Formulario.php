<?php

namespace App\Livewire\Medios\SoportesPromocionales;

use App\Models\Distrito;
use App\Models\Localidad;
use App\Models\Municipio;
use App\Models\Partido;
use App\Models\Periodo;
use App\Models\SoportePromocional;
use App\Models\Sujeto;
use App\Models\TipoEleccion;
use App\Models\TipoPublicidad;
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

    public string $tipo_medio = 'medios-soportes-promocionales';

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

    public ?int $distrito_id = null;
    public ?int $municipio_id = null;
    public ?int $localidad_id = null;
    public string $latitud = '';
    public string $longitud = '';
    public string $vialidad = '';
    public string $seccion = '';

    public string $publicacion_medidas = '';
    public ?int $publicacion_tipo_id = null;
    public string $publicacion_version = '';
    public ?int $publicacion_cantidad = null;
    public ?int $publicacion_numero_fotos = null;

    public string $referencia = '';
    public string $referencia_domiciliaria = '';
    public string $observaciones = '';

    public array $archivos = [];
    public ?int $registro_editando_id = null;
    public array $archivos_existentes = [];
    public array $archivos_eliminados = [];

    public bool $mostrar_panel_cualitativo = false;
    public ?int $registro_cualitativo_id = null;
    public array $registro_cualitativo = [];
    public array $imagenes_cualitativas = [];

    public ?string $cuali_valoracion = null;
    public ?string $cuali_lenguaje_inclusivo = null;
    public ?string $cuali_estereotipo = null;
    public ?int $cuali_violencia_temas_id = null;
    public ?string $cuali_objetividad = null;
    public ?string $cuali_equidad = null;
    public ?string $cuali_calidad = null;

    public $partidos;
    public $periodos;
    public $tipos_eleccion;
    public $distritos;
    public $municipios;
    public $localidades;
    public $tipos_publicidad;
    public $violencia_temas;

    public array $valoraciones = ['Positiva', 'Negativa', 'Neutral'];
    public array $opciones_si_no = ['Si', 'No'];
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

    public function mount(): void
    {
        $this->fecha_inicio_registro = now()->startOfMonth()->format('Y-m-d');
        $this->fecha_fin_registro = now()->format('Y-m-d');
        $this->cargarCatalogos();
    }

    public function render()
    {
        return view('livewire.medios.soportes-promocionales.formulario', [
            'registros' => $this->consultarRegistros(),
        ]);
    }

    public function cargarCatalogos(): void
    {
        $this->partidos = Partido::orderBy('nombre')->get();
        $this->periodos = Periodo::orderBy('nombre')->get();
        $this->tipos_eleccion = TipoEleccion::orderBy('nombre')->get();
        $this->distritos = Distrito::orderBy('nombre')->get();

        $this->municipios = collect();
        $this->localidades = collect();

        if ($this->distrito_id) {
            $this->cargarMunicipiosPorDistrito();
        }

        if ($this->municipio_id) {
            $this->cargarLocalidadesPorMunicipio();
        }

        $this->tipos_publicidad = TipoPublicidad::orderBy('nombre')->get();
        $this->violencia_temas = ViolenciaTema::orderBy('nombre')->get();
    }

    public function updatedDistritoId($value): void
    {
        $this->municipio_id = null;
        $this->localidad_id = null;

        $this->municipios = collect();
        $this->localidades = collect();

        if ($value) {
            $this->cargarMunicipiosPorDistrito();
        }
    }

    public function updatedMunicipioId($value): void
    {
        $this->localidad_id = null;
        $this->localidades = collect();

        if ($value) {
            $this->cargarLocalidadesPorMunicipio();
        }
    }

    private function cargarMunicipiosPorDistrito(): void
    {
        $this->municipios = Municipio::where('distrito_id', $this->distrito_id)
            ->orderBy('nombre')
            ->get();
    }

    private function cargarLocalidadesPorMunicipio(): void
    {
        $this->localidades = Localidad::where('municipio_id', $this->municipio_id)
            ->orderBy('nombre')
            ->get();
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
            'distrito_id' => 'nullable|exists:distritos,id',
            'municipio_id' => 'nullable|exists:municipios,id',
            'localidad_id' => 'nullable|exists:localidades,id',
            'latitud' => 'nullable|numeric|between:-90,90',
            'longitud' => 'nullable|numeric|between:-180,180',
            'vialidad' => 'nullable|string|max:255',
            'seccion' => 'nullable|string|max:255',
            'publicacion_medidas' => 'nullable|string|max:255',
            'publicacion_tipo_id' => 'nullable|exists:tipo_publicidad,id',
            'publicacion_version' => 'nullable|string|max:255',
            'publicacion_cantidad' => 'nullable|integer|min:0',
            'publicacion_numero_fotos' => 'nullable|integer|min:0',
            'referencia' => 'nullable|string|max:255',
            'referencia_domiciliaria' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string|max:255',
            'archivos' => $this->registro_editando_id ? 'nullable|array' : 'required|array|min:1',
            'archivos.*' => 'image|max:10240|mimes:jpg,jpeg,png,webp',
        ];
    }

    public function guardar(): void
    {
        $datos = $this->validate();
        $datos['organizacion_id'] = $datos['organizacion_politica_id'] ?? null;
        unset($datos['organizacion_politica_id']);
        $datos['latitud'] = $datos['latitud'] !== '' ? $datos['latitud'] : null;
        $datos['longitud'] = $datos['longitud'] !== '' ? $datos['longitud'] : null;

        DB::transaction(function () use ($datos) {
            $registro = $this->registro_editando_id
                ? SoportePromocional::findOrFail($this->registro_editando_id)
                : SoportePromocional::create(array_merge($datos, [
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

            $rutas_archivos = array_values(array_merge(
                $this->archivos_existentes,
                $rutas_nuevas
            ));

            $registro->update(array_merge($datos, [
                'tipo_medio' => $this->tipo_medio,
                'archivos' => $rutas_archivos,
            ]));
        });

        $mensaje = $this->registro_editando_id
            ? 'Registro actualizado correctamente.'
            : 'Registro de soporte promocional guardado correctamente.';

        $this->dispatch('soportes-promocionales-registro-guardado', datos: $this->datosParaRecuperar());
        $this->limpiarFormulario();
        session()->flash('success', $mensaje);
    }

    private function guardarArchivosDelRegistro(int $registro_id, int $consecutivo_inicial = 1): array
    {
        $rutas = [];

        foreach ($this->archivos as $indice => $archivo) {
            $consecutivo = str_pad((string) ($consecutivo_inicial + $indice), 2, '0', STR_PAD_LEFT);
            $extension = strtolower($archivo->getClientOriginalExtension() ?: 'jpg');
            $nombre = "soporte_promocional_{$registro_id}_{$consecutivo}.{$extension}";
            $ruta = "medios/soportes-promocionales/{$nombre}";

            Storage::disk('public')->put($ruta, file_get_contents($archivo->getRealPath()));
            $rutas[] = $ruta;
        }

        return $rutas;
    }

    public function editar(int $id): void
    {
        $registro = SoportePromocional::findOrFail($id);

        $this->registro_editando_id = $registro->id;
        $this->sujeto_id = $registro->sujeto_id;
        $this->sujeto_seleccionado = Sujeto::find($registro->sujeto_id);
        $this->busqueda_sujeto = $this->sujeto_seleccionado?->nombre ?? '';
        $this->organizacion_politica_id = $registro->organizacion_id;
        $this->periodo_id = $registro->periodo_id;
        $this->etapa_sujeto = $registro->etapa_sujeto;
        $this->tipo_eleccion_id = $registro->tipo_eleccion_id;
        $this->distrito_id = $registro->distrito_id;
        $this->municipio_id = $registro->municipio_id;
        $this->localidad_id = $registro->localidad_id;
        $this->municipios = collect();
        $this->localidades = collect();

        if ($this->distrito_id) {
            $this->cargarMunicipiosPorDistrito();
        }

        if ($this->municipio_id) {
            $this->cargarLocalidadesPorMunicipio();
        }
        $this->latitud = $registro->latitud !== null ? (string) $registro->latitud : '';
        $this->longitud = $registro->longitud !== null ? (string) $registro->longitud : '';
        $this->vialidad = $registro->vialidad ?? '';
        $this->seccion = $registro->seccion ?? '';
        $this->publicacion_medidas = $registro->publicacion_medidas ?? '';
        $this->publicacion_tipo_id = $registro->publicacion_tipo_id;
        $this->publicacion_version = $registro->publicacion_version ?? '';
        $this->publicacion_cantidad = $registro->publicacion_cantidad;
        $this->publicacion_numero_fotos = $registro->publicacion_numero_fotos;
        $this->referencia = $registro->referencia ?? '';
        $this->referencia_domiciliaria = $registro->referencia_domiciliaria ?? '';
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
            'distrito_id',
            'municipio_id',
            'localidad_id',
            'latitud',
            'longitud',
            'vialidad',
            'seccion',
            'publicacion_medidas',
            'publicacion_tipo_id',
            'publicacion_version',
            'publicacion_cantidad',
            'publicacion_numero_fotos',
            'referencia',
            'referencia_domiciliaria',
            'observaciones',
            'archivos',
            'registro_editando_id',
            'archivos_existentes',
            'archivos_eliminados',
        ]);

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
        $registro = SoportePromocional::query()
            ->leftJoin('sujetos', 'soportes_promocionales.sujeto_id', '=', 'sujetos.id')
            ->leftJoin('partidos', 'soportes_promocionales.organizacion_id', '=', 'partidos.id')
            ->leftJoin('periodos', 'soportes_promocionales.periodo_id', '=', 'periodos.id')
            ->leftJoin('tipos_eleccion', 'soportes_promocionales.tipo_eleccion_id', '=', 'tipos_eleccion.id')
            ->leftJoin('distritos', 'soportes_promocionales.distrito_id', '=', 'distritos.id')
            ->leftJoin('municipios', 'soportes_promocionales.municipio_id', '=', 'municipios.id')
            ->leftJoin('localidades', 'soportes_promocionales.localidad_id', '=', 'localidades.id')
            ->leftJoin('tipo_publicidad', 'soportes_promocionales.publicacion_tipo_id', '=', 'tipo_publicidad.id')
            ->where('soportes_promocionales.id', $id)
            ->select([
                'soportes_promocionales.*',
                'sujetos.nombre as sujeto_nombre',
                'partidos.nombre as organizacion_nombre',
                'periodos.nombre as periodo_nombre',
                'tipos_eleccion.nombre as tipo_eleccion_nombre',
                'distritos.nombre as distrito_nombre',
                'municipios.nombre as municipio_nombre',
                'localidades.nombre as localidad_nombre',
                'tipo_publicidad.nombre as tipo_publicidad_nombre',
            ])
            ->firstOrFail();

        $this->registro_cualitativo_id = $registro->id;
        $this->registro_cualitativo = $registro->toArray();
        $this->imagenes_cualitativas = is_array($registro->archivos) ? $registro->archivos : [];
        $this->cuali_valoracion = $registro->cuali_valoracion;
        $this->cuali_lenguaje_inclusivo = $registro->cuali_lenguaje_inclusivo;
        $this->cuali_estereotipo = $registro->cuali_estereotipo;
        $this->cuali_violencia_temas_id = $registro->cuali_violencia_temas_id;
        $this->cuali_objetividad = $registro->cuali_objetividad;
        $this->cuali_equidad = $registro->cuali_equidad;
        $this->cuali_calidad = $registro->cuali_calidad;
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
            'cuali_objetividad' => 'nullable|string|max:255',
            'cuali_equidad' => 'nullable|string|max:255',
            'cuali_calidad' => 'nullable|string|max:255',
        ]);

        SoportePromocional::findOrFail($this->registro_cualitativo_id)->update($datos);
        $this->dispatch('soportes-promocionales-cualitativos-guardados', datos: $datos);
        $this->cerrarCualitativos();
        session()->flash('success', 'Datos cualitativos guardados correctamente.');
    }

    public function cerrarCualitativos(): void
    {
        $this->mostrar_panel_cualitativo = false;
        $this->registro_cualitativo_id = null;
        $this->registro_cualitativo = [];
        $this->imagenes_cualitativas = [];
    }

    public function confirmarEliminacion(int $id): void
    {
        $registro = SoportePromocional::findOrFail($id);
        $this->registro_eliminar_id = $registro->id;
        $this->registro_eliminar_referencia = $registro->referencia ?: 'Registro #' . $registro->id;
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
        $registro = SoportePromocional::findOrFail($this->registro_eliminar_id);

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

    private function consultarRegistros()
    {
        return SoportePromocional::query()
            ->leftJoin('sujetos', 'soportes_promocionales.sujeto_id', '=', 'sujetos.id')
            ->leftJoin('partidos', 'soportes_promocionales.organizacion_id', '=', 'partidos.id')
            ->leftJoin('distritos', 'soportes_promocionales.distrito_id', '=', 'distritos.id')
            ->leftJoin('municipios', 'soportes_promocionales.municipio_id', '=', 'municipios.id')
            ->leftJoin('tipo_publicidad', 'soportes_promocionales.publicacion_tipo_id', '=', 'tipo_publicidad.id')
            ->select([
                'soportes_promocionales.id',
                'soportes_promocionales.referencia',
                'soportes_promocionales.publicacion_medidas',
                'soportes_promocionales.publicacion_version',
                'soportes_promocionales.publicacion_cantidad',
                'soportes_promocionales.publicacion_numero_fotos',
                'soportes_promocionales.archivos',
                'soportes_promocionales.created_at',
                'sujetos.nombre as sujeto_nombre',
                'partidos.nombre as organizacion_nombre',
                'distritos.nombre as distrito_nombre',
                'municipios.nombre as municipio_nombre',
                'tipo_publicidad.nombre as tipo_publicidad_nombre',
            ])
            ->where('soportes_promocionales.tipo_medio', $this->tipo_medio)
            ->when($this->fecha_inicio_registro, fn($q) => $q->whereDate('soportes_promocionales.created_at', '>=', $this->fecha_inicio_registro))
            ->when($this->fecha_fin_registro, fn($q) => $q->whereDate('soportes_promocionales.created_at', '<=', $this->fecha_fin_registro))
            ->when($this->filtro_tipo_eleccion_id !== '', fn($q) => $q->where('soportes_promocionales.tipo_eleccion_id', $this->filtro_tipo_eleccion_id))
            ->when($this->busqueda_tabla, function ($query) {
                $busqueda = '%' . trim($this->busqueda_tabla) . '%';

                $query->where(function ($q) use ($busqueda) {
                    $q->where('soportes_promocionales.id', 'like', $busqueda)
                        ->orWhere('soportes_promocionales.referencia', 'like', $busqueda)
                        ->orWhere('soportes_promocionales.referencia_domiciliaria', 'like', $busqueda)
                        ->orWhere('soportes_promocionales.vialidad', 'like', $busqueda)
                        ->orWhere('soportes_promocionales.seccion', 'like', $busqueda)
                        ->orWhere('soportes_promocionales.publicacion_medidas', 'like', $busqueda)
                        ->orWhere('soportes_promocionales.publicacion_version', 'like', $busqueda)
                        ->orWhere('sujetos.nombre', 'like', $busqueda)
                        ->orWhere('partidos.nombre', 'like', $busqueda)
                        ->orWhere('distritos.nombre', 'like', $busqueda)
                        ->orWhere('municipios.nombre', 'like', $busqueda)
                        ->orWhere('tipo_publicidad.nombre', 'like', $busqueda);
                });
            })
            ->orderByDesc('soportes_promocionales.id')
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
        $this->distrito_id = ! empty($datos['distrito_id']) ? (int) $datos['distrito_id'] : null;
        $this->municipio_id = ! empty($datos['municipio_id']) ? (int) $datos['municipio_id'] : null;
        $this->localidad_id = ! empty($datos['localidad_id']) ? (int) $datos['localidad_id'] : null;
        $this->latitud = $datos['latitud'] ?? '';
        $this->longitud = $datos['longitud'] ?? '';
        $this->vialidad = $datos['vialidad'] ?? '';
        $this->seccion = $datos['seccion'] ?? '';
        $this->publicacion_medidas = $datos['publicacion_medidas'] ?? '';
        $this->publicacion_tipo_id = ! empty($datos['publicacion_tipo_id']) ? (int) $datos['publicacion_tipo_id'] : null;
        $this->publicacion_version = $datos['publicacion_version'] ?? '';
        $this->publicacion_cantidad = ! empty($datos['publicacion_cantidad']) ? (int) $datos['publicacion_cantidad'] : null;
        $this->publicacion_numero_fotos = ! empty($datos['publicacion_numero_fotos']) ? (int) $datos['publicacion_numero_fotos'] : null;
        $this->referencia = $datos['referencia'] ?? '';
        $this->referencia_domiciliaria = $datos['referencia_domiciliaria'] ?? '';
        $this->observaciones = $datos['observaciones'] ?? '';
        $this->archivos = [];
        $this->archivos_existentes = [];
        $this->archivos_eliminados = [];
        $this->registro_editando_id = null;
        $this->resetValidation();
        session()->flash('success', 'Información anterior recuperada. Debes seleccionar nuevamente las imágenes.');
    }

    public function recuperarDatosCualitativos(array $datos): void
    {
        $this->cuali_valoracion = $datos['cuali_valoracion'] ?? null;
        $this->cuali_lenguaje_inclusivo = $datos['cuali_lenguaje_inclusivo'] ?? null;
        $this->cuali_estereotipo = $datos['cuali_estereotipo'] ?? null;
        $this->cuali_violencia_temas_id = ! empty($datos['cuali_violencia_temas_id']) ? (int) $datos['cuali_violencia_temas_id'] : null;
        $this->cuali_objetividad = $datos['cuali_objetividad'] ?? null;
        $this->cuali_equidad = $datos['cuali_equidad'] ?? null;
        $this->cuali_calidad = $datos['cuali_calidad'] ?? null;
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
            'distrito_id' => $this->distrito_id,
            'municipio_id' => $this->municipio_id,
            'localidad_id' => $this->localidad_id,
            'latitud' => $this->latitud,
            'longitud' => $this->longitud,
            'vialidad' => $this->vialidad,
            'seccion' => $this->seccion,
            'publicacion_medidas' => $this->publicacion_medidas,
            'publicacion_tipo_id' => $this->publicacion_tipo_id,
            'publicacion_version' => $this->publicacion_version,
            'publicacion_cantidad' => $this->publicacion_cantidad,
            'publicacion_numero_fotos' => $this->publicacion_numero_fotos,
            'referencia' => $this->referencia,
            'referencia_domiciliaria' => $this->referencia_domiciliaria,
            'observaciones' => $this->observaciones,
        ];
    }
}
