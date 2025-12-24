<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Operador\OperadorController;
use App\Http\Controllers\Pantalla\PantallaController;

/*
Route::get('/', function () {
    return view('welcome');
});
*/

/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS (Login, Pantalla)
|--------------------------------------------------------------------------
*/

// Página de inicio → redirige al login
Route::get('/', function () {
    return redirect()->route('login');
});

// Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

// Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Pantalla pública de turnos Honduras (sin autenticación)
Route::get('/pantalla/HN', [PantallaController::class, 'index'])->name('pantalla.publica');

// Pantalla pública específica para San José (Costa Rica)
Route::get('/pantalla/CR', [PantallaController::class, 'publicaPorPais'])->name('pantalla.sanjose')->defaults('pais', 'CR');


/*
|--------------------------------------------------------------------------
| RUTAS DEL ADMINISTRADOR
|--------------------------------------------------------------------------
|
| Estas rutas usan el middleware 'rol:administrador' que tú definiste.
| Solo los usuarios con rol = 'administrador' pueden acceder.
|
*/
Route::middleware('rol:administrador')->prefix('admin')->group(function () {

    // Dashboard principal
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // Datos del dashboard (para auto-actualización)
    Route::get('/admin/dashboard/data', [AdminController::class, 'dashboardData'])
    ->name('admin.dashboard.data');

    // Gestión de Usuarios (CRUD completo)
    Route::get('/usuarios', [AdminController::class, 'usuarios'])->name('admin.usuarios');
    Route::get('/usuarios/crear', [AdminController::class, 'crearUsuario'])->name('admin.usuarios.crear');
    Route::post('/usuarios', [AdminController::class, 'guardarUsuario'])->name('admin.usuarios.guardar');
    Route::get('/usuarios/{id}/editar', [AdminController::class, 'editarUsuario'])->name('admin.usuarios.editar');
    Route::put('/usuarios/{id}', [AdminController::class, 'actualizarUsuario'])->name('admin.usuarios.actualizar');
    Route::delete('/usuarios/{id}', [AdminController::class, 'eliminarUsuario'])->name('admin.usuarios.eliminar');


    // GESTIÓN DE Departamentos y Ventanillas
    Route::get('/departamentos-ventanillas', function () {
    return view('admin.departamentos-ventanillas');
    })->name('admin.deptos-ventanillas');

    // GESTIÓN DE DEPARTAMENTOS
    Route::get('/departamentos', [AdminController::class, 'departamentos'])->name('admin.departamentos');
    Route::get('/departamentos/crear', [AdminController::class, 'crearDepartamento'])->name('admin.departamentos.crear');
    Route::post('/departamentos', [AdminController::class, 'guardarDepartamento'])->name('admin.departamentos.guardar');
    Route::get('/departamentos/{id}/editar', [AdminController::class, 'editarDepartamento'])->name('admin.departamentos.editar');
    Route::put('/departamentos/{id}', [AdminController::class, 'actualizarDepartamento'])->name('admin.departamentos.actualizar');
    Route::delete('/departamentos/{id}', [AdminController::class, 'eliminarDepartamento'])->name('admin.departamentos.eliminar');
    
    // GESTIÓN DE VENTANILLAS
    Route::get('/ventanillas', [AdminController::class, 'ventanillas'])->name('admin.ventanillas');
    Route::get('/ventanillas/crear', [AdminController::class, 'crearVentanilla'])->name('admin.ventanillas.crear');
    Route::post('/ventanillas', [AdminController::class, 'guardarVentanilla'])->name('admin.ventanillas.guardar');
    Route::get('/ventanillas/{id}/editar', [AdminController::class, 'editarVentanilla'])->name('admin.ventanillas.editar');
    Route::put('/ventanillas/{id}', [AdminController::class, 'actualizarVentanilla'])->name('admin.ventanillas.actualizar');
    Route::delete('/ventanillas/{id}', [AdminController::class, 'eliminarVentanilla'])->name('admin.ventanillas.eliminar');
    
    // GESTIÓN DE ASIGNACIONES (USUARIO x VENTANILLA)
    Route::get('/asignaciones', [AdminController::class, 'asignaciones'])->name('admin.asignaciones');
    
    // Módulo de asignación
    Route::get('/asignaciones/ventanillas', [AdminController::class, 'vistaAsignaciones'])->name('admin.asignaciones.ventanillas');
    Route::post('/asignaciones/asignar', [AdminController::class, 'asignarManual'])->name('admin.asignaciones.asignar');
    Route::post('/asignaciones/cerrar', [AdminController::class, 'cerrarAsignacion'])->name('admin.asignaciones.cerrar');


    // Reportes
    Route::get('/reportes', [AdminController::class, 'reportes'])->name('admin.reportes');
    Route::get('/reportes/pdf', [AdminController::class, 'reportesPdf'])->name('admin.reportes.pdf');

    // SOCIEDAD
    Route::get('/sociedad', [AdminController::class, 'sociedad'])->name('admin.sociedad');
    Route::get('/sociedad/crear', [AdminController::class, 'crearSociedad'])->name('admin.sociedad.crear');
    Route::post('/sociedad', [AdminController::class, 'guardarSociedad'])->name('admin.sociedad.guardar');
    Route::get('/sociedad/{id}/editar', [AdminController::class, 'editarSociedad'])->name('admin.sociedad.editar');
    Route::put('/sociedad/{id}', [AdminController::class, 'actualizarSociedad'])->name('admin.sociedad.actualizar');
    Route::delete('/sociedad/{id}', [AdminController::class, 'eliminarSociedad'])->name('admin.sociedad.eliminar');

    // SUCURSALES
    Route::get('/sucursales', [AdminController::class, 'sucursales'])->name('admin.sucursales');
    Route::get('/sucursales/crear', [AdminController::class, 'crearSucursal'])->name('admin.sucursales.crear');
    Route::post('/sucursales', [AdminController::class, 'guardarSucursal'])->name('admin.sucursales.guardar');
    Route::get('/sucursales/{id}/editar', [AdminController::class, 'editarSucursal'])->name('admin.sucursales.editar');
    Route::put('/sucursales/{id}', [AdminController::class, 'actualizarSucursal'])->name('admin.sucursales.actualizar');
    Route::delete('/sucursales/{id}', [AdminController::class, 'eliminarSucursal'])->name('admin.sucursales.eliminar');

    //API PARA DASHBOARD KPI'S
    Route::get('/detalle/total', [AdminController::class, 'detalleTotalTurnos']);
    Route::get('/detalle/cola', [AdminController::class, 'detalleEnCola']);
    Route::get('/detalle/llamados', [AdminController::class, 'detalleLlamados']);
    Route::get('/detalle/atendidos', [AdminController::class, 'detalleAtendidos']);
    Route::get('/detalle/espera', [AdminController::class, 'detallePromedioEspera']);

}); 


