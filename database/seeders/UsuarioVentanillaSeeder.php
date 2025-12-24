<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UsuarioVentanillaSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar tabla antes de insertar
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('usuario_x_ventanilla')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $asignaciones = [
            // HONDURAS - Tegucigalpa (ventanillas 1..4)
            [
                'id_usuario' => 2,
                'id_ventanilla' => 1, // Caja 1 - TGU (Cajas)
                'hora_inicio' => Carbon::now(),
                'estado' => 'abierta',
            ],
            [
                'id_usuario' => 3,
                'id_ventanilla' => 2, // Créditos 1 - TGU (Créditos)
                'hora_inicio' => Carbon::now(),
                'estado' => 'abierta',
            ],
            [
                'id_usuario' => 4,
                'id_ventanilla' => 3, // Atención al cliente 1 - TGU (Atención)
                'hora_inicio' => Carbon::now(),
                'estado' => 'abierta',
            ],
            [
                'id_usuario' => 5,
                'id_ventanilla' => 4, // Servicio Técnico 1 - TGU (Mantenimiento)
                'hora_inicio' => Carbon::now(),
                'estado' => 'abierta',
            ],

            // COSTA RICA - San José (ventanillas 5..8)
            [
                'id_usuario' => 7,
                'id_ventanilla' => 5, // Caja 2 - SJ
                'hora_inicio' => Carbon::now(),
                'estado' => 'abierta',
            ],
            [
                'id_usuario' => 8,
                'id_ventanilla' => 6, // Créditos 2 - SJ
                'hora_inicio' => Carbon::now(),
                'estado' => 'abierta',
            ],
            [
                'id_usuario' => 9,
                'id_ventanilla' => 7, // Atención al Cliente 2 - SJ
                'hora_inicio' => Carbon::now(),
                'estado' => 'abierta',
            ],
            [
                'id_usuario' => 10,
                'id_ventanilla' => 8, // Servicio Técnico 2 - SJ
                'hora_inicio' => Carbon::now(),
                'estado' => 'abierta',
            ],
        ];
        DB::table('usuario_x_ventanilla')->insert($asignaciones);
    }
}
