<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    //
    protected $primaryKey = 'productoid';
    protected $fillable = ['categoriaid','producto_nombre','producto_existencia','producto_barra','producto_pcompra','producto_pventa','producto_imagen','producto_estado'];

    public function categoria()
    {
        return $this->belongsTo('App\Categoria');
    }
}
