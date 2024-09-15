<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServicioEstudio extends Model
{
    protected $table = 'servicios_estudios';
    protected $fillable = [
                            'id_servicio_estado',
                            'id_proyecto',
                            'id_cliente',
                            'id_familia',
                            'id_orden_servicio',
                            'id_colaborador',
                            'es_cliente_comun',
                            'candidato',
                            'situacion',
                            'email',
                            'telefono_movil',
                            'telefono_contacto',
                            'curp',
                            'domicilio',
                            'entrecalles',
                            'departamento',
                            'anterior_empleo',
                            'anterior_puesto',
                            'anterior_empresa',
                            'anterior_antiguedad',
                            'directorio',
                          ];

    public function cliente(){
        return $this->belongsTo('App\Clientes', 'id_cliente');
    }

    public function proyecto(){
        return $this->belongsTo('App\Proyectos', 'id_proyecto');
    }

    public function ordenServicio(){
        return $this->belongsTo('App\OrdenesServicio', 'id_orden_servicio');
    }

    // Relación con FamiliasPadres
    public function familiasPadres()
    {
        return $this->hasMany('App\FamiliasPadres', 'id_servicio_estudio');
    }

    // Obtener padre
    public function padre()
    {
        return $this->hasOne('App\FamiliasPadres', 'id_servicio_estudio')->where('id_familias_padres_tipo', 1);
    }

    // Obtener madre
    public function madre()
    {
        return $this->hasOne('App\FamiliasPadres', 'id_servicio_estudio')->where('id_familias_padres_tipo', 2);
    }

    public function colaborador(){
        return $this->belongsTo('App\User', 'id_colaborador');
    }

    public function familia(){
        return $this->belongsTo('App\User', 'id_familia');
    }
}
