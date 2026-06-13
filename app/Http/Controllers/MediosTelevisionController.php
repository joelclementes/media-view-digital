<?php

namespace App\Http\Controllers;

use App\Models\MonitoreoMedioTelevision;
use Barryvdh\DomPDF\Facade\Pdf;

class MediosTelevisionController extends Controller
{
    public function index()
    {
        return view('medios-television.index');
    }

    public function show(int $registro)
    {
        $registro = MonitoreoMedioTelevision::query()
            ->leftJoin('sujetos', 'monitoreo_television.sujeto_id', '=', 'sujetos.id')
            ->leftJoin('partidos', 'monitoreo_television.organizacion_id', '=', 'partidos.id')
            ->leftJoin('periodos', 'monitoreo_television.periodo_id', '=', 'periodos.id')
            ->leftJoin('tipos_eleccion', 'monitoreo_television.tipo_eleccion_id', '=', 'tipos_eleccion.id')
            ->leftJoin('municipios', 'monitoreo_television.medio_municipio_id', '=', 'municipios.id')
            ->leftJoin('municipios as plazas', 'monitoreo_television.medio_plaza_id', '=', 'plazas.id')
            ->leftJoin('generos_sujetos', 'monitoreo_television.genero_autor_id', '=', 'generos_sujetos.id')
            ->leftJoin('violencia_temas', 'monitoreo_television.cuali_violencia_temas_id', '=', 'violencia_temas.id')
            ->leftJoin('tipos_eleccion as cuali_tipos_eleccion', 'monitoreo_television.cuali_tipos_eleccion_id', '=', 'cuali_tipos_eleccion.id')
            ->where('monitoreo_television.id', $registro)
            ->where('monitoreo_television.tipo_medio', 'medios-television')
            ->select([
                'monitoreo_television.*',
                'sujetos.nombre as sujeto_nombre',
                'partidos.nombre as organizacion_nombre',
                'periodos.nombre as periodo_nombre',
                'tipos_eleccion.nombre as tipo_eleccion_nombre',
                'municipios.nombre as municipio_nombre',
                'plazas.nombre as plaza_nombre',
                'generos_sujetos.nombre as genero_autor_nombre',
                'violencia_temas.nombre as violencia_tema_nombre',
                'cuali_tipos_eleccion.nombre as cuali_tipo_eleccion_nombre',
            ])
            ->firstOrFail();

        return view('medios-television.show', compact('registro'));
    }

    public function testigo(MonitoreoMedioTelevision $registro)
    {
        abort_unless($registro->tipo_medio === 'medios-television', 404);

        $datos = MonitoreoMedioTelevision::query()
            ->leftJoin('sujetos', 'monitoreo_television.sujeto_id', '=', 'sujetos.id')
            ->leftJoin('partidos', 'monitoreo_television.organizacion_id', '=', 'partidos.id')
            ->leftJoin('periodos', 'monitoreo_television.periodo_id', '=', 'periodos.id')
            ->leftJoin('tipos_eleccion', 'monitoreo_television.tipo_eleccion_id', '=', 'tipos_eleccion.id')
            ->leftJoin('municipios', 'monitoreo_television.medio_municipio_id', '=', 'municipios.id')
            ->leftJoin('municipios as plazas', 'monitoreo_television.medio_plaza_id', '=', 'plazas.id')
            ->leftJoin('generos_sujetos', 'monitoreo_television.genero_autor_id', '=', 'generos_sujetos.id')
            ->where('monitoreo_television.id', $registro->id)
            ->select([
                'monitoreo_television.*',
                'sujetos.nombre as sujeto_nombre',
                'partidos.nombre as organizacion_nombre',
                'periodos.nombre as periodo_nombre',
                'tipos_eleccion.nombre as tipo_eleccion_nombre',
                'municipios.nombre as municipio_nombre',
                'plazas.nombre as plaza_nombre',
                'generos_sujetos.nombre as genero_autor_nombre',
            ])
            ->firstOrFail();

        $pdf = Pdf::loadView('pdf.medios-television.testigo', [
            'registro' => $datos,
        ])->setPaper('letter', 'portrait');

        return $pdf->stream(
            'm_television_' . $registro->id . '_' . now()->format('Ymd') . '.pdf'
        );
    }
}
