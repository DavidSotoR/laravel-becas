<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sucursales extends Model
{
    protected $table = 'sucursales';
    protected $fillable = [
        'id_cliente',
        'nombre',
        'telefono',
        'domicilio',
        'razon_social',
    ];
}
