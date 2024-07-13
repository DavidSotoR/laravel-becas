<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClientesHermanos extends Model
{
    protected $fillable = ['nombre'];

    public function lista(){
        return $this->hasMany('App\Clientes','id_clientes_hermanos','id');
    }
}



