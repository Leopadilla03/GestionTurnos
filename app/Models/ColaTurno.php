<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ColaTurno extends Model
{
    protected $table = 'cola_turnos';
    protected $primaryKey = 'id_cola';
    public $timestamps = true;

    protected $fillable = [
        'prioridad',
        'tiempo_espera',
        'orden_asignado',
        'estado',
        'id_turno',
    ];

    public function turno() {
        return $this->belongsTo(Turno::class, 'id_turno');
    }
}
