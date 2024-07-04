<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CatalogoEncuestas extends Model
{
    protected $fillable = ['nombre','descripcion','id_tipo_cliente'];

    public function tipoCliente(){
        return $this->hasOne('App\TiposClientes','id','id_tipo_cliente');
    }
    public function preguntas(){
        return $this->hasMany('App\CatalogoEncuestasPreguntas','id','id_tipo_cliente');
    }
}
