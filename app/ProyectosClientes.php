<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProyectosClientes extends Model
{
    protected $fillable = ['id_proyecto','id_cliente','id_encuesta'];

     public function cliente()
     {
         return $this->belongsTo('App\Clientes', 'id_cliente');
     }

     public function encuesta()
     {
         return $this->belongsTo('App\CatalogoEncuestas', 'id_encuesta');
     }

     public function proyecto()
     {
         return $this->belongsTo('App\Proyectos', 'id_proyecto');
     }

    public function serviciosEstudios()
    {
        return $this->hasMany(ServiciosEstudios::class, 'id_proyecto', 'id_proyecto');
    }
}
