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
        Schema::create('turnos', function (Blueprint $table) {
            $table->id('id_turno');
            $table->string('numero', 10);
            $table->enum('tipo', ['normal', 'preferencial'])->default('normal');
            $table->enum('estado', ['espera','asignado','atendiendo','pausado','finalizado'])->default('espera');
            $table->timestamp('hora_creacion')->useCurrent();
            $table->timestamp('hora_inicio_atencion')->nullable();
            $table->timestamp('hora_fin_atencion')->nullable();
            $table->timestamps();

            // Relaciones
            $table->foreignId('id_cliente')
                ->constrained('clientes', 'id_cliente')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreignId('id_departamento')
                ->constrained('departamentos', 'id_departamento')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreignId('id_ventanilla')
                ->nullable()
                ->constrained('ventanillas', 'id_ventanilla')
                ->onUpdate('cascade')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('turnos');
    }
};
