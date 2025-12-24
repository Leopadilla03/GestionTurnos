<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->unsignedBigInteger('id_sucursal')->nullable()->after('estado');
            $table->unsignedBigInteger('id_departamento')->nullable()->after('id_sucursal');

            $table->foreign('id_sucursal')->references('id_sucursal')->on('sucursal');
            $table->foreign('id_departamento')->references('id_departamento')->on('departamentos');
        });
    }

    public function down()
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropForeign(['id_sucursal']);
            $table->dropForeign(['id_departamento']);
            $table->dropColumn(['id_sucursal', 'id_departamento']);
        });
    }

};
