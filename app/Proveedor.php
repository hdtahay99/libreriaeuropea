<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    //
    protected $primaryKey = 'proveedorid';
    protected $fillable = ['proveedor_nombre', 'proveedor_direccion', 'proveedor_nit', 'proveedor_telefono', 'proveedor_cuenta_deposito', 'proveedor_estado'];
}
