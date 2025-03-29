<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Proyectos extends Model
{
    protected $fillable = ['nombre','activo','id_tipo_cliente','anio', 'anio_proyecto'];

    public function tipoCliente(){
        return $this->hasOne('App\TiposClientes','id','id_tipo_cliente');
    }

    public function clientes(){
        //return $this->belongsTo('App\ProyectosClientes','id_proyecto','id');
        return $this->belongsToMany('App\Clientes', 'proyectos_clientes', 'id_proyecto','id_cliente');
    }

}
