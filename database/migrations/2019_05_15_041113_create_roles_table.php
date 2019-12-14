<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('rolid');
            $table->string('rol_nombre', 20);
            $table->string('rol_descripcion', 30);
            $table->boolean('rol_estado')->default(1);
        });
        DB::table('roles')->insert(array('rolid'=>'1', 'rol_nombre'=>'Administrador', 'rol_descripcion' => 'Encargados total del sistema.'));
        DB::table('roles')->insert(array('rolid'=>'2', 'rol_nombre'=>'Vendedor', 'rol_descripcion' => 'Encargado del área de venta.'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
