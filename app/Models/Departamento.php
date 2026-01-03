<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $table = 'departamentos';
    protected $primaryKey = 'id_departamento';
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'descripcion',
        'atiende_preferencial',
    ];

    public function turnos() {
        return $this->hasMany(Turno::class, 'id_departamento');
    }

    public function ventanillas() {
        return $this->hasMany(Ventanilla::class, 'id_departamento');
    }

    public function sucursal(){
        return $this->belongsTo(Sucursal::class, 'id_sucursal');
    }
}
