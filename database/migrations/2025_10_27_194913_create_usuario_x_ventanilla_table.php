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
        Schema::create('usuario_x_ventanilla', function (Blueprint $table) {
            // No id autoincrement; usamos clave primaria compuesta para reflejar el SQL original
            $table->foreignId('id_usuario')
                ->constrained('usuarios', 'id_usuario')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreignId('id_ventanilla')
                ->constrained('ventanillas', 'id_ventanilla')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->timestamp('hora_inicio')->useCurrent();
            $table->timestamp('hora_fin')->nullable();
            $table->enum('estado', ['abierta', 'cerrada'])->default('abierta');

            $table->primary(['id_usuario', 'id_ventanilla']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario_x_ventanilla');
    }
};
