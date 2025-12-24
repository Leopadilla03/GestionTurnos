@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold text-primary">✏️ Editar Sucursal</h3>
        <a href="{{ route('admin.sucursales') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.sucursales.actualizar', $sucursal->id_sucursal) }}" method="POST" class="card p-4 shadow-sm">
        @csrf @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nombre de la sucursal</label>
            <input type="text" name="nombre" value="{{ old('nombre', $sucursal->nombre) }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Dirección</label>
            <textarea name="direccion" class="form-control" rows="2">{{ old('direccion', $sucursal->direccion) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Teléfono</label>
            <input type="text" name="telefono" value="{{ old('telefono', $sucursal->telefono) }}" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Sociedad</label>
            <select name="id_sociedad" class="form-select" required>
                @foreach($sociedades as $soc)
                    <option value="{{ $soc->id_sociedad }}" {{ (old('id_sociedad', $sucursal->id_sociedad) == $soc->id_sociedad) ? 'selected' : '' }}>
                        {{ $soc->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('admin.sucursales') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Cancelar
            </a>
            <button type="submit" class="btn btn-primary">Actualizar sucursal</button>
        </div>
    </form>
</div>
@endsection