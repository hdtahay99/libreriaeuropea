<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProveedoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->bigIncrements('proveedorid');
            $table->string('proveedor_nombre',255);
            $table->string('proveedor_direccion',255);
            $table->string('proveedor_nit',15);
            $table->string('proveedor_telefono',13)->nullable();
            $table->string('proveedor_cuenta_deposito',12)->nullable();
            $table->boolean('proveedor_estado')->default(1);
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
        Schema::dropIfExists('proveedores');
    }
}
