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
        Schema::create('registros', function (Blueprint $table) {
            $table->id('id_registro');
            $table->string('accion', 50);
            $table->timestamp('fecha_hora')->useCurrent();
            $table->timestamps();

            $table->foreignId('id_turno')
                ->constrained('turnos', 'id_turno')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreignId('id_usuario')
                ->constrained('usuarios', 'id_usuario')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registros');
    }
};
