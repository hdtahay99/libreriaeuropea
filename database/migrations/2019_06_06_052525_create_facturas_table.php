<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->increments('facturaid');
            
            $table->integer('empleadoid')->unsigned();
            $table->foreign('empleadoid')->references('empleadoid')->on('empleados');

            $table->integer('clienteid')->unsigned();
            $table->foreign('clienteid')->references('clienteid')->on('clientes');

            $table->dateTime('factura_fecha');
            $table->decimal('factura_total',9,2);
            $table->decimal('factura_pago',9,2)->nullable();
            $table->string('factura_estado',25)->nullable();
            $table->boolean('condicion')->default(1);
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
        Schema::dropIfExists('facturas');
    }
}
