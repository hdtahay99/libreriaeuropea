<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    //
    protected $primaryKey = 'categoriaid';
    protected $fillable = ['categoria_nombre','categoria_estado'];

    public function productos()
    {
        return $this->hasMany('App\Producto');
    }
}
