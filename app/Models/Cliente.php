<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';
    protected $primaryKey = 'id_cliente';
    public $timestamps = true;

    protected $fillable = [
        'documento',
        'tipo_preferencial',
    ];

    // Relaciones
    public function turnos() {
        return $this->hasMany(Turno::class, 'id_cliente');
    }
}
