<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clientes extends Model
{
    protected $fillable = ['nombre','descripcion','notificaciones_email','id_tipo_cliente','id_clientes_hermanos'];

    public function tipoCliente(){
        return $this->hasOne('App\TiposClientes','id','id_tipo_cliente');
    }
}
