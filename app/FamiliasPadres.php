<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FamiliasPadres extends Model
{
    protected $fillable = [
        'id_servicio_estudio',
        'id_familias_padres_tipo',
        'nombre',
        'edad',
        'vive',
        'direccion',
        'ocupacion_actual',
        'empresa_trabajo',
        'email',
        'telefono_casa'
    ];
}
