<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CatalogoEncuestasPreguntasParametrosClasificacions extends Model
{
    protected $table ='catalogo_encuestas_preguntas_parametros_clasificaciones';
    protected $fillable =['id_catalogo_encuesta','nombre','puntos_maximo','id_catalogo_encuestas_preguntas_parametros_clasificaciones_tipos',];

    public function tipoParametro(){
        return $this->hasOne('App\CatalogoEncuestasPreguntasParametrosClasificacionsTipo','id','id_catalogo_encuestas_preguntas_parametros_clasificaciones_tipos');
    }

    public function items(){
        return $this->hasMany('App\CatalogoEncuestasPreguntasParametrosClasificacionItems','id_catalogo_encuestas_preguntas_parametro_clasificacion','id');
    }
}
