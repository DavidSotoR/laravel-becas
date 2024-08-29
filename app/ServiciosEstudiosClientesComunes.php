<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiciosEstudiosClientesComunes extends Model
{
    protected $fillable = ['id_servicio_estudio','id_cliente'];
}