/*
|--------------------------------------------------------------------------
| RUTAS DEL OPERADOR
|--------------------------------------------------------------------------
|
| Rutas para los usuarios operadores que atienden turnos.
| Protegidas por 'rol:operador'.
|
*/
Route::middleware('rol:operador')->prefix('operador')->group(function () {

    // Panel principal del operador (cola de turnos)
    Route::get('/panel', [OperadorController::class, 'panel'])->name('operador.panel');

    // Acción: llamar siguiente turno
    Route::post('/llamar', [OperadorController::class, 'llamar'])->name('operador.llamar');

    // Acción: pausar turno actual
    Route::post('/pausar', [OperadorController::class, 'pausar'])->name('operador.pausar');

    // Acción: finalizar turno actual
    Route::post('/finalizar', [OperadorController::class, 'finalizar'])->name('operador.finalizar');

    // Acción: transferir turno a otro departamento
    Route::post('/transferir', [OperadorController::class, 'transferir'])->name('operador.transferir');

    // Historial de turnos atendidos por el operador
    Route::get('/historial', [OperadorController::class, 'historial'])->name('operador.historial');
/*
    //ASIGNAR VENTANILLAS
    Route::get('/asignar-ventanilla', [OperadorController::class, 'asignarVentanilla'])->name('operador.asignarVentanilla');

    // Ruta para actualizar turno en tiempo real (Ajax)
    Route::get('/api/turno-actual/{id_ventanilla}', [OperadorController::class, 'apiTurnoActual'])->name('api.turno.actual');

    Route::get('/api/turno-actual-global', [OperadorController::class, 'apiTurnoActualGlobal'])->name('api.turno.actual.global');

    Route::get('/api/cola-global', [OperadorController::class, 'apiColaGlobal'])->name('api.cola.global');
*/

});
