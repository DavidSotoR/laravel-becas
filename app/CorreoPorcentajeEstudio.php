<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CorreoPorcentajeEstudio extends Model
{
    protected $table = 'correos_porcentajes_estudios'; // Nombre de la tabla en la BD

    protected $fillable = [
        'id_servicio_estudio',
        'id_proyecto',
        'uniquekey',
        'correo_contacto',
        'contador',
        'fecha_envio',
        'fecha_reenvio',
    ];

    public $timestamps = true; // Usa los campos created_at y updated_at

    // Relación con la tabla de servicio_estudio
    public function servicioEstudio()
    {
        return $this->belongsTo(ServicioEstudio::class, 'id_servicio_estudio');
    }

    // Relación con la tabla de proyecto
    public function proyecto()
    {
        return $this->belongsTo(Proyectos::class, 'id_proyecto');
    }
}
