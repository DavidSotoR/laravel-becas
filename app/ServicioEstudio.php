<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
                            'id_calidad',
                            'id_gerencia',
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
                            'direccion',
                            'latitud',
                            'longitud',
                            'calle',
                            'numero_exterior',
                            'colonia',
                            'municipio',
                            'estado',
                            'codigo_postal',
                            'pais',
                            'visita_fecha',
                            'visita_hora',
                            'visita_recordatorio',
                            'porcentaje_otorgado',
                            'clave_familia_colegio',
                          ];



    protected $casts = [
        'es_cliente_comun' => 'boolean',
    ];

    public function estado(){
        return $this->belongsTo('App\ServicioEstados', 'id_servicio_estado');
    }

    public function cliente(){
        return $this->belongsTo('App\Clientes', 'id_cliente');
    }

    public function proyecto(){
        return $this->belongsTo('App\Proyectos', 'id_proyecto');
    }

    public function ordenServicio(){
        return $this->belongsTo('App\OrdenesServicio', 'id_orden_servicio');
    }

    public function colegiosComunes(){
        return $this->belongsToMany(
            Clientes::class,  // El modelo relacionado
            'servicios_estudios_clientes_comunes',  // Tabla pivote
            'id_servicio_estudio',  // Foreign key en la tabla pivote (para servicios_estudios)
            'id_cliente'  // Foreign key en la tabla pivote (para clientes)
        );
        //return $this->hasMany('App\ServiciosEstudiosClientesComunes', 'id_servicio_estudio','id');
        //return $this->belongsTo('App\OrdenesServicio', 'id_orden_servicio');
        /*return $this->hasOneThrough(
            CatalogoEncuestas::class,    // El modelo final al que quieres llegar (CatalogoEncuestas)
            ProyectosClientes::class,    // El modelo intermedio (ProyectosClientes)
            'id_proyecto',               // Foreign key en ProyectosClientes (id_proyecto)
            'id',                        // Foreign key en CatalogoEncuestas (id)
            'id_proyecto',               // Local key en ServiciosEstudios (id_proyecto)
            'id_encuesta'                // Local key en ProyectosClientes que se refiere a CatalogoEncuestas (id_encuesta)
        )->where(function ($query) {
            $query->where('proyectos_clientes.id_cliente', $this->id_cliente);
        });*/
    }

    // Relación con FamiliasPadres
    public function familiasPadres()
    {
        return $this->hasMany('App\FamiliasPadres', 'id_servicio_estudio');
    }
    // Obtener contacto principal
    public function contactoPrincipal()
    {
        return $this->hasOne('App\FamiliasPadres', 'id_servicio_estudio')->where('contecto_principal', 1);
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
