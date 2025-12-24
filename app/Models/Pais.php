<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pais extends Model
{
    protected $table = 'paises';
    protected $primaryKey = 'id_pais';
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'codigo_iso',
    ];

    public function sociedades() {
        return $this->hasMany(Sociedad::class, 'id_pais');
    }
}
