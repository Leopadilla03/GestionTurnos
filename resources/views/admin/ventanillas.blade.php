@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="bi bi-window"></i> Ventanillas</h2>
        <a href="{{ route('admin.ventanillas.crear') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Nueva Ventanilla
        </a>
    </div>

    <div class="mb-3">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left-circle"></i> Volver al Panel
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Sucursal</th>
                <th>Departamento</th>
                <th>Estado</th>
                <th class="text-center">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($ventanillas as $ventanilla)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $ventanilla->nombre }}</td>
                    <td>{{ $ventanilla->sucursal->nombre ?? '—' }}</td>
                    <td>{{ $ventanilla->departamento->nombre ?? '—' }}</td>
                    <td>
                        @if($ventanilla->estado === 'activa')
                            <span class="badge bg-success">Activa</span>
                        @else
                            <span class="badge bg-secondary">Inactiva</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ route('admin.ventanillas.editar', $ventanilla->id_ventanilla) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil"></i> Editar
                        </a>
                        <form action="{{ route('admin.ventanillas.eliminar', $ventanilla->id_ventanilla) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('¿Eliminar esta ventanilla?')" class="btn btn-danger btn-sm">
                                <i class="bi bi-trash"></i> Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No hay ventanillas registradas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
