@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-pencil-square"></i> Editar Usuario</h5>
            <a href="{{ route('admin.usuarios') }}" class="btn btn-light btn-sm">← Volver</a>
        </div>

        <div class="card-body">
            {{-- Mensaje de éxito --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Mensajes de error --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Formulario de edición --}}
            <form action="{{ route('admin.usuarios.actualizar', $usuario->id_usuario) }}" method="POST" class="needs-validation" novalidate>
                @csrf
                @method('PUT')

                {{-- Nombre --}}
                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input 
                        type="text" 
                        name="nombre" 
                        value="{{ old('nombre', $usuario->nombre) }}" 
                        class="form-control @error('nombre') is-invalid @enderror" 
                        required
                    >
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Correo electrónico --}}
                <div class="mb-3">
                    <label class="form-label">Correo Electrónico</label>
                    <input 
                        type="email" 
                        name="email" 
                        value="{{ old('email', $usuario->email) }}" 
                        class="form-control @error('email') is-invalid @enderror" 
                        required
                    >
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Rol --}}
                <div class="mb-3">
                    <label class="form-label">Rol</label>
                    <select 
                        name="rol" 
                        class="form-select @error('rol') is-invalid @enderror" 
                        required
                    >
                        <option value="administrador" {{ old('rol', $usuario->rol) == 'administrador' ? 'selected' : '' }}>Administrador</option>
                        <option value="operador" {{ old('rol', $usuario->rol) == 'operador' ? 'selected' : '' }}>Operador</option>
                    </select>
                    @error('rol')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Contraseña (opcional) --}}
                <div class="mb-3">
                    <label class="form-label">Nueva Contraseña <span class="text-muted">(opcional)</span></label>
                    <input 
                        type="password" 
                        name="password" 
                        class="form-control @error('password') is-invalid @enderror" 
                        placeholder="Dejar vacío si no desea cambiarla"
                    >
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Estado --}}
                <div class="mb-3">
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-select">
                        <option value="activo" {{ $usuario->estado == 'activo' ? 'selected' : '' }}>Activo</option>
                        <option value="inactivo" {{ $usuario->estado == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>

                {{-- Botones --}}
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-warning me-2">
                        <i class="bi bi-save"></i> Actualizar
                    </button>
                    <a href="{{ route('admin.usuarios') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script de validación de Bootstrap --}}
<script>
    (function () {
        'use strict';
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();
</script>
@endsection
