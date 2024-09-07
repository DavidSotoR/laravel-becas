<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FamiliasDocumentos extends Model
{
    protected $fillable = ['id_familia','id_familias_documentos_tipo','id_servicio_estudio','nombre','directorio','alias'];
}
