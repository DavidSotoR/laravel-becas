<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CatalogoEncuestasPreguntasParametrosClasificacions extends Model
{
    protected $table ='catalogo_encuestas_preguntas_parametros_clasificaciones';
    protected $fillable =['id_catalogo_encuesta','nombre','puntos_maximo','id_catalogo_encuestas_preguntas_parametros_clasificaciones_tipos','color',];

    public function tipoParametro(){
        return $this->hasOne('App\CatalogoEncuestasPreguntasParametrosClasificacionsTipo','id','id_catalogo_encuestas_preguntas_parametros_clasificaciones_tipos');
    }

    public function items(){
        return $this->hasMany('App\CatalogoEncuestasPreguntasParametrosClasificacionItems','id_catalogo_encuestas_preguntas_parametro_clasificacion','id');
    }

    public function porPregunta(){
        return $this->hasMany('App\CatalogoEncuestasPreguntas','id_catalogo_encuestas_preguntas_parametro_clasificacion','id')
                                ->whereHas('clasificacionParametro', function($query) {
                                    $query->tipo(2);
                                });
    }

    public function scopeTipo($query, $tipo)
    {
        return $query->where('id_catalogo_encuestas_preguntas_parametros_clasificaciones_tipos', $tipo);
    }
}
