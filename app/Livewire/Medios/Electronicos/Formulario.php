<?php

namespace App\Livewire\Medios\Electronicos;

use App\Models\Genero;
use App\Models\GeneroSujeto;
use App\Models\MonitoreoMedioElectronico;
use App\Models\Partido;
use App\Models\Periodo;
use App\Models\PortalInternet;
use App\Models\Sujeto;
use App\Models\TamanoPublicacion;
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

    //region PROPIEDADES GENERALES

    public string $tipo_medio = 'medios-electronicos';
    public string $fecha_inicio_registro = '';
    public string $fecha_fin_registro = '';
    public string $busqueda_tabla = '';
    public string $filtro_tipo_eleccion_id = '';
    public int $cantidad_por_pagina = 10;

    protected $paginationTheme = 'tailwind';
    public bool $mostrar_filtros_tabla = false;

    public bool $mostrar_modal_eliminar = false;
    public ?int $registro_eliminar_id = null;
    public string $registro_eliminar_referencia = '';

    //endregion

    //region PROPIEDADES: SUJETO

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

    //endregion

    //region PROPIEDADES: MEDIO ELECTRÓNICO

    public ?string $selector_portal = '';
    public ?int $portal_internet_id = null;
    public string $url_pagina = '';

    public bool $mostrar_formulario_portal = false;

    public array $nuevo_portal = [
        'nombre' => '',
        'url' => '',
        'ciudad' => '',
        'tipo' => '',
    ];

    //endregion

    //region PROPIEDADES: PUBLICACIÓN

    public string $fecha = '';
    public ?int $tamano_id = null;
    public ?int $genero_id = null;

    //endregion

    //region PROPIEDADES: AUTOR

    public ?int $genero_autor_id = null;
    public string $nombre_autor = '';

    //endregion

    //region PROPIEDADES: REFERENCIA Y OBSERVACIONES

    public string $referencia = '';
    public string $observaciones = '';

    //endregion

    //region PROPIEDADES: ARCHIVOS

    public array $archivos = [];
    public ?int $registro_editando_id = null;
    public array $archivos_existentes = [];
    public array $archivos_eliminados = [];

    //endregion

    //region PROPIEDADES: CUALITATIVOS

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
    public ?string $cuali_modalidad = null;
    public ?string $cuali_objetividad = null;
    public ?string $cuali_tipo_mensaje = null;
    public ?string $cuali_formato = null;

    public $violencia_temas;

    //endregion

    //region PROPIEDADES: CATÁLOGOS

    public $partidos;
    public $periodos;
    public $tipos_eleccion;
    public $portales;
    public $tamanos;
    public $generos;
    public $generos_sujeto;

    //endregion

    //region CICLO DE VIDA

    public function mount(): void
    {
        $this->fecha = now()->format('Y-m-d');
        $this->fecha_inicio_registro = now()->startOfMonth()->format('Y-m-d');
        $this->fecha_fin_registro = now()->format('Y-m-d');

        $this->cargarCatalogos();
    }

    public function render()
    {
        return view('livewire.medios.electronicos.formulario', [
            'registros' => $this->consultarRegistros(),
        ]);
    }

    //endregion

    //region CATÁLOGOS

    public function cargarCatalogos(): void
    {
        $this->partidos = Partido::orderBy('nombre')->get();
        $this->periodos = Periodo::orderBy('nombre')->get();
        $this->tipos_eleccion = TipoEleccion::orderBy('nombre')->get();
        $this->violencia_temas = ViolenciaTema::orderBy('nombre')->get();

        $this->portales = PortalInternet::orderBy('nombre')->get();
        $this->tamanos = TamanoPublicacion::orderBy('nombre')->get();

        $this->generos = Genero::porMedio('electronico')
            ->orderBy('nombre')
            ->get();

        $this->generos_sujeto = GeneroSujeto::orderBy('nombre')->get();
    }

    //endregion

    //region SUJETO

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

        $this->validateOnly('sujeto_id');
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

    //endregion

    //region PORTALES

    public function updatedSelectorPortal($valor): void
    {
        if ($valor === 'otro') {
            $this->mostrar_formulario_portal = true;
            $this->portal_internet_id = null;
            $this->url_pagina = '';
            return;
        }

        $this->mostrar_formulario_portal = false;
        $this->portal_internet_id = $valor ? (int) $valor : null;

        $portal = PortalInternet::find($this->portal_internet_id);

        if ($portal) {
            $this->url_pagina = $portal->url ?? '';
        }

        $this->validateOnly('portal_internet_id');
    }

    public function guardarNuevoPortal(): void
    {
        $datos = $this->validate([
            'nuevo_portal.nombre' => 'required|string|min:3|max:255',
            'nuevo_portal.url' => 'nullable|url|max:255',
            'nuevo_portal.ciudad' => 'required|string|max:255',
            'nuevo_portal.tipo' => 'required|string|max:255',
        ], [
            'nuevo_portal.nombre.required' => 'Debes capturar el nombre del portal.',
            'nuevo_portal.nombre.min' => 'El nombre del portal debe tener al menos 3 caracteres.',
            'nuevo_portal.url.url' => 'La URL del portal no es válida.',
            'nuevo_portal.ciudad.required' => 'Debes capturar la ciudad.',
            'nuevo_portal.tipo.required' => 'Debes seleccionar el tipo de portal.',
        ]);

        $portal = PortalInternet::create($datos['nuevo_portal']);

        $this->cargarCatalogos();

        $this->portal_internet_id = $portal->id;
        $this->selector_portal = (string) $portal->id;
        $this->url_pagina = $portal->url ?? '';

        $this->mostrar_formulario_portal = false;

        $this->nuevo_portal = [
            'nombre' => '',
            'url' => '',
            'ciudad' => '',
            'tipo' => '',
        ];

        session()->flash('success', 'Portal agregado correctamente.');
    }

    public function cancelarNuevoPortal(): void
    {
        $this->mostrar_formulario_portal = false;
        $this->selector_portal = '';
        $this->portal_internet_id = null;

        $this->nuevo_portal = [
            'nombre' => '',
            'url' => '',
            'ciudad' => '',
            'tipo' => '',
        ];

        $this->resetValidation([
            'nuevo_portal.nombre',
            'nuevo_portal.url',
            'nuevo_portal.ciudad',
            'nuevo_portal.tipo',
        ]);
    }

    //endregion

    //region ARCHIVOS

    public function updatedArchivos(): void
    {
        $this->validateOnly('archivos');
    }

    public function eliminarArchivo(int $indice): void
    {
        if (isset($this->archivos[$indice])) {
            unset($this->archivos[$indice]);
            $this->archivos = array_values($this->archivos);
        }
    }

    private function guardarImagenOptimizada($archivo, int $registro_id, int $consecutivo): string
    {
        $ruta_original = $archivo->getRealPath();
        $mime = $archivo->getMimeType();

        if ($mime === 'image/jpeg' || $mime === 'image/jpg') {
            $imagen_original = imagecreatefromjpeg($ruta_original);
        } elseif ($mime === 'image/png') {
            $imagen_original = imagecreatefrompng($ruta_original);
        } else {
            throw new \Exception('Formato de imagen no soportado.');
        }

        if (! $imagen_original) {
            throw new \Exception('No se pudo procesar la imagen.');
        }

        $ancho_original = imagesx($imagen_original);
        $alto_original = imagesy($imagen_original);

        $ancho_maximo = 1200;

        if ($ancho_original > $ancho_maximo) {
            $nuevo_ancho = $ancho_maximo;
            $nuevo_alto = intval(($alto_original * $nuevo_ancho) / $ancho_original);
        } else {
            $nuevo_ancho = $ancho_original;
            $nuevo_alto = $alto_original;
        }

        $imagen_final = imagecreatetruecolor($nuevo_ancho, $nuevo_alto);

        imagecopyresampled(
            $imagen_final,
            $imagen_original,
            0,
            0,
            0,
            0,
            $nuevo_ancho,
            $nuevo_alto,
            $ancho_original,
            $alto_original
        );

        $consecutivo_formateado = str_pad(
            (string) $consecutivo,
            2,
            '0',
            STR_PAD_LEFT
        );

        $nombre_archivo = "electronicos_{$registro_id}_{$consecutivo_formateado}.jpg";
        $ruta_relativa = "medios/electronicos/{$nombre_archivo}";

        ob_start();
        imagejpeg($imagen_final, null, 80);
        $contenido_jpg = ob_get_clean();

        Storage::disk('public')->put($ruta_relativa, $contenido_jpg);

        imagedestroy($imagen_original);
        imagedestroy($imagen_final);

        return $ruta_relativa;
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

    //endregion

    //region VALIDACIÓN EN TIEMPO REAL

    public function updated($propiedad): void
    {
        if (array_key_exists($propiedad, $this->rules())) {
            $this->validateOnly($propiedad);
        }
    }

    protected function rules(): array
    {
        return [
            'sujeto_id' => 'required|exists:sujetos,id',
            'organizacion_politica_id' => 'nullable|exists:partidos,id',
            'periodo_id' => 'nullable|exists:periodos,id',
            'etapa_sujeto' => 'nullable|in:candidatura,precandidatura,candidatura_independiente',
            'tipo_eleccion_id' => 'nullable|exists:tipos_eleccion,id',

            'portal_internet_id' => 'nullable|exists:portales_internet,id',
            'url_pagina' => 'required|url|max:500',

            'fecha' => 'required|date',
            'tamano_id' => 'required|exists:tamanos_publicacion,id',
            'genero_id' => 'required|exists:generos,id',

            'genero_autor_id' => 'required|exists:generos_sujetos,id',
            'nombre_autor' => 'nullable|string|max:255',

            'referencia' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string|max:255',

            'archivos' => $this->registro_editando_id ? 'nullable|array' : 'required|array|min:1',
            'archivos.*' => 'image|max:10240|mimes:jpg,jpeg,png',

            'cuali_valoracion' => 'nullable|in:Positiva,Negativa,Neutral',
            'cuali_lenguaje_inclusivo' => 'nullable|in:Si,No',
            'cuali_estereotipo' => 'nullable|string|max:255',
            'cuali_violencia_temas_id' => 'nullable|exists:violencia_temas,id',
            'cuali_tipos_eleccion_id' => 'nullable|exists:tipos_eleccion,id',
            'cuali_resumen' => 'nullable|string|max:255',
            'cuali_modalidad' => 'nullable|in:Politica,Electoral',
            'cuali_objetividad' => 'nullable|string|max:255',
            'cuali_tipo_mensaje' => 'nullable|in:A favor,Descalificativo,Crítica,Imparcial',
            'cuali_formato' => 'nullable|in:Mensaje,De entrevista,Informativo-narrativo',
        ];
    }

    protected function mensajes(): array
    {
        return [
            'sujeto_id.required' => 'Debes seleccionar un sujeto.',
            'sujeto_id.exists' => 'El sujeto seleccionado no existe.',

            'url_pagina.required' => 'Debes capturar la URL de la publicación.',
            'url_pagina.url' => 'La URL de la publicación no es válida.',

            'fecha.required' => 'Debes capturar la fecha de publicación.',
            'fecha.date' => 'La fecha no es válida.',

            'tamano_id.required' => 'Debes seleccionar el tamaño de publicación.',
            'genero_id.required' => 'Debes seleccionar el género periodístico.',

            'genero_autor_id.required' => 'Debes seleccionar el género del autor.',

            'referencia.max' => 'La referencia no puede exceder 255 caracteres.',
            'observaciones.max' => 'Las observaciones no pueden exceder 255 caracteres.',

            'archivos.required' => 'Debes seleccionar al menos un archivo.',
            'archivos.array' => 'Debes seleccionar archivos válidos.',
            'archivos.min' => 'Debes seleccionar al menos un archivo.',
            'archivos.*.image' => 'Solo se permiten archivos de imagen.',
            'archivos.*.mimes' => 'Solo se permiten imágenes JPG, JPEG o PNG.',
            'archivos.*.max' => 'Cada imagen puede pesar máximo 10 MB.',
        ];
    }

    //endregion

    //region GUARDADO

    public function editar(int $id): void
    {
        $registro = MonitoreoMedioElectronico::findOrFail($id);

        $this->registro_editando_id = $registro->id;

        $sujeto = Sujeto::find($registro->sujeto_id);

        $this->sujeto_seleccionado = $sujeto;
        $this->sujeto_id = $registro->sujeto_id;
        $this->busqueda_sujeto = $sujeto?->nombre ?? '';

        $this->organizacion_politica_id = $registro->organizacion_politica_id;
        $this->periodo_id = $registro->periodo_id;
        $this->etapa_sujeto = $registro->etapa_sujeto;
        $this->tipo_eleccion_id = $registro->tipo_eleccion_id;

        $this->portal_internet_id = $registro->portal_internet_id;
        $this->selector_portal = $registro->portal_internet_id ? (string) $registro->portal_internet_id : '';
        $this->url_pagina = $registro->url_pagina ?? '';

        $this->fecha = $registro->fecha?->format('Y-m-d') ?? now()->format('Y-m-d');
        $this->tamano_id = $registro->tamano_id;
        $this->genero_id = $registro->genero_id;

        $this->genero_autor_id = $registro->genero_autor_id;
        $this->nombre_autor = $registro->nombre_autor ?? '';

        $this->referencia = $registro->referencia ?? '';
        $this->observaciones = $registro->observaciones ?? '';

        $this->archivos = [];
        $this->archivos_existentes = $registro->archivos ?? [];
        $this->archivos_eliminados = [];

        $this->resetValidation();

        session()->flash('success', 'Registro cargado para edición.');
    }

    public function guardar(): void
    {
        $datos = $this->validate($this->rules(), $this->mensajes());

        $esta_editando = filled($this->registro_editando_id);

        DB::transaction(function () use ($datos, $esta_editando) {
            if ($esta_editando) {
                $registro = MonitoreoMedioElectronico::findOrFail($this->registro_editando_id);

                foreach ($this->archivos_eliminados as $ruta) {
                    Storage::disk('public')->delete($ruta);
                }

                $rutas_nuevas = $this->guardarArchivosDelRegistro(
                    registro_id: $registro->id,
                    consecutivo_inicial: count($this->archivos_existentes) + 1
                );

                $rutas_archivos = array_values(array_merge(
                    $this->archivos_existentes,
                    $rutas_nuevas
                ));

                $registro->update([
                    'tipo_medio' => $this->tipo_medio,

                    'sujeto_id' => $datos['sujeto_id'],
                    'organizacion_politica_id' => $datos['organizacion_politica_id'],
                    'periodo_id' => $datos['periodo_id'],
                    'etapa_sujeto' => $datos['etapa_sujeto'],
                    'tipo_eleccion_id' => $datos['tipo_eleccion_id'],

                    'portal_internet_id' => $datos['portal_internet_id'],
                    'url_pagina' => $datos['url_pagina'],

                    'fecha' => $datos['fecha'],
                    'tamano_id' => $datos['tamano_id'],
                    'genero_id' => $datos['genero_id'],

                    'genero_autor_id' => $datos['genero_autor_id'],
                    'nombre_autor' => $datos['nombre_autor'],

                    'referencia' => $datos['referencia'],
                    'observaciones' => $datos['observaciones'],

                    'archivos' => $rutas_archivos,
                ]);

                return;
            }

            $registro = MonitoreoMedioElectronico::create([
                'tipo_medio' => $this->tipo_medio,

                'sujeto_id' => $datos['sujeto_id'],
                'organizacion_politica_id' => $datos['organizacion_politica_id'],
                'periodo_id' => $datos['periodo_id'],
                'etapa_sujeto' => $datos['etapa_sujeto'],
                'tipo_eleccion_id' => $datos['tipo_eleccion_id'],

                'portal_internet_id' => $datos['portal_internet_id'],
                'url_pagina' => $datos['url_pagina'],

                'fecha' => $datos['fecha'],
                'tamano_id' => $datos['tamano_id'],
                'genero_id' => $datos['genero_id'],

                'genero_autor_id' => $datos['genero_autor_id'],
                'nombre_autor' => $datos['nombre_autor'],

                'referencia' => $datos['referencia'],
                'observaciones' => $datos['observaciones'],

                'archivos' => null,
            ]);

            $rutas_archivos = $this->guardarArchivosDelRegistro($registro->id);

            $registro->update([
                'archivos' => $rutas_archivos,
            ]);
        });

        $this->dispatch('electronicos-registro-guardado', datos: [
            'sujeto_id' => $this->sujeto_id,
            'organizacion_politica_id' => $this->organizacion_politica_id,
            'periodo_id' => $this->periodo_id,
            'etapa_sujeto' => $this->etapa_sujeto,
            'tipo_eleccion_id' => $this->tipo_eleccion_id,
            'portal_internet_id' => $this->portal_internet_id,
            'url_pagina' => $this->url_pagina,
            'fecha' => $this->fecha,
            'tamano_id' => $this->tamano_id,
            'genero_id' => $this->genero_id,
            'genero_autor_id' => $this->genero_autor_id,
            'nombre_autor' => $this->nombre_autor,
            'referencia' => $this->referencia,
            'observaciones' => $this->observaciones,
        ]);

        $this->limpiarFormulario();

        session()->flash(
            'success',
            $esta_editando
                ? 'Registro actualizado correctamente.'
                : 'Registro de medio electrónico guardado correctamente.'
        );
    }

    private function guardarArchivosDelRegistro(int $registro_id, int $consecutivo_inicial = 1): array
    {
        $rutas_archivos = [];

        foreach ($this->archivos as $indice => $archivo) {
            $consecutivo = $consecutivo_inicial + $indice;

            $rutas_archivos[] = $this->guardarImagenOptimizada(
                archivo: $archivo,
                registro_id: $registro_id,
                consecutivo: $consecutivo
            );
        }

        return $rutas_archivos;
    }


    //endregion

    //region LIMPIEZA

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

            'selector_portal',
            'portal_internet_id',
            'url_pagina',

            'tamano_id',
            'genero_id',

            'genero_autor_id',
            'nombre_autor',

            'referencia',
            'observaciones',
            'archivos',

            'mostrar_formulario_portal',
            'nuevo_portal',
            'registro_editando_id',
            'archivos_existentes',
            'archivos_eliminados',
        ]);

        $this->fecha = now()->format('Y-m-d');

        $this->nuevo_portal = [
            'nombre' => '',
            'url' => '',
            'ciudad' => '',
            'tipo' => '',
        ];

        $this->resetValidation();
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

    //endregion

    //region TABLA DE REGISTROS
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

    private function consultarRegistros()
    {
        return MonitoreoMedioElectronico::query()
            ->leftJoin('sujetos', 'monitoreo_medios_electronicos.sujeto_id', '=', 'sujetos.id')
            ->leftJoin('partidos', 'monitoreo_medios_electronicos.organizacion_politica_id', '=', 'partidos.id')
            ->leftJoin('portales_internet', 'monitoreo_medios_electronicos.portal_internet_id', '=', 'portales_internet.id')
            ->select([
                'monitoreo_medios_electronicos.id',
                'monitoreo_medios_electronicos.referencia',
                'monitoreo_medios_electronicos.fecha',
                'monitoreo_medios_electronicos.url_pagina',
                'monitoreo_medios_electronicos.archivos',
                'monitoreo_medios_electronicos.created_at',
                'sujetos.nombre as sujeto_nombre',
                'partidos.nombre as organizacion_nombre',
                'portales_internet.nombre as portal_nombre',
            ])
            ->where('monitoreo_medios_electronicos.tipo_medio', $this->tipo_medio)
            ->when($this->fecha_inicio_registro, function ($query) {
                $query->whereDate('monitoreo_medios_electronicos.created_at', '>=', $this->fecha_inicio_registro);
            })
            ->when($this->fecha_fin_registro, function ($query) {
                $query->whereDate('monitoreo_medios_electronicos.created_at', '<=', $this->fecha_fin_registro);
            })
            ->when($this->filtro_tipo_eleccion_id !== '', function ($query) {
                $query->where(
                    'monitoreo_medios_electronicos.tipo_eleccion_id',
                    $this->filtro_tipo_eleccion_id
                );
            })
            ->when($this->busqueda_tabla, function ($query) {
                $texto_busqueda = trim($this->busqueda_tabla);
                $busqueda = '%' . $texto_busqueda . '%';

                $query->where(function ($q) use ($texto_busqueda, $busqueda) {
                    if (is_numeric($texto_busqueda)) {
                        $q->where('monitoreo_medios_electronicos.id', (int) $texto_busqueda);
                    }

                    $q->orWhere('monitoreo_medios_electronicos.referencia', 'like', $busqueda)
                        ->orWhere('monitoreo_medios_electronicos.observaciones', 'like', $busqueda)
                        ->orWhere('monitoreo_medios_electronicos.url_pagina', 'like', $busqueda)
                        ->orWhere('sujetos.nombre', 'like', $busqueda)
                        ->orWhere('partidos.nombre', 'like', $busqueda)
                        ->orWhere('portales_internet.nombre', 'like', $busqueda);
                });
            })
            ->orderByDesc('monitoreo_medios_electronicos.id')
            ->paginate($this->cantidad_por_pagina);
    }
    //endregion

    //region CUALITATIVOS

    public function abrirCualitativos(int $id): void
    {
        $registro = MonitoreoMedioElectronico::query()
            ->leftJoin('sujetos', 'monitoreo_medios_electronicos.sujeto_id', '=', 'sujetos.id')
            ->leftJoin('partidos', 'monitoreo_medios_electronicos.organizacion_politica_id', '=', 'partidos.id')
            ->leftJoin('periodos', 'monitoreo_medios_electronicos.periodo_id', '=', 'periodos.id')
            ->leftJoin('portales_internet', 'monitoreo_medios_electronicos.portal_internet_id', '=', 'portales_internet.id')
            ->leftJoin('tamanos_publicacion', 'monitoreo_medios_electronicos.tamano_id', '=', 'tamanos_publicacion.id')
            ->leftJoin('generos', 'monitoreo_medios_electronicos.genero_id', '=', 'generos.id')
            ->leftJoin('generos_sujetos', 'monitoreo_medios_electronicos.genero_autor_id', '=', 'generos_sujetos.id')
            ->where('monitoreo_medios_electronicos.id', $id)
            ->select([
                'monitoreo_medios_electronicos.*',
                'sujetos.nombre as sujeto_nombre',
                'partidos.nombre as organizacion_nombre',
                'periodos.nombre as periodo_nombre',
                'portales_internet.nombre as portal_nombre',
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
            'fecha' => $registro->fecha ? $registro->fecha->format('d-M-Y') : 'Sin fecha',
            'portal' => $registro->portal_nombre ?? 'Sin medio electrónico',
            'tamano' => $registro->tamano_nombre ?? 'Sin tamaño',
            'genero' => $registro->genero_nombre ?? 'Sin género',
            'url' => $registro->url_pagina ?? '',
            'referencia' => $registro->referencia ?? '',
            'observaciones' => $registro->observaciones ?? '',
        ];

        $this->imagenes_cualitativas = is_array($registro->archivos)
            ? array_values($registro->archivos)
            : [];

        $this->imagen_actual_indice = 0;
        $this->modal_imagen_abierto = false;

        $this->cuali_valoracion = $registro->cuali_valoracion;
        $this->cuali_lenguaje_inclusivo = $registro->cuali_lenguaje_inclusivo;
        $this->cuali_estereotipo = $registro->cuali_estereotipo;
        $this->cuali_violencia_temas_id = $registro->cuali_violencia_temas_id;
        $this->cuali_tipos_eleccion_id = $registro->cuali_tipos_eleccion_id;
        $this->cuali_resumen = $registro->cuali_resumen;
        $this->cuali_modalidad = $registro->cuali_modalidad;
        $this->cuali_objetividad = $registro->cuali_objetividad;
        $this->cuali_tipo_mensaje = $registro->cuali_tipo_mensaje;
        $this->cuali_formato = $registro->cuali_formato;

        $this->mostrar_panel_cualitativo = true;
        $this->resetValidation();
    }

    public function guardarCualitativos(): void
    {
        if (! $this->registro_cualitativo_id) {
            session()->flash('error', 'No hay registro seleccionado.');
            return;
        }

        $datos = $this->validate([
            'cuali_valoracion' => 'nullable|in:Positiva,Negativa,Neutral',
            'cuali_lenguaje_inclusivo' => 'nullable|in:Si,No',
            'cuali_estereotipo' => 'nullable|string|max:255',
            'cuali_violencia_temas_id' => 'nullable|exists:violencia_temas,id',
            'cuali_tipos_eleccion_id' => 'nullable|exists:tipos_eleccion,id',
            'cuali_resumen' => 'nullable|string|max:255',
            'cuali_modalidad' => 'nullable|in:Politica,Electoral',
            'cuali_objetividad' => 'nullable|string|max:255',
            'cuali_tipo_mensaje' => 'nullable|in:A favor,Descalificativo,Crítica,Imparcial',
            'cuali_formato' => 'nullable|in:Mensaje,De entrevista,Informativo-narrativo',
        ]);

        MonitoreoMedioElectronico::findOrFail($this->registro_cualitativo_id)
            ->update($datos);

        $this->dispatch('electronicos-cualitativos-guardados', datos: [
            'cuali_valoracion' => $this->cuali_valoracion,
            'cuali_lenguaje_inclusivo' => $this->cuali_lenguaje_inclusivo,
            'cuali_estereotipo' => $this->cuali_estereotipo,
            'cuali_violencia_temas_id' => $this->cuali_violencia_temas_id,
            'cuali_tipos_eleccion_id' => $this->cuali_tipos_eleccion_id,
            'cuali_resumen' => $this->cuali_resumen,
            'cuali_modalidad' => $this->cuali_modalidad,
            'cuali_objetividad' => $this->cuali_objetividad,
            'cuali_tipo_mensaje' => $this->cuali_tipo_mensaje,
            'cuali_formato' => $this->cuali_formato,
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
        $this->imagen_actual_indice = 0;
        $this->modal_imagen_abierto = false;

        $this->reset([
            'cuali_valoracion',
            'cuali_lenguaje_inclusivo',
            'cuali_estereotipo',
            'cuali_violencia_temas_id',
            'cuali_tipos_eleccion_id',
            'cuali_resumen',
            'cuali_modalidad',
            'cuali_objetividad',
            'cuali_tipo_mensaje',
            'cuali_formato',
        ]);

        $this->resetValidation();
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

    //endregion

    //region RECUPERAR INFO ANTERIOR

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

        $this->portal_internet_id = ! empty($datos['portal_internet_id']) ? (int) $datos['portal_internet_id'] : null;
        $this->selector_portal = $this->portal_internet_id ? (string) $this->portal_internet_id : '';
        $this->url_pagina = $datos['url_pagina'] ?? '';

        $this->fecha = $datos['fecha'] ?? now()->format('Y-m-d');
        $this->tamano_id = ! empty($datos['tamano_id']) ? (int) $datos['tamano_id'] : null;
        $this->genero_id = ! empty($datos['genero_id']) ? (int) $datos['genero_id'] : null;

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
        $this->cuali_modalidad = $datos['cuali_modalidad'] ?? null;
        $this->cuali_objetividad = $datos['cuali_objetividad'] ?? null;
        $this->cuali_tipo_mensaje = $datos['cuali_tipo_mensaje'] ?? null;
        $this->cuali_formato = $datos['cuali_formato'] ?? null;

        $this->resetValidation();

        session()->flash('success', 'Datos cualitativos recuperados.');
    }

    //endregion

    //region ELIMINAR

    public function confirmarEliminacion(int $id): void
    {
        $registro = MonitoreoMedioElectronico::findOrFail($id);

        $this->registro_eliminar_id = $registro->id;

        $this->registro_eliminar_referencia =
            $registro->referencia
            ?: 'Registro #' . $registro->id;

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

        $registro = MonitoreoMedioElectronico::findOrFail(
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
    //endregion
}
