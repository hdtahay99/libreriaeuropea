<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compras', function (Blueprint $table) {
            $table->bigIncrements('compraid');
            $table->bigInteger('codigo_compra');

            $table->integer('empleadoid')->unsigned();
            $table->foreign('empleadoid')->references('empleadoid')->on('empleados');

            $table->bigInteger('proveedorid')->unsigned();
            $table->foreign('proveedorid')->references('proveedorid')->on('proveedores');

            $table->dateTime('compra_fecha');
            $table->decimal('compra_total',9,2);
            $table->integer('compra_estado')->nullable();
            $table->dateTime('fecha_credito_limite')->nullable();
            $table->boolean('estado')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('compras');
    }
}
