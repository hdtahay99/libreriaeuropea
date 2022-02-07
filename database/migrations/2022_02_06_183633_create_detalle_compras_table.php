<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleComprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_compras', function (Blueprint $table) {
            $table->bigIncrements('detalleid');
            $table->string('codigo_detalle_compra');
            
            $table->bigInteger('compraid')->unsigned();
            $table->foreign('compraid')->references('compraid')->on('compras')->onDelete('cascade');

            $table->integer('productoid')->unsigned();
            $table->foreign('productoid')->references('productoid')->on('productos');

            $table->integer('detalle_cantidad');
            $table->decimal('detalle_monto',9,2);
            $table->integer('detalle_estado');

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
        Schema::dropIfExists('detalle_compras');
    }
}
