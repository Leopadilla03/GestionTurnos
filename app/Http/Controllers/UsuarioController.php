<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

class UsuarioController extends Controller
{
     /**
     * 📋 Listar todos los usuarios
     */
    public function index(Request $request)
    {
        $query = Usuario::query();

        // Filtrar por rol si se envía
        if ($request->has('rol')) {
            $query->where('rol', $request->rol);
        }

        // Filtrar por nombre
        if ($request->has('nombre')) {
            $query->where('nombre', 'like', '%' . $request->nombre . '%');
        }

        $usuarios = $query->orderBy('id_usuario', 'asc')->get();

        return response()->json($usuarios);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * 🆕 Registrar un nuevo usuario
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'correo' => 'required|email|unique:usuarios,correo',
            'password' => 'required|string|min:6',
            'rol' => 'required|in:admin,operador',
        ]);

        $usuario = Usuario::create([
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'password' => Hash::make($request->password),
            'rol' => $request->rol,
        ]);

        return response()->json([
            'message' => 'Usuario creado exitosamente',
            'data' => $usuario
        ], 201);
    }

    /**
     * 🔍 Mostrar un usuario específico
     */
    public function show(string $id)
    {
        $usuario = Usuario::findOrFail($id);
        return response()->json($usuario);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
    * ✏️ Actualizar información del usuario
    */
    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        $request->validate([
            'nombre' => 'sometimes|string|max:100',
            'correo' => 'sometimes|email|unique:usuarios,correo,' . $usuario->id_usuario . ',id_usuario',
            'password' => 'nullable|string|min:6',
            'rol' => 'sometimes|in:admin,operador',
        ]);

        $usuario->update([
            'nombre' => $request->nombre ?? $usuario->nombre,
            'correo' => $request->correo ?? $usuario->correo,
            'rol' => $request->rol ?? $usuario->rol,
            'password' => $request->password
                ? Hash::make($request->password)
                : $usuario->password,
        ]);

        return response()->json([
            'message' => 'Usuario actualizado correctamente',
            'data' => $usuario
        ]);
    }

    /**
     * 🗑️ Eliminar usuario
     */
    public function destroy(string $id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->delete();

        return response()->json(['message' => 'Usuario eliminado correctamente']);
    }
}
