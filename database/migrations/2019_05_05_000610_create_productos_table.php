<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->increments('productoid');
            $table->integer('categoriaid')->unsigned();
            $table->string('producto_nombre',100);
            $table->integer('producto_existencia');
            $table->bigInteger('producto_barra')->nullable();
            $table->decimal('producto_pcompra',9,2);
            $table->decimal('producto_pventa',9,2);
            $table->string('producto_imagen')->nullable();
            $table->boolean('producto_estado')->default(1);
            $table->timestamps();

            $table->foreign('categoriaid')->references('categoriaid')->on('categorias');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productos');
    }
}
