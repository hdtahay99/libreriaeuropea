<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    //
    protected $primaryKey = 'empleadoid';
    protected $fillable = ['empleadoid', 'empleado_nombre', 'empleado_apellido', 'empleado_telefono', 'empleado_direccion', 'empleado_estado'];

    public function users(){
        return $this->hasMany('App\User');
    }
}
