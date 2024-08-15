<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrdenesServicio extends Model
{
    protected $table = 'ordenes_servicio';

    protected $fillable = [
        'id_proyecto',
        'activo',
        'descripcion',
        'notas',
        'fecha_estimada_entrega',
        'fecha_real_entrega',
        'fecha_estimada_finalizacion',
        'fecha_real_finalizacion'
    ];

    public function proyecto()
    {
        return $this->belongsTo('App\Proyecto', 'id_proyecto','id');
    }
}
