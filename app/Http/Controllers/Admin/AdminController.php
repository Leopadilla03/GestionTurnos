<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Models\Usuario;
use App\Models\Ventanilla;
use App\Models\Sucursal;
use App\Models\Departamento;
use App\Models\UsuarioXVentanilla;
use App\Models\Turno;
use App\Services\ChartService;


class AdminController extends Controller
{
    // ----------------
    // helper interno (dentro del controller) para obtener sucursales según el admin
    private function sucursalesSegunAdmin()
    {
        $user = Auth::user();
        $pais = $user->id_pais ?? null;

        if (!$pais) {
            // si no tiene pais, retornamos todas
            return DB::table('sucursal')->pluck('id_sucursal')->toArray();
        }

        return DB::table('sucursal')
            ->join('sociedad', 'sociedad.id_sociedad', '=', 'sucursal.id_sociedad')
            ->where('sociedad.id_pais', $pais)
            ->pluck('sucursal.id_sucursal')
            ->toArray();
    }

    public function index()
    {
        $user = Auth::user();
        $pais = $user->id_pais;

        // Obtener sucursales del admin para filtrar datos
        $sucursalesFiltro = $this->sucursalesSegunAdmin();

        // 1️⃣ Usuarios del mismo país (filtrados por sucursal)
        $usuarios = DB::table('usuarios')
            ->whereIn('id_sucursal', $sucursalesFiltro)
            ->count();

        // 2️⃣ Departamentos (son generales, no por país)
        $departamentos = DB::table('departamentos')->count();

        // 3️⃣ Ventanillas por país (usando sucursal → sociedad → país). Si no hay país, contar todas.
        $ventanillasQuery = DB::table('ventanillas')
            ->join('sucursal', 'sucursal.id_sucursal', '=', 'ventanillas.id_sucursal')
            ->join('sociedad', 'sociedad.id_sociedad', '=', 'sucursal.id_sociedad');
        if ($pais) $ventanillasQuery->where('sociedad.id_pais', $pais);
        $ventanillas = $ventanillasQuery->count();

        // 4️⃣ Sociedades del país (o todas si no hay país)
        $sociedadesQuery = DB::table('sociedad');
        if ($pais) $sociedadesQuery->where('id_pais', $pais);
        $sociedades = $sociedadesQuery->count();

        // 5️⃣ Sucursales del país (o todas si no hay país)
        $sucursalesQuery = DB::table('sucursal')
            ->join('sociedad', 'sociedad.id_sociedad', '=', 'sucursal.id_sociedad');
        if ($pais) $sucursalesQuery->where('sociedad.id_pais', $pais);
        $sucursales = $sucursalesQuery->count();

        // =========================
        // KPI Count (scoped por país/sucursales)
        // (ya tenemos $sucursalesFiltro del paso anterior)
        // =========================

        // Total turnos en alcance (filtrado por id de sucursal en turnos o en ventanillas)
        $totalTurnos = DB::table('turnos')
            ->leftJoin('ventanillas', 'ventanillas.id_ventanilla', '=', 'turnos.id_ventanilla')
            ->where(function($q) use ($sucursalesFiltro) {
                $q->whereIn('turnos.id_sucursal', $sucursalesFiltro)
                  ->orWhereIn('ventanillas.id_sucursal', $sucursalesFiltro);
            })
            ->distinct('turnos.id_turno')
            ->count('turnos.id_turno');

        // En cola (espera) - filtrado por id de sucursal en turnos o ventanillas
        $enCola = DB::table('turnos')
            ->leftJoin('ventanillas', 'ventanillas.id_ventanilla', '=', 'turnos.id_ventanilla')
            ->where('turnos.estado', 'espera')
            ->where(function($q) use ($sucursalesFiltro) {
                $q->whereIn('turnos.id_sucursal', $sucursalesFiltro)
                  ->orWhereIn('ventanillas.id_sucursal', $sucursalesFiltro);
            })
            ->distinct('turnos.id_turno')
            ->count('turnos.id_turno');

        // Llamados (atendiendo/pausado) - filtrado por id de sucursal en turnos o ventanillas
        $llamados = DB::table('turnos')
            ->leftJoin('ventanillas', 'ventanillas.id_ventanilla', '=', 'turnos.id_ventanilla')
            ->whereIn('turnos.estado', ['atendiendo', 'pausado'])
            ->where(function($q) use ($sucursalesFiltro) {
                $q->whereIn('turnos.id_sucursal', $sucursalesFiltro)
                  ->orWhereIn('ventanillas.id_sucursal', $sucursalesFiltro);
            })
            ->distinct('turnos.id_turno')
            ->count('turnos.id_turno');

        // Atendidos (finalizado) - filtrado por id de sucursal en turnos o ventanillas
        $atendidos = DB::table('turnos')
            ->leftJoin('ventanillas', 'ventanillas.id_ventanilla', '=', 'turnos.id_ventanilla')
            ->where('turnos.estado', 'finalizado')
            ->where(function($q) use ($sucursalesFiltro) {
                $q->whereIn('turnos.id_sucursal', $sucursalesFiltro)
                  ->orWhereIn('ventanillas.id_sucursal', $sucursalesFiltro);
            })
            ->distinct('turnos.id_turno')
            ->count('turnos.id_turno');

        // Promedio de espera (minutos) - filtrado por id de sucursal en turnos o ventanillas
        $promedioEspera = DB::table('turnos')
            ->leftJoin('ventanillas', 'ventanillas.id_ventanilla', '=', 'turnos.id_ventanilla')
            ->whereNotNull('hora_inicio_atencion')
            ->where(function($q) use ($sucursalesFiltro) {
                $q->whereIn('turnos.id_sucursal', $sucursalesFiltro)
                  ->orWhereIn('ventanillas.id_sucursal', $sucursalesFiltro);
            })
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, hora_creacion, hora_inicio_atencion)) as prom')
            ->value('prom') ?? 0;

        $promedioEspera = round($promedioEspera, 2);

        return view('admin.dashboard', compact(
            'usuarios',
            'departamentos',
            'ventanillas',
            'sociedades',
            'sucursales',
            'totalTurnos',
            'enCola',
            'llamados',
            'atendidos',
            'promedioEspera'
        ));
    }

    //-------------------------------------------------------------------
    public function usuarios()
    {
        $sucursalesFiltro = $this->sucursalesSegunAdmin();

        $usuarios = Usuario::whereIn('id_sucursal', $sucursalesFiltro)
            ->orderBy('id_usuario', 'asc')
            ->get();

        return view('admin.usuarios', compact('usuarios'));
    }

    // Formulario de creación
    public function crearUsuario() {
        return view('admin.usuarios-crear');
    }

    // Guardar nuevo usuario
    public function guardarUsuario(Request $request) {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|min:6',
            'rol' => 'required|in:administrador,operador',
        ]);

        // ⭐ Obtener país del administrador logueado
        $admin = Auth::user();
        $paisAdmin = $admin->id_pais;

        Usuario::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => $request->rol,
            'estado' => 'activo',
            'id_pais' => $request->id_pais,
            'id_sucursal' => $admin->id_sucursal ?? null, // opcional: asignar misma sucursal del admin
        ]);

        return redirect()->route('admin.usuarios')->with('success', 'Usuario creado correctamente.');
    }

    // Formulario de edición
    public function editarUsuario($id)
    {
        $usuario = Usuario::findOrFail($id);
        return view('admin.usuarios-editar', compact('usuario'));
    }

    // Actualizar usuario
    public function actualizarUsuario(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:100',
            'email' => 'required|email|unique:usuarios,email,' . $id . ',id_usuario',
            'rol' => 'required|in:administrador,operador',
            'password' => 'nullable|min:6'
        ]);

        $usuario->nombre = $request->nombre;
        $usuario->email = $request->email;
        $usuario->rol = $request->rol;
        $usuario->estado = $request->estado ?? 'activo';

        if ($request->filled('password')) {
            $usuario->password = Hash::make($request->password);
        }

        $usuario->save();

        return redirect()->route('admin.usuarios')->with('success', 'Usuario actualizado correctamente.');
    }

    // Eliminar usuario
    public function eliminarUsuario($id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->delete();

        return redirect()->route('admin.usuarios')->with('success', 'Usuario eliminado correctamente.');
    }
    //-------------------------------------------------------------------

    //-------------------------------------------------------------------
    // Listar departamentos
    public function departamentos()
    {
        $departamentos = Departamento::orderBy('id_departamento', 'asc')->get();
        return view('admin.departamentos', compact('departamentos'));
    }

    // Formulario de creación
    public function crearDepartamento()
    {
        return view('admin.departamentos-crear');
    }

    // Guardar nuevo departamento
    public function guardarDepartamento(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:departamentos,nombre',
            'descripcion' => 'nullable|string|max:255',
            'atiende_preferencial' => 'nullable|boolean'
        ]);

        Departamento::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'atiende_preferencial' => $request->atiende_preferencial ?? 0,
        ]);

        return redirect()->route('admin.departamentos')->with('success', 'Departamento creado correctamente.');
    }

    // Formulario de edición
    public function editarDepartamento($id)
    {
        $departamento = Departamento::findOrFail($id);
        return view('admin.departamentos-editar', compact('departamento'));
    }

    // Actualizar departamento
    public function actualizarDepartamento(Request $request, $id)
    {
        $departamento = Departamento::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:100|unique:departamentos,nombre,' . $id . ',id_departamento',
            'descripcion' => 'nullable|string|max:255',
            'atiende_preferencial' => 'nullable|boolean'
        ]);

        $departamento->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'atiende_preferencial' => $request->atiende_preferencial ?? 0,
        ]);

        return redirect()->route('admin.departamentos')->with('success', 'Departamento actualizado correctamente.');
    }

    // Eliminar departamento
    public function eliminarDepartamento($id)
    {
        $departamento = Departamento::findOrFail($id);
        $departamento->delete();

        return redirect()->route('admin.departamentos')->with('success', 'Departamento eliminado correctamente.');
    }

    //-------------------------------------------------------------------
    
    //-------------------------------------------------------------------
    // VENTANILLAS

    // Listar ventanillas
    public function ventanillas()
    {
        $sucursalesFiltro = $this->sucursalesSegunAdmin();

        $ventanillas = Ventanilla::with(['sucursal', 'departamento'])
            ->whereIn('id_sucursal', $sucursalesFiltro)
            ->orderBy('id_ventanilla')
            ->get();

        return view('admin.ventanillas', compact('ventanillas'));
    }

    // Formulario de creación
    public function crearVentanilla()
    {
        $sucursales = Sucursal::orderBy('nombre')->get();
        $departamentos = Departamento::orderBy('nombre')->get();
        return view('admin.ventanillas-crear', compact('sucursales', 'departamentos'));
    }

    // Guardar ventanilla
    public function guardarVentanilla(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'id_sucursal' => 'required|exists:sucursal,id_sucursal',
            'id_departamento' => 'required|exists:departamentos,id_departamento',
            'estado' => 'required|in:activa,inactiva',
        ]);

        Ventanilla::create([
            'nombre' => $request->nombre,
            'estado' => $request->estado,
            'id_sucursal' => $request->id_sucursal,
            'id_departamento' => $request->id_departamento,
        ]);

        return redirect()->route('admin.ventanillas')->with('success', 'Ventanilla creada correctamente.');
    }

    // Formulario de edición
    public function editarVentanilla($id)
    {
        $ventanilla = Ventanilla::findOrFail($id);
        $sucursales = Sucursal::orderBy('nombre')->get();
        $departamentos = Departamento::orderBy('nombre')->get();
        return view('admin.ventanillas-editar', compact('ventanilla', 'sucursales', 'departamentos'));
    }

    // Actualizar ventanilla
    public function actualizarVentanilla(Request $request, $id)
    {
        $ventanilla = Ventanilla::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:100',
            'id_sucursal' => 'required|exists:sucursal,id_sucursal',
            'id_departamento' => 'required|exists:departamentos,id_departamento',
            'estado' => 'required|in:activa,inactiva',
        ]);

        $ventanilla->update([
            'nombre' => $request->nombre,
            'estado' => $request->estado,
            'id_sucursal' => $request->id_sucursal,
            'id_departamento' => $request->id_departamento,
        ]);

        return redirect()->route('admin.ventanillas')->with('success', 'Ventanilla actualizada correctamente.');
    }

    // Eliminar ventanilla
    public function eliminarVentanilla($id)
    {
        $ventanilla = Ventanilla::findOrFail($id);
        $ventanilla->delete();

        return redirect()->route('admin.ventanillas')->with('success', 'Ventanilla eliminada correctamente.');
    }
    //-------------------------------------------------------------------

    //-------------------------------------------------------------------
    // ASIGNACIONES USUARIO x VENTANILLA

    // Listar asignaciones actuales (vista mejorada)
    public function asignaciones()
    {
        $usuario = Auth::user();
        $pais = $usuario->id_pais;
        $sucursalesFiltro = $this->sucursalesSegunAdmin();

        // Asignaciones filtradas por el país/sucursales del admin
        $asignaciones = DB::table('usuario_x_ventanilla')
            ->join('usuarios', 'usuarios.id_usuario', '=', 'usuario_x_ventanilla.id_usuario')
            ->join('ventanillas', 'ventanillas.id_ventanilla', '=', 'usuario_x_ventanilla.id_ventanilla')
            ->join('sucursal', 'sucursal.id_sucursal', '=', 'ventanillas.id_sucursal')
            ->join('departamentos', 'departamentos.id_departamento', '=', 'ventanillas.id_departamento')
            ->whereIn('ventanillas.id_sucursal', $sucursalesFiltro)
            ->select(
                'usuario_x_ventanilla.id_usuario',
                'usuario_x_ventanilla.id_ventanilla',
                'usuario_x_ventanilla.hora_inicio',
                'usuario_x_ventanilla.hora_fin',
                'usuario_x_ventanilla.estado',
                'usuarios.nombre as usuario',
                'ventanillas.nombre as ventanilla',
                'sucursal.nombre as sucursal',
                'departamentos.nombre as departamento'
            )
            ->orderBy('usuario_x_ventanilla.hora_inicio', 'desc')
            ->get()
            ->map(function ($row) {
                // convertir strings a Carbon para que la vista pueda usar ->format()
                $row->hora_inicio = $row->hora_inicio ? Carbon::parse($row->hora_inicio) : null;
                $row->hora_fin = $row->hora_fin ? Carbon::parse($row->hora_fin) : null;
                return $row;
            });

        return view('admin.asignaciones-lista', compact('asignaciones'));
    }

    public function vistaAsignaciones()
    {

        $sucursalesFiltro = $this->sucursalesSegunAdmin();

        $operadores = \App\Models\Usuario::where('rol', 'operador')
            ->whereIn('id_sucursal', $sucursalesFiltro)
            ->with(['sucursal', 'departamento'])
            ->orderBy('id_usuario')
            ->get();

        // Ventanillas disponibles y su sucursal (filtradas por país)
        $ventanillas = \App\Models\Ventanilla::with(['departamento', 'sucursal'])
            ->whereIn('id_sucursal', $sucursalesFiltro)
            ->orderBy('id_ventanilla')
            ->get();

        // Asignaciones actuales
        $asignaciones = \App\Models\UsuarioXVentanilla::with(['usuario', 'ventanilla.sucursal', 'ventanilla.departamento'])
            ->where('estado', 'abierta')
            ->get();

        return view('admin.asignaciones-ventanillas', compact('operadores', 'ventanillas', 'asignaciones'));
    }

    public function asignarManual(Request $request)
    {
        $request->validate([
            'id_usuario' => 'required|exists:usuarios,id_usuario',
            'id_ventanilla' => 'required|exists:ventanillas,id_ventanilla',
        ]);

        // Cerrar cualquier asignación abierta para este usuario
        UsuarioXVentanilla::where('id_usuario', $request->id_usuario)
            ->where('estado', 'abierta')
            ->update([
                'estado' => 'cerrada',
                'hora_fin' => now(),
            ]);

        // Crear nueva asignación
        UsuarioXVentanilla::create([
            'id_usuario' => $request->id_usuario,
            'id_ventanilla' => $request->id_ventanilla,
            'estado' => 'abierta',
            'hora_inicio' => now(),
        ]);

        return redirect()->route('admin.asignaciones.ventanillas')->with('success', 'Ventanilla asignada correctamente.');
    }

    public function cerrarAsignacion(Request $request)
    {
        $request->validate([
            'id_usuario' => 'required|exists:usuarios,id_usuario',
            'id_ventanilla' => 'required|exists:ventanillas,id_ventanilla'
        ]);

        DB::table('usuario_x_ventanilla')
            ->where('id_usuario', $request->id_usuario)
            ->where('id_ventanilla', $request->id_ventanilla)
            ->where('estado', 'abierta')
            ->update([
                'estado' => 'cerrada',
                'hora_fin' => now(),
            ]);

        return back()->with('success', 'Asignación cerrada correctamente.');
    }

    //-------------------------------------------------------------------
    
    //-------------------------------------------------------------------
    // 📊 SECCIÓN DE REPORTES

    public function reportes(Request $request)
    {
        $user = Auth::user();
        $sucursalesAdmin = $this->sucursalesSegunAdmin();

        $fechaInicio = $request->input('fecha_inicio', Carbon::now()->subDays(7)->format('Y-m-d'));
        $fechaFin    = $request->input('fecha_fin', Carbon::now()->format('Y-m-d'));
        $ventanillaId = $request->input('ventanilla', null);
        $departamentoId = $request->input('departamento', null);

                // Base query: solo turnos finalizados en sucursales del admin (considerando id_sucursal en turnos o ventanillas)
                $query = DB::table('turnos')
                        ->leftJoin('ventanillas', 'ventanillas.id_ventanilla', '=', 'turnos.id_ventanilla')
                        ->where('turnos.estado', 'finalizado')
                        ->whereBetween('turnos.hora_fin_atencion', [$fechaInicio . " 00:00:00", $fechaFin . " 23:59:59"])
                        ->where(function($q) use ($sucursalesAdmin) {
                                $q->whereIn('turnos.id_sucursal', $sucursalesAdmin)
                                    ->orWhereIn('ventanillas.id_sucursal', $sucursalesAdmin);
                        });

        if ($ventanillaId) {
            $query->where('turnos.id_ventanilla', $ventanillaId);
        }
        if ($departamentoId) {
            $query->where('turnos.id_departamento', $departamentoId);
        }

        // turnos por día (fecha finalización)
        $turnosPorDia = (clone $query)
            ->select(DB::raw('DATE(turnos.hora_fin_atencion) as fecha'), DB::raw('COUNT(*) as total'))
            ->groupBy('fecha')
            ->orderBy('fecha', 'asc')
            ->get();

        // turnos por departamento
        $turnosPorDepto = (clone $query)
            ->join('departamentos', 'departamentos.id_departamento', '=', 'turnos.id_departamento')
            ->select('departamentos.nombre', DB::raw('COUNT(turnos.id_turno) as total'))
            ->groupBy('departamentos.nombre')
            ->get();

            $labels = $turnosPorDepto->pluck('nombre')->toArray();
            $data   = $turnosPorDepto->pluck('total')->toArray();

            $chartConfigDepto = ChartService::turnosPorDepartamento($labels, $data);
            $chartJsonDepto   = json_encode($chartConfigDepto);


        // turnos por ventanilla
        $turnosPorVentanilla = (clone $query)
            ->select('ventanillas.nombre', DB::raw('COUNT(turnos.id_turno) as total'))
            ->groupBy('ventanillas.nombre')
            ->get();

        // promedio atencion (cálculo robusto: sumar segundos y dividir para permitir decimales precisos)
        $promData = (clone $query)
            ->whereNotNull('hora_inicio_atencion')
            ->whereNotNull('hora_fin_atencion')
            ->selectRaw('SUM(TIMESTAMPDIFF(SECOND, hora_inicio_atencion, hora_fin_atencion)) as total_seconds, COUNT(*) as cnt')
            ->first();

        if ($promData && $promData->cnt > 0) {
            $promedioAtencion = ($promData->total_seconds / $promData->cnt) / 60.0; // minutos con decimales
        } else {
            $promedioAtencion = 0;
        }

        // chartUrl (QuickChart) — solo si hay datos de depto
        $chartUrl = null;
        if ($turnosPorDepto->count() > 0) {
            $labels = $turnosPorDepto->pluck('nombre')->toArray();
            $data   = $turnosPorDepto->pluck('total')->toArray();

            // Función única para obtener colores por departamento
            $getDepartmentColors = function($labels) {
                // Mapeo exacto por nombre de departamento
                $departmentColorMap = [
                    'Créditos' => '#004B93',           // Azul
                    'Servicio Técnico' => '#F6C85F',   // Amarillo
                    'Cajas' => '#2ca02c',              // Verde
                    'Atención al Cliente' => '#d62728',// Rojo
                ];
                $palette = ['#004B93','#F6C85F','#2ca02c','#d62728','#9467bd','#8c564b','#e377c2','#7f7f7f','#bcbd22','#17becf'];
                $bgColors = [];
                foreach ($labels as $i => $label) {
                    if (isset($departmentColorMap[$label])) {
                        $bgColors[] = $departmentColorMap[$label];
                    } else {
                        $bgColors[] = $palette[$i % count($palette)];
                    }
                }
                return $bgColors;
            };

            $bgColors = $getDepartmentColors($labels);

            $chartConfig = [
                'type' => 'bar',
                'data' => [
                    'labels' => $labels,
                    'datasets' => [[
                        'label' => 'Turnos por Departamento',
                        'data' => $data,
                        'backgroundColor' => $bgColors,
                        'borderColor' => '#050505ff',
                        'borderWidth' => 1
                    ]]
                ],
                'options' => [
                    'plugins' => [
                        'title' => ['display' => true, 'text' => 'Turnos por Departamento'],
                        'legend' => ['display' => false]
                    ],
                    'scales' => [
                        'yAxes' => [[
                            'ticks' => [
                                'min' => 0,
                                'stepSize' => 1,
                                'beginAtZero' => true
                            ]
                        ]]
                    ]
                ]
            ];
            $chartUrl = 'https://quickchart.io/chart?c=' . urlencode(json_encode($chartConfig)) . '&r=' . time();
        }

        // filtros select
        $ventanillas = Ventanilla::whereIn('id_sucursal', $sucursalesAdmin)->get();
        $departamentos = Departamento::all();

        return view('admin.reportes', compact(
            'turnosPorDia',
            'turnosPorDepto',
            'turnosPorVentanilla',
            'promedioAtencion',
            'fechaInicio',
            'fechaFin',
            'ventanillas',
            'departamentos',
            'ventanillaId',
            'departamentoId',
            'chartUrl'
        ));
    }

    public function reportesPdf(Request $request)
    {
        $user = Auth::user();
        $sucursalesAdmin = $this->sucursalesSegunAdmin();

        $fechaInicio = $request->input('fecha_inicio', Carbon::now()->subDays(7)->format('Y-m-d'));
        $fechaFin    = $request->input('fecha_fin', Carbon::now()->format('Y-m-d'));
        $ventanillaId = $request->input('ventanilla', null);
        $departamentoId = $request->input('departamento', null);

        // Base query: solo turnos finalizados en sucursales del admin, con rango de fecha de finalización
        // Nota: incluimos la sucursal de la ventanilla para no perder turnos antiguos sin id_sucursal en la tabla turnos
        $query = DB::table('turnos')
            ->leftJoin('ventanillas', 'ventanillas.id_ventanilla', '=', 'turnos.id_ventanilla')
            ->where('turnos.estado', 'finalizado')
            ->whereBetween('turnos.hora_fin_atencion', [$fechaInicio . " 00:00:00", $fechaFin . " 23:59:59"])
            ->where(function($q) use ($sucursalesAdmin) {
                $q->whereIn('turnos.id_sucursal', $sucursalesAdmin)
                  ->orWhereIn('ventanillas.id_sucursal', $sucursalesAdmin);
            });

        if ($ventanillaId) {
            $query->where('turnos.id_ventanilla', $ventanillaId);
        }
        if ($departamentoId) {
            $query->where('turnos.id_departamento', $departamentoId);
        }

        // total turnos
        $totalTurnos = (clone $query)->count();

        // turnos por tipo (normal / preferencial)
        $turnosPorTipo = (clone $query)
            ->select('tipo', DB::raw('COUNT(id_turno) as total'))
            ->groupBy('tipo')
            ->get();

        // turnos por estado (estado de turnos, no de ventanillas)
        $turnosPorEstado = (clone $query)
            ->select('turnos.estado', DB::raw('COUNT(turnos.id_turno) as total'))
            ->groupBy('turnos.estado')
            ->get();

        // turnos por departamento
        $turnosPorDepto = (clone $query)
            ->join('departamentos', 'departamentos.id_departamento', '=', 'turnos.id_departamento')
            ->select('departamentos.nombre', DB::raw('COUNT(turnos.id_turno) as total'))
            ->groupBy('departamentos.nombre')
            ->get();

        $labels = $turnosPorDepto->pluck('nombre')->toArray();
        $data   = $turnosPorDepto->pluck('total')->toArray();

        $chartConfigDepto = ChartService::turnosPorDepartamento($labels, $data);
        $chartUrlDepto = ChartService::toQuickChart($chartConfigDepto);
            

        // turnos por día (opcional en PDF)
        $turnosPorDia = (clone $query)
            ->select(DB::raw('DATE(turnos.created_at) as fecha'), DB::raw('COUNT(*) as total'))
            ->groupBy('fecha')
            ->orderBy('fecha', 'asc')
            ->get();

        // promedio de atención (cálculo robusto: sumar segundos y dividir para permitir decimales precisos)
        $promData = (clone $query)
            ->whereNotNull('hora_inicio_atencion')
            ->whereNotNull('hora_fin_atencion')
            ->selectRaw('SUM(TIMESTAMPDIFF(SECOND, hora_inicio_atencion, hora_fin_atencion)) as total_seconds, COUNT(*) as cnt')
            ->first();

        if ($promData && $promData->cnt > 0) {
            $promedioAtencion = ($promData->total_seconds / $promData->cnt) / 60.0; // minutos con decimales
        } else {
            $promedioAtencion = 0;
        }

        // Generar chartUrl para PDF (QuickChart) - opcional
        $chartUrl = null;
        if ($turnosPorDepto->count() > 0) {
            $labels = $turnosPorDepto->pluck('nombre')->toArray();
            $data   = $turnosPorDepto->pluck('total')->toArray();

            // Función única para obtener colores por departamento (misma que web)
            $getDepartmentColors = function($labels) {
                // Mapeo exacto por nombre de departamento
                $departmentColorMap = [
                    'Créditos' => '#004B93',           // Azul
                    'Servicio Técnico' => '#F6C85F',   // Amarillo
                    'Cajas' => '#2ca02c',              // Verde
                    'Atención al Cliente' => '#d62728',// Rojo
                ];
                $palette = ['#004B93','#F6C85F','#2ca02c','#d62728','#9467bd','#8c564b','#e377c2','#7f7f7f','#bcbd22','#17becf'];
                $bgColors = [];
                foreach ($labels as $i => $label) {
                    if (isset($departmentColorMap[$label])) {
                        $bgColors[] = $departmentColorMap[$label];
                    } else {
                        $bgColors[] = $palette[$i % count($palette)];
                    }
                }
                return $bgColors;
            };

            $bgColorsPdf = $getDepartmentColors($labels);

            $chartConfig = [
                'type' => 'bar',
                'data' => [
                    'labels' => $labels,
                    'datasets' => [[
                        'label' => 'Turnos por Departamento',
                        'data' => $data,
                        'backgroundColor' => $bgColorsPdf,
                        'borderColor' => '#333333',
                        'borderWidth' => 1
                    ]]
                ],
                'options' => [
                    'plugins' => [
                        'title' => ['display' => true, 'text' => 'Turnos por Departamento'],
                        'legend' => ['display' => false]
                    ],
                    'scales' => ['y' => ['type' => 'linear', 'beginAtZero' => true, 'ticks' => ['stepSize' => 1]]]
                ]
            ];
            $chartUrl = 'https://quickchart.io/chart?c=' . urlencode(json_encode($chartConfig));
        }

        $infoRegion = DB::table('sucursal')
            ->join('sociedad', 'sociedad.id_sociedad', '=', 'sucursal.id_sociedad')
            ->join('paises', 'paises.id_pais', '=', 'sociedad.id_pais')
            ->whereIn('sucursal.id_sucursal', $sucursalesAdmin)
            ->select('paises.nombre as pais')
            ->first();

        $pais = $infoRegion->pais ?? 'N/D';

        // Ciudad fija por país (regla que definiste)
        $ciudad = match ($pais) {
            'Honduras'     => 'Tegucigalpa',
            'Costa Rica'  => 'San José',
            default       => 'N/D',
        };

        $pref = $turnosPorTipo->firstWhere('tipo','preferencial')->total ?? 0;
        $normal = $turnosPorTipo->firstWhere('tipo','normal')->total ?? 0;

        // Detalle de turnos atendidos (similar al modal del dashboard)
        $turnosAtendidosDetalle = (clone $query)
            ->join('sucursal', 'sucursal.id_sucursal', '=', 'ventanillas.id_sucursal')
            ->leftJoin('departamentos', 'departamentos.id_departamento', '=', 'turnos.id_departamento')
            ->select(
                'turnos.numero',
                'turnos.estado',
                'departamentos.nombre as departamento',
                'ventanillas.nombre as ventanilla',
                'sucursal.nombre as sucursal',
                DB::raw('DATE_FORMAT(turnos.hora_fin_atencion, "%d/%m/%Y") as fecha')
            )
            ->orderBy('turnos.hora_fin_atencion', 'desc')
            ->get();

        // renderiza y descarga PDF
        $pdf = PDF::loadView('admin.reportes_pdf', [
            'chartJsonDepto',
            'chartUrlDepto' => $chartUrlDepto,
            'fechaFin' => $fechaFin,
            'totalTurnos' => $totalTurnos,
            'turnosPorTipo' => $turnosPorTipo,
            'turnosPorEstado' => $turnosPorEstado,
            'turnosPorDepto' => $turnosPorDepto,
            'turnosPorDia' => $turnosPorDia,
            'promedioAtencion' => $promedioAtencion,
            'chartUrl' => $chartUrl,
            'pais' => $pais,
            'ciudad' => $ciudad,
            'pref' => $pref,
            'normal' => $normal,
            'promedioAtencion' => round($promedioAtencion, 2),
            'fechaInicio' => $fechaInicio,
            'turnosAtendidosDetalle' => $turnosAtendidosDetalle,
        ]);

        return $pdf->download('reporte_turnos_' . now()->format('Ymd_His') . '.pdf');
    }
    //-------------------------------------------------------------------
    // SOCIEDAD
    // -------------------------------------------------------------------
    public function sociedad()
    {
        $sucursalesFiltro = $this->sucursalesSegunAdmin();

        $sociedades = DB::table('sociedad')
            ->join('sucursal', 'sucursal.id_sociedad', '=', 'sociedad.id_sociedad')
            ->join('paises', 'paises.id_pais', '=', 'sociedad.id_pais')
            ->whereIn('sucursal.id_sucursal', $sucursalesFiltro)
            ->select(
                'sociedad.id_sociedad',
                'sociedad.nombre',
                'paises.nombre as nombre_pais' // 👈 NUEVO
            )
            ->distinct()
            ->get();

        return view('admin.sociedad', compact('sociedades'));
    }

    public function crearSociedad()
    {
        $paises = DB::table('paises')->get();
        return view('admin.sociedad-crear', compact('paises'));
    }

    public function guardarSociedad(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
            'direccion' => 'nullable|string|max:255',
            'id_pais' => 'required|integer|exists:paises,id_pais',
        ]);

        DB::table('sociedad')->insert([
            'nombre' => $request->nombre,
            'direccion' => $request->direccion,
            'id_pais' => $request->id_pais,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.sociedad')->with('success', 'Sociedad creada correctamente.');
    }

    public function editarSociedad($id)
    {
        $sociedad = DB::table('sociedad')->where('id_sociedad', $id)->first();
        $paises = DB::table('paises')->get();

        if (!$sociedad) {
            return redirect()->route('admin.sociedad')->with('error', 'Sociedad no encontrada.');
        }

        return view('admin.sociedad-editar', compact('sociedad', 'paises'));
    }

    public function actualizarSociedad(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
            'direccion' => 'nullable|string|max:255',
            'id_pais' => 'required|integer|exists:paises,id_pais',
        ]);

        DB::table('sociedad')->where('id_sociedad', $id)->update([
            'nombre' => $request->nombre,
            'direccion' => $request->direccion,
            'id_pais' => $request->id_pais,
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.sociedad')->with('success', 'Sociedad actualizada correctamente.');
    }

    public function eliminarSociedad($id)
    {
        DB::table('sociedad')->where('id_sociedad', $id)->delete();
        return redirect()->route('admin.sociedad')->with('success', 'Sociedad eliminada correctamente.');
    }

    // ===============================
    // SUCURSALES
    // ===============================
    public function sucursales()
    {
        $sucursalesFiltro = $this->sucursalesSegunAdmin();

        $sucursales = DB::table('sucursal')
            ->join('sociedad', 'sociedad.id_sociedad', '=', 'sucursal.id_sociedad')
            ->join('paises', 'paises.id_pais', '=', 'sociedad.id_pais')
            ->whereIn('sucursal.id_sucursal', $sucursalesFiltro)
            ->select(
                'sucursal.id_sucursal',
                'sucursal.nombre',
                'sucursal.direccion',
                'sucursal.telefono',
                'paises.nombre as pais'
            )
            ->orderBy('sucursal.id_sucursal')
            ->get();

        return view('admin.sucursales', compact('sucursales'));
    }

    public function crearSucursal()
    {
        $sociedades = DB::table('sociedad')->get();
        return view('admin.sucursales-crear', compact('sociedades'));
    }

    public function guardarSucursal(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:50',
            'id_sociedad' => 'required|integer|exists:sociedad,id_sociedad',
        ]);

        DB::table('sucursal')->insert([
            'nombre' => $request->nombre,
            'direccion' => $request->direccion,
            'telefono' => $request->telefono,
            'id_sociedad' => $request->id_sociedad,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.sucursales')->with('success', 'Sucursal creada correctamente.');
    }

    public function editarSucursal($id)
    {
        $sucursal = DB::table('sucursal')->where('id_sucursal', $id)->first();
        $sociedades = DB::table('sociedad')->get();

        if (!$sucursal) {
            return redirect()->route('admin.sucursales')->with('error', 'Sucursal no encontrada.');
        }

        return view('admin.sucursales-editar', compact('sucursal', 'sociedades'));
    }

    public function actualizarSucursal(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:50',
            'id_sociedad' => 'required|integer|exists:sociedad,id_sociedad',
        ]);

        DB::table('sucursal')->where('id_sucursal', $id)->update([
            'nombre' => $request->nombre,
            'direccion' => $request->direccion,
            'telefono' => $request->telefono,
            'id_sociedad' => $request->id_sociedad,
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.sucursales')->with('success', 'Sucursal actualizada correctamente.');
    }

    public function eliminarSucursal($id)
    {
        DB::table('sucursal')->where('id_sucursal', $id)->delete();
        return redirect()->route('admin.sucursales')->with('success', 'Sucursal eliminada correctamente.');
    }

    // ======================
    // Detalles para KPIs
    // ======================

    /**
    * Detalle — Total Turnos (todos los turnos del alcance del admin)
    */
    public function detalleTotalTurnos()
    {
        $sucursales = $this->sucursalesSegunAdmin();

         $turnos = DB::table('turnos')
        ->leftJoin('ventanillas', 'ventanillas.id_ventanilla', '=', 'turnos.id_ventanilla')
        ->leftJoin('sucursal', 'sucursal.id_sucursal', '=', 'turnos.id_sucursal')
        ->leftJoin('departamentos', 'departamentos.id_departamento', '=', 'turnos.id_departamento')
        ->whereIn('turnos.id_sucursal', $sucursales)
        ->select(
            'turnos.numero',
            'turnos.estado',
            'departamentos.nombre as departamento',
            DB::raw('COALESCE(ventanillas.nombre, "-") as ventanilla'),
            DB::raw('COALESCE(sucursal.nombre, "-") as sucursal'),
            DB::raw('DATE(turnos.hora_creacion) as fecha')
        )
        ->orderBy('turnos.hora_creacion', 'desc')
        ->get();

        return response()->json($turnos);
    }

    /**
    * Detalle — En Cola (espera)
    */
    public function detalleEnCola()
    {
        $sucursales = $this->sucursalesSegunAdmin();
        
        $turnos = DB::table('turnos')
        ->where('turnos.estado', 'espera')
        ->leftJoin('departamentos', 'departamentos.id_departamento', '=', 'turnos.id_departamento')
        ->leftJoin('sucursal', 'sucursal.id_sucursal', '=', 'turnos.id_sucursal')
        ->whereIn('turnos.id_sucursal', $sucursales)
        ->select(
            'turnos.numero',
            'turnos.estado',
            'departamentos.nombre as departamento',
            DB::raw('"-" as ventanilla'),
            DB::raw('COALESCE(sucursal.nombre, "-") as sucursal'),
            DB::raw('DATE(turnos.hora_creacion) as fecha')
        )
        ->orderBy('turnos.hora_creacion', 'asc')
        ->get();

        return response()->json($turnos);
    }

    /**
    * Detalle — Llamados (atendiendo, pausado)
    */
    public function detalleLlamados()
    {
        $sucursales = $this->sucursalesSegunAdmin();

        $turnos = DB::table('turnos')
        ->whereIn('turnos.estado', ['atendiendo', 'pausado'])
        ->join('ventanillas', 'ventanillas.id_ventanilla', '=', 'turnos.id_ventanilla')
        ->join('sucursal', 'sucursal.id_sucursal', '=', 'ventanillas.id_sucursal')
        ->whereIn('sucursal.id_sucursal', $sucursales)
        ->leftJoin('departamentos', 'departamentos.id_departamento', '=', 'turnos.id_departamento')
        ->select(
            'turnos.numero',
            'turnos.estado',
            'departamentos.nombre as departamento',
            'ventanillas.nombre as ventanilla',
            'sucursal.nombre as sucursal',
            DB::raw('DATE(turnos.hora_creacion) as fecha')
        )
        ->orderBy('turnos.hora_creacion', 'desc')
        ->get();

        return response()->json($turnos);
    }

    
    /**
    * Detalle — Atendidos (finalizado)
    */
    public function detalleAtendidos()
    {
        $sucursales = $this->sucursalesSegunAdmin();

        $turnos = DB::table('turnos')
            ->where('turnos.estado', 'finalizado')
            ->join('ventanillas', 'ventanillas.id_ventanilla', '=', 'turnos.id_ventanilla')
            ->join('sucursal', 'sucursal.id_sucursal', '=', 'ventanillas.id_sucursal')
            ->whereIn('sucursal.id_sucursal', $sucursales)
            ->leftJoin('departamentos', 'departamentos.id_departamento', '=', 'turnos.id_departamento')
            ->select(
                'turnos.numero',
                'turnos.estado',
                'departamentos.nombre as departamento',
                'ventanillas.nombre as ventanilla',
                'sucursal.nombre as sucursal',
                DB::raw('DATE(turnos.hora_fin_atencion) as fecha')
            )
            ->orderBy('turnos.hora_fin_atencion', 'desc')
            ->get();

        return response()->json($turnos);
    }

    /**
    * Detalle — Promedio espera (lista por minutos de espera)
    */
    public function detallePromedioEspera()
    {
        $sucursales = $this->sucursalesSegunAdmin();

        $turnos = DB::table('turnos')
            ->whereNotNull('hora_inicio_atencion')
            ->join('ventanillas', 'ventanillas.id_ventanilla', '=', 'turnos.id_ventanilla')
            ->join('sucursal', 'sucursal.id_sucursal', '=', 'ventanillas.id_sucursal')
            ->whereIn('sucursal.id_sucursal', $sucursales)
            ->leftJoin('departamentos', 'departamentos.id_departamento', '=', 'turnos.id_departamento')
            ->select(
                'turnos.numero',
                'turnos.estado',
                DB::raw('TIMESTAMPDIFF(MINUTE, hora_creacion, hora_inicio_atencion) AS minutos_espera'),
                'departamentos.nombre as departamento',
                'ventanillas.nombre as ventanilla',
                'sucursal.nombre as sucursal',
                DB::raw('DATE(turnos.hora_inicio_atencion) as fecha')
            )
            ->orderBy('minutos_espera')
            ->get();

        return response()->json($turnos);
    }
}