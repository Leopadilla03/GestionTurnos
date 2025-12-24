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
                'id_usuario' => 1,
                'nombre' => 'Administrador CREDI Q Honduras',
                'email' => 'admin_hn@crediq.com',
                'password' => Hash::make('admin123'),
                'rol' => 'administrador',
                'estado' => 'activo',
                'id_sucursal' => 1,
                'id_departamento' => null,
                'id_pais' => 1, // Honduras
            ],
                [
                    'id_usuario' => 2,
                    'nombre' => 'Operador Tegucigalpa Cajas',
                    'email' => 'operador_tgu_cajas@crediq.com',
                    'password' => Hash::make('cajas123'),
                    'rol' => 'operador',
                    'estado' => 'activo',
                    'id_sucursal' => 1, // Tegucigalpa
                    'id_departamento' => 1, // Cajas
                ],
                [
                    'id_usuario' => 3,
                    'nombre' => 'Operador Tegucigalpa Créditos',
                    'email' => 'operador_tgu_creditos@crediq.com',
                    'password' => Hash::make('creditos123'),
                    'rol' => 'operador',
                    'estado' => 'activo',
                    'id_sucursal' => 1, // Tegucigalpa
                    'id_departamento' => 2, // Creditos
                ],
                [
                    'id_usuario' => 4,
                    'nombre' => 'Operador Tegucigalpa Atención al Cliente',
                    'email' => 'operador_tgu_atencion@crediq.com',
                    'password' => Hash::make('atencion123'),
                    'rol' => 'operador',
                    'estado' => 'activo',
                    'id_sucursal' => 1, // Tegucigalpa
                    'id_departamento' => 3, // Atención al Cliente
                ],
                [
                    'id_usuario' => 5,
                    'nombre' => 'Operador Tegucigalpa Servicio Técnico',
                    'email' => 'operador_tgu_mservicio@crediq.com',
                    'password' => Hash::make('servicio123'),
                    'rol' => 'operador',
                    'estado' => 'activo',
                    'id_sucursal' => 1, // Tegucigalpa
                    'id_departamento' => 4, // Mantenimiento y Garantías
                ],

            // Costa Rica - San José
            [
                'id_usuario' => 6,
                'nombre' => 'Administrador CREDI Q Costa Rica',
                'email' => 'admin_cr@crediq.com',
                'password' => Hash::make('admin456'),
                'rol' => 'administrador',
                'estado' => 'activo',
                'id_sucursal' => 2,
                'id_departamento' => null,
                'id_pais' => 2, // Costa Rica
            ],
                [
                    'id_usuario' => 7,
                    'nombre' => 'Operador San José Cajas',
                    'email' => 'operador_sj_cajas@crediq.com',
                    'password' => Hash::make('cajas456'),
                    'rol' => 'operador',
                    'estado' => 'activo',
                    'id_sucursal' => 2, // San José
                    'id_departamento' => 1, // Cajas
                ],
                [
                    'id_usuario' => 8,
                    'nombre' => 'Operador San José Créditos',
                    'email' => 'operador_sj_creditos@crediq.com',
                    'password' => Hash::make('creditos456'),
                    'rol' => 'operador',
                    'estado' => 'activo',
                    'id_sucursal' => 2, // San José
                    'id_departamento' => 2, // Creditos
                ],
                [
                    'id_usuario' => 9,
                    'nombre' => 'Operador San José Atención al Cliente',
                    'email' => 'operador_sj_atencion@crediq.com',
                    'password' => Hash::make('atencion456'),
                    'rol' => 'operador',
                    'estado' => 'activo',
                    'id_sucursal' => 2, // San José
                    'id_departamento' => 3, // Atención al Cliente
                ],
                [
                    'id_usuario' => 10,
                    'nombre' => 'Operador San José Servicio Técnico',
                    'email' => 'operador_sj_servicio@crediq.com',
                    'password' => Hash::make('servicio456'),
                    'rol' => 'operador',
                    'estado' => 'activo',
                    'id_sucursal' => 2, // San José
                    'id_departamento' => 4, // Mantenimiento y Garantías
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
