@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold mb-4">
        <i class="bi bi-person-check-fill me-2"></i> Asignaciones - Operadores a Ventanillas
    </h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('admin.asignaciones.ventanillas') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i> Asignar Nueva Ventanilla
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if($asignaciones->count())
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th>Operador</th>
                                <th>Ventanilla</th>
                                <th>Sucursal</th>
                                <th>Departamento</th>
                                <th>Fecha Inicio</th>
                                <th>Fecha Fin</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($asignaciones as $asig)
                                <tr>
                                    <td>
                                        <strong>{{ $asig->usuario }}</strong><br>
                                        <small class="text-muted">ID: {{ $asig->id_usuario }}</small>
                                    </td>
                                    <td>{{ $asig->ventanilla }}</td>
                                    <td>{{ $asig->sucursal }}</td>
                                    <td>{{ $asig->departamento }}</td>
                                    <td>
                                        <small>{{ $asig->hora_inicio ? $asig->hora_inicio->format('d/m/Y H:i') : '-' }}</small>
                                    </td>
                                    <td>
                                        @if($asig->hora_fin)
                                            <small>{{ $asig->hora_fin->format('d/m/Y H:i') }}</small>
                                        @else
                                            <span class="badge bg-warning text-dark">Sin cerrar</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($asig->estado === 'abierta')
                                            <span class="badge bg-success">Abierta</span>
                                        @else
                                            <span class="badge bg-secondary">Cerrada</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($asig->estado === 'abierta')
                                            <form action="{{ route('admin.asignaciones.cerrar') }}" method="POST" style="display: inline;">
                                                @csrf
                                                <input type="hidden" name="id_usuario" value="{{ $asig->id_usuario }}">
                                                <input type="hidden" name="id_ventanilla" value="{{ $asig->id_ventanilla }}">
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Cerrar asignación?')">
                                                    <i class="bi bi-x-circle me-1"></i> Cerrar
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle me-2"></i> No hay asignaciones registradas.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
