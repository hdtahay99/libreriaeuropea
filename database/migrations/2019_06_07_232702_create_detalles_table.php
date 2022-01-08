<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalles', function (Blueprint $table) {
            $table->increments('detalleid');

            
            $table->integer('facturaid')->unsigned();
            $table->foreign('facturaid')->references('facturaid')->on('facturas')->onDelete('cascade');

            $table->integer('productoid')->unsigned();
            $table->foreign('productoid')->references('productoid')->on('productos');

            $table->integer('detalle_cantidad');
            $table->decimal('detalle_monto',9,2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalles');
    }
}
