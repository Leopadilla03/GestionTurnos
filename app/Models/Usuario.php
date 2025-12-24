<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios'; // nombre de la tabla real
    protected $primaryKey = 'id_usuario';

    protected $fillable = [
        'nombre',
        'email',
        'password',
        'rol',
        'estado',
        'id_pais',
        'id_sucursal',
        'id_departamento',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function pais()
    {
        return match($this->id_sucursal) {
            1 => 'Honduras',
            2 => 'Costa Rica',
            default => 'Desconocido'
        };
    }

    public function sucursal() {
        return $this->belongsTo(Sucursal::class, 'id_sucursal');
    }

    public function departamento() {
        return $this->belongsTo(Departamento::class, 'id_departamento');
    }

    public function ventanillas()
    {
        return $this->hasMany(UsuarioXVentanilla::class, 'id_usuario')
            ->where('estado', 'abierta');
    }

    public function ventanillaActiva()
    {
        return $this->hasOne(UsuarioXVentanilla::class, 'id_usuario')
            ->where('estado', 'abierta')
            ->latest('hora_inicio');
    }

    public function asignacionActual()
    {
        return $this->hasOne(UsuarioXVentanilla::class, 'id_usuario')
            ->where('estado', 'abierta')
            ->latest('hora_inicio');
    }
}
