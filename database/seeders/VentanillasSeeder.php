<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VentanillasSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('ventanillas')->insert([
            
            // HONDURAS - TGU
            [
                'nombre' => 'Caja 1 - TGU',
                'estado' => 'activa',
                'id_departamento' => 1,
                'id_sucursal' => 1,
            ],
            [
                'nombre' => 'Caja 2 - TGU',
                'estado' => 'activa',
                'id_departamento' => 1,
                'id_sucursal' => 1,
            ],
            [
                'nombre' => 'Caja 3 - TGU',
                'estado' => 'activa',
                'id_departamento' => 1,
                'id_sucursal' => 1,
            ],
            [
                'nombre' => 'Créditos 1 - TGU',
                'estado' => 'activa',
                'id_departamento' => 2,
                'id_sucursal' => 1,
            ],
            [
                'nombre' => 'Créditos 2 - TGU',
                'estado' => 'activa',
                'id_departamento' => 2,
                'id_sucursal' => 1,
            ],
            [
                'nombre' => 'Créditos 3 - TGU',
                'estado' => 'activa',
                'id_departamento' => 2,
                'id_sucursal' => 1,
            ],
            [
                'nombre' => 'Atención al cliente 1 - TGU',
                'estado' => 'activa',
                'id_departamento' => 3,
                'id_sucursal' => 1,
            ],
            [
                'nombre' => 'Atención al cliente 2 - TGU',
                'estado' => 'activa',
                'id_departamento' => 3,
                'id_sucursal' => 1,
            ],
            [
                'nombre' => 'Atención al cliente 3 - TGU',
                'estado' => 'activa',
                'id_departamento' => 3,
                'id_sucursal' => 1,
            ],
            [
                'nombre' => 'Servicio Técnico 1 - TGU',
                'estado' => 'activa',
                'id_departamento' => 4,
                'id_sucursal' => 1,
            ],
            [
                'nombre' => 'Servicio Técnico 2 - TGU',
                'estado' => 'activa',
                'id_departamento' => 4,
                'id_sucursal' => 1,
            ],
            [
                'nombre' => 'Servicio Técnico 3 - TGU',
                'estado' => 'activa',
                'id_departamento' => 4,
                'id_sucursal' => 1,
            ],


            // COSTA RICA - SJ
            [
                'nombre' => 'Caja 1 - SJ',
                'estado' => 'activa',
                'id_departamento' => 1,
                'id_sucursal' => 2,
            ],
            [
                'nombre' => 'Caja 2 - SJ',
                'estado' => 'activa',
                'id_departamento' => 1,
                'id_sucursal' => 2,
            ],
            [
                'nombre' => 'Caja 3 - SJ',
                'estado' => 'activa',
                'id_departamento' => 1,
                'id_sucursal' => 2,
            ],
            [
                'nombre' => 'Créditos 1 - SJ',
                'estado' => 'activa',
                'id_departamento' => 2,
                'id_sucursal' => 2,
            ],
            [
                'nombre' => 'Créditos 2 - SJ',
                'estado' => 'activa',
                'id_departamento' => 2,
                'id_sucursal' => 2,
            ],
            [
                'nombre' => 'Créditos 3 - SJ',
                'estado' => 'activa',
                'id_departamento' => 2,
                'id_sucursal' => 2,
            ],
            [
                'nombre' => 'Atención al cliente 1 - SJ',
                'estado' => 'activa',
                'id_departamento' => 3,
                'id_sucursal' => 2,
            ],
            [
                'nombre' => 'Atención al cliente 2 - SJ',
                'estado' => 'activa',
                'id_departamento' => 3,
                'id_sucursal' => 2,
            ],
            [
                'nombre' => 'Atención al cliente 3 - SJ',
                'estado' => 'activa',
                'id_departamento' => 3,
                'id_sucursal' => 2,
            ],
            [
                'nombre' => 'Servicio Técnico 1 - SJ',
                'estado' => 'activa',
                'id_departamento' => 4,
                'id_sucursal' => 2,
            ],
            [
                'nombre' => 'Servicio Técnico 2 - SJ',
                'estado' => 'activa',
                'id_departamento' => 4,
                'id_sucursal' => 2,
            ],
            [
                'nombre' => 'Servicio Técnico 3 - SJ',
                'estado' => 'activa',
                'id_departamento' => 4,
                'id_sucursal' => 2,
            ],
        ]);
    }
}
