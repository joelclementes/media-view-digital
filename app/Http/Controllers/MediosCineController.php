<?php

namespace App\Http\Controllers;

use App\Models\MonitoreoMedioCine;
use Barryvdh\DomPDF\Facade\Pdf;

class MediosCineController extends Controller
{
    public function index()
    {
        return view('medios-cine.index');
    }

    public function show(int $registro)
    {
        $registro = MonitoreoMedioCine::query()
            ->leftJoin('sujetos', 'monitoreo_cine.sujeto_id', '=', 'sujetos.id')
            ->leftJoin('partidos', 'monitoreo_cine.organizacion_id', '=', 'partidos.id')
            ->leftJoin('periodos', 'monitoreo_cine.periodo_id', '=', 'periodos.id')
            ->leftJoin('tipos_eleccion', 'monitoreo_cine.tipo_eleccion_id', '=', 'tipos_eleccion.id')
            ->leftJoin('cines', 'monitoreo_cine.medio_cine_id', '=', 'cines.id')
            ->leftJoin('distritos', 'monitoreo_cine.medio_distrito_id', '=', 'distritos.id')
            ->leftJoin('municipios', 'monitoreo_cine.medio_municipio_id', '=', 'municipios.id')
            ->leftJoin('localidades', 'monitoreo_cine.medio_localidad_id', '=', 'localidades.id')
            ->leftJoin('violencia_temas', 'monitoreo_cine.cuali_violencia_temas_id', '=', 'violencia_temas.id')
            ->where('monitoreo_cine.id', $registro)
            ->where('monitoreo_cine.tipo_medio', 'medios-cine')
            ->select([
                'monitoreo_cine.*',
                'sujetos.nombre as sujeto_nombre',
                'partidos.nombre as organizacion_nombre',
                'periodos.nombre as periodo_nombre',
                'tipos_eleccion.nombre as tipo_eleccion_nombre',
                'cines.nombre as cine_nombre',
                'cines.ubicacion as cine_ubicacion',
                'cines.ciudad as cine_ciudad',
                'cines.nombre_cine as cine_nombre_comercial',
                'distritos.nombre as distrito_nombre',
                'municipios.nombre as municipio_nombre',
                'localidades.nombre as localidad_nombre',
                'violencia_temas.nombre as violencia_tema_nombre',
            ])
            ->firstOrFail();

        return view('medios-cine.show', compact('registro'));
    }

    public function testigo(MonitoreoMedioCine $registro)
    {
        abort_unless($registro->tipo_medio === 'medios-cine', 404);

        $datos = MonitoreoMedioCine::query()
            ->leftJoin('sujetos', 'monitoreo_cine.sujeto_id', '=', 'sujetos.id')
            ->leftJoin('partidos', 'monitoreo_cine.organizacion_id', '=', 'partidos.id')
            ->leftJoin('periodos', 'monitoreo_cine.periodo_id', '=', 'periodos.id')
            ->leftJoin('tipos_eleccion', 'monitoreo_cine.tipo_eleccion_id', '=', 'tipos_eleccion.id')
            ->leftJoin('cines', 'monitoreo_cine.medio_cine_id', '=', 'cines.id')
            ->leftJoin('distritos', 'monitoreo_cine.medio_distrito_id', '=', 'distritos.id')
            ->leftJoin('municipios', 'monitoreo_cine.medio_municipio_id', '=', 'municipios.id')
            ->leftJoin('localidades', 'monitoreo_cine.medio_localidad_id', '=', 'localidades.id')
            ->where('monitoreo_cine.id', $registro->id)
            ->select([
                'monitoreo_cine.*',
                'sujetos.nombre as sujeto_nombre',
                'partidos.nombre as organizacion_nombre',
                'periodos.nombre as periodo_nombre',
                'tipos_eleccion.nombre as tipo_eleccion_nombre',
                'cines.nombre as cine_nombre',
                'cines.ubicacion as cine_ubicacion',
                'cines.ciudad as cine_ciudad',
                'cines.nombre_cine as cine_nombre_comercial',
                'distritos.nombre as distrito_nombre',
                'municipios.nombre as municipio_nombre',
                'localidades.nombre as localidad_nombre',
            ])
            ->firstOrFail();

        $pdf = Pdf::loadView('pdf.medios-cine.testigo', [
            'registro' => $datos,
        ])->setPaper('letter', 'portrait');

        return $pdf->stream('m_cine_' . $registro->id . '_' . now()->format('Ymd') . '.pdf');
    }
}
