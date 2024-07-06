<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CatalogoEncuestasPreguntas extends Model
{
    protected $fillable =['pregunta','puntos_maximos','id_catalogo_encuesta','id_catalogo_encuestas_preguntas_tipo','id_catalogo_encuestas_preguntas_parametro_clasificacion'];

    public function catalogoEncuesta(){
        return $this->hasOne('App\CatalogoEncuestas','id','id_catalogo_encuesta');
    }

    public function tipoPreguntas(){
        return $this->hasOne('App\CatalogoEncuestasPreguntasTipos','id','id_catalogo_encuestas_preguntas_tipo');
    }

    public function parametroDeClasificacion(){
        return $this->hasOne('App\CatalogoEncuestasPreguntasParametrosClasificacions','id','id_catalogo_encuestas_preguntas_parametro_clasificacion');
    }
}
