@extends('layouts.app')

@section('title', 'Panel del Operador')

@section('content')
<div class="container mt-4">

    {{-- 🔹 Título principal --}}
    <h2 class="mb-4 text-center fw-bold text-danger">Panel del Operador</h2>

    {{-- 🔹 Ventanilla asignada --}}
    <div class="text-center mb-4">
        <h4>Ventanilla Asignada: <strong>{{ $ventanilla->nombre ?? 'No asignada' }}</strong></h4>
        <p class="text-muted">Departamento: {{ $ventanilla->departamento->nombre ?? '—' }}</p>
    </div>

    {{-- 🔹 Turno actual --}}
    <div class="card border-0 shadow-sm p-4 text-center">
        <h3 class="fw-bold mb-3 text-secondary">Turno Actual</h3>

        {{-- 🔸 Si hay turno activo --}}
        @if($turnoActual)
            <h1 id="turno-actual" class="display-3 fw-bold 
                @if($turnoActual->estado === 'atendiendo') text-primary 
                @elseif($turnoActual->estado === 'pausado') text-warning 
                @elseif($turnoActual->estado === 'finalizado') text-success 
                @else text-muted @endif">
                {{ $turnoActual->numero }}
            </h1>

            <p id="estado-turno" class="fs-4 fw-semibold 
                @if($turnoActual->estado === 'atendiendo') text-primary 
                @elseif($turnoActual->estado === 'pausado') text-warning 
                @elseif($turnoActual->estado === 'finalizado') text-success 
                @else text-muted @endif">
                {{ ucfirst($turnoActual->estado) }}
            </p>
        @else
            {{-- 🔸 Si no hay turno activo --}}
            <h1 id="turno-actual" class="display-3 text-muted fw-bold">--</h1>
            <p id="estado-turno" class="fs-4 fw-semibold text-muted">Sin turno</p>
        @endif

        {{-- 🔹 Botones de acción --}}
        <div class="d-flex justify-content-center gap-3 mt-3">
            <button id="btnLlamar" class="btn btn-lg btn-primary">
                <i class="bi bi-megaphone"></i> Llamar Siguiente
            </button>
            <button id="btnPausar" class="btn btn-lg btn-warning text-white">
                <i class="bi bi-pause-circle"></i> Pausar
            </button>
            <button id="btnFinalizar" class="btn btn-lg btn-success">
                <i class="bi bi-check-circle"></i> Finalizar
            </button>
        </div>

        {{-- 🔹 Transferir --}}
        <div class="mt-3">
            <select id="selectDepto" class="form-select d-inline-block w-auto">
                @foreach(\App\Models\Departamento::all() as $dep)
                    <option value="{{ $dep->id_departamento }}">{{ $dep->nombre }}</option>
                @endforeach
            </select>
            <button id="btnTransferir" class="btn btn-outline-danger ms-2">
                <i class="bi bi-arrow-left-right"></i> Transferir
            </button>
        </div>
    </div>

    {{-- 🔹 Cola de turnos --}}
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body">
            <h5 class="fw-bold text-secondary mb-3 text-center">
                <i class="bi bi-list-ul"></i> Cola de Turnos
            </h5>

            @forelse($cola as $turno)
                <div class="d-flex justify-content-between align-items-center border-bottom py-2 px-3">
                    <div>
                        <strong>{{ $turno->numero }}</strong>
                        <small class="text-muted ms-2">({{ ucfirst($turno->tipo) }})</small>
                    </div>
                    <span class="badge bg-info text-dark">{{ ucfirst($turno->estado) }}</span>
                </div>
            @empty
                <p class="text-center text-muted mt-3 mb-0">No hay turnos en espera.</p>
            @endforelse
        </div>
    </div>

    {{-- 🔹 Mensajes flash --}}
    <div id="mensaje" class="mt-4 text-center"></div>
</div>

