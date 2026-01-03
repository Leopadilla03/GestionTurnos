@extends('layouts.kiosco')

@section('content')
<style>
    .documento-input {
        border: 3px solid #e2e8f0;
        border-radius: 20px;
        font-size: 1.5rem;
        padding: 1.5rem;
        text-align: center;
        font-weight: 600;
        transition: all 0.3s ease;
        background: #f7fafc;
    }
    .documento-input:focus {
        border-color: var(--grad-start);
        box-shadow: 0 0 0 4px rgba(0, 0, 0, 0.1);
        background: white;
        outline: none;
    }
    .tipo-atencion-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    .radio-option {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 1rem 2.5rem;
        border-radius: 15px;
        border: 3px solid #e2e8f0;
        margin: 0 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 600;
        font-size: 1.1rem;
        gap: 0.5rem;
        user-select: none;
    }
    .radio-option:hover {
        border-color: var(--grad-start);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }
    .radio-option input[type="radio"] {
        display: none;
    }
    .radio-option span {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }
    .radio-option input[type="radio"]:checked + span {
        color: white;
    }
    .radio-option.checked {
        background: linear-gradient(135deg, var(--grad-start) 0%, var(--grad-end) 100%);
        color: white;
        border-color: var(--grad-start);
    }
    .departamento-card {
        background: white;
        border: none;
        border-radius: 20px;
        padding: 2.5rem 1.5rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        cursor: pointer;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .departamento-card:hover {
        transform: translateY(-10px) scale(1.05);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.25);
    }
    .departamento-card:active {
        transform: translateY(-5px) scale(1.02);
    }
    .departamento-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        background: linear-gradient(135deg, var(--grad-start) 0%, var(--grad-end) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .departamento-nombre {
        font-weight: 700;
        font-size: 1.2rem;
        color: #2d3748;
        margin: 0;
    }
    .error-alert {
        background: var(--accent-soft);
        border: 2px solid var(--accent);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        color: #4a5568;
    }
</style>

<div class="text-center">
    <div class="country-badge float-animation">
        <i class="bi bi-geo-alt-fill"></i> {{ strtoupper($pais ?? 'HN') }}
    </div>
    <h1 class="kiosco-title">¡Bienvenido a Credi Q!</h1>
    <p class="subtitle"><i class="bi bi-hand-index"></i> Ingrese su documento y seleccione su trámite</p>
</div>

{{-- Mostrar errores si hay --}}
@if($errors->any())
    <div class="error-alert">
        <h5><i class="bi bi-exclamation-triangle-fill"></i> Por favor corrija lo siguiente:</h5>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ isset($pais) && strtoupper($pais) === 'CR' ? route('kiosco.cr.turno') : route('kiosco.turno') }}">
    @csrf
    
    {{-- Documento --}}
    <div class="mb-4">
        <label class="fw-bold fs-5 mb-3" style="color: #4a5568;">
            <i class="bi bi-person-badge"></i> Número de Documento
        </label>
        <input type="text"
               name="documento"
               class="form-control documento-input"
               placeholder="Ingrese su Identidad o Cédula"
               value="{{ old('documento') }}"
               required
               autofocus>
    </div>

    {{-- Tipo atención --}}
    <div class="tipo-atencion-card">
        <label class="fw-bold fs-5 mb-3" style="color: #4a5568;">
            <i class="bi bi-star-fill"></i> Tipo de Atención
        </label>
        <div>
            @php
                $tipoSeleccionado = old('tipo') ?? 'normal';
            @endphp
            <label class="radio-option {{ $tipoSeleccionado === 'normal' ? 'checked' : '' }}">
                <input type="radio" name="tipo" value="normal" {{ $tipoSeleccionado === 'normal' ? 'checked' : '' }}>
                <span><i class="bi bi-person"></i> Normal</span>
            </label>
            <label class="radio-option {{ $tipoSeleccionado === 'preferencial' ? 'checked' : '' }}">
                <input type="radio" name="tipo" value="preferencial" {{ $tipoSeleccionado === 'preferencial' ? 'checked' : '' }}>
                <span><i class="bi bi-star-fill"></i> Preferencial</span>
            </label>
        </div>
    </div>

    {{-- Departamentos --}}
    <div class="mb-3">
        <label class="fw-bold fs-5 mb-4" style="color: #4a5568;">
            <i class="bi bi-grid-3x3-gap-fill"></i> Seleccione su Departamento
        </label>
    </div>
    <div class="row justify-content-center g-4">
        @php
            $iconos = [
                'Créditos' => 'bi-cash-coin',
                'Servicio Técnico' => 'bi-tools',
                'Cajas' => 'bi-calculator',
                'Atención al Cliente' => 'bi-headset'
            ];
        @endphp
        @foreach($departamentos as $dep)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <button type="submit"
                        name="id_departamento"
                        value="{{ $dep->id_departamento }}"
                        class="departamento-card w-100">
                    <i class="bi {{ $iconos[$dep->nombre] ?? 'bi-building' }} departamento-icon"></i>
                    <p class="departamento-nombre">{{ $dep->nombre }}</p>
                </button>
            </div>
        @endforeach
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const options = document.querySelectorAll('.radio-option');
    const inputs = document.querySelectorAll('input[name="tipo"]');

    const syncChecked = () => {
        options.forEach(o => o.classList.remove('checked'));
        inputs.forEach(i => {
            if (i.checked && i.closest('.radio-option')) {
                i.closest('.radio-option').classList.add('checked');
            }
        });
    };

    inputs.forEach(input => {
        input.addEventListener('change', syncChecked);
    });

    syncChecked();
});
</script>
@endsection