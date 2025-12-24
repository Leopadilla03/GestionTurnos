<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsuarioXVentanilla extends Model
{
    protected $table = 'usuario_x_ventanilla';
    protected $primaryKey = 'id_asignacion';
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'id_ventanilla',
        'hora_inicio',
        'hora_fin',
        'estado',
    ];

    /**
     * Cast timestamps to Carbon instances so views can call ->format()
     */
    protected $casts = [
        'hora_inicio' => 'datetime',
        'hora_fin' => 'datetime',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    public function ventanilla()
    {
        return $this->belongsTo(Ventanilla::class, 'id_ventanilla');
    }
}
