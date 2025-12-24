<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('id_usuario');
            $table->string('nombre', 100);
            $table->string('email', 150)->unique();
            $table->string('password', 255);
            $table->enum('rol', ['administrador', 'operador'])->default('operador');
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            // Agregar campo id_pais que apunte a tabla paises
            $table->unsignedBigInteger('id_pais')->nullable();
            $table->timestamps();
            
            
            $table->foreign('id_pais')
                ->references('id_pais')
                ->on('paises')
                ->onDelete('set null');
                
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
        $table->dropForeign(['id_pais']);
        $table->dropColumn('id_pais');
        });

    }
};
