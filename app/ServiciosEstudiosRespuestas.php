<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiciosEstudiosRespuestas extends Model
{
    protected $fillable = [
                            'id_servicio_estudio',
                            'id_catalogo_encuestas_pregunta',
                            'parentesco',
                            'nombre',
                            'texto',
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
                        ];

    protected $casts = [
                            'vive' => 'boolean',
                            'activo' => 'boolean',
                        ];
}
