<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pantalla extends Model
{
    protected $table = 'pantallas';
    protected $primaryKey = 'id_pantalla';
    public $timestamps = true;

    protected $fillable = [
        'tipo',
        'url_stream',
        'estado',
        'id_sucursal',
    ];

    public function sucursal() {
        return $this->belongsTo(Sucursal::class, 'id_sucursal');
    }
}
