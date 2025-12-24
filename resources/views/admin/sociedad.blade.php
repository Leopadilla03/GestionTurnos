@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold text-primary">🏢 Sociedades</h3>
        <a href="{{ route('admin.sociedad.crear') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Nueva Sociedad
        </a>
    </div>

    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-dark">
        <i class="bi bi-house-door"></i> Volver al Panel
    </a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-3">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="table-primary">
                        <tr>
                            <th>ID</th>
                            <th>Sociedad</th>
                            <th>País</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($sociedades as $s)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $s->nombre }}</td>
                                <td>{{ $s->nombre_pais ?? '-' }}</td>

                                <td>
                                    <a href="{{ route('admin.sociedad.editar', $s->id_sociedad) }}" 
                                    class="btn btn-warning btn-sm">
                                    Editar
                                    </a>

                                    <form action="{{ route('admin.sociedad.eliminar', $s->id_sociedad) }}"
                                        method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-danger btn-sm"
                                                onclick="return confirm('¿Eliminar sociedad?')">
                                            Eliminar
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
