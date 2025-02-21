<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PerfilCliente extends Model
{
    protected $table = 'perfiles_cliente';

    protected $fillable = [
        'id_perfil',
        'id_cliente',
        'nombre',
        'activo',
        'borrado',
    ];

    public function perfil()
    {
        return $this->belongsTo('App\Perfiles', 'id_perfil');
    }

    public function cliente()
    {
        return $this->belongsTo('App\Clientes', 'id_cliente');
    }
}
