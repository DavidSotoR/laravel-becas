<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegistroToken extends Model
{
    protected $table = 'registro_tokens';

    protected $fillable = [
        'id_cliente',
        'id_proyecto',
        'id_orden_servicio',
        'token',
        'token_parte1',
        'token_parte2',
        'activo',
        'link_registro',
    ];

    public function cliente()
    {
        return $this->belongsTo(Clientes::class, 'id_cliente');
    }

    public function proyecto()
    {
        return $this->belongsTo(Proyectos::class, 'id_proyecto');
    }

    public function ordenServicio()
    {
        return $this->belongsTo(OrdenesServicio::class, 'id_orden_servicio');
    }

}
