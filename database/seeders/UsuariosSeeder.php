<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuariosSeeder extends Seeder
{
    public function run(): void
    {
        $usuarios = [

            // Honduras - Tegucigalpa
            [
                //Administrador CREDI Q Honduras
                'nombre' => 'Leonardo Padilla',
                'email' => 'leopadilla_hn@crediq.com',
                'password' => Hash::make('admin123'),
                'rol' => 'administrador',
                'estado' => 'activo',
                'id_sucursal' => 1,
                'id_departamento' => null,
                'id_pais' => 1, // Honduras
            ],
                // =========================
                // CAJAS – TGU
                // =========================
                [
                    'nombre' => 'Juan Pérez',
                    'email' => 'juanperez_hn@crediq.com',
                    'password' => Hash::make('crediq123'),
                    'rol' => 'operador',
                    'estado' => 'activo',
                    'id_sucursal' => 1,
                    'id_departamento' => 1, // Cajas
                ],
                [
                    'nombre' => 'Ana Rodríguez',
                    'email' => 'anarodriguez_hn@crediq.com',
                    'password' => Hash::make('crediq123'),
                    'rol' => 'operador',
                    'estado' => 'activo',
                    'id_sucursal' => 1,
                    'id_departamento' => 1,
                ],
                [
                    'nombre' => 'Josue Mejía',
                    'email' => 'josuemejia_hn@crediq.com',
                    'password' => Hash::make('crediq123'),
                    'rol' => 'operador',
                    'estado' => 'activo',
                    'id_sucursal' => 1,
                    'id_departamento' => 1,
                ],

                // =========================
                // CRÉDITOS – TGU
                // =========================
                [
                    'nombre' => 'María López',
                    'email' => 'marialopez_hn@crediq.com',
                    'password' => Hash::make('crediq123'),
                    'rol' => 'operador',
                    'estado' => 'activo',
                    'id_sucursal' => 1,
                    'id_departamento' => 2,
                ],
                [
                    'nombre' => 'Luis Hernández',
                    'email' => 'luishernandez_hn@crediq.com',
                    'password' => Hash::make('crediq123'),
                    'rol' => 'operador',
                    'estado' => 'activo',
                    'id_sucursal' => 1,
                    'id_departamento' => 2,
                ],
                [
                    'nombre' => 'Paola Castillo',
                    'email' => 'paolacastillo_hn@crediq.com',
                    'password' => Hash::make('crediq123'),
                    'rol' => 'operador',
                    'estado' => 'activo',
                    'id_sucursal' => 1,
                    'id_departamento' => 2,
                ],

                // =========================
                // ATENCIÓN AL CLIENTE – TGU
                // =========================
                [
                    'nombre' => 'Carlos Martínez',
                    'email' => 'carlosmartinez_hn@crediq.com',
                    'password' => Hash::make('crediq123'),
                    'rol' => 'operador',
                    'estado' => 'activo',
                    'id_sucursal' => 1,
                    'id_departamento' => 3,
                ],
                [
                    'nombre' => 'Sofía Morales',
                    'email' => 'sofiamorales_hn@crediq.com',
                    'password' => Hash::make('crediq123'),
                    'rol' => 'operador',
                    'estado' => 'activo',
                    'id_sucursal' => 1,
                    'id_departamento' => 3,
                ],
                [
                    'nombre' => 'Daniel Reyes',
                    'email' => 'danielreyes_hn@crediq.com',
                    'password' => Hash::make('crediq123'),
                    'rol' => 'operador',
                    'estado' => 'activo',
                    'id_sucursal' => 1,
                    'id_departamento' => 3,
                ],

                // =========================
                // SERVICIO TÉCNICO – TGU
                // =========================
                [
                    'nombre' => 'Alex Rodríguez',
                    'email' => 'alexrodriguez_servicio_hn@crediq.com',
                    'password' => Hash::make('servicio123'),
                    'rol' => 'operador',
                    'estado' => 'activo',
                    'id_sucursal' => 1,
                    'id_departamento' => 4,
                ],
                [
                    'nombre' => 'Kevin Flores',
                    'email' => 'kevinflores_servicio_hn@crediq.com',
                    'password' => Hash::make('servicio123'),
                    'rol' => 'operador',
                    'estado' => 'activo',
                    'id_sucursal' => 1,
                    'id_departamento' => 4,
                ],
                [
                    'nombre' => 'Jorge Pineda',
                    'email' => 'jorgepineda_servicio_hn@crediq.com',
                    'password' => Hash::make('servicio123'),
                    'rol' => 'operador',
                    'estado' => 'activo',
                    'id_sucursal' => 1,
                    'id_departamento' => 4,
                ],

                
            // Costa Rica - San José
            [
                //Administrador CREDI Q Costa Rica
                'nombre' => 'Jose Mario Arce',
                'email' => 'jmarce_cr@crediq.com',
                'password' => Hash::make('admin456'),
                'rol' => 'administrador',
                'estado' => 'activo',
                'id_sucursal' => 2,
                'id_departamento' => null,
                'id_pais' => 2, // Costa Rica
            ],
            
                // =========================
                // CAJAS – SJ
                // =========================
                [
                    'nombre' => 'José Ramírez',
                    'email' => 'jramirez_cr@crediq.com',
                    'password' => Hash::make('crediq456'),
                    'rol' => 'operador',
                    'estado' => 'activo',
                    'id_sucursal' => 2,
                    'id_departamento' => 1, // Cajas
                ],
                [
                    'nombre' => 'Daniela Vargas',
                    'email' => 'dvargas_cr@crediq.com',
                    'password' => Hash::make('crediq456'),
                    'rol' => 'operador',
                    'estado' => 'activo',
                    'id_sucursal' => 2,
                    'id_departamento' => 1,
                ],
                [
                    'nombre' => 'Esteban Mora',
                    'email' => 'emora_cr@crediq.com',
                    'password' => Hash::make('crediq456'),
                    'rol' => 'operador',
                    'estado' => 'activo',
                    'id_sucursal' => 2,
                    'id_departamento' => 1,
                ],

                // =========================
                // CRÉDITOS – SJ
                // =========================
                [
                    'nombre' => 'Valeria Chaves',
                    'email' => 'vchaves_cr@crediq.com',
                    'password' => Hash::make('crediq456'),
                    'rol' => 'operador',
                    'estado' => 'activo',
                    'id_sucursal' => 2,
                    'id_departamento' => 2,
                ],
                [
                    'nombre' => 'Mauricio Rojas',
                    'email' => 'mrojas_cr@crediq.com',
                    'password' => Hash::make('crediq456'),
                    'rol' => 'operador',
                    'estado' => 'activo',
                    'id_sucursal' => 2,
                    'id_departamento' => 2,
                ],
                [
                    'nombre' => 'Natalia Soto',
                    'email' => 'nsoto_cr@crediq.com',
                    'password' => Hash::make('crediq456'),
                    'rol' => 'operador',
                    'estado' => 'activo',
                    'id_sucursal' => 2,
                    'id_departamento' => 2,
                ],

                // =========================
                // ATENCIÓN AL CLIENTE – SJ
                // =========================
                [
                    'nombre' => 'Carlos Jiménez',
                    'email' => 'cjimenez_cr@crediq.com',
                    'password' => Hash::make('crediq456'),
                    'rol' => 'operador',
                    'estado' => 'activo',
                    'id_sucursal' => 2,
                    'id_departamento' => 3,
                ],
                [
                    'nombre' => 'Fernanda León',
                    'email' => 'fleon_cr@crediq.com',
                    'password' => Hash::make('crediq456'),
                    'rol' => 'operador',
                    'estado' => 'activo',
                    'id_sucursal' => 2,
                    'id_departamento' => 3,
                ],
                [
                    'nombre' => 'Ricardo Solís',
                    'email' => 'rsolis_cr@crediq.com',
                    'password' => Hash::make('crediq456'),
                    'rol' => 'operador',
                    'estado' => 'activo',
                    'id_sucursal' => 2,
                    'id_departamento' => 3,
                ],

                // =========================
                // SERVICIO TÉCNICO – SJ
                // =========================
                [
                    'nombre' => 'Pablo Navarro',
                    'email' => 'pnavarro_cr@crediq.com',
                    'password' => Hash::make('crediq456'),
                    'rol' => 'operador',
                    'estado' => 'activo',
                    'id_sucursal' => 2,
                    'id_departamento' => 4,
                ],
                [
                    'nombre' => 'Sergio Calderón',
                    'email' => 'scalderon_cr@crediq.com',
                    'password' => Hash::make('crediq456'),
                    'rol' => 'operador',
                    'estado' => 'activo',
                    'id_sucursal' => 2,
                    'id_departamento' => 4,
                ],
                [
                    'nombre' => 'Diego Herrera',
                    'email' => 'dherrera_cr@crediq.com',
                    'password' => Hash::make('crediq456'),
                    'rol' => 'operador',
                    'estado' => 'activo',
                    'id_sucursal' => 2,
                    'id_departamento' => 4,
                ],
        ];

        foreach ($usuarios as $u) {
            DB::table('usuarios')->updateOrInsert(
                ['email' => $u['email']],   // campo único
                $u                           // valores a actualizar
            );
        }
    }
}
