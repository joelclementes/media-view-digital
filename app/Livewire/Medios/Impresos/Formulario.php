<?php

namespace App\Livewire\Medios\Impresos;

use App\Models\Distrito;
use App\Models\Genero;
use App\Models\GeneroSujeto;
use App\Models\MonitoreoMedioImpreso;
use App\Models\Partido;
use App\Models\Periodo;
use App\Models\PortalPrensa;
use App\Models\Sujeto;
use App\Models\TamanoPublicacion;
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

    public string $tipo_medio = 'medios-impresos';

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

    public ?int $medio_prensa_id = null;

    public string $publicacion_fecha = '';
    public string $publicacion_lugar = '';
    public ?int $publicacion_tamano_id = null;
    public ?int $publicacion_genero_id = null;
    public string $publicacion_seccion = '';
    public string $publicacion_pagina = '';

    public ?int $genero_autor_id = null;
    public string $nombre_autor = '';

    public string $referencia = '';
    public string $observaciones = '';

    public array $archivos = [];
    public ?int $registro_editando_id = null;
    public array $archivos_existentes = [];
    public array $archivos_eliminados = [];

    public bool $mostrar_panel_cualitativo = false;
    public ?int $registro_cualitativo_id = null;
    public array $registro_cualitativo = [];
    public array $imagenes_cualitativas = [];
    public int $imagen_actual_indice = 0;
    public bool $modal_imagen_abierto = false;

    public ?string $cuali_valoracion = null;
    public ?string $cuali_lenguaje_inclusivo = null;
    public ?string $cuali_estereotipo = null;
    public ?int $cuali_violencia_temas_id = null;
    public ?int $cuali_tipos_eleccion_id = null;
    public ?string $cuali_resumen = null;
    public ?string $cuali_criterio_evaluacion = null;
    public ?string $cuali_modalidad = null;
    public ?string $cuali_periodicidad = null;
    public ?int $cuali_tiraje = null;
    public ?string $cuali_circulacion = null;
    public ?int $cuali_distritos_id = null;

    public $partidos;
    public $periodos;
    public $tipos_eleccion;
    public $medios_prensa;
    public $tamanos;
    public $generos;
    public $generos_sujeto;
    public $violencia_temas;
    public $distritos;

    public function mount(): void
    {
        $this->publicacion_fecha = now()->format('Y-m-d');
        $this->fecha_inicio_registro = now()->startOfMonth()->format('Y-m-d');
        $this->fecha_fin_registro = now()->format('Y-m-d');

        $this->cargarCatalogos();
    }

    public function render()
    {
        return view('livewire.medios.impresos.formulario', [
            'registros' => $this->consultarRegistros(),
        ]);
    }

    public function cargarCatalogos(): void
    {
        $this->partidos = Partido::orderBy('nombre')->get();
        $this->periodos = Periodo::orderBy('nombre')->get();
        $this->tipos_eleccion = TipoEleccion::orderBy('nombre')->get();
        $this->medios_prensa = PortalPrensa::orderBy('nombre')->get();
        $this->tamanos = TamanoPublicacion::orderBy('nombre')->get();
        $this->generos = Genero::porMedio('impreso')->orderBy('nombre')->get();
        $this->generos_sujeto = GeneroSujeto::orderBy('nombre')->get();
        $this->violencia_temas = ViolenciaTema::orderBy('nombre')->get();
        $this->distritos = Distrito::orderBy('nombre')->get();
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

            'medio_prensa_id' => 'nullable|exists:portales_prensa,id',

            'publicacion_fecha' => 'required|date',
            'publicacion_lugar' => 'required|string|max:255',
            'publicacion_tamano_id' => 'required|exists:tamanos_publicacion,id',
            'publicacion_genero_id' => 'required|exists:generos,id',
            'publicacion_seccion' => 'required|string|max:255',
            'publicacion_pagina' => 'required|string|max:255',

            'genero_autor_id' => 'required|exists:generos_sujetos,id',
            'nombre_autor' => 'nullable|string|max:255',

            'referencia' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string|max:255',

            'archivos' => $this->registro_editando_id ? 'nullable|array' : 'required|array|min:1',
            'archivos.*' => 'image|max:10240|mimes:jpg,jpeg,png',
        ];
    }

    public function guardar(): void
    {
        $datos = $this->validate();

        DB::transaction(function () use ($datos) {
            if ($this->registro_editando_id) {
                $registro = MonitoreoMedioImpreso::findOrFail($this->registro_editando_id);
            } else {
                $registro = MonitoreoMedioImpreso::create(array_merge($datos, [
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

        $this->dispatch('impresos-registro-guardado', datos: [
            'sujeto_id' => $this->sujeto_id,
            'organizacion_politica_id' => $this->organizacion_politica_id,
            'periodo_id' => $this->periodo_id,
            'etapa_sujeto' => $this->etapa_sujeto,
            'tipo_eleccion_id' => $this->tipo_eleccion_id,
            'medio_prensa_id' => $this->medio_prensa_id,
            'publicacion_fecha' => $this->publicacion_fecha,
            'publicacion_lugar' => $this->publicacion_lugar,
            'publicacion_tamano_id' => $this->publicacion_tamano_id,
            'publicacion_genero_id' => $this->publicacion_genero_id,
            'publicacion_seccion' => $this->publicacion_seccion,
            'publicacion_pagina' => $this->publicacion_pagina,
            'genero_autor_id' => $this->genero_autor_id,
            'nombre_autor' => $this->nombre_autor,
            'referencia' => $this->referencia,
            'observaciones' => $this->observaciones,
        ]);

        $estaEditando = filled($this->registro_editando_id);

        $this->limpiarFormulario();

        session()->flash(
            'success',
            $estaEditando
                ? 'Registro actualizado correctamente.'
                : 'Registro de medio impreso guardado correctamente.'
        );
    }

    private function guardarArchivosDelRegistro(int $registro_id, int $consecutivo_inicial = 1): array
    {
        $rutas = [];

        foreach ($this->archivos as $indice => $archivo) {
            $consecutivo = str_pad((string) ($consecutivo_inicial + $indice), 2, '0', STR_PAD_LEFT);
            $nombre = "impresos_{$registro_id}_{$consecutivo}.jpg";
            $ruta = "medios/impresos/{$nombre}";

            $imagen = imagecreatefromstring(file_get_contents($archivo->getRealPath()));
            $ancho = imagesx($imagen);
            $alto = imagesy($imagen);
            $max = 1200;

            if ($ancho > $max) {
                $nuevoAncho = $max;
                $nuevoAlto = intval(($alto * $nuevoAncho) / $ancho);
            } else {
                $nuevoAncho = $ancho;
                $nuevoAlto = $alto;
            }

            $final = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
            imagecopyresampled($final, $imagen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);

            ob_start();
            imagejpeg($final, null, 80);
            Storage::disk('public')->put($ruta, ob_get_clean());

            imagedestroy($imagen);
            imagedestroy($final);

            $rutas[] = $ruta;
        }

        return $rutas;
    }

    public function editar(int $id): void
    {
        $registro = MonitoreoMedioImpreso::findOrFail($id);

        $this->registro_editando_id = $registro->id;

        $sujeto = Sujeto::find($registro->sujeto_id);
        $this->sujeto_seleccionado = $sujeto;
        $this->sujeto_id = $registro->sujeto_id;
        $this->busqueda_sujeto = $sujeto?->nombre ?? '';

        foreach (
            $registro->only([
                'organizacion_politica_id',
                'periodo_id',
                'etapa_sujeto',
                'tipo_eleccion_id',
                'medio_prensa_id',
                'publicacion_lugar',
                'publicacion_tamano_id',
                'publicacion_genero_id',
                'publicacion_seccion',
                'publicacion_pagina',
                'genero_autor_id',
                'nombre_autor',
                'referencia',
                'observaciones',
            ]) as $campo => $valor
        ) {
            $this->{$campo} = $valor ?? '';
        }

        $this->publicacion_fecha = $registro->publicacion_fecha?->format('Y-m-d') ?? now()->format('Y-m-d');
        $this->archivos = [];
        $this->archivos_existentes = $registro->archivos ?? [];
        $this->archivos_eliminados = [];

        session()->flash('success', 'Registro cargado para edición.');
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
            'medio_prensa_id',
            'publicacion_lugar',
            'publicacion_tamano_id',
            'publicacion_genero_id',
            'publicacion_seccion',
            'publicacion_pagina',
            'genero_autor_id',
            'nombre_autor',
            'referencia',
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
        $registro = MonitoreoMedioImpreso::query()
            ->leftJoin('sujetos', 'monitoreo_medios_impresos.sujeto_id', '=', 'sujetos.id')
            ->leftJoin('partidos', 'monitoreo_medios_impresos.organizacion_politica_id', '=', 'partidos.id')
            ->leftJoin('periodos', 'monitoreo_medios_impresos.periodo_id', '=', 'periodos.id')
            ->leftJoin('portales_prensa', 'monitoreo_medios_impresos.medio_prensa_id', '=', 'portales_prensa.id')
            ->leftJoin('tamanos_publicacion', 'monitoreo_medios_impresos.publicacion_tamano_id', '=', 'tamanos_publicacion.id')
            ->leftJoin('generos', 'monitoreo_medios_impresos.publicacion_genero_id', '=', 'generos.id')
            ->leftJoin('generos_sujetos', 'monitoreo_medios_impresos.genero_autor_id', '=', 'generos_sujetos.id')
            ->where('monitoreo_medios_impresos.id', $id)
            ->select([
                'monitoreo_medios_impresos.*',
                'sujetos.nombre as sujeto_nombre',
                'partidos.nombre as organizacion_nombre',
                'periodos.nombre as periodo_nombre',
                'portales_prensa.nombre as medio_prensa_nombre',
                'tamanos_publicacion.nombre as tamano_nombre',
                'generos.nombre as genero_nombre',
                'generos_sujetos.nombre as genero_sujeto_nombre',
            ])
            ->firstOrFail();

        $this->registro_cualitativo_id = $registro->id;

        $this->registro_cualitativo = [
            'organizacion' => $registro->organizacion_nombre ?? 'Sin organización',
            'sujeto' => $registro->sujeto_nombre ?? 'Sin sujeto',
            'genero_sujeto' => $registro->genero_sujeto_nombre ?? 'Sin género',
            'periodo' => $registro->periodo_nombre ?? 'Sin periodo',
            'fecha' => $registro->publicacion_fecha ? $registro->publicacion_fecha->format('d/m/Y') : 'Sin fecha',
            'medio' => $registro->medio_prensa_nombre ?? 'Sin medio impreso',
            'tamano' => $registro->tamano_nombre ?? 'Sin tamaño',
            'genero' => $registro->genero_nombre ?? 'Sin género',
            'seccion' => $registro->publicacion_seccion ?? '',
            'pagina' => $registro->publicacion_pagina ?? '',
            'referencia' => $registro->referencia ?? '',
            'observaciones' => $registro->observaciones ?? '',
        ];

        $this->imagenes_cualitativas = is_array($registro->archivos) ? array_values($registro->archivos) : [];

        $this->imagen_actual_indice = 0;
        $this->modal_imagen_abierto = false;

        foreach (
            [
                'cuali_valoracion',
                'cuali_lenguaje_inclusivo',
                'cuali_estereotipo',
                'cuali_violencia_temas_id',
                'cuali_tipos_eleccion_id',
                'cuali_resumen',
                'cuali_criterio_evaluacion',
                'cuali_modalidad',
                'cuali_periodicidad',
                'cuali_tiraje',
                'cuali_circulacion',
                'cuali_distritos_id',
            ] as $campo
        ) {
            $this->{$campo} = $registro->{$campo};
        }

        $this->mostrar_panel_cualitativo = true;
    }

    public function guardarCualitativos(): void
    {
        $datos = $this->validate([
            'cuali_valoracion' => 'nullable|in:Positiva,Negativa,Neutral',
            'cuali_lenguaje_inclusivo' => 'nullable|in:Si,No',
            'cuali_estereotipo' => 'nullable|string|max:255',
            'cuali_violencia_temas_id' => 'nullable|exists:violencia_temas,id',
            'cuali_tipos_eleccion_id' => 'nullable|exists:tipos_eleccion,id',
            'cuali_resumen' => 'nullable|string',
            'cuali_criterio_evaluacion' => 'nullable|string|max:255',
            'cuali_modalidad' => 'nullable|in:Politica,Electoral',
            'cuali_periodicidad' => 'nullable|string|max:255',
            'cuali_tiraje' => 'nullable|integer|min:0',
            'cuali_circulacion' => 'nullable|in:Nacional,Regional,Local',
            'cuali_distritos_id' => 'nullable|exists:distritos,id',
        ]);

        $datos['usuario2_id'] = Auth::id();

        MonitoreoMedioImpreso::findOrFail($this->registro_cualitativo_id)
            ->update($datos);

        $this->dispatch('impresos-cualitativos-guardados', datos: [
            'cuali_valoracion' => $this->cuali_valoracion,
            'cuali_lenguaje_inclusivo' => $this->cuali_lenguaje_inclusivo,
            'cuali_estereotipo' => $this->cuali_estereotipo,
            'cuali_violencia_temas_id' => $this->cuali_violencia_temas_id,
            'cuali_tipos_eleccion_id' => $this->cuali_tipos_eleccion_id,
            'cuali_resumen' => $this->cuali_resumen,
            'cuali_criterio_evaluacion' => $this->cuali_criterio_evaluacion,
            'cuali_modalidad' => $this->cuali_modalidad,
            'cuali_periodicidad' => $this->cuali_periodicidad,
            'cuali_tiraje' => $this->cuali_tiraje,
            'cuali_circulacion' => $this->cuali_circulacion,
            'cuali_distritos_id' => $this->cuali_distritos_id,
        ]);

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
        $registro = MonitoreoMedioImpreso::findOrFail($id);

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
        if (! $this->registro_eliminar_id) {
            return;
        }

        $registro = MonitoreoMedioImpreso::findOrFail(
            $this->registro_eliminar_id
        );

        DB::transaction(function () use ($registro) {
            $archivos = is_array($registro->archivos)
                ? $registro->archivos
                : [];

            foreach ($archivos as $ruta) {
                if (
                    $ruta &&
                    Storage::disk('public')->exists($ruta)
                ) {
                    Storage::disk('public')->delete($ruta);
                }
            }

            $registro->delete();
        });

        $this->cancelarEliminacion();

        session()->flash(
            'success',
            'Registro eliminado correctamente.'
        );
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
    return MonitoreoMedioImpreso::query()
        ->with([
            'capturista:id,name',
            'analista:id,name',
        ])
        ->leftJoin('sujetos', 'monitoreo_medios_impresos.sujeto_id', '=', 'sujetos.id')
        ->leftJoin('partidos', 'monitoreo_medios_impresos.organizacion_politica_id', '=', 'partidos.id')
        ->leftJoin('portales_prensa', 'monitoreo_medios_impresos.medio_prensa_id', '=', 'portales_prensa.id')
        ->select([
            'monitoreo_medios_impresos.*',
            'sujetos.nombre as sujeto_nombre',
            'partidos.nombre as organizacion_nombre',
            'portales_prensa.nombre as medio_prensa_nombre',
        ])
        ->where('monitoreo_medios_impresos.tipo_medio', $this->tipo_medio)

        ->when(! $this->usuarioPuedeVerTodo(), function ($query) {
            $query->where('monitoreo_medios_impresos.usuario1_id', Auth::id());
        })

        ->when($this->fecha_inicio_registro, function ($query) {
            $query->whereDate('monitoreo_medios_impresos.created_at', '>=', $this->fecha_inicio_registro);
        })
        ->when($this->fecha_fin_registro, function ($query) {
            $query->whereDate('monitoreo_medios_impresos.created_at', '<=', $this->fecha_fin_registro);
        })
        ->when($this->filtro_tipo_eleccion_id !== '', function ($query) {
            $query->where(
                'monitoreo_medios_impresos.tipo_eleccion_id',
                $this->filtro_tipo_eleccion_id
            );
        })
        ->when($this->busqueda_tabla, function ($query) {
            $texto_busqueda = trim($this->busqueda_tabla);
            $busqueda = '%' . $texto_busqueda . '%';

            $query->where(function ($q) use ($texto_busqueda, $busqueda) {
                if (is_numeric($texto_busqueda)) {
                    $q->where('monitoreo_medios_impresos.id', (int) $texto_busqueda);
                }

                $q->orWhere('monitoreo_medios_impresos.referencia', 'like', $busqueda)
                    ->orWhere('monitoreo_medios_impresos.observaciones', 'like', $busqueda)
                    ->orWhere('monitoreo_medios_impresos.publicacion_seccion', 'like', $busqueda)
                    ->orWhere('monitoreo_medios_impresos.publicacion_pagina', 'like', $busqueda)
                    ->orWhere('sujetos.nombre', 'like', $busqueda)
                    ->orWhere('partidos.nombre', 'like', $busqueda)
                    ->orWhere('portales_prensa.nombre', 'like', $busqueda);
            });
        })
        ->orderByDesc('monitoreo_medios_impresos.id')
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

        $this->medio_prensa_id = ! empty($datos['medio_prensa_id']) ? (int) $datos['medio_prensa_id'] : null;

        $this->publicacion_fecha = $datos['publicacion_fecha'] ?? now()->format('Y-m-d');
        $this->publicacion_lugar = $datos['publicacion_lugar'] ?? '';
        $this->publicacion_tamano_id = ! empty($datos['publicacion_tamano_id']) ? (int) $datos['publicacion_tamano_id'] : null;
        $this->publicacion_genero_id = ! empty($datos['publicacion_genero_id']) ? (int) $datos['publicacion_genero_id'] : null;
        $this->publicacion_seccion = $datos['publicacion_seccion'] ?? '';
        $this->publicacion_pagina = $datos['publicacion_pagina'] ?? '';

        $this->genero_autor_id = ! empty($datos['genero_autor_id']) ? (int) $datos['genero_autor_id'] : null;
        $this->nombre_autor = $datos['nombre_autor'] ?? '';

        $this->referencia = $datos['referencia'] ?? '';
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
        $this->cuali_tipos_eleccion_id = ! empty($datos['cuali_tipos_eleccion_id']) ? (int) $datos['cuali_tipos_eleccion_id'] : null;
        $this->cuali_resumen = $datos['cuali_resumen'] ?? null;
        $this->cuali_criterio_evaluacion = $datos['cuali_criterio_evaluacion'] ?? null;
        $this->cuali_modalidad = $datos['cuali_modalidad'] ?? null;
        $this->cuali_periodicidad = $datos['cuali_periodicidad'] ?? null;
        $this->cuali_tiraje = ! empty($datos['cuali_tiraje']) ? (int) $datos['cuali_tiraje'] : null;
        $this->cuali_circulacion = $datos['cuali_circulacion'] ?? null;
        $this->cuali_distritos_id = ! empty($datos['cuali_distritos_id']) ? (int) $datos['cuali_distritos_id'] : null;

        $this->resetValidation();

        session()->flash('success', 'Datos cualitativos recuperados.');
    }

    public function abrirModalImagen(int $indice = 0): void
    {
        if (! isset($this->imagenes_cualitativas[$indice])) {
            return;
        }

        $this->imagen_actual_indice = $indice;
        $this->modal_imagen_abierto = true;
    }

    public function cerrarModalImagen(): void
    {
        $this->modal_imagen_abierto = false;
    }

    public function imagenAnterior(): void
    {
        if (count($this->imagenes_cualitativas) <= 1) {
            return;
        }

        $this->imagen_actual_indice =
            $this->imagen_actual_indice === 0
            ? count($this->imagenes_cualitativas) - 1
            : $this->imagen_actual_indice - 1;
    }

    public function imagenSiguiente(): void
    {
        if (count($this->imagenes_cualitativas) <= 1) {
            return;
        }

        $this->imagen_actual_indice =
            $this->imagen_actual_indice === count($this->imagenes_cualitativas) - 1
            ? 0
            : $this->imagen_actual_indice + 1;
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
