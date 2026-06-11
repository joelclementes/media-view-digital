<?php

namespace App\Http\Controllers;

use App\Models\SoportePromocional;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class MediosSoportesPromocionalesController extends Controller
{
    public function index()
    {
        return view('medios-soportes-promocionales.index');
    }

    public function show(int $registro)
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
            ->leftJoin('violencia_temas', 'soportes_promocionales.cuali_violencia_temas_id', '=', 'violencia_temas.id')
            ->where('soportes_promocionales.id', $registro)
            ->where('soportes_promocionales.tipo_medio', 'medios-soportes-promocionales')
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
                'violencia_temas.nombre as violencia_tema_nombre',
            ])
            ->firstOrFail();

        return view('medios-soportes-promocionales.show', compact('registro'));
    }

    public function testigo(SoportePromocional $registro)
    {
        abort_unless($registro->tipo_medio === 'medios-soportes-promocionales', 404);

        $datos = SoportePromocional::query()
            ->leftJoin('sujetos', 'soportes_promocionales.sujeto_id', '=', 'sujetos.id')
            ->leftJoin('partidos', 'soportes_promocionales.organizacion_id', '=', 'partidos.id')
            ->leftJoin('periodos', 'soportes_promocionales.periodo_id', '=', 'periodos.id')
            ->leftJoin('tipos_eleccion', 'soportes_promocionales.tipo_eleccion_id', '=', 'tipos_eleccion.id')
            ->leftJoin('distritos', 'soportes_promocionales.distrito_id', '=', 'distritos.id')
            ->leftJoin('municipios', 'soportes_promocionales.municipio_id', '=', 'municipios.id')
            ->leftJoin('localidades', 'soportes_promocionales.localidad_id', '=', 'localidades.id')
            ->leftJoin('tipo_publicidad', 'soportes_promocionales.publicacion_tipo_id', '=', 'tipo_publicidad.id')
            ->where('soportes_promocionales.id', $registro->id)
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

        $archivos = is_array($datos->archivos) ? $datos->archivos : [];

        $rutas_imagenes = collect($archivos)
            ->take(4)
            ->filter(fn ($archivo) => $archivo && Storage::disk('public')->exists($archivo))
            ->map(fn ($archivo) => 'file://' . str_replace('\\', '/', storage_path('app/public/' . $archivo)))
            ->values()
            ->all();

        $pdf = Pdf::loadView('pdf.medios-soportes-promocionales.testigo', [
            'registro' => $datos,
            'rutas_imagenes' => $rutas_imagenes,
        ])->setPaper('letter', 'portrait');

        return $pdf->stream(
            'm_soportes_' . $registro->id . '_' . now()->format('Ymd') . '.pdf'
        );
    }
}
