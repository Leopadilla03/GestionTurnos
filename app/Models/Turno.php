<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turno extends Model
{
    use HasFactory;

    protected $table = 'turnos';
    protected $primaryKey = 'id_turno';

    protected $fillable = [
        'numero',
        'tipo',
        'estado',
        'origen',
        'id_cliente',
        'id_departamento',
        'id_sucursal',
        'id_ventanilla',
        'id_usuario',
        'hora_creacion',
        'hora_inicio_atencion',
        'hora_fin_atencion',
    ];

    public $timestamps = false;

    // Relaciones
    public function cliente() {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }

    public function departamento() {
        return $this->belongsTo(Departamento::class, 'id_departamento');
    }

    public function ventanilla() {
        return $this->belongsTo(Ventanilla::class, 'id_ventanilla');
    }

    public function usuario() {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }
}