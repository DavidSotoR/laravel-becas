<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clientes extends Model
{
    public function tipoCliente(){
        return $this->hasOne('App\TiposClientes','id','id_tipo_cliente');
    }
}
