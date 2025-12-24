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
        Schema::create('sociedad', function (Blueprint $table) {
            $table->id('id_sociedad');
            $table->string('nombre', 150);
            $table->string('direccion', 255)->nullable();
            $table->timestamps();

            $table->foreignId('id_pais')
                ->constrained('paises', 'id_pais')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sociedad');
    }
};
