<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Turno;
use App\Models\UsuarioXVentanilla;

class TurnoController extends Controller
{
    /**
     * 📋 Mostrar todos los turnos (o filtrados por estado)
     */
    public function index(Request $request)
    {
        $query = Turno::with(['cliente', 'departamento', 'ventanilla']);

        if ($request->has('estado')) {
            $query->where('estado', $request->estado);
        }

        $turnos = $query->orderBy('created_at', 'desc')->get();

        return response()->json($turnos);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * 🆕 Crear un nuevo turno
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_cliente' => 'required|exists:clientes,id_cliente',
            'id_departamento' => 'required|exists:departamentos,id_departamento',
            'tipo' => 'required|in:normal,preferencial',
        ]);

        // Obtener el último número de turno del departamento
        $ultimoTurno = Turno::where('id_departamento', $request->id_departamento)
            ->orderBy('id_turno', 'desc')
            ->first();

        $nuevoNumero = $ultimoTurno ? $ultimoTurno->id_turno + 1 : 1;

        $turno = Turno::create([
            'numero' => 'T-' . str_pad($nuevoNumero, 3, '0', STR_PAD_LEFT),
            'tipo' => $request->tipo,
            'estado' => 'espera',
            'id_cliente' => $request->id_cliente,
            'id_departamento' => $request->id_departamento,
        ]);

        return response()->json([
            'message' => 'Turno generado exitosamente',
            'data' => $turno
        ], 201);
    }

    /**
     * 🔍 Mostrar un turno específico
     */
    public function show(string $id)
    {
        $turno = Turno::with(['cliente', 'departamento', 'ventanilla'])->findOrFail($id);
        return response()->json($turno);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * ⚙️ Actualizar el estado del turno (ejemplo: llamado, atendido, finalizado)
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'estado' => 'required|in:espera,llamado,atendiendo,finalizado,cancelado',
            'id_ventanilla' => 'nullable|exists:ventanillas,id_ventanilla',
        ]);

        $turno = Turno::findOrFail($id);
        $turno->update([
            'estado' => $request->estado,
            'id_ventanilla' => $request->id_ventanilla,
        ]);

        return response()->json([
            'message' => 'Turno actualizado correctamente',
            'data' => $turno
        ]);
    }

    /**
     * 🗑️ Eliminar un turno
     */
    public function destroy(string $id)
    {
        $turno = Turno::findOrFail($id);
        $turno->delete();

        return response()->json(['message' => 'Turno eliminado correctamente']);
    }

    /**
     * 🎯 Obtener el siguiente turno en espera para un departamento
     */
    public function siguienteTurno()
{
    $usuario = Auth::user();

    // 1️⃣ Obtener la ventanilla activa del cajero
    $asignacion = UsuarioXVentanilla::where('id_usuario', $usuario->id_usuario)
        ->where('estado', 'abierta')
        ->first();

    if (!$asignacion) {
        return response()->json([
            'error' => 'No tiene una caja asignada'
        ], 403);
    }

    $ventanilla = $asignacion->ventanilla;

    // 2️⃣ Buscar el turno más antiguo del departamento
    DB::beginTransaction();

    try {
        $turno = Turno::where('estado', 'espera')
            ->where('id_departamento', $ventanilla->id_departamento)
            ->orderBy('hora_creacion', 'asc')
            ->lockForUpdate()
            ->first();

        if (!$turno) {
            DB::rollBack();
            return response()->json([
                'mensaje' => 'No hay turnos en espera'
            ]);
        }

        // 3️⃣ Asignar turno a la caja y cajero
        $turno->update([
            'estado' => 'atendiendo',
            'id_ventanilla' => $ventanilla->id_ventanilla,
            'id_usuario' => $usuario->id_usuario,
            'hora_inicio_atencion' => now(),
        ]);

        DB::commit();

        return response()->json([
            'turno' => $turno->numero,
            'caja'  => $ventanilla->nombre,
            'mensaje' => "Turno {$turno->numero}, pase a {$ventanilla->nombre}"
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => 'Error al llamar turno'], 500);
    }
}
}
