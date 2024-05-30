<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Alumnos extends Model
{
    protected $fillable = ['id_familias','id_ciclo_escolar','nombre','domicilio','colonia','municipio','codigo_postal','telefono_madre','telefono_padre',];
}
