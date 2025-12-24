<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ventanilla;
use App\Models\Usuario;
use App\Models\Turno;
use Carbon\Carbon;

class VentanillaController extends Controller
{
    /**
     * Mostrar todas las ventanillas
     */
    public function index()
    {
        $ventanillas = Ventanilla::with('usuario')->get();
        return response()->json($ventanillas);
    }

    /**
     * Abrir ventanilla (cuando el operador inicia sesión)
     */
    public function abrirVentanilla(Request $request)
    {
        $request->validate([
            'id_ventanilla' => 'required|exists:ventanillas,id_ventanilla',
            'id_usuario' => 'required|exists:usuarios,id_usuario',
        ]);

        $ventanilla = Ventanilla::findOrFail($request->id_ventanilla);

        // Asociar usuario y marcar ventanilla como activa
        $ventanilla->update([
            'estado' => 'abierta',
            'id_usuario' => $request->id_usuario,
            'hora_apertura' => Carbon::now(),
        ]);

        return response()->json([
            'message' => 'Ventanilla abierta correctamente',
            'ventanilla' => $ventanilla,
        ]);
    }

    /**
     * Cerrar ventanilla (cuando el operador termina turno)
     */
    public function cerrarVentanilla($id)
    {
        $ventanilla = Ventanilla::findOrFail($id);

        $ventanilla->update([
            'estado' => 'cerrada',
            'id_usuario' => null,
            'hora_cierre' => Carbon::now(),
        ]);

        return response()->json([
            'message' => 'Ventanilla cerrada correctamente',
            'ventanilla' => $ventanilla,
        ]);
    }

    /**
     * Mostrar los turnos pendientes asignados a esta ventanilla
     */
    public function turnosAsignados($id)
    {
        $ventanilla = Ventanilla::findOrFail($id);

        $turnos = Turno::where('id_ventanilla', $id)
            ->whereIn('estado', ['llamado', 'atendiendo'])
            ->with('cliente')
            ->orderBy('hora_inicio_atencion', 'desc')
            ->get();

        return response()->json([
            'ventanilla' => $ventanilla,
            'turnos' => $turnos,
        ]);
    }

    /**
     * Consultar si hay ventanillas abiertas (útil para dashboard)
     */
    public function ventanillasActivas()
    {
        $abiertas = Ventanilla::where('estado', 'abierta')
            ->with('usuario')
            ->get();

        return response()->json($abiertas);
    }
}

