@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-plus-circle"></i> Crear Nuevo Departamento</h5>
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

            <form method="POST" action="{{ route('admin.departamentos.guardar') }}" class="needs-validation" novalidate>
                @csrf

                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control">{{ old('descripcion') }}</textarea>
                </div>

                <div class="form-check mb-3">
                    <input type="checkbox" name="atiende_preferencial" id="atiende_preferencial" value="1" class="form-check-input">
                    <label for="atiende_preferencial" class="form-check-label">Atiende clientes preferenciales</label>
                </div>

                <button type="submit" class="btn btn-success">Guardar</button>
            </form>
        </div>
    </div>
</div>
@endsection
