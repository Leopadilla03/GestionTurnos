<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SucursalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('sucursal')->insert([
            
            // Honduras
            [
                'nombre' => 'CREDI Q Tegucigalpa',
                'direccion' => 'Complejo automotriz Grupo Q Blvd, Centro América frente a C.C. Plaza Miraflores.',
                'telefono' => '2290-3747',
                'id_sociedad' => 1,
            ],

            // Costa Rica
            [
                'nombre' => 'CREDI Q San José',
                'direccion' => 'Costado este del edificio Isuzu, La Uruca., San José',
                'telefono' => '+506 2522-7474',
                'id_sociedad' => 2,
            ],
        ]);    
    }
}
