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
use Livewire\Component;
use Livewire\WithFileUploads;

class Formulario extends Component
{
    use WithFileUploads;

    public string $tipo_medio = 'medios-electronicos';

    public string $busqueda_sujeto = '';
    public array $resultados_sujetos = [];
    public $sujeto_seleccionado = null;

    public ?int $sujeto_id = null;
    public ?int $organizacion_politica_id = null;
    public ?int $periodo_id = null;
    public ?string $etapa_sujeto = null;
    public ?int $tipo_eleccion_id = null;

    public ?string $selector_portal = '';
    public ?int $portal_internet_id = null;
    public string $url_pagina = '';

    public string $fecha = '';
    public ?int $tamano_id = null;
    public ?int $genero_id = null;

    public ?int $genero_sujeto_id = null;
    public string $nombre_autor = '';

    public string $referencia = '';
    public string $observaciones = '';

    public array $archivos = [];

    public bool $mostrar_formulario_portal = false;

    public array $nuevo_portal = [
        'nombre' => '',
        'url' => '',
        'ciudad' => '',
        'tipo' => '',
    ];

    public $partidos;
    public $periodos;
    public $tipos_eleccion;
    public $portales;
    public $tamanos;
    public $generos;
    public $generos_sujeto;

    public array $etapas_sujeto = [
        'candidatura' => 'Candidatura',
        'precandidatura' => 'Precandidatura',
        'candidatura_independiente' => 'Candidatura independiente',
    ];

    public function mount(): void
    {
        $this->fecha = now()->format('Y-m-d');

        $this->cargarCatalogos();
    }

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

    public function updated($propiedad): void
    {
        if (array_key_exists($propiedad, $this->rules())) {
            $this->validateOnly($propiedad);
        }
    }

    public function guardar(): void
    {
        $datos = $this->validate($this->rules(), $this->mensajes());

        $rutas_archivos = [];

        DB::transaction(function () use ($datos, &$rutas_archivos) {
            foreach ($this->archivos as $archivo) {
                $rutas_archivos[] = $archivo->store('medios/electronicos', 'public');
            }

            MonitoreoMedioElectronico::create([
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

                'archivos' => $rutas_archivos,

                'payload' => [
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
                ],
            ]);
        });

        $this->limpiarFormulario();

        session()->flash('success', 'Registro de medio electrónico guardado correctamente.');
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
            'archivos.*' => 'file|max:10240|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx,mp3,mp4',
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
            'archivos.*.file' => 'Uno de los archivos no es válido.',
            'archivos.*.mimes' => 'Formato de archivo no permitido.',
            'archivos.*.max' => 'Cada archivo puede pesar máximo 10 MB.',
        ];
    }

    public function render()
    {
        return view('livewire.medios.electronicos.formulario');
    }
}