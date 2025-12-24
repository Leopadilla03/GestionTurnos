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
        Schema::table('sucursal', function (Blueprint $table) {
            $table->unsignedBigInteger('id_pais')->nullable()->after('nombre');

            $table->foreign('id_pais')
                ->references('id_pais')
                ->on('paises')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sucursal', function (Blueprint $table) {
            $table->dropForeign(['id_pais']);
            $table->dropColumn('id_pais');
        });
    }
};
