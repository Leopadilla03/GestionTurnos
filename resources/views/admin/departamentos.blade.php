@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="bi bi-building"></i> Departamentos</h2>
        <a href="{{ route('admin.departamentos.crear') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Nuevo Departamento
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
                <th>Descripción</th>
                <th>Atiende Preferencial</th>
                <th class="text-center">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($departamentos as $departamento)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $departamento->nombre }}</td>
                    <td>{{ $departamento->descripcion ?? '—' }}</td>
                    <td>
                        @if($departamento->atiende_preferencial)
                            <span class="badge bg-success">Sí</span>
                        @else
                            <span class="badge bg-secondary">No</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ route('admin.departamentos.editar', $departamento->id_departamento) }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil"></i> Editar
                        </a>
                        <form action="{{ route('admin.departamentos.eliminar', $departamento->id_departamento) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('¿Eliminar este departamento?')" class="btn btn-danger btn-sm">
                                <i class="bi bi-trash"></i> Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No hay departamentos registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
