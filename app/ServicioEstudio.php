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

}
