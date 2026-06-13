<?php

namespace App\Http\Controllers;

use App\Models\PropagandaMovil;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class MediosPropagandaMovilController extends Controller
{
    public function index()
    {
        return view('medios-propaganda-movil.index');
    }

    public function show(int $registro)
    {
        $registro = PropagandaMovil::query()
            ->leftJoin('sujetos', 'monitoreo_propaganda_movil.sujeto_id', '=', 'sujetos.id')
            ->leftJoin('partidos', 'monitoreo_propaganda_movil.organizacion_id', '=', 'partidos.id')
            ->leftJoin('periodos', 'monitoreo_propaganda_movil.periodo_id', '=', 'periodos.id')
            ->leftJoin('tipos_eleccion', 'monitoreo_propaganda_movil.tipo_eleccion_id', '=', 'tipos_eleccion.id')
            ->leftJoin('distritos', 'monitoreo_propaganda_movil.distrito_id', '=', 'distritos.id')
            ->leftJoin('municipios', 'monitoreo_propaganda_movil.municipio_id', '=', 'municipios.id')
            ->leftJoin('localidades', 'monitoreo_propaganda_movil.localidad_id', '=', 'localidades.id')
            ->leftJoin('tipo_publicidad', 'monitoreo_propaganda_movil.publicacion_tipo_id', '=', 'tipo_publicidad.id')
            ->leftJoin('violencia_temas', 'monitoreo_propaganda_movil.cuali_violencia_temas_id', '=', 'violencia_temas.id')
            ->where('monitoreo_propaganda_movil.id', $registro)
            ->where('monitoreo_propaganda_movil.tipo_medio', 'medios-propaganda-movil')
            ->select([
                'monitoreo_propaganda_movil.*',
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

        return view('medios-propaganda-movil.show', compact('registro'));
    }

    public function testigo(PropagandaMovil $registro)
    {
        abort_unless($registro->tipo_medio === 'medios-propaganda-movil', 404);

        $datos = PropagandaMovil::query()
            ->leftJoin('sujetos', 'monitoreo_propaganda_movil.sujeto_id', '=', 'sujetos.id')
            ->leftJoin('partidos', 'monitoreo_propaganda_movil.organizacion_id', '=', 'partidos.id')
            ->leftJoin('periodos', 'monitoreo_propaganda_movil.periodo_id', '=', 'periodos.id')
            ->leftJoin('tipos_eleccion', 'monitoreo_propaganda_movil.tipo_eleccion_id', '=', 'tipos_eleccion.id')
            ->leftJoin('distritos', 'monitoreo_propaganda_movil.distrito_id', '=', 'distritos.id')
            ->leftJoin('municipios', 'monitoreo_propaganda_movil.municipio_id', '=', 'municipios.id')
            ->leftJoin('localidades', 'monitoreo_propaganda_movil.localidad_id', '=', 'localidades.id')
            ->leftJoin('tipo_publicidad', 'monitoreo_propaganda_movil.publicacion_tipo_id', '=', 'tipo_publicidad.id')
            ->where('monitoreo_propaganda_movil.id', $registro->id)
            ->select([
                'monitoreo_propaganda_movil.*',
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

        $pdf = Pdf::loadView('pdf.medios-propaganda-movil.testigo', [
            'registro' => $datos,
            'rutas_imagenes' => $rutas_imagenes,
        ])->setPaper('letter', 'portrait');

        return $pdf->stream(
            'monitoreo_m_propaganda_movil_' . $registro->id . '_' . now()->format('Ymd') . '.pdf'
        );
    }
}
