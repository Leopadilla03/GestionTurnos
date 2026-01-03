<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UsuarioVentanillaSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('usuario_x_ventanilla')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
/*
        $map = [
            'juanperez_caja1@crediq.com'     => 'Caja 1 - TGU',
            'anarodriguez_caja2@crediq.com'      => 'Caja 2 - TGU',
            'josuemejia_caja3@crediq.com'    => 'Caja 3 - TGU',
            'marialopez_creditos1@crediq.com' => 'Créditos 1 - TGU',
            'luishernandez_creditos2@crediq.com' => 'Créditos 2 - TGU',
            'paolacastillo_creditos3@crediq.com' => 'Créditos 3 - TGU',
            'carlosmartinez_atencion1@crediq.com' => 'Atención al cliente 1 - TGU',
            'sofiamorales_atencion2@crediq.com' => 'Atención al cliente 2 - TGU',
            'danielreyes_atencion3@crediq.com' => 'Atención al cliente 3 - TGU',
            'alexrodriguez_servicio1@crediq.com' => 'Servicio Técnico 1 - TGU',
            'kevinflores_servicio2@crediq.com' => 'Servicio Técnico 2 - TGU',
            'jorgepineda_servicio3@crediq.com' => 'Servicio Técnico 3 - TGU',
        ];

        foreach ($map as $email => $ventanillaNombre) {

            $usuario = DB::table('usuarios')->where('email', $email)->first();
            $ventanilla = DB::table('ventanillas')->where('nombre', $ventanillaNombre)->first();

            if ($usuario && $ventanilla) {
                DB::table('usuario_x_ventanilla')->insert([
                    'id_usuario' => $usuario->id_usuario,
                    'id_ventanilla' => $ventanilla->id_ventanilla,
                    'hora_inicio' => Carbon::now(),
                    'estado' => 'abierta',
                ]);
            }
        }
    */
    }
}
