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
        Schema::create('pantallas', function (Blueprint $table) {
            $table->id('id_pantalla');
            $table->enum('tipo', ['principal', 'secundaria', 'anuncio'])->default('principal');
            $table->string('url_stream', 255)->nullable();
            $table->enum('estado', ['activa', 'inactiva'])->default('activa');
            $table->timestamps();

            $table->foreignId('id_sucursal')
                ->constrained('sucursal', 'id_sucursal')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pantallas');
    }
};
