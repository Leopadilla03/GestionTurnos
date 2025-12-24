@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="text-center mb-4">📊 Reportes del Sistema</h2>

    <div class="mb-3">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left-circle"></i> Volver al Panel Principal
        </a>
    </div>

    {{-- 🔹 FORMULARIO DE FILTROS --}}
    <form method="GET" action="{{ route('admin.reportes') }}" class="row g-3 mb-4">
        <div class="col-md-3">
            <label for="fecha_inicio" class="form-label">Desde</label>
            <input type="date" name="fecha_inicio" value="{{ $fechaInicio }}" class="form-control">
        </div>
        <div class="col-md-3">
            <label for="fecha_fin" class="form-label">Hasta</label>
            <input type="date" name="fecha_fin" value="{{ $fechaFin }}" class="form-control">
        </div>
        <div class="col-md-2">
            <label for="ventanilla" class="form-label">Ventanilla</label>
            <select name="ventanilla" class="form-select">
                <option value="">Todas</option>
                @foreach ($ventanillas as $vent)
                    <option value="{{ $vent->id_ventanilla }}" {{ $ventanillaId == $vent->id_ventanilla ? 'selected' : '' }}>
                        {{ $vent->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label for="departamento" class="form-label">Departamento</label>
            <select name="departamento" class="form-select">
                <option value="">Todos</option>
                @foreach ($departamentos as $dpto)
                    <option value="{{ $dpto->id_departamento }}" {{ $departamentoId == $dpto->id_departamento ? 'selected' : '' }}>
                        {{ $dpto->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-success w-100">Filtrar</button>
        </div>
    </form>

    {{-- 🔹 BOTÓN EXPORTAR PDF --}}
    <div class="mb-3 text-end">
        <form method="GET" action="{{ route('admin.reportes.pdf') }}" target="_blank">
            <input type="hidden" name="fecha_inicio" value="{{ $fechaInicio }}">
            <input type="hidden" name="fecha_fin" value="{{ $fechaFin }}">
            <input type="hidden" name="ventanilla" value="{{ $ventanillaId }}">
            <input type="hidden" name="departamento" value="{{ $departamentoId }}">
            <button type="submit" class="btn btn-outline-danger">
                <i class="bi bi-file-earmark-pdf"></i> Exportar PDF
            </button>
        </form>
    </div>

    {{-- 🔹 AQUÍ VA EL BLOQUE DEL GRÁFICO PRINCIPAL (INSÉRTALO AQUÍ) --}}
    {{-- gráfico --}}
    @if(!empty($chartUrl))
        <div class="text-center my-3">
            <img src="{{ $chartUrl }}" alt="Gráfico" width="600">
        </div>
    @else
        <p class="text-muted text-center mt-3">No hay datos disponibles para mostrar el gráfico.</p>
    @endif

    {{-- tabla turnos por día --}}
    @if(isset($turnosPorDia) && $turnosPorDia->count() > 0)
        <div class="card mt-4">
            <div class="card-header bg-primary text-white">Turnos finalizados por día</div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Total de Turnos</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($turnosPorDia as $t)
                            <tr>
                                <td>{{ $t->fecha }}</td>
                                <td>{{ $t->total }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <p class="text-center text-secondary mt-4">No hay registros de turnos finalizados en este rango de fechas.</p>
    @endif

    {{-- tabla turnos por ventanilla --}}
    @if(isset($turnosPorVentanilla) && $turnosPorVentanilla->count() > 0)
        <div class="card mt-4">
            <div class="card-header bg-primary text-white">Turnos finalizados por Ventanilla</div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Ventanilla</th>
                            <th>Total de Turnos</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($turnosPorVentanilla as $t)
                            <tr>
                                <td>{{ $t->nombre ?? '—' }}</td>
                                <td>{{ $t->total }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <p class="text-center text-secondary mt-4">No hay datos de ventanillas.</p>
    @endif

    {{-- 🔹 PROMEDIO DE ATENCIÓN --}}
    <div class="alert alert-info mt-4 text-center">
        <strong>Promedio de tiempo de atención:</strong> {{ number_format($promedioAtencion, 2) }} minutos
    </div>
</div>
@endsection