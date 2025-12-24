<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turno extends Model
{
    use HasFactory;

    protected $table = 'turnos';
    protected $primaryKey = 'id_turno';
    public $timestamps = true;

    protected $fillable = [
        'numero',
        'tipo',
        'estado',
        'hora_creacion',
        'hora_inicio_atencion',
        'hora_fin_atencion',
        'id_cliente',
        'id_departamento',
        'id_ventanilla',
    ];

    public function cliente() {
        return $this->belongsTo(Cliente::class, 'id_cliente');
    }

    public function departamento() {
        return $this->belongsTo(Departamento::class, 'id_departamento');
    }

    public function ventanilla() {
        return $this->belongsTo(Ventanilla::class, 'id_ventanilla');
    }

    public function cola() {
        return $this->hasOne(ColaTurno::class, 'id_turno');
    }

    public function registros() {
        return $this->hasMany(Registro::class, 'id_turno');
    }
}
