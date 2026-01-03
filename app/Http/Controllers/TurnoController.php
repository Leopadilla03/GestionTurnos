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
        /*
        $request->validate([
            'id_departamento' => 'required|exists:departamentos,id_departamento',
            'tipo' => 'required|in:normal,preferencial',
            'documento' => 'required|string|min:9|max:13'
        ]);

        DB::beginTransaction();

        try {
            // 1️⃣ Buscar o crear cliente
            $cliente = \App\Models\Cliente::firstOrCreate(
                ['documento' => $request->documento],
                ['nombre' => 'Cliente']
            );

            // 2️⃣ Prefijo según tipo
            $prefijo = $request->tipo === 'preferencial' ? 'P' : 'N';

            // 3️⃣ Último turno DEL MISMO TIPO y departamento
            $ultimo = Turno::where('id_departamento', $request->id_departamento)
                ->where('tipo', $request->tipo)
                ->where('numero', 'like', $prefijo.'%')
                ->orderBy('id_turno', 'desc')
                ->lockForUpdate()
                ->first();

            $consecutivo = $ultimo
                ? intval(substr($ultimo->numero, 1)) + 1
                : 1;

            // 4️⃣ Crear turno (SOLO kiosco)
            $turno = Turno::create([
                'numero' => $prefijo . str_pad($consecutivo, 3, '0', STR_PAD_LEFT),
                'tipo' => $request->tipo,
                'estado' => 'espera',
                'id_cliente' => $cliente->id_cliente,
                'id_departamento' => $request->id_departamento,
                'hora_creacion' => now(),
            ]);

            DB::commit();

            return response()->json([
                'mensaje' => "Turno {$turno->numero} generado correctamente",
                'turno' => $turno
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Los turnos solo se generan desde el kiosco'], 403);
        }
            */
         abort(403, 'Los turnos solo se generan desde el kiosco');
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


        // 1️⃣ Detectar la caja del cajero
        $asignacion = UsuarioXVentanilla::where('id_usuario', $usuario->id_usuario)
            ->where('estado', 'abierta')
            ->first();

        if (!$asignacion) {
            return response()->json([
                'error' => 'No tiene una caja asignada'
            ], 403);
        }

        $ventanilla = $asignacion->ventanilla;

        $turnoActivo = Turno::where('id_ventanilla', $ventanilla->id_ventanilla)
            ->whereIn('estado', ['atendiendo', 'pausado'])
            ->first();

        if ($turnoActivo) {
            return response()->json([
                'error' => 'Debe finalizar o pausar el turno actual antes de llamar otro'
            ], 409);
        }


        DB::beginTransaction();

        try {
            // 2️⃣ Tomar el siguiente turno EN ESPERA del departamento
            $turno = Turno::where('estado', 'espera')
                ->where('id_departamento', $ventanilla->id_departamento)
                ->whereNull('id_ventanilla')
                ->orderByRaw("CASE WHEN tipo='preferencial' THEN 0 ELSE 1 END")
                ->orderBy('hora_creacion', 'asc')
                ->lockForUpdate()
                ->first();

            if (!$turno) {
                DB::rollBack();
                return response()->json([
                    'error' => 'No hay turnos en espera'
                ]);
            }

            // 3️⃣ Asignar turno al cajero y a la caja
            $turno->update([
                'estado' => 'atendiendo',
                'id_ventanilla' => $ventanilla->id_ventanilla,
                'id_usuario' => $usuario->id_usuario,
                'hora_inicio_atencion' => now(),
            ]);

            DB::commit();

            // 4️⃣ Mensaje real
            return response()->json([
                'success' => true,
                'turno' => [
                    'numero' => $turno->numero,
                    'ventanilla' => $ventanilla->nombre,
                    'departamento' => $ventanilla->departamento->nombre
                ],
                'mensaje' => "Turno {$turno->numero}, pase a {$ventanilla->nombre}"
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al llamar turno'], 500);
        }
    }
}