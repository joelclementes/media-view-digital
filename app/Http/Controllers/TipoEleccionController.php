<?php

namespace App\Http\Controllers;

use App\Models\TipoEleccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TipoEleccionController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');

        $tiposEleccion = TipoEleccion::query()
            ->when($buscar, function ($query) use ($buscar) {
                $query->where('nombre', 'like', '%' . $buscar . '%');
            })
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();

        return view('catalogos.tipos-eleccion.index', [
            'tiposEleccion' => $tiposEleccion,
            'buscar' => $buscar,
            'tipoEleccionEdit' => null,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => ['required', 'string', 'max:255', 'unique:tipos_eleccion,nombre'],
        ]);

        TipoEleccion::create([
            'nombre' => $request->nombre,
        ]);

        return redirect()
            ->route('cat-tipos-eleccion.index')
            ->with('success', 'Tipo de elección guardado correctamente.');
    }

    public function edit(Request $request, TipoEleccion $tipoEleccion)
    {
        $buscar = $request->input('buscar');

        $tiposEleccion = TipoEleccion::query()
            ->when($buscar, function ($query) use ($buscar) {
                $query->where('nombre', 'like', '%' . $buscar . '%');
            })
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();

        return view('catalogos.tipos-eleccion.index', [
            'tiposEleccion' => $tiposEleccion,
            'buscar' => $buscar,
            'tipoEleccionEdit' => $tipoEleccion,
        ]);
    }

    public function update(Request $request, TipoEleccion $tipoEleccion)
    {
        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
                'unique:tipos_eleccion,nombre,' . $tipoEleccion->id,
            ],
        ]);

        $tipoEleccion->update([
            'nombre' => $request->nombre,
        ]);

        return redirect()
            ->route('cat-tipos-eleccion.index')
            ->with('success', 'Tipo de elección actualizado correctamente.');
    }

    public function destroy(TipoEleccion $tipoEleccion)
    {
        $tablas = [
            'monit_soportes_promocionales',
            'monitoreo_cine',
            'monitoreo_medios_electronicos',
            'monitoreo_medios_impresos',
            'monitoreo_propaganda_movil',
            'monitoreo_radio',
            'monitoreo_television',
        ];

        foreach ($tablas as $tabla) {
            $existeRelacion = DB::table($tabla)
                ->where('tipo_eleccion_id', $tipoEleccion->id)
                ->exists();

            if ($existeRelacion) {
                return redirect()
                    ->route('cat-tipos-eleccion.index')
                    ->with('error', 'No se puede borrar porque este tipo de elección ya está relacionado con registros de monitoreo.');
            }
        }

        $tipoEleccion->delete();

        return redirect()
            ->route('cat-tipos-eleccion.index')
            ->with('success', 'Tipo de elección eliminado correctamente.');
    }
}