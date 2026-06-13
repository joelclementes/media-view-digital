<?php

namespace App\Http\Controllers;

use App\Models\MonitoreoMedioRadio;
use Barryvdh\DomPDF\Facade\Pdf;

class MediosRadioController extends Controller
{
    public function index()
    {
        return view('medios-radio.index');
    }

    public function show(int $registro)
    {
        $registro = MonitoreoMedioRadio::query()
            ->leftJoin('sujetos', 'monitoreo_radio.sujeto_id', '=', 'sujetos.id')
            ->leftJoin('partidos', 'monitoreo_radio.organizacion_politica_id', '=', 'partidos.id')
            ->leftJoin('periodos', 'monitoreo_radio.periodo_id', '=', 'periodos.id')
            ->leftJoin('tipos_eleccion', 'monitoreo_radio.tipo_eleccion_id', '=', 'tipos_eleccion.id')
            ->leftJoin('municipios', 'monitoreo_radio.medio_municipio_id', '=', 'municipios.id')
            ->leftJoin('generos_sujetos', 'monitoreo_radio.genero_autor_id', '=', 'generos_sujetos.id')
            ->leftJoin('violencia_temas', 'monitoreo_radio.cuali_violencia_temas_id', '=', 'violencia_temas.id')
            ->leftJoin('tipos_eleccion as cuali_tipos_eleccion', 'monitoreo_radio.cuali_tipos_eleccion_id', '=', 'cuali_tipos_eleccion.id')
            ->where('monitoreo_radio.id', $registro)
            ->where('monitoreo_radio.tipo_medio', 'medios-radio')
            ->select([
                'monitoreo_radio.*',
                'sujetos.nombre as sujeto_nombre',
                'partidos.nombre as organizacion_nombre',
                'periodos.nombre as periodo_nombre',
                'tipos_eleccion.nombre as tipo_eleccion_nombre',
                'municipios.nombre as municipio_nombre',
                'generos_sujetos.nombre as genero_autor_nombre',
                'violencia_temas.nombre as violencia_tema_nombre',
                'cuali_tipos_eleccion.nombre as cuali_tipo_eleccion_nombre',
            ])
            ->firstOrFail();

        return view('medios-radio.show', compact('registro'));
    }

    public function testigo(MonitoreoMedioRadio $registro)
    {
        abort_unless($registro->tipo_medio === 'medios-radio', 404);

        $datos = MonitoreoMedioRadio::query()
            ->leftJoin('sujetos', 'monitoreo_radio.sujeto_id', '=', 'sujetos.id')
            ->leftJoin('partidos', 'monitoreo_radio.organizacion_politica_id', '=', 'partidos.id')
            ->leftJoin('periodos', 'monitoreo_radio.periodo_id', '=', 'periodos.id')
            ->leftJoin('tipos_eleccion', 'monitoreo_radio.tipo_eleccion_id', '=', 'tipos_eleccion.id')
            ->leftJoin('municipios', 'monitoreo_radio.medio_municipio_id', '=', 'municipios.id')
            ->leftJoin('generos_sujetos', 'monitoreo_radio.genero_autor_id', '=', 'generos_sujetos.id')
            ->where('monitoreo_radio.id', $registro->id)
            ->select([
                'monitoreo_radio.*',
                'sujetos.nombre as sujeto_nombre',
                'partidos.nombre as organizacion_nombre',
                'periodos.nombre as periodo_nombre',
                'tipos_eleccion.nombre as tipo_eleccion_nombre',
                'municipios.nombre as municipio_nombre',
                'generos_sujetos.nombre as genero_autor_nombre',
            ])
            ->firstOrFail();

        $pdf = Pdf::loadView('pdf.medios-radio.testigo', [
            'registro' => $datos,
        ])->setPaper('letter', 'portrait');

        return $pdf->stream(
            'm_radio_' . $registro->id . '_' . now()->format('Ymd') . '.pdf'
        );
    }
}
