<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CatalogoEncuestasPreguntas extends Model
{
    protected $fillable =[
        'pregunta',
        'puntos_maximos',
        'id_catalogo_encuesta',
        'id_catalogo_encuestas_preguntas_tipo',
        'id_catalogo_encuestas_preguntas_parametro_clasificacion',
        'id_parametro_clasificacion_tipo',
        'orden',
        'numero_pregunta',
        'longitud_respuesta',
    ];

    public function catalogoEncuesta(){
        return $this->hasOne('App\CatalogoEncuestas','id','id_catalogo_encuesta');
    }

    public function tipoPreguntas(){
        return $this->hasOne('App\CatalogoEncuestasPreguntasTipos','id','id_catalogo_encuestas_preguntas_tipo');
    }

    public function clasificacionParametro(){
        return $this->hasOne('App\CatalogoEncuestasPreguntasParametrosClasificacions','id','id_catalogo_encuestas_preguntas_parametro_clasificacion');
    }

    public function calsificacionParametroTipo(){
        return $this->hasOne('App\CatalogoEncuestasPreguntasParametrosClasificacionsTipo','id','id_parametro_clasificacion_tipo');
    }

    public function items(){
        return $this->hasMany('App\CatalogoEncuestasPreguntasParametrosClasificacionItems','id_catalogo_encuestas_preguntas','id');
    }
}
