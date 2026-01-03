<?php

namespace App\Http\Controllers\Operador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Turno;
use App\Models\Departamento;
use App\Models\Usuario;
use App\Models\Ventanilla;
use App\Models\UsuarioXVentanilla;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OperadorController extends Controller
{
    /**
     * Panel del operador
     */
    public function panel(Request $request)
    {
        $usuario = $request->user();

        // Obtener asignación activa (UsuarioXVentanilla)
        $asig = $usuario->ventanillaActiva()->first();

        if (!$asig) {
            return view('operador.sin_asignacion');
        }

        // Obtener ventanilla (model)
        $ventanilla = Ventanilla::find($asig->id_ventanilla);

        if (!$ventanilla) {
            return view('operador.sin_asignacion');
        }

        // Turno actual atendiendo o pausado en esta ventanilla
        $turnoActual = Turno::where('id_ventanilla', $ventanilla->id_ventanilla)
            ->whereIn('estado', ['atendiendo', 'pausado'])
            ->orderByDesc('hora_creacion')
            ->first();

        // Cola: solo turnos en espera del mismo departamento y sin ventanilla asignada
       $cola = Turno::where('estado', 'espera')
        ->where('origen', 'kiosco')
        ->where('id_departamento', $ventanilla->id_departamento)
        ->where('id_sucursal', $ventanilla->id_sucursal)
        ->orderByRaw("CASE WHEN tipo='preferencial' THEN 0 ELSE 1 END")
        ->orderBy('hora_creacion')
        ->get();

        return view('operador.panel', compact('ventanilla', 'turnoActual', 'cola'));
    }

    /**
     * Llamar siguiente turno 
     */
    public function llamar(Request $request)
    {
        try {
            $usuario = $request->user();
            if (!$usuario) {
                return response()->json(['error' => 'No hay usuario autenticado.'], 401);
            }

            $asig = $usuario->ventanillaActiva()->first();
            if (!$asig) {
                return response()->json(['error' => 'No tienes ventanilla asignada.'], 400);
            }

            $ventanilla = Ventanilla::find($asig->id_ventanilla);
            if (!$ventanilla) {
                return response()->json(['error' => 'Ventanilla no encontrada.'], 404);
            }

            $departamentoId = $ventanilla->id_departamento;
            $sucursalId = $ventanilla->id_sucursal;

            // Usamos transacción para evitar race conditions
            $turno = DB::transaction(function () use ($departamentoId, $sucursalId, $ventanilla) {
                // Intentamos obtener el turno disponible que cumpla:
                // 1) tipo preferencial antes que normal
                // 2) dentro de cada tipo, por hora_creacion asc (FIFO)
                // 3) estado = espera, id_ventanilla = null, misma sucursal y departamento
                //
                // Hacemos lockForUpdate para que dos procesos no tomen el mismo turno.
                $query = Turno::where('id_departamento', $departamentoId)
                    ->where('id_sucursal', $sucursalId)
                    ->where('estado', 'espera')
                    ->whereNull('id_ventanilla')
                    // Priorizar PREFERENCIAL (si tu valor es 'preferencial' y 'normal')
                    ->orderByRaw("(CASE WHEN tipo = 'preferencial' THEN 0 ELSE 1 END) ASC")
                    // Ordenar por hora_creacion para FIFO dentro de tipos
                    ->orderBy('hora_creacion', 'asc');

                // Tomar el primer registro con lock
                $turno = $query->lockForUpdate()->first();

                if (!$turno) {
                    return null;
                }

                // Marcar como atendiendo y asignar ventanilla/hora inicio
                $turno->estado = 'atendiendo';
                $turno->id_ventanilla = $ventanilla->id_ventanilla;
                $turno->id_sucursal = $ventanilla->id_sucursal;
                $turno->hora_inicio_atencion = now();
                $turno->save();

                return $turno;
            }, 5); // retry up to 5 veces si hay deadlocks

            if (!$turno) {
                return response()->json(['error' => 'No hay turnos en espera.']);
            }

            return response()->json([
                'success' => "Turno {$turno->numero}, pase a {$ventanilla->nombre}",
                'turno' => [
                    'id' => $turno->id_turno,
                    'numero' => $turno->numero,
                    'estado' => $turno->estado,
                    'ventanilla' => $ventanilla->nombre,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al llamar turno: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Pausar / reanudar turno
     */
    public function pausar(Request $request)
    {
        try {
            $turno = Turno::find($request->id_turno);

            if (!$turno) {
                return response()->json(['error' => 'No se encontró el turno.']);
            }

            if ($turno->estado === 'pausado') {
                $turno->update(['estado' => 'atendiendo']);
                return response()->json(['success' => "Turno {$turno->numero} reanudado.", 'estado' => 'atendiendo']);
            }

            if ($turno->estado !== 'atendiendo') {
                return response()->json(['error' => 'No puedes pausar un turno que no está en atención.']);
            }

            $turno->update(['estado' => 'pausado']);
            return response()->json(['success' => "Turno {$turno->numero} pausado.", 'estado' => 'pausado']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al pausar turno: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Finalizar turno
     */
    public function finalizar(Request $request)
    {
        try {
            $id = $request->input('id_turno');

            if (!$id) {
                return response()->json(['error' => 'No se recibió el ID del turno.'], 400);
            }

            $turno = Turno::find($id);

            if (!$turno) {
                return response()->json(['error' => 'Turno no encontrado.'], 404);
            }

            if (!in_array($turno->estado, ['atendiendo', 'pausado'])) {
                return response()->json(['error' => 'El turno no está siendo atendido.'], 400);
            }

            $turno->update([
                'estado' => 'finalizado',
                'hora_fin_atencion' => now(),
                // Mantener id_ventanilla para conservar historial de qué ventanilla atendió el turno
            ]);
            // No llamar automáticamente al siguiente turno: el operador debe presionar 'llamar siguiente'
            return response()->json([
                'success' => "Turno {$turno->numero} finalizado correctamente.",
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al finalizar turno: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Transferir turno a otro departamento (vuelve a la cola)
     */
    public function transferir(Request $request)
    {
        try {
            $turno = Turno::find($request->id_turno);

            if (!$turno) {
                return response()->json(['error' => 'Turno no encontrado.']);
            }

            if (!in_array($turno->estado, ['atendiendo', 'pausado'])) {
                return response()->json(['error' => 'Solo puedes transferir turnos activos o pausados.']);
            }

            $turno->update([
                'id_departamento' => $request->id_departamento,
                'estado' => 'espera', // vuelve a la fila
                'id_ventanilla' => null, // deja de estar asignado
            ]);

            return response()->json(['success' => "Turno {$turno->numero} transferido correctamente."]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al transferir: ' . $e->getMessage()], 500);
        }
    }
/*
    // Estado ausente 
    public function ausente(Request $request)
    {
        $usuario = $request->user();

        UsuarioXVentanilla::where('id_usuario', $usuario->id_usuario)
            ->where('estado', 'abierta')
            ->update(['estado' => 'pausada']);

        return response()->json(['ok' => true]);
    }

    // Regresar de ausente
    public function regresar(Request $request)
    {
        $usuario = $request->user();

        UsuarioXVentanilla::where('id_usuario', $usuario->id_usuario)
            ->where('estado', 'pausada')
            ->update(['estado' => 'abierta']);

        return response()->json(['ok' => true]);
    }
*/
    /**
     * Historial de la ventanilla del operador
     */
    public function historial(Request $request)
    {
        try {
            $usuario = $request->user();
            $asig = $usuario->ventanillaActiva()->first();

            if (!$asig) {
                return view('operador.sin_asignacion');
            }

            $ventanilla = Ventanilla::find($asig->id_ventanilla);
            if (!$ventanilla) {
                return view('operador.sin_asignacion');
            }

            $historial = Turno::with(['ventanilla.usuarios'])
                ->where('id_ventanilla', $ventanilla->id_ventanilla)
                ->where('estado', 'finalizado')
                ->orderBy('hora_fin_atencion', 'desc')
                ->get();

            return view('operador.historial', compact('historial', 'ventanilla'));

        } catch (\Exception $e) {
            return back()->with('error', 'Error al cargar historial: ' . $e->getMessage());
        }
    }

    /**
     * Asignar una ventanilla libre al operador (crea un registro usuario_x_ventanilla)
     */
    public function asignarVentanilla(Request $request)
    {
        $usuario = Auth::user();

        // Buscar ventanilla libre (sin asignación "abierta") en la misma sucursal/país del usuario
        $ventanillasQuery = DB::table('ventanillas')
            ->leftJoin('usuario_x_ventanilla', 'ventanillas.id_ventanilla', '=', 'usuario_x_ventanilla.id_ventanilla')
            ->leftJoin('sucursal', 'ventanillas.id_sucursal', '=', 'sucursal.id_sucursal')
            ->where(function ($q) {
                $q->whereNull('usuario_x_ventanilla.id_usuario')
                  ->orWhere('usuario_x_ventanilla.estado', 'cerrada');
            });

        // Si el usuario tiene asignada una sucursal preferimos esa
        if (!empty($usuario->id_sucursal)) {
            $ventanillasQuery->where('ventanillas.id_sucursal', $usuario->id_sucursal);
        } elseif (!empty($usuario->id_pais)) {
            // Si no tiene sucursal, filtrar por país de la sucursal
            $ventanillasQuery->where('sucursal.id_pais', $usuario->id_pais);
        }

        $ventanillaLibre = $ventanillasQuery->select('ventanillas.id_ventanilla', 'ventanillas.nombre')->first();

        if (!$ventanillaLibre) {
            return back()->with('error', 'No hay ventanillas disponibles en este momento.');
        }

        DB::table('usuario_x_ventanilla')->insert([
            'id_usuario'   => $usuario->id_usuario,
            'id_ventanilla'=> $ventanillaLibre->id_ventanilla,
            'hora_inicio'  => now(),
            'estado'       => 'abierta'
        ]);

        return back()->with('success', "Se te asignó la ventanilla: {$ventanillaLibre->nombre}");
    }

    /**
     * API: turno actual por ventanilla
     */
    public function apiTurnoActual($id_ventanilla)
    {
        try {
            $turno = Turno::where('id_ventanilla', $id_ventanilla)
                ->whereIn('estado', ['atendiendo', 'pausado'])
                ->orderBy('hora_creacion', 'desc')
                ->first();

            return response()->json([
                'success' => true,
                'turno' => $turno ? [
                    'numero' => $turno->numero,
                    'estado' => $turno->estado,
                    'tipo' => $turno->tipo,
                    'id_departamento' => $turno->id_departamento
                ] : null
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * API: cola global (ejemplo para mostrar en panel principal)
     */
    public function apiColaGlobal()
    {
        $actual = Turno::whereIn('estado', ['atendiendo', 'pausado'])
            ->orderBy('hora_creacion', 'desc')
            ->first();

        $cola = Turno::where('estado', 'espera')
            ->orderBy('hora_creacion', 'asc')
            ->limit(5)
            ->get(['numero', 'tipo', 'estado', 'id_departamento']);

        return response()->json([
            'success' => true,
            'actual'  => $actual ? ['numero' => $actual->numero, 'estado' => $actual->estado] : null,
            'cola' => $cola
        ]);
    }
}
