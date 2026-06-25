<?php

namespace App\Http\Controllers;

use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ReporteCapturasController extends Controller
{
    private function medios(): array
    {
        return [
            'monitoreo_medios_electronicos' => [
                'label' => 'Medios electrónicos',
                'tabla' => 'monitoreo_medios_electronicos',
                'fecha' => 'created_at',
            ],
            'monitoreo_medios_impresos' => [
                'label' => 'Medios impresos',
                'tabla' => 'monitoreo_medios_impresos',
                'fecha' => 'created_at',
            ],
            'monitoreo_radio' => [
                'label' => 'Radio',
                'tabla' => 'monitoreo_radio',
                'fecha' => 'created_at',
            ],
            'monitoreo_television' => [
                'label' => 'Televisión',
                'tabla' => 'monitoreo_television',
                'fecha' => 'created_at',
            ],
            'monit_soportes_promocionales' => [
                'label' => 'Soportes promocionales',
                'tabla' => 'monit_soportes_promocionales',
                'fecha' => 'created_at',
            ],
            'monitoreo_propaganda_movil' => [
                'label' => 'Propaganda móvil',
                'tabla' => 'monitoreo_propaganda_movil',
                'fecha' => 'created_at',
            ],
            'monitoreo_cine' => [
                'label' => 'Cine',
                'tabla' => 'monitoreo_cine',
                'fecha' => 'created_at',
            ],
        ];
    }

    public function index(Request $request)
    {
        $medios = $this->medios();
        $resultados = collect();
        $totalCapturas = 0;
        $consultado = $request->has('consultar');
        $tipoSeleccionado = null;

        if ($consultado) {
            $validated = $request->validate([
                'tipo' => ['required', Rule::in(array_keys($medios))],
                'fecha_inicial' => ['nullable', 'date'],
                'fecha_final' => ['nullable', 'date', 'after_or_equal:fecha_inicial'],
            ]);

            $tipoSeleccionado = $validated['tipo'];

            $resultados = $this->generarResultados(
                $validated['tipo'],
                $validated['fecha_inicial'] ?? null,
                $validated['fecha_final'] ?? null
            );

            $totalCapturas = $resultados->sum('total');
        }

        return view('reportes.capturas.index', compact(
            'medios',
            'resultados',
            'totalCapturas',
            'consultado',
            'tipoSeleccionado'
        ));
    }

    public function pdf(Request $request)
    {
        $medios = $this->medios();

        $validated = $request->validate([
            'tipo' => ['required', Rule::in(array_keys($medios))],
            'fecha_inicial' => ['nullable', 'date'],
            'fecha_final' => ['nullable', 'date', 'after_or_equal:fecha_inicial'],
        ]);

        $resultados = $this->generarResultados(
            $validated['tipo'],
            $validated['fecha_inicial'] ?? null,
            $validated['fecha_final'] ?? null
        );

        $pdf = Pdf::loadView('pdf.reportes.capturas', [
            'resultados' => $resultados,
            'medio' => $medios[$validated['tipo']]['label'],
            'fecha_inicial' => $validated['fecha_inicial'] ?? null,
            'fecha_final' => $validated['fecha_final'] ?? null,
            'totalCapturas' => $resultados->sum('total'),
        ])->setPaper('letter', 'portrait');

        return $pdf->stream('reporte_capturas_' . now()->format('Ymd_His') . '.pdf');
    }

    private function generarResultados(string $tipo, ?string $fechaInicial, ?string $fechaFinal)
    {
        $medio = $this->medios()[$tipo];

        $queryConteos = DB::table($medio['tabla'])
            ->select('usuario1_id', DB::raw('COUNT(*) as total'))
            ->whereNotNull('usuario1_id');

        if ($fechaInicial) {
            $queryConteos->whereDate($medio['fecha'], '>=', $fechaInicial);
        }

        if ($fechaFinal) {
            $queryConteos->whereDate($medio['fecha'], '<=', $fechaFinal);
        }

        $conteos = $queryConteos
            ->groupBy('usuario1_id')
            ->pluck('total', 'usuario1_id');

        return User::role('Capturista')
            ->select('id', 'name', 'email')
            ->orderBy('name')
            ->get()
            ->map(function ($user) use ($conteos) {
                return (object) [
                    'id' => $user->id,
                    'nombre' => $user->name,
                    'email' => $user->email,
                    'total' => (int) ($conteos[$user->id] ?? 0),
                ];
            });
    }
}