{{-- ========================================================= --}}
{{-- 🔸 SCRIPT: manejo dinámico de botones --}}
{{-- ========================================================= --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const msg = document.getElementById('mensaje');
    const turnoLabel = document.getElementById('turno-actual');
    const estadoLabel = document.getElementById('estado-turno');
    const headers = { 
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Accept': 'application/json'
    };

    async function postData(url, data = {}) {
        const res = await fetch(url, {
            method: 'POST',
            headers,
            body: new URLSearchParams(data)
        });
        return res.json();
    }

    // 🔵 Llamar siguiente turno
    document.getElementById('btnLlamar').onclick = async () => {
        const r = await postData('{{ route("operador.llamar") }}');
        msg.innerHTML = r.success
            ? `<div class="alert alert-success">${r.success}</div>`
            : `<div class="alert alert-danger">${r.error}</div>`;

        if (r.turno) {
            // 🔹 Muestra el turno y lo marca como “Atendiendo”
            turnoLabel.innerHTML = `
                <div>${r.turno.numero}</div>
                <small class="text-muted fs-5">Pase a ${r.turno.ventanilla}</small>
            `;           
            turnoLabel.classList.remove('text-warning', 'text-success', 'text-muted');
            turnoLabel.classList.add('text-primary');

            estadoLabel.textContent = 'Atendiendo';
            estadoLabel.classList.remove('text-warning', 'text-success', 'text-muted');
            estadoLabel.classList.add('text-primary', 'animate__animated', 'animate__pulse');
        }
    };

    // 🟡 Pausar turno
    document.getElementById('btnPausar').onclick = async () => {
        const id = '{{ $turnoActual->id_turno ?? "" }}';
        if (!id) return alert('No hay turno activo');

        const r = await postData('{{ route("operador.pausar") }}', { id_turno: id });
        msg.innerHTML = `<div class="alert ${r.success ? 'alert-warning' : 'alert-danger'}">${r.success ?? r.error}</div>`;

        if (r.success) {
            // 🟡 Cambia color del turno y del estado a amarillo
            turnoLabel.classList.remove('text-primary', 'text-success', 'text-muted');
            turnoLabel.classList.add('text-warning');

            estadoLabel.textContent = 'Pausado';
            estadoLabel.classList.remove('text-primary', 'text-success', 'text-muted');
            estadoLabel.classList.add('text-warning', 'animate__animated', 'animate__pulse', 'animate__infinite');
        }
    };

    // 🟢 Finalizar turno
    document.getElementById('btnFinalizar').onclick = async () => {
        const id = '{{ $turnoActual->id_turno ?? "" }}';
        if (!id) return alert('No hay turno activo');

        const r = await postData('{{ route("operador.finalizar") }}', { id_turno: id });
        msg.innerHTML = `<div class="alert ${r.success ? 'alert-success' : 'alert-danger'}">${r.success ?? r.error}</div>`;

        if (r.success) {
            // 🟢 Cambia color del turno y lo limpia después
            turnoLabel.classList.remove('text-warning', 'text-primary', 'text-muted');
            turnoLabel.classList.add('text-success');
            turnoLabel.textContent = '✔';

            estadoLabel.textContent = 'Finalizado';
            estadoLabel.classList.remove('text-warning', 'text-primary', 'text-muted');
            estadoLabel.classList.add('text-success', 'animate__animated', 'animate__fadeIn');

            setTimeout(() => {
                turnoLabel.textContent = '--';
                estadoLabel.textContent = 'Sin turno';
                estadoLabel.className = 'fs-4 fw-semibold text-muted';
            }, 2500);
        }
    };

    // 🔹 Transferir turno
    document.getElementById('btnTransferir').onclick = async () => {
        const idTurno = '{{ $turnoActual->id_turno ?? "" }}';
        const depto = document.getElementById('selectDepto').value;

        const r = await postData('{{ route("operador.transferir") }}', {
            id_turno: idTurno,
            id_departamento: depto
        });

        msg.innerHTML = `<div class="alert ${r.success ? 'alert-info' : 'alert-danger'}">${r.success ?? r.error}</div>`;
    };
});
</script>

<script>
setInterval(() => {
    const ventanillaID = "{{ $ventanilla->id_ventanilla }}";

    fetch(`/api/turno-actual/${ventanillaID}`)
        .then(res => res.json())
        .then(data => {
            if (data.success && data.turno) {
                // Actualizar número
                document.getElementById('turno-actual').textContent = data.turno.numero;

                // Actualizar estado
                const estadoLabel = document.getElementById('estado-turno');
                estadoLabel.textContent = data.turno.estado.charAt(0).toUpperCase() + data.turno.estado.slice(1);

                if (data.turno.estado === 'atendiendo') {
                    estadoLabel.className = 'fs-4 fw-semibold text-primary';
                }

                if (data.turno.estado === 'pausado') {
                    estadoLabel.className = 'fs-4 fw-semibold text-warning';
                }

            } else {
                document.getElementById('turno-actual').textContent = '--';
                document.getElementById('estado-turno').textContent = 'Sin turno';
            }
        });
}, 3000);
</script>
@endsection