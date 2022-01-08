<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Detalle extends Model
{
    protected $primaryKey = 'detalleid';
    protected $fillable= [
        'facturaid',
        'productoid',
        'detalle_cantidad',
        'detalle_monto'
    ];

    public $timestamps = false;

    public function factura()
    {
        return $this->belongsTo('App\Factura');
    }

}
