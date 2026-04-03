<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GaleriaImagen extends Model
{
    protected $table = 'galeria';
    protected $primaryKey = 'ID_GALERIA';
    public $timestamps = false;

    protected $fillable = [
        'ID_OBJETO_PADRE',
        'GALERIA_ARCHIVO',
        'TIPO_ARCHIVO',
        'TIPO',
        'ESTADO',
        'ORDEN',
    ];

    // Relación inversa hacia la publicación
    public function publicacion()
    {
        return $this->belongsTo(Publicacion::class, 'ID_OBJETO_PADRE', 'ID_PUBLICACION');
    }

    public function scopeActiva($query)
    {
        return $query->where('ESTADO', 'activo');
    }

    public function scopeOrdenadas($query)
    {
        return $query->orderBy('ORDEN');
    }
}