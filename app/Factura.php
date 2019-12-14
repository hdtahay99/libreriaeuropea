<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $primaryKey = 'facturaid';
    protected $fillable = [
        'empleadoid',
        'clienteid',
        'factura_fecha',
        'factura_total',
        'factura_pago',
        'factura_estado',
        'condicion'
    ];

    public function empleado()
    {
        return $this->belongsTo('App\Empleado');
    }

    public function cliente()
    {
        return $this->belongsTo('App\Cliente');
    }

    public function detalles()
    {
        return $this->hasMany('App\Detalle');
    }
}
