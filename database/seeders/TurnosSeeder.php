<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

class TurnosSeeder extends Seeder
{
    public function run()
    {
        
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('turnos')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        /*
        $totalNormal = 27;
        $totalPref = 13;

        $turnos = [];
        $counterN = 1;
        $counterP = 1;

        // Distribución: 20 Honduras (sucursales 1), 20 Costa Rica (sucursal 3)
        $sucursalHonduras = [1]; // Tegucigalpa=1,
        $sucursalCosta = [2]; // San José = 2

        // Generamos mezcla: primero llenamos 20 Honduras then 20 CostaR
        $target = [];

        // create array with 20 entries for Honduras (randomly assign sucursal among 1/2)
        for ($i=0; $i<20; $i++) {
            $target[] = [
                'id_sucursal' => $sucursalHonduras[array_rand($sucursalHonduras)],
            ];
        }
        // 20 for Costa Rica
        for ($i=0; $i<20; $i++) {
            $target[] = [
                'id_sucursal' => $sucursalCosta[0],
            ];
        }

        $types = array_merge(array_fill(0, $totalNormal, 'normal'), array_fill(0, $totalPref, 'preferencial'));
        shuffle($types);

        $departamentos = [1,2,3,4]; // tus departamentos

        for ($i=0; $i<40; $i++) {
            $tipo = $types[$i] ?? 'normal';
            $numero = ($tipo === 'normal' ? 'N-' . str_pad($counterN++, 3, '0', STR_PAD_LEFT) : 'P-' . str_pad($counterP++, 3, '0', STR_PAD_LEFT));
            $sucursal = $target[$i]['id_sucursal'];
            $turnos[] = [
                'numero' => $numero,
                'tipo' => $tipo,
                'estado' => 'espera',
                'id_cliente' => rand(1, 40), // ClientesSeeder crea 40 clientes (IDs 1-40)
                'id_departamento' => $departamentos[array_rand($departamentos)],
                'id_sucursal' => $sucursal,
                'hora_creacion' => Carbon::now()->subMinutes(rand(0, 600)),
            ];
        }

        DB::table('turnos')->insert($turnos);
        */
    }
}