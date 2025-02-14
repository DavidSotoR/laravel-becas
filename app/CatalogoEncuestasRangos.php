<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CatalogoEncuestasRangos extends Model
{
    protected $fillable = [
        'id_catalogo_encuesta',
        'limite_superior',
        'limite_inferior',
        'porcentaje_sujerido',
     ];
}
