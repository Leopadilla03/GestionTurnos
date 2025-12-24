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
        Schema::table('turnos', function (Blueprint $table) {
            // Añadir id_sucursal (nullable para turnos sin asignación aún)
            if (!Schema::hasColumn('turnos', 'id_sucursal')) {
                $table->foreignId('id_sucursal')
                    ->nullable()
                    ->after('id_ventanilla')
                    ->constrained('sucursal', 'id_sucursal')
                    ->onUpdate('cascade')
                    ->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('turnos', function (Blueprint $table) {
            $table->dropForeignKeyIfExists(['id_sucursal']);
            $table->dropColumn('id_sucursal');
        });
    }
};
