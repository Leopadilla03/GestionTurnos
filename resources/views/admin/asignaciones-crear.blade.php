@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-plus-circle"></i> Nueva Asignación</h5>
            <a href="{{ route('admin.asignaciones') }}" class="btn btn-light btn-sm">← Volver</a>
        </div>

        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.asignaciones.guardar') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Operador</label>
                    <select name="id_usuario" class="form-select" required>
                        <option value="">Seleccione un operador</option>
                        @foreach($usuarios as $usuario)
                            <option value="{{ $usuario->id_usuario }}">{{ $usuario->nombre }} ({{ $usuario->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Ventanilla</label>
                    <select name="id_ventanilla" class="form-select" required>
                        <option value="">Seleccione una ventanilla</option>
                        @foreach($ventanillas as $ventanilla)
                            <option value="{{ $ventanilla->id_ventanilla }}">
                                {{ $ventanilla->nombre }} — {{ $ventanilla->sucursal->nombre ?? '' }} / {{ $ventanilla->departamento->nombre ?? '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-success">Asignar</button>
            </form>
        </div>
    </div>
</div>
@endsection
