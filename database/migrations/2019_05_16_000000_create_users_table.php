<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->integer('userid')->unsigned();
            $table->foreign('userid')->references('empleadoid')->on('empleados')->onDelete('cascade');

            $table->string('usuario')->unique();
            $table->string('password');
            $table->boolean('estado')->default(1);

            $table->integer('rolid')->unsigned();
            $table->foreign('rolid')->references('rolid')->on('roles');

            $table->rememberToken();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
