<?php

namespace App\Http\Controllers;

use App\Models\MonitoreoMedioElectronico;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class MediosElectronicosController extends Controller
{
    public function index()
    {
        return view('medios-electronicos.index');
    }

    public function testigo(MonitoreoMedioElectronico $registro)
    {
        abort_unless($registro->tipo_medio === 'medios-electronicos', 404);

        $registro->load([]);

        $datos = MonitoreoMedioElectronico::query()
            ->leftJoin('sujetos', 'monitoreo_medios_electronicos.sujeto_id', '=', 'sujetos.id')
            ->leftJoin('partidos', 'monitoreo_medios_electronicos.organizacion_politica_id', '=', 'partidos.id')
            ->leftJoin('periodos', 'monitoreo_medios_electronicos.periodo_id', '=', 'periodos.id')
            ->leftJoin('tipos_eleccion', 'monitoreo_medios_electronicos.tipo_eleccion_id', '=', 'tipos_eleccion.id')
            ->leftJoin('portales_internet', 'monitoreo_medios_electronicos.portal_internet_id', '=', 'portales_internet.id')
            ->leftJoin('tamanos_publicacion', 'monitoreo_medios_electronicos.tamano_id', '=', 'tamanos_publicacion.id')
            ->leftJoin('generos', 'monitoreo_medios_electronicos.genero_id', '=', 'generos.id')
            ->where('monitoreo_medios_electronicos.id', $registro->id)
            ->select([
                'monitoreo_medios_electronicos.*',
                'sujetos.nombre as sujeto_nombre',
                'partidos.nombre as organizacion_nombre',
                'periodos.nombre as periodo_nombre',
                'tipos_eleccion.nombre as tipo_eleccion_nombre',
                'portales_internet.nombre as portal_nombre',
                'tamanos_publicacion.nombre as tamano_nombre',
                'generos.nombre as genero_nombre',
            ])
            ->firstOrFail();

        $archivos = is_array($datos->archivos) ? $datos->archivos : [];

        $rutas_imagenes = collect($archivos)
            ->take(2)
            ->filter(fn($archivo) => $archivo && Storage::disk('public')->exists($archivo))
            ->map(fn($archivo) => 'file://' . str_replace('\\', '/', storage_path('app/public/' . $archivo)))
            ->values()
            ->all();

        $pdf = Pdf::loadView('pdf.medios-electronicos.testigo', [
            'registro' => $datos,
            'rutas_imagenes' => $rutas_imagenes,
        ])->setPaper('letter', 'portrait');

        return $pdf->stream(
            'm_elect_' . $registro->id . '_' . now()->format('Ymd') . '.pdf'
        );
    }
}
