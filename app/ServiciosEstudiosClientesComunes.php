<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiciosEstudiosClientesComunes extends Model
{
    protected $fillable = ['id_servicio_estudio','id_cliente'];

    public function clientes(){
        return $this->belongsTo('App\Clientes', 'id_cliente');
    }
}
