<?php

namespace App\Http\Controllers\Pantalla;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Turno;

class PantallaController extends Controller
{
    /**
     * Pantalla pública por defecto (redirige a un país por defecto)
     */
    public function index(Request $request)
    {
        // País por defecto: Honduras ('hn') — puedes cambiarlo a 'cr' si lo prefieres
        return $this->publicaPorPais($request, 'HN');
    }
    /**
     * Mostrar pantalla pública filtrada por país
     * URL ejemplo: /pantalla/hn o /pantalla/cr
     */
    public function publicaPorPais(Request $request, $pais)
    {
        $map = [
            'HN' => 1,
            'CR' => 2,
        ];

        if (!isset($map[$pais])) {
            abort(404);
        }

        $idPais = $map[$pais];

        $sucursales = DB::table('sucursal')
            ->join('sociedad', 'sociedad.id_sociedad', '=', 'sucursal.id_sociedad')
            ->where('sociedad.id_pais', $idPais)
            ->pluck('sucursal.id_sucursal')
            ->toArray();

        // 🔵 TURNOS ACTUALES POR CAJA
        $actuales = DB::table('turnos')
            ->leftJoin('ventanillas', 'ventanillas.id_ventanilla', '=', 'turnos.id_ventanilla')
            ->whereIn('turnos.estado', ['atendiendo', 'pausado'])
            ->whereIn('ventanillas.id_sucursal', $sucursales)
            ->select(
                'turnos.numero',
                'turnos.estado',
                'ventanillas.nombre as caja'
            )
            ->orderBy('turnos.hora_inicio_atencion', 'desc')
            ->get()
            ->groupBy('caja');

        // 🟡 COLA
        $cola = DB::table('turnos')
            ->where('estado', 'espera')
            ->whereIn('id_sucursal', $sucursales)
            ->orderBy('hora_creacion')
            ->get(['numero', 'tipo']);

        // 🟢 RECIENTES
        $recientes = DB::table('turnos')
            ->leftJoin('ventanillas', 'ventanillas.id_ventanilla', '=', 'turnos.id_ventanilla')
            ->where('turnos.estado', 'finalizado')
            ->whereIn('turnos.id_sucursal', $sucursales)
            ->orderByDesc('hora_fin_atencion')
            ->limit(10)
            ->get([
                'turnos.numero',
                'turnos.tipo',
                'ventanillas.nombre as ventanilla'
            ]);

        // 🔥 SI ES AJAX → JSON
        if ($request->ajax()) {
            return response()->json([
                'actuales'  => $actuales,
                'cola'      => $cola,
                'recientes' => $recientes,
            ]);
        }

        // 🔥 SI ES NORMAL → VISTA
        return view('pantalla.publica', compact(
            'actuales',
            'cola',
            'recientes',
            'pais'
        ));
    }
}
