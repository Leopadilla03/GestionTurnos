<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Turno;
use App\Models\Departamento;
use App\Models\Cliente;
use Illuminate\Support\Facades\DB;

class KioscoController extends Controller
{
    public function index(Request $request, $pais = 'HN')
    {
        $departamentos = Departamento::all();
        return view('kiosco.index', compact('departamentos', 'pais'));
    }

    public function generarTurno(Request $request, $pais = 'HN')
    {
        $request->validate([
            'documento' => 'required|min:9|max:13',
            'tipo' => 'required|in:normal,preferencial',
            'id_departamento' => 'required|exists:departamentos,id_departamento',
        ]);

        DB::beginTransaction();

        try {
            // Cliente
            $cliente = Cliente::firstOrCreate(
                ['documento' => $request->documento],
                ['nombre' => 'Cliente']
            );

            // Determinar sucursal según país
            $mapaSucursales = [
                'HN' => 1, // Tegucigalpa
                'CR' => 2, // San José
            ];
            $idSucursal = $mapaSucursales[strtoupper($pais)] ?? 1;

            // Correlativo por tipo Y sucursal (independiente por país)
            $ultimo = Turno::where('tipo', $request->tipo)
                ->where('id_sucursal', $idSucursal)
                ->orderBy('id_turno', 'desc')
                ->lockForUpdate()
                ->first();

            $num = $ultimo
                ? intval(substr($ultimo->numero, 2)) + 1
                : 1;

            $prefijo = $request->tipo === 'normal' ? 'N-' : 'P-';

            $turno = Turno::create([
                'numero' => $prefijo . str_pad($num, 3, '0', STR_PAD_LEFT),
                'tipo' => $request->tipo,
                'estado' => 'espera',
                'origen' => 'kiosco',
                'id_cliente' => $cliente->id_cliente,
                'id_departamento' => $request->id_departamento,
                'id_sucursal' => $idSucursal,
                'hora_creacion' => now(),
            ]);

            DB::commit();

            return view('kiosco.ticket', compact('turno', 'pais'));

        } catch (\Exception $e) {
            DB::rollBack();
            abort(500, 'Error al generar turno');
        }
    }
}