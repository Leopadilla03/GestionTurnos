@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-center">👥 Asignaciones de Usuarios a Ventanillas</h2>

    <div class="mb-3">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left-circle"></i> Volver al Panel Principal
        </a>
    </div>

    @if($asignaciones->isEmpty())
        <div class="alert alert-info text-center">
            No hay asignaciones registradas actualmente.
        </div>
    @else
        <table class="table table-striped">
            <thead class="table-primary">
                <tr>
                    <th>Usuario</th>
                    <th>Ventanilla</th>
                    <th>Inicio</th>
                    <th>Fin</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($asignaciones as $asig)
                    <tr>
                        <td>{{ $asig->usuario }}</td>
                        <td>{{ $asig->ventanilla }}</td>
                        <td>{{ $asig->hora_inicio }}</td>
                        <td>{{ $asig->hora_fin ?? '—' }}</td>
                        <td>
                            <span class="badge bg-{{ $asig->estado == 'abierta' ? 'success' : 'secondary' }}">
                                {{ ucfirst($asig->estado) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
