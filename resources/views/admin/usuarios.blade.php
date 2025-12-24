@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2>Gestión de Usuarios</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('admin.usuarios.crear') }}" class="btn btn-primary mb-3">Crear Nuevo Usuario</a>

    <div class="text-center mb-2">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left-circle"></i> Volver al panel
        </a>
    </div>

    <table class="table table-bordered table-striped mt-3">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Estado</th>
                <th class="text-center">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($usuarios ?? [] as $usuario)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $usuario->nombre }}</td>
                    <td>{{ $usuario->email }}</td>
                    <td>{{ ucfirst($usuario->rol) }}</td>
                    <td>{{ ucfirst($usuario->estado) }}</td>
                    <td class="text-center">
                        <a href="{{ route('admin.usuarios.editar', $usuario->id_usuario) }}" 
                        class="btn btn-warning btn-sm">Editar</a>
                                <form action="{{ route('admin.usuarios.eliminar', $usuario->id_usuario) }}" 
                                    method="POST" 
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('¿Estás seguro de eliminar este usuario?')">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                <tr>
                    <td colspan="6" class="text-center">No hay usuarios registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
