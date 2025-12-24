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
         Schema::create('ventanillas', function (Blueprint $table) {
            $table->id('id_ventanilla');
            $table->string('nombre', 100);
            $table->enum('estado', ['activa', 'inactiva'])->default('inactiva');
            $table->timestamps();

            // Relaciones
            $table->foreignId('id_sucursal')
                ->constrained('sucursal', 'id_sucursal')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreignId('id_departamento')
                ->nullable()
                ->constrained('departamentos', 'id_departamento')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventanillas');
    }
};
