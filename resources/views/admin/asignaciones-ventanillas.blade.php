@extends('layouts.app')

@section('content')

<div class="container py-4">
    <h2 class="fw-bold text-primary">
        <i class="bi bi-diagram-3"></i> Asignación de Operadores a Ventanillas
    </h2>

    @if(session('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif

    <div class="card shadow mt-4">
        <div class="card-body">

            <table class="table table-bordered text-center">
                <thead class="table-light">
                    <tr>
                        <th>Operador</th>
                        <th>Sucursal</th>
                        <th>Asignación Actual</th>
                        <th>Asignar Nueva</th>
                        <th>Acción</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($operadores as $op)
                    @php
                        $asign = $asignaciones->firstWhere('id_usuario', $op->id_usuario);
                    @endphp

                    <tr>
                        <td class="fw-bold">{{ $op->nombre }}</td>
                        <td>{{ $op->sucursal->nombre ?? '-' }}</td>

                        <td>
                            @if($asign)
                                <span class="badge bg-success">
                                    {{ $asign->ventanilla->nombre }}
                                    ({{ $asign->ventanilla->sucursal->nombre }})
                                </span>
                            @else
                                <span class="badge bg-secondary">Sin asignación</span>
                            @endif
                        </td>

                        <td>
                            <form action="{{ route('admin.asignaciones.asignar') }}" method="POST">
                                @csrf

                                <input type="hidden" name="id_usuario" value="{{ $op->id_usuario }}">

                                <select name="id_ventanilla" class="form-select" required>
                                    <option value="">Seleccionar ventanilla</option>

                                    @foreach($ventanillas as $v)
                                        @if($v->id_sucursal == $op->id_sucursal)
                                            <option value="{{ $v->id_ventanilla }}">
                                                {{ $v->nombre }} - {{ $v->departamento->nombre }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>

                                <button class="btn btn-primary btn-sm mt-2">Asignar</button>
                            </form>
                        </td>

                        <td>
                            @if($asign)
                                <form action="{{ route('admin.asignaciones.cerrar') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id_usuario" value="{{ $op->id_usuario }}">
                                    <button class="btn btn-danger btn-sm">Cerrar</button>
                                </form>
                            @else
                                -
                            @endif
                        </td>
                    </tr>

                @endforeach
                </tbody>

            </table>
        </div>
    </div>
</div>
@endsection