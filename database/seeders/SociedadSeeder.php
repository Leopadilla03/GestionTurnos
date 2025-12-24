<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SociedadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sociedad')->insert([
            [
                'nombre' => 'CREDI Q HN',
                'id_pais' => 1, // Honduras
            ],
            [
                'nombre' => 'CREDI Q CR',
                'id_pais' => 2, // Costa Rica
            ],
        ]);
    }
}
