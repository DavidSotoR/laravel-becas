<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Familias extends Model
{
    protected $fillable = ['id_ciclo_escolar','nombre','situacion_beca'];
}
