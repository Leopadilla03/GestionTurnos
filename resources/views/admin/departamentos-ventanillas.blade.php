@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="text-center mb-4">
        <h2><i class="bi bi-diagram-3"></i> Departamentos y Ventanillas</h2>
        <p class="text-muted">Administra las áreas de atención y los puntos de servicio activos en tu sistema.</p>
    </div>

    <div class="row justify-content-center">
        <!-- Departamentos -->
        <div class="col-md-4">
            <div class="card text-center shadow-sm border-0">
                <div class="card-body">
                    <i class="bi bi-building-fill-gear text-primary" style="font-size: 2.5rem;"></i>
                    <h5 class="mt-3">Departamentos</h5>
                    <p class="text-muted">Configura las áreas de atención y define si atienden turnos preferenciales.</p>
                    <a href="{{ route('admin.departamentos') }}" class="btn btn-primary w-100">Ver departamentos</a>
                </div>
            </div>
        </div>

        <!-- Ventanillas -->
        <div class="col-md-4">
            <div class="card text-center shadow-sm border-0">
                <div class="card-body">
                    <i class="bi bi-window-dock text-success" style="font-size: 2.5rem;"></i>
                    <h5 class="mt-3">Ventanillas</h5>
                    <p class="text-muted">Gestiona las ventanillas activas, sus estados y las sucursales asociadas.</p>
                    <a href="{{ route('admin.ventanillas') }}" class="btn btn-success w-100">Ver ventanillas</a>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-5">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left-circle"></i> Volver al panel
        </a>
    </div>
</div>
@endsection
