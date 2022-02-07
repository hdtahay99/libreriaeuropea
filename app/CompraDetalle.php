<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompraDetalle extends Model
{
    //
    protected $primaryKey = 'detalleid';
    protected $fillable= [
        'codigo_detalle_compra',
        'compraid',
        'productoid',
        'detalle_cantidad',
        'detalle_monto',
        'detalle_estado',
        'estado'
    ];

    public function compra()
    {
        return $this->belongsTo('App\Compra');
    }
}
