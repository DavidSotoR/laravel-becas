<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CatalogoEncuestasPreguntasParametrosClasificacions extends Model
{
    protected $table ='catalogo_encuestas_preguntas_parametros_clasificaciones';
    protected $fillable =['id_catalogo_encuesta','nombre','puntos_maximo'];
}
