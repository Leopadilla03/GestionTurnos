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
    public function publicaPorPais(Request $request, $pais = 'HN')
    {
        // Determinar sucursal según país
        $mapaSucursales = [
            'HN' => 1, // Tegucigalpa
            'CR' => 2, // San José
        ];
        $idSucursal = $mapaSucursales[strtoupper($pais)] ?? 1;
        
        $sucursales = [$idSucursal];

        // 🔵 TURNOS ACTUALES POR CAJA
        $actuales = DB::table('turnos')
            ->leftJoin('ventanillas', 'ventanillas.id_ventanilla', '=', 'turnos.id_ventanilla')
            ->whereIn('turnos.estado', ['atendiendo', 'pausado'])
            ->where('turnos.origen', 'kiosco')
            ->whereIn('turnos.id_sucursal', $sucursales)
            ->select(
                'turnos.numero',
                'turnos.estado',
                'ventanillas.nombre as caja'
            )
            ->orderBy('turnos.hora_inicio_atencion', 'desc')
            ->get()
            ->groupBy('caja');

        // 🟡 COLA DE ESPERA
        $cola = DB::table('turnos')
            ->where('estado', 'espera')
            ->where('origen', 'kiosco')
            ->whereIn('id_sucursal', $sucursales)
            ->orderByRaw("CASE WHEN tipo='preferencial' THEN 0 ELSE 1 END")
            ->orderBy('hora_creacion')
            ->get(['numero', 'tipo']);

        // 🟢 TURNOS RECIENTES
        $recientes = DB::table('turnos')
            ->leftJoin('ventanillas', 'ventanillas.id_ventanilla', '=', 'turnos.id_ventanilla')
            ->where('turnos.estado', 'finalizado')
            ->where('turnos.origen', 'kiosco')
            ->whereIn('turnos.id_sucursal', $sucursales)
            ->orderByDesc('hora_fin_atencion')
            ->limit(10)
            ->get([
                'turnos.numero',
                'turnos.tipo',
                'ventanillas.nombre as ventanilla'
            ]);

        // 🔄 AJAX
        if ($request->ajax()) {
            return response()->json([
                'actuales'  => $actuales,
                'cola'      => $cola,
                'recientes' => $recientes,
            ]);
        }

        // 🖥️ VISTA PANTALLA PÚBLICA
        return view('pantalla.publica', compact(
            'actuales',
            'cola',
            'recientes',
            'pais'
        ));
    }
}

