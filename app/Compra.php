<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    //
    protected $primaryKey = 'compraid';
    protected $fillable = [
        'codigo_compra',
        'empleadoid',
        'proveedorid',
        'compra_fecha',
        'compra_total',
        'compra_estado',
        'fecha_credito_limite',
        'estado'
    ];

    public function empleado()
    {
        return $this->belongsTo('App\Empleado');
    }

    public function proveedor()
    {
        return $this->belongsTo('App\Proveedor');
    }

    public function detalleCompras()
    {
        return $this->hasMany('App\CompraDetalle');
    }

    public function creditos()
    {
        return $this->hasMany('App\CompraCredito');
    }
}
