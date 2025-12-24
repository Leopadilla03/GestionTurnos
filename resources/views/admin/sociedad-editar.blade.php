@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold text-primary">✏️ Editar Sociedad</h3>
        <a href="{{ route('admin.sociedad') }}" class="btn btn-outline-secondary">
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

    <form action="{{ route('admin.sociedad.actualizar', $sociedad->id_sociedad) }}" method="POST" class="card p-4 shadow-sm">
        @csrf @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nombre de la sociedad</label>
            <input type="text" name="nombre" value="{{ old('nombre', $sociedad->nombre) }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">País</label>
            <select name="id_pais" class="form-select" required>
                @foreach($paises as $p)
                    <option value="{{ $p->id_pais }}" {{ (old('id_pais', $sociedad->id_pais) == $p->id_pais) ? 'selected' : '' }}>
                        {{ $p->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('admin.sociedad') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Cancelar
            </a>
            <button type="submit" class="btn btn-primary">Actualizar sociedad</button>
        </div>
    </form>
</div>
@endsection
