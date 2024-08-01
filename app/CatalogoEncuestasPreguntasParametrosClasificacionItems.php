<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CatalogoEncuestasPreguntasParametrosClasificacionItems extends Model
{
    protected $table ='catalogo_encuestas_preguntas_parametros_clasificacion_items';


    protected $fillable =['id_catalogo_encuestas_preguntas_parametro_clasificacion','id_catalogo_encuestas_preguntas','texto','limite_superior','limiten_inferior','valor',];

    /*public function tipoParametro(){
        return $this->belongsTo('App\User','id_cliente','id');
    }

    public function tipoParametro(){
        return $this->belongsTo('App\User','id_cliente','id');
    }*/
}
