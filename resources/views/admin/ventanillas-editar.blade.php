@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-pencil"></i> Editar Ventanilla</h5>
            <a href="{{ route('admin.ventanillas') }}" class="btn btn-light btn-sm">← Volver</a>
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

            <form action="{{ route('admin.ventanillas.actualizar', $ventanilla->id_ventanilla) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" value="{{ $ventanilla->nombre }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Sucursal</label>
                    <select name="id_sucursal" class="form-select" required>
                        @foreach($sucursales as $sucursal)
                            <option value="{{ $sucursal->id_sucursal }}" {{ $ventanilla->id_sucursal == $sucursal->id_sucursal ? 'selected' : '' }}>
                                {{ $sucursal->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Departamento</label>
                    <select name="id_departamento" class="form-select" required>
                        @foreach($departamentos as $departamento)
                            <option value="{{ $departamento->id_departamento }}" {{ $ventanilla->id_departamento == $departamento->id_departamento ? 'selected' : '' }}>
                                {{ $departamento->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-select">
                        <option value="activa" {{ $ventanilla->estado == 'activa' ? 'selected' : '' }}>Activa</option>
                        <option value="inactiva" {{ $ventanilla->estado == 'inactiva' ? 'selected' : '' }}>Inactiva</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-warning">Actualizar</button>
            </form>
        </div>
    </div>
</div>
@endsection
