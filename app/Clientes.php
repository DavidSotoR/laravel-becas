<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Clientes extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'notificaciones_email',
        'id_tipo_cliente',
        'id_clientes_hermanos',
        'rfc',
        'tipo_persona',
        'rso',
        'nombre_uno',
        'telefono_uno',
        'nombre_dos',
        'telefono_dos',
        'telefono_mobil',
        'calle',
        'entre_cale',
        'colonia',
        'codigo_postal',
        'ciudad',
        'estado',
        'pais',
        'rason_social',
        'id_catalogo_encuesta',
        'documentacion_digital',
        'habilitar_resumen',
        'ubicacion_logo',
        'requiere_facturar',
        'terminos'
    ];

    public function tipoCliente()
    {
        return $this->hasOne('App\TiposClientes', 'id', 'id_tipo_cliente');
    }
    public function encuesta()
    {
        return $this->hasOne('App\CatalogoEncuestasPreguntas', 'id', 'id_catalogo_encuesta');
    }

    public function encuesta_asignada()
    {
        return $this->hasOne('App\CatalogoEncuestas', 'id', 'id_catalogo_encuesta');
    }

    public function usuarios()
    {
        return $this->belongsTo('App\User', 'id_cliente', 'id');
    }

    public function proyectos()
    {
        return $this->belongsToMany('App\Proyectos', 'proyectos_clientes', 'id_cliente', 'id_proyecto');
    }
}
