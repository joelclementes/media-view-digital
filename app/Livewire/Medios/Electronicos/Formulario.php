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

    public ?int $genero_sujeto_id = null;
    public string $nombre_autor = '';

    //endregion

    //region PROPIEDADES: REFERENCIA Y OBSERVACIONES

    public string $referencia = '';
    public string $observaciones = '';

    //endregion

    //region PROPIEDADES: ARCHIVOS

    public array $archivos = [];

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
        $this->fecha_inicio_registro = now()->format('Y-m-d');
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
            3,
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

            'genero_sujeto_id' => 'required|exists:generos_sujetos,id',
            'nombre_autor' => 'nullable|string|max:255',

            'referencia' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string|max:255',

            'archivos' => 'required|array|min:1',
            'archivos.*' => 'image|max:10240|mimes:jpg,jpeg,png',
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

            'genero_sujeto_id.required' => 'Debes seleccionar el género del autor.',

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

    public function guardar(): void
    {
        $datos = $this->validate($this->rules(), $this->mensajes());

        DB::transaction(function () use ($datos) {
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

                'genero_sujeto_id' => $datos['genero_sujeto_id'],
                'nombre_autor' => $datos['nombre_autor'],

                'referencia' => $datos['referencia'],
                'observaciones' => $datos['observaciones'],

                'archivos' => [],
                'payload' => [],
            ]);

            $rutas_archivos = $this->guardarArchivosDelRegistro($registro->id);

            $payload = $this->crearPayload($datos, $rutas_archivos);

            $registro->update([
                'archivos' => $rutas_archivos,
                'payload' => $payload,
            ]);
        });

        $this->limpiarFormulario();

        session()->flash('success', 'Registro de medio electrónico guardado correctamente.');
    }

    private function guardarArchivosDelRegistro(int $registro_id): array
    {
        $rutas_archivos = [];

        foreach ($this->archivos as $indice => $archivo) {
            $consecutivo = $indice + 1;

            $rutas_archivos[] = $this->guardarImagenOptimizada(
                archivo: $archivo,
                registro_id: $registro_id,
                consecutivo: $consecutivo
            );
        }

        return $rutas_archivos;
    }

    private function crearPayload(array $datos, array $rutas_archivos): array
    {
        return [
            'persona' => [
                'sujeto_id' => $datos['sujeto_id'],
                'organizacion_politica_id' => $datos['organizacion_politica_id'],
                'periodo_id' => $datos['periodo_id'],
                'etapa_sujeto' => $datos['etapa_sujeto'],
                'tipo_eleccion_id' => $datos['tipo_eleccion_id'],
            ],
            'medio' => [
                'portal_internet_id' => $datos['portal_internet_id'],
                'url_pagina' => $datos['url_pagina'],
            ],
            'publicacion' => [
                'fecha' => $datos['fecha'],
                'tamano_id' => $datos['tamano_id'],
                'genero_id' => $datos['genero_id'],
            ],
            'autor' => [
                'genero_sujeto_id' => $datos['genero_sujeto_id'],
                'nombre_autor' => $datos['nombre_autor'],
            ],
            'referencia' => [
                'referencia' => $datos['referencia'],
            ],
            'observaciones' => [
                'observaciones' => $datos['observaciones'],
            ],
            'archivos' => $rutas_archivos,
        ];
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

            'genero_sujeto_id',
            'nombre_autor',

            'referencia',
            'observaciones',
            'archivos',

            'mostrar_formulario_portal',
            'nuevo_portal',
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
        $this->fecha_inicio_registro = now()->format('Y-m-d');
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
}
