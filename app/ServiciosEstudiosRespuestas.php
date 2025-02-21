<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiciosEstudiosRespuestas extends Model
{
    protected $fillable = [
                            'id_servicio_estudio',
                            'id_catalogo_encuestas_pregunta',
                            'seccion',
                            'parentesco',
                            'nombre',
                            'texto',
                            'texto_plural',
                            'respuesta',
                            'vive',
                            'activo',
                            'padre_monto',
                            'madre_monto',
                            'monto',
                            'valor',
                            'tipo',
                            'marca_modelo',
                            'anio',
                            'propietario',
                            'id_respuestas_clasificacions',
                        ];

    protected $casts = [
                            'vive' => 'boolean',
                            'activo' => 'boolean',
                        ];
}
