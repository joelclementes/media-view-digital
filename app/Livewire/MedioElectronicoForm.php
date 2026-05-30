<?php

namespace App\Livewire;

use App\Models\MonitoreoMedioElectronico;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class MedioElectronicoForm extends Component
{
    use WithFileUploads;

    public array $persona = [];
    public array $medio = [];
    public array $publicacion = [];
    public array $autor = [];
    public array $referencia = [];
    public array $observaciones = [];
    public array $archivos = [];

    public string $tipoMedio = 'medios-electronicos';

    protected $listeners = [
        'updatedPersona' => 'handleUpdatedPersona',
        'updatedMedioElectronico' => 'handleUpdatedMedioElectronico',
        'updatedPublicacionElectronico' => 'handleUpdatedPublicacionElectronico',
        'updatedAutorElectronico' => 'handleUpdatedAutorElectronico',
        'updatedReferencia' => 'handleUpdatedReferencia',
        'updatedObservaciones' => 'handleUpdatedObservaciones',
    ];

    public function mount(): void
    {
        $this->persona = $this->defaultPersona();
        $this->medio = $this->defaultMedio();
        $this->publicacion = $this->defaultPublicacion();
        $this->autor = $this->defaultAutor();
        $this->referencia = $this->defaultReferencia();
        $this->observaciones = $this->defaultObservaciones();
    }

    public function handleUpdatedPersona($payload = []): void
    {
        $this->persona = array_merge($this->defaultPersona(), $payload['payload'] ?? $payload);
    }

    public function handleUpdatedMedioElectronico($payload = []): void
    {
        $this->medio = array_merge($this->defaultMedio(), $payload['payload'] ?? $payload);
    }

    public function handleUpdatedPublicacionElectronico($payload = []): void
    {
        $this->publicacion = array_merge($this->defaultPublicacion(), $payload['payload'] ?? $payload);
    }

    public function handleUpdatedAutorElectronico($payload = []): void
    {
        $this->autor = array_merge($this->defaultAutor(), $payload['payload'] ?? $payload);
    }

    public function handleUpdatedReferencia($payload = []): void
    {
        $this->referencia = array_merge($this->defaultReferencia(), $payload['payload'] ?? $payload);
    }

    public function handleUpdatedObservaciones($payload = []): void
    {
        $this->observaciones = array_merge($this->defaultObservaciones(), $payload['payload'] ?? $payload);
    }

    public function updatedArchivos(): void
    {
        $this->validateOnly('archivos', [
            'archivos' => 'required|array|min:1',
            'archivos.*' => 'file|max:10240|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx,mp3,mp4',
        ]);
    }

    public function eliminarArchivo(int $index): void
    {
        if (isset($this->archivos[$index])) {
            unset($this->archivos[$index]);
            $this->archivos = array_values($this->archivos);
        }
    }

    public function guardar(): void
    {
        $validated = $this->validate($this->rules(), $this->messages());
        $rutasArchivos = [];

        DB::transaction(function () use ($validated, &$rutasArchivos) {
            $directorio = $this->resolveDirectorioArchivos();

            foreach ($this->archivos as $archivo) {
                $rutasArchivos[] = $archivo->store($directorio, 'public');
            }

            MonitoreoMedioElectronico::create([
                'tipo_medio' => $this->tipoMedio,
                'sujeto_id' => $validated['persona']['sujeto_id'],
                'organizacion_politica_id' => $validated['persona']['organizacion_politica_id'],
                'periodo_id' => $validated['persona']['periodo_id'],
                'etapa_sujeto' => $validated['persona']['etapa_sujeto'],
                'tipo_eleccion_id' => $validated['persona']['tipo_eleccion_id'],
                'portal_internet_id' => $validated['medio']['portal_internet_id'],
                'url_pagina' => $validated['medio']['url_pagina'],
                'fecha' => $validated['publicacion']['fecha'],
                'tamano_id' => $validated['publicacion']['tamano_id'],
                'genero_id' => $validated['publicacion']['genero_id'],
                'genero_sujeto_id' => $validated['autor']['genero_sujeto_id'],
                'nombre_autor' => $validated['autor']['nombre_autor'],
                'referencia' => $validated['referencia']['referencia'],
                'observaciones' => $validated['observaciones']['observaciones'],
                'archivos' => $rutasArchivos,
                'payload' => [
                    'persona' => $validated['persona'],
                    'medio' => $validated['medio'],
                    'publicacion' => $validated['publicacion'],
                    'autor' => $validated['autor'],
                    'referencia' => $validated['referencia'],
                    'observaciones' => $validated['observaciones'],
                    'archivos' => $rutasArchivos,
                ],
            ]);
        });

        $this->reset('archivos');
        session()->flash('success', 'Registro de medio electrónico guardado correctamente con archivos adjuntos.');
    }

    public function render()
    {
        return view('livewire.m-electronicos.medio-electronico-form');
    }

    private function rules(): array
    {
        return [
            'persona.sujeto_id' => 'required|exists:sujetos,id',
            'persona.organizacion_politica_id' => 'nullable|exists:partidos,id',
            'persona.periodo_id' => 'nullable|exists:periodos,id',
            'persona.etapa_sujeto' => 'nullable|in:candidatura,precandidatura,candidatura_independiente',
            'persona.tipo_eleccion_id' => 'nullable|exists:tipos_eleccion,id',
            'medio.portal_internet_id' => 'nullable|exists:portales_internet,id',
            'medio.url_pagina' => 'required|url',
            'publicacion.fecha' => 'required|date',
            'publicacion.tamano_id' => 'required|exists:tamanos_publicacion,id',
            'publicacion.genero_id' => 'required|exists:generos,id',
            'autor.genero_sujeto_id' => 'required|exists:generos_sujetos,id',
            'autor.nombre_autor' => 'nullable|string|max:255',
            'referencia.referencia' => 'nullable|string|max:255',
            'observaciones.observaciones' => 'nullable|string|max:255',
            'archivos' => 'required|array|min:1',
            'archivos.*' => 'file|max:10240|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx,mp3,mp4',
        ];
    }

    private function messages(): array
    {
        return [
            'persona.sujeto_id.required' => 'Debes seleccionar un sujeto.',
            'medio.url_pagina.required' => 'Debes indicar la URL de la publicación.',
            'publicacion.fecha.required' => 'Debes capturar la fecha de publicación.',
            'publicacion.tamano_id.required' => 'Debes seleccionar un tamaño de publicación.',
            'publicacion.genero_id.required' => 'Debes seleccionar un género periodístico.',
            'autor.genero_sujeto_id.required' => 'Debes seleccionar el género del autor.',
            'archivos.required' => 'Debes seleccionar al menos un archivo.',
            'archivos.*.mimes' => 'Formato de archivo no permitido.',
            'archivos.*.max' => 'Cada archivo puede pesar máximo 10 MB.',
        ];
    }

    private function resolveDirectorioArchivos(): string
    {
        $mapa = [
            'medios-electronicos' => 'medios/electronicos',
            'medios-impresos' => 'medios/impresos',
            'cine' => 'medios/cine',
            'radio' => 'medios/radio',
            'television' => 'medios/television',
            'propaganda-movil' => 'medios/propaganda-movil',
            'soportes-promocionales' => 'medios/soportes-promocionales',
        ];

        return $mapa[$this->tipoMedio] ?? 'medios/otros';
    }

    private function defaultPersona(): array
    {
        return [
            'sujeto_id' => null,
            'organizacion_politica_id' => null,
            'periodo_id' => null,
            'etapa_sujeto' => null,
            'tipo_eleccion_id' => null,
        ];
    }

    private function defaultMedio(): array
    {
        return [
            'portal_internet_id' => null,
            'url_pagina' => null,
        ];
    }

    private function defaultPublicacion(): array
    {
        return [
            'fecha' => null,
            'tamano_id' => null,
            'genero_id' => null,
        ];
    }

    private function defaultAutor(): array
    {
        return [
            'genero_sujeto_id' => null,
            'nombre_autor' => null,
        ];
    }

    private function defaultReferencia(): array
    {
        return [
            'referencia' => null,
        ];
    }

    private function defaultObservaciones(): array
    {
        return [
            'observaciones' => null,
        ];
    }
}
