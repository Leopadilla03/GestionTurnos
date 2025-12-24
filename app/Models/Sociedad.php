<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sociedad extends Model
{
    protected $table = 'sociedad';
    protected $primaryKey = 'id_sociedad';
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'direccion',
        'id_pais',
    ];

    public function pais() {
        return $this->belongsTo(Pais::class, 'id_pais');
    }

    public function sucursales() {
        return $this->hasMany(Sucursal::class, 'id_sociedad');
    }
}
