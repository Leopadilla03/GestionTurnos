@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-pencil"></i> Editar Departamento</h5>
            <a href="{{ route('admin.departamentos') }}" class="btn btn-light btn-sm">← Volver</a>
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

            <form method="POST" action="{{ route('admin.departamentos.actualizar', $departamento->id_departamento) }}" class="needs-validation" novalidate>
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" value="{{ old('nombre', $departamento->nombre) }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control">{{ old('descripcion', $departamento->descripcion) }}</textarea>
                </div>

                <div class="form-check mb-3">
                    <input 
                        type="checkbox" 
                        name="atiende_preferencial" 
                        id="atiende_preferencial" 
                        value="1"
                        class="form-check-input"
                        {{ $departamento->atiende_preferencial ? 'checked' : '' }}>
                    <label for="atiende_preferencial" class="form-check-label">Atiende clientes preferenciales</label>
                </div>

                <button type="submit" class="btn btn-warning">Actualizar</button>
            </form>
        </div>
    </div>
</div>
@endsection
