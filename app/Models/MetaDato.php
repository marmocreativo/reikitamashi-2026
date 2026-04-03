<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetaDato extends Model
{
    protected $table = 'meta_datos';
    public $incrementing = false;
    public $timestamps = false;

    const TIPO_PUBLICACION = 'publicacion';
    const TIPO_CATEGORIA   = 'categoria';

    protected $fillable = [
        'ID_OBJETO',
        'DATO_NOMBRE',
        'DATO_VALOR',
        'TIPO_OBJETO',
    ];

    // Relación inversa hacia publicación
    public function publicacion()
    {
        return $this->belongsTo(Publicacion::class, 'ID_OBJETO', 'ID_PUBLICACION');
    }

    // Relación inversa hacia categoría
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'ID_OBJETO', 'ID_CATEGORIA');
    }
}