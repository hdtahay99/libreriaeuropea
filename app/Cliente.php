<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    //
    protected $primaryKey = 'clienteid';
    protected $fillable = ['clienteid', 'cliente_nombre', 'cliente_apellido', 'cliente_direccion', 'cliente_nit', 'cliente_estado'];

}
