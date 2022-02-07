<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditoComprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credito_compras', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->bigInteger('compraid')->unsigned();
            $table->foreign('compraid')->references('compraid')->on('compras');

            $table->integer('empleadoid')->unsigned();
            $table->foreign('empleadoid')->references('empleadoid')->on('empleados');

            $table->decimal('credito_abono',9,2);
            $table->decimal('credito_saldo',9,2);
            $table->dateTime('credito_fecha');
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
        Schema::dropIfExists('credito_compras');
    }
}
