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
        Schema::create('cola_turnos', function (Blueprint $table) {
            $table->id('id_cola');
            $table->integer('prioridad')->default(3);
            $table->integer('tiempo_espera')->default(0);
            $table->integer('orden_asignado')->nullable();
            $table->enum('estado', ['en_cola', 'llamado', 'atendido'])->default('en_cola');
            $table->timestamps();

            // Relación con turnos
            $table->foreignId('id_turno')
                ->constrained('turnos', 'id_turno')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cola_turnos');
    }
};
