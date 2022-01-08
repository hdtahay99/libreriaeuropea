<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'rolid';
    protected $fillable = ['rol_nombre', 'rol_descripcion','rol_estado'];
    public $timestamps = false;

    public function users(){
        return $this->hasMany('App\User');
    }
}
