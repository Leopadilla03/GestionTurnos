@extends('layouts.kiosco')

@section('content')
<style>
    @keyframes ticketAppear {
        0% {
            opacity: 0;
            transform: scale(0.5) rotateY(90deg);
        }
        100% {
            opacity: 1;
            transform: scale(1) rotateY(0deg);
        }
    }
    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
    }
    .ticket-container {
        animation: ticketAppear 0.8s ease-out;
    }
    .success-icon {
        font-size: 5rem;
        color: #48bb78;
        margin-bottom: 1rem;
        animation: pulse 2s ease-in-out infinite;
    }
    .ticket-numero {
        background: linear-gradient(135deg, var(--grad-start) 0%, var(--grad-end) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-size: 8rem;
        font-weight: 900;
        line-height: 1;
        margin: 2rem 0;
        text-shadow: 0 4px 8px rgba(0,0,0,0.1);
        letter-spacing: 0.1em;
    }
    .ticket-card {
        background: white;
        border-radius: 30px;
        padding: 3rem;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        border: 5px solid #e2e8f0;
        margin-bottom: 2rem;
    }
    .departamento-badge {
        display: inline-block;
        background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
        color: white;
        padding: 1rem 2rem;
        border-radius: 50px;
        font-weight: 700;
        font-size: 1.5rem;
        margin: 1rem 0;
        box-shadow: 0 4px 15px rgba(72, 187, 120, 0.4);
    }
    .instruction-box {
        background: linear-gradient(135deg, #fef5e7 0%, #fdeaa3 100%);
        border-radius: 20px;
        padding: 2rem;
        margin: 2rem 0;
        border: 3px dashed #f59e0b;
    }
    .instruction-text {
        color: #92400e;
        font-size: 1.3rem;
        font-weight: 600;
        margin: 0;
    }
    .btn-nuevo-turno {
        background: linear-gradient(135deg, var(--grad-start) 0%, var(--grad-end) 100%);
        color: white;
        border: none;
        padding: 1.2rem 3rem;
        border-radius: 50px;
        font-size: 1.2rem;
        font-weight: 700;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25);
    }
    .btn-nuevo-turno:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 25px rgba(0, 0, 0, 0.35);
        color: white;
    }
    .decorative-line {
        height: 3px;
        background: linear-gradient(90deg, transparent, #667eea, transparent);
        margin: 2rem 0;
    }
</style>

<div class="ticket-container text-center">
    <div class="success-icon">
        <i class="bi bi-check-circle-fill"></i>
    </div>
    
    <h1 class="kiosco-title">
        <i class="bi bi-ticket-perforated"></i> ¡Turno Generado Exitosamente!
    </h1>
    
    <div class="ticket-card">
        <p class="text-muted mb-2" style="font-size: 1.2rem; font-weight: 600;">
            Su número de turno es:
        </p>
        
        <div class="ticket-numero">
            {{ $turno->numero }}
        </div>
        
        <div class="decorative-line"></div>
        
        <div class="departamento-badge">
            <i class="bi bi-building"></i> {{ $turno->departamento->nombre }}
        </div>
        
        <div class="instruction-box">
            <p class="instruction-text">
                <i class="bi bi-info-circle-fill"></i> Por favor espere a ser llamado<br>
                <small style="font-size: 0.9rem; color: #b45309;">
                    <i class="bi bi-tv"></i> Observe la pantalla de turnos
                </small>
            </p>
        </div>
        
        <div style="color: #718096; font-size: 0.95rem; margin-top: 1.5rem;">
            <i class="bi bi-clock"></i> {{ now()->format('d/m/Y H:i:s') }}
        </div>
    </div>
    
    <a href="{{ isset($pais) && strtoupper($pais) === 'CR' ? route('kiosco.cr') : route('kiosco.index') }}" 
       class="btn btn-nuevo-turno">
        <i class="bi bi-arrow-repeat"></i> Generar Otro Turno
    </a>
</div>
@endsection