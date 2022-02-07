<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompraCredito extends Model
{
    //
    protected $primaryKey = 'id';
    protected $fillable= [
        'codigo_detalle_compra',
        'compraid',
        'empleadoid',
        'credito_abono',
        'credito_saldo',
        'credito_fecha',
        'estado'
    ];

    public function compra()
    {
        return $this->belongsTo('App\Compra');
    }
}
