@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold mb-4 text-center text-primary">
        <i class="bi bi-speedometer2 me-2"></i> Panel de Administración - Credi Q
    </h2>

    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    {{-- ==================== PRIMERA FILA ==================== --}}
    <div class="row g-4 mb-4">
        {{-- USUARIOS --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-people fs-1 text-primary"></i>
                    <h5 class="mt-2">Usuarios</h5>
                    <h2 class="fw-bold text-dark mb-1">{{ $usuarios }}</h2>
                    <p class="text-muted small mb-3">usuarios registrados</p>
                    <a href="{{ route('admin.usuarios') }}" class="btn btn-outline-primary">
                        Ver usuarios
                    </a>
                </div>
            </div>
        </div>

        {{-- DEPARTAMENTOS --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-layers fs-1 text-warning"></i>
                    <h5 class="mt-2">Departamentos</h5>
                    <h2 class="fw-bold text-dark mb-1">{{ $departamentos }}</h2>
                    <p class="text-muted small mb-3">departamentos activos</p>
                    <a href="{{ route('admin.departamentos') }}" class="btn btn-outline-warning">
                        Ver departamentos
                    </a>
                </div>
            </div>
        </div>

        {{-- VENTANILLAS --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-pc-display fs-1 text-info"></i>
                    <h5 class="mt-2">Ventanillas</h5>
                    <h2 class="fw-bold text-dark mb-1">{{ $ventanillas }}</h2>
                    <p class="text-muted small mb-3">puntos de atención</p>
                    <a href="{{ route('admin.ventanillas') }}" class="btn btn-outline-info">
                        Ver ventanillas
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== SEGUNDA FILA ==================== --}}
    <div class="row g-4 mb-4">
        {{-- SOCIEDADES --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-building fs-1 text-primary"></i>
                    <h5 class="mt-2">Sociedades</h5>
                    <h2 class="fw-bold text-dark mb-1">{{ $sociedades }}</h2>
                    <p class="text-muted small mb-3">sociedades registradas</p>
                    <a href="{{ route('admin.sociedad') }}" class="btn btn-outline-primary">
                        Ver sociedades
                    </a>
                </div>
            </div>
        </div>

        {{-- SUCURSALES --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-diagram-3 fs-1 text-success"></i>
                    <h5 class="mt-2">Sucursales</h5>
                    <h2 class="fw-bold text-dark mb-1">{{ $sucursales }}</h2>
                    <p class="text-muted small mb-3">oficinas activas</p>
                    <a href="{{ route('admin.sucursales') }}" class="btn btn-outline-success">
                        Ver sucursales
                    </a>
                </div>
            </div>
        </div>

        {{-- REPORTES --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <i class="bi bi-clipboard-data fs-1 text-danger"></i>
                    <h5 class="mt-2">Reportes</h5>
                    <p class="text-muted">Consulta estadísticas de atención y productividad.</p>
                    <a href="{{ route('admin.reportes') }}" class="btn btn-outline-danger">
                        Ver reportes
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== ESTILO FINAL ==================== --}}
    <div class="text-center text-muted small mt-4">
        © {{ date('Y') }} <strong>Credi Q</strong> | Sistema de Gestión de Turnos v1.0
    </div>

    {{-- ==================== ESTADÍSTICAS DE TURNO (TIPO NOVOSGA) ==================== --}}
    <div class="row g-4 mt-2">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-4 text-primary">
                        <i class="bi bi-activity"></i> Estado General del Sistema
                    </h5>

                {{-- KPI values computed in controller scoped by admin country --}}
                <div class="row text-center">

                    {{-- Total de Turnos --}}
                    <div class="col-md-2 col-6 mb-3">
                        <div class="p-3 border rounded shadow-sm bg-light kpi"
                            data-url="{{ url('admin/detalle/total') }}">
                            <i class="bi bi-person-lines-fill fs-3 text-primary"></i>
                            <h4 class="fw-bold mb-0">{{ $totalTurnos ?? 0 }}</h4>
                            <small class="text-muted">Total Turnos</small>
                        </div>
                    </div>

                    {{-- En Cola --}}
                    <div class="col-md-2 col-6 mb-3">
                        <div class="p-3 border rounded shadow-sm bg-light kpi"
                            data-url="{{ url('admin/detalle/cola') }}">
                            <i class="bi bi-hourglass-split fs-3 text-warning"></i>
                            <h4 class="fw-bold mb-0">{{ $enCola ?? 0 }}</h4>
                            <small class="text-muted">En Cola</small>
                        </div>
                    </div>

                    {{-- Llamados --}}
                    <div class="col-md-2 col-6 mb-3">
                        <div class="p-3 border rounded shadow-sm bg-light kpi"
                            data-url="{{ url('admin/detalle/llamados') }}">
                            <i class="bi bi-bell-fill fs-3 text-info"></i>
                            <h4 class="fw-bold mb-0">{{ $llamados ?? 0 }}</h4>
                            <small class="text-muted">Llamados</small>
                        </div>
                    </div>

                    {{-- Atendidos --}}
                    <div class="col-md-2 col-6 mb-3">
                        <div class="p-3 border rounded shadow-sm bg-light kpi"
                            data-url="{{ url('admin/detalle/atendidos') }}">
                            <i class="bi bi-check-circle-fill fs-3 text-success"></i>
                            <h4 class="fw-bold mb-0">{{ $atendidos ?? 0 }}</h4>
                            <small class="text-muted">Atendidos</small>
                        </div>
                    </div>

                    {{-- Promedio Espera --}}
                    <div class="col-md-2 col-6 mb-3">
                        <div class="p-3 border rounded shadow-sm bg-light kpi"
                            data-url="{{ url('admin/detalle/espera') }}">
                            <i class="bi bi-stopwatch-fill fs-3 text-danger"></i>
                            <h4 class="fw-bold mb-0">{{ $promedioEspera ?? 0 }} min</h4>
                            <small class="text-muted">Promedio Espera</small>
                        </div>
                    </div>

                    {{-- Última actualización --}}
                    <div class="col-md-2 col-6 mb-3">
                        <div class="p-3 border rounded shadow-sm bg-light">
                            <i class="bi bi-clock-history fs-3 text-secondary"></i>
                            <h6 class="fw-bold mb-0">{{ now()->format('H:i:s') }}</h6>
                            <small class="text-muted">Actualizado</small>
                        </div>
                    </div>
                </div>
                    <div class="text-center mt-3">
                        <small class="text-muted">Datos actualizados automáticamente en cada carga de panel.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detalle KPI -->
<div class="modal fade" id="modalKPIs" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold" id="tituloModalKPI"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <table class="table table-striped" id="tablaKPI">
            <thead>
                <tr>
                    <th>Número</th>
                    <th>Estado</th>
                    <th>Departamento</th>
                    <th>Ventanilla</th>
                    <th>Sucursal</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", () => {

    document.querySelectorAll(".kpi").forEach(card => {
        card.style.cursor = "pointer";

        card.addEventListener("click", () => {
            let url = card.dataset.url;

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    const modal = new bootstrap.Modal(document.getElementById('modalKPIs'));
                    const tbody = document.querySelector("#tablaKPI tbody");
                    tbody.innerHTML = "";

                    document.getElementById("tituloModalKPI").innerText = 
                        card.querySelector("small").innerText;

                    data.forEach(t => {
                        tbody.innerHTML += `
                            <tr>
                                <td>${t.numero ?? '-'}</td>
                                <td>${t.estado ?? '-'}</td>
                                <td>${t.departamento ?? '-'}</td>
                                <td>${t.ventanilla ?? '-'}</td>
                                <td>${t.sucursal ?? '-'}</td>
                                <td>${t.fecha ?? '-'}</td>
                            </tr>`;
                    });

                    modal.show();
                });
        });
    });

});
</script>
@endpush


