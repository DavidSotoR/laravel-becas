<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CatalogoEncuestas extends Model
{
    protected $fillable = ['nombre','descripcion','id_tipo_cliente'];
}
