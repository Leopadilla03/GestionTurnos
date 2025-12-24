<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;

class ClientesController extends Controller
{
    /**
     * 📋 Listar todos los clientes
     */
    public function index(Request $request)
    {
        $query = Cliente::query();

        // Si se envía un documento, filtrar por él
        if ($request->has('documento')) {
            $query->where('documento', 'like', '%' . $request->documento . '%');
        }

        $clientes = $query->orderBy('id_cliente', 'desc')->get();

        return response()->json($clientes);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * 🆕 Registrar un nuevo cliente
     */
    public function store(Request $request)
    {
        $request->validate([
            'documento' => 'required|string|max:20|unique:clientes,documento',
            'tipo_preferencial' => 'required|in:normal,preferencial',
        ]);

        $cliente = Cliente::create([
            'documento' => $request->documento,
            'tipo_preferencial' => $request->tipo_preferencial,
        ]);

        return response()->json([
            'message' => 'Cliente registrado exitosamente',
            'data' => $cliente
        ], 201);
    }

    /**
     * 🔍 Mostrar un cliente específico
     */
    public function show($id)
    {
        $cliente = Cliente::findOrFail($id);
        return response()->json($cliente);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * ✏️ Actualizar los datos de un cliente
     */
    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);

        $request->validate([
            'documento' => 'required|string|max:20|unique:clientes,documento,' . $cliente->id_cliente . ',id_cliente',
            'tipo_preferencial' => 'required|in:normal,preferencial',
        ]);

        $cliente->update($request->only(['documento', 'tipo_preferencial']));

        return response()->json([
            'message' => 'Cliente actualizado correctamente',
            'data' => $cliente
        ]);
    }

    /**
     * 🗑️ Eliminar un cliente
     */
    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();

        return response()->json(['message' => 'Cliente eliminado correctamente']);
    }
}
