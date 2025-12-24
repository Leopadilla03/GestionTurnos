<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartamentosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('departamentos')->insert([
            [
                'id_departamento' => 1,
                'nombre' => 'Cajas',
                'descripcion' => 'Pagos, abonos y cobros',
                'atiende_preferencial' => 1,
            ],
            [
                'id_departamento' => 2,
                'nombre' => 'Créditos',
                'descripcion' => 'Gestión de préstamos y financiamiento vehicular',
                'atiende_preferencial' => 1,
            ],
            [
                'id_departamento' => 3,
                'nombre' => 'Atención al Cliente',
                'descripcion' => 'Consultas, soporte e información',
                'atiende_preferencial' => 1,
            ],
            [
                'id_departamento' => 4,
                'nombre' => 'Servicio Técnico',
                'descripcion' => 'Mantenimientos y garantías',
                'atiende_preferencial' => 1,
            ]
        ]);
    }
}
