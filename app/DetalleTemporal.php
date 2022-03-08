<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleTemporal extends Model
{
    //
    protected $table = 'detalle_temporales';
    protected $primaryKey = 'detempid';
    protected $fillable   = ['facturaid', 'temporal_producto', 'temporal_monto', 'temporal_cantidad', 'estado'];

    public function factura()
    {
        return $this->belongsTo('App\Factura');
    }
}
