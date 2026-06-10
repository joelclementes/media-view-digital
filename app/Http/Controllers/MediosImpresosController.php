<?php

namespace App\Http\Controllers;

use App\Models\MonitoreoMedioImpreso;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class MediosImpresosController extends Controller
{
    public function index()
    {
        return view('medios-impresos.index');
    }

    public function show(int $registro)
    {
        $registro = MonitoreoMedioImpreso::query()
            ->leftJoin('sujetos', 'monitoreo_medios_impresos.sujeto_id', '=', 'sujetos.id')
            ->leftJoin('partidos', 'monitoreo_medios_impresos.organizacion_politica_id', '=', 'partidos.id')
            ->leftJoin('periodos', 'monitoreo_medios_impresos.periodo_id', '=', 'periodos.id')
            ->leftJoin('tipos_eleccion', 'monitoreo_medios_impresos.tipo_eleccion_id', '=', 'tipos_eleccion.id')
            ->leftJoin('portales_prensa', 'monitoreo_medios_impresos.medio_prensa_id', '=', 'portales_prensa.id')
            ->leftJoin('tamanos_publicacion', 'monitoreo_medios_impresos.publicacion_tamano_id', '=', 'tamanos_publicacion.id')
            ->leftJoin('generos', 'monitoreo_medios_impresos.publicacion_genero_id', '=', 'generos.id')
            ->leftJoin('generos_sujetos', 'monitoreo_medios_impresos.genero_autor_id', '=', 'generos_sujetos.id')
            ->leftJoin('violencia_temas', 'monitoreo_medios_impresos.cuali_violencia_temas_id', '=', 'violencia_temas.id')
            ->leftJoin('tipos_eleccion as cuali_tipos_eleccion', 'monitoreo_medios_impresos.cuali_tipos_eleccion_id', '=', 'cuali_tipos_eleccion.id')
            ->leftJoin('distritos', 'monitoreo_medios_impresos.cuali_distritos_id', '=', 'distritos.id')
            ->where('monitoreo_medios_impresos.id', $registro)
            ->where('monitoreo_medios_impresos.tipo_medio', 'medios-impresos')
            ->select([
                'monitoreo_medios_impresos.*',
                'sujetos.nombre as sujeto_nombre',
                'partidos.nombre as organizacion_nombre',
                'periodos.nombre as periodo_nombre',
                'tipos_eleccion.nombre as tipo_eleccion_nombre',
                'portales_prensa.nombre as medio_prensa_nombre',
                'tamanos_publicacion.nombre as tamano_nombre',
                'generos.nombre as genero_nombre',
                'generos_sujetos.nombre as genero_autor_nombre',
                'violencia_temas.nombre as violencia_tema_nombre',
                'cuali_tipos_eleccion.nombre as cuali_tipo_eleccion_nombre',
                'distritos.nombre as distrito_nombre',
            ])
            ->firstOrFail();

        return view('medios-impresos.show', compact('registro'));
    }

    public function testigo(MonitoreoMedioImpreso $registro)
    {
        abort_unless($registro->tipo_medio === 'medios-impresos', 404);

        $datos = MonitoreoMedioImpreso::query()
            ->leftJoin('sujetos', 'monitoreo_medios_impresos.sujeto_id', '=', 'sujetos.id')
            ->leftJoin('partidos', 'monitoreo_medios_impresos.organizacion_politica_id', '=', 'partidos.id')
            ->leftJoin('periodos', 'monitoreo_medios_impresos.periodo_id', '=', 'periodos.id')
            ->leftJoin('tipos_eleccion', 'monitoreo_medios_impresos.tipo_eleccion_id', '=', 'tipos_eleccion.id')
            ->leftJoin('portales_prensa', 'monitoreo_medios_impresos.medio_prensa_id', '=', 'portales_prensa.id')
            ->leftJoin('tamanos_publicacion', 'monitoreo_medios_impresos.publicacion_tamano_id', '=', 'tamanos_publicacion.id')
            ->leftJoin('generos', 'monitoreo_medios_impresos.publicacion_genero_id', '=', 'generos.id')
            ->where('monitoreo_medios_impresos.id', $registro->id)
            ->select([
                'monitoreo_medios_impresos.*',
                'sujetos.nombre as sujeto_nombre',
                'partidos.nombre as organizacion_nombre',
                'periodos.nombre as periodo_nombre',
                'tipos_eleccion.nombre as tipo_eleccion_nombre',
                'portales_prensa.nombre as medio_prensa_nombre',
                'tamanos_publicacion.nombre as tamano_nombre',
                'generos.nombre as genero_nombre',
            ])
            ->firstOrFail();

        $archivos = is_array($datos->archivos) ? $datos->archivos : [];

        $rutas_imagenes = collect($archivos)
            ->take(2)
            ->filter(fn ($archivo) => $archivo && Storage::disk('public')->exists($archivo))
            ->map(fn ($archivo) => 'file://' . str_replace('\\', '/', storage_path('app/public/' . $archivo)))
            ->values()
            ->all();

        $pdf = Pdf::loadView('pdf.medios-impresos.testigo', [
            'registro' => $datos,
            'rutas_imagenes' => $rutas_imagenes,
        ])->setPaper('letter', 'portrait');

        return $pdf->stream(
            'm_impreso_' . $registro->id . '_' . now()->format('Ymd') . '.pdf'
        );
    }
}
