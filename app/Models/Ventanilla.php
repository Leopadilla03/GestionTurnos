<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ventanilla extends Model
{
    protected $table = 'ventanillas';
    protected $primaryKey = 'id_ventanilla';
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'estado',
        'id_sucursal',
        'id_departamento',
    ];

    public function sucursal() {
        return $this->belongsTo(Sucursal::class, 'id_sucursal');
    }

    public function departamento() {
        return $this->belongsTo(Departamento::class, 'id_departamento');
    }

    public function turnos() {
        return $this->hasMany(Turno::class, 'id_ventanilla');
    }

    public function usuarios()
    {
        return $this->belongsToMany(
            Usuario::class,
            'usuario_x_ventanilla',
            'id_ventanilla',
            'id_usuario'
        );
    }

    public function usuarioAsignado()
    {
        return $this->hasOne(UsuarioXVentanilla::class, 'id_ventanilla');
    }
}
