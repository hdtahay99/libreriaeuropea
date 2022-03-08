<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleTemporalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_temporales', function (Blueprint $table) {
            $table->bigIncrements('detempid');
    
            $table->integer('facturaid')->unsigned();
            $table->foreign('facturaid')->references('facturaid')->on('facturas')->onDelete('cascade');

            $table->string('temporal_producto');
            $table->decimal('temporal_monto',9,2);
            $table->integer('temporal_cantidad');

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
        Schema::dropIfExists('detalle_temporal');
    }
}
