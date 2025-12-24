<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    protected $table = 'sucursal';
    protected $primaryKey = 'id_sucursal';
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'id_sociedad',
    ];

    public function sociedad() {
        return $this->belongsTo(Sociedad::class, 'id_sociedad');
    }

    public function ventanillas() {
        return $this->hasMany(Ventanilla::class, 'id_sucursal');
    }

    public function pantallas() {
        return $this->hasMany(Pantalla::class, 'id_sucursal');
    }
}
