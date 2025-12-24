<?php

namespace Database\Seeders;

use App\Models\Turno;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       $this->call([
            PaisesSeeder::class,
            SociedadSeeder::class,
            SucursalSeeder::class,
            DepartamentosSeeder::class,
            UsuariosSeeder::class,
            VentanillasSeeder::class,
            ClientesSeeder::class,
            TurnosSeeder::class,
            UsuarioVentanillaSeeder::class,
        ]);
    }   
}