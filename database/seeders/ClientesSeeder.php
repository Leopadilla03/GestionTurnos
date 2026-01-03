<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ClientesSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('clientes')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $clientes = [];

        // 🇭🇳 HONDURAS - 27 clientes normales
        for ($i = 1; $i <= 27; $i++) {
            $clientes[] = [
                'documento' => '0801' . str_pad($i, 9, '0', STR_PAD_LEFT),
                'tipo_preferencial' => 'normal',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        // 🇨🇷 COSTA RICA - 13 clientes preferenciales
        for ($i = 1; $i <= 13; $i++) {
            $clientes[] = [
                'documento' => 'CR' . str_pad($i, 11, '0', STR_PAD_LEFT),
                'tipo_preferencial' => 'preferencial',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        DB::table('clientes')->insert($clientes);
    }
}
