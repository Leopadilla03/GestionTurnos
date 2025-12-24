@extends('layouts.app')

@section('title', 'Historial de Turnos')

@section('content')
<div class="container mt-4">
    <h2 class="fw-bold text-center text-primary mb-4">
        Historial de Turnos – {{ $ventanilla->nombre }}
    </h2>

    @if($historial->count() == 0)
        <p class="text-center text-muted">No hay turnos finalizados.</p>
    @else
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Turno</th>
                            <th>Tipo</th>
                            <th>Caja</th>
                            <th>Cajero</th>
                            <th>Estado</th>
                            <th>Hora</th>
                    </thead>
                    <tbody>
                        @foreach($historial as $t)
                            <tr>
                            <td><strong>{{ $t->numero }}</strong></td>

                            <td>{{ ucfirst($t->tipo) }}</td>

                            {{-- Caja --}}
                            <td>
                                {{ $t->ventanilla->nombre ?? '—' }}
                            </td>

                            {{-- Cajero --}}
                            <td>
                                {{ optional($t->ventanilla->usuarios->first())->nombre ?? '—' }}
                            </td>

                            <td class="text-success fw-bold">
                                {{ ucfirst($t->estado) }}
                            </td>

                            <td>
                                {{ \Carbon\Carbon::parse($t->hora_fin_atencion)
                                    ->setTimezone(config('app.timezone'))
                                    ->format('d-m-Y h:i:s A') 
                                }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
