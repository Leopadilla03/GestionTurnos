<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class PaisesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
       DB::table('paises')->updateOrInsert(
            ['codigo_iso' => 'HN'],
            ['nombre' => 'Honduras']
        );

        DB::table('paises')->updateOrInsert(
            ['codigo_iso' => 'CR'],
            ['nombre' => 'Costa Rica']
        );
    }
}
