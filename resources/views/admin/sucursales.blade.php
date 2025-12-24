@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold text-primary">🏬 Sucursales</h3>
        <a href="{{ route('admin.sucursales.crear') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Nueva Sucursal
        </a>
    </div>

    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-dark">
        <i class="bi bi-house-door"></i> Volver al Panel
    </a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Dirección</th>
                            <th>Teléfono</th>
                            <th>País</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($sucursales as $s)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $s->nombre }}</td>
                                <td>{{ $s->direccion }}</td>
                                <td>{{ $s->telefono }}</td>
                                <td>{{ $s->pais }}</td>

                                <td>
                                    <a href="{{ route('admin.sucursales.editar', $s->id_sucursal) }}" 
                                    class="btn btn-sm btn-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <form action="{{ route('admin.sucursales.eliminar', $s->id_sucursal) }}" 
                                        method="POST" 
                                        style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-sm btn-danger"
                                                onclick="return confirm('¿Eliminar sucursal?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
