<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Publicacion extends Model
{
    protected $table = 'publicaciones';
    protected $primaryKey = 'ID_PUBLICACION';
    public $timestamps = false;

    const TIPOS = [
        'pagina',
        'curso_reiki',
        'cursos',
        'terapia',
        'galeria',
        'promocion',
        'egresado',
    ];

    protected $fillable = [
        'PUBLICACION_TITULO',
        'URL',
        'PUBLICACION_RESUMEN',
        'PUBLICACION_CONTENIDO',
        'IMAGEN',
        'PUBLICACION_PADRE',
        'FECHA_REGISTRO',
        'FECHA_ACTUALIZACION',
        'FECHA_PUBLICACION',
        'TIPO',
        'ESTADO',
        'ORDEN',
        'DESTACADA',
    ];

    protected $casts = [
        'FECHA_REGISTRO'      => 'datetime',
        'FECHA_ACTUALIZACION' => 'datetime',
        'FECHA_PUBLICACION'   => 'datetime',
    ];

    // Relación con categorias a través de la tabla pivote categorias_objetos
    public function categorias()
    {
        return $this->belongsToMany(
            Categoria::class,
            'categorias_objetos',
            'ID_OBJETO',
            'ID_CATEGORIA'
        )->wherePivot('TIPO', $this->TIPO);
    }

    // Metadatos de esta publicación
    public function metaDatos()
    {
        return $this->hasMany(MetaDato::class, 'ID_OBJETO', 'ID_PUBLICACION')
                    ->where('TIPO_OBJETO', 'publicacion');
    }

    // Acceso directo a un metadato por nombre
    public function getMeta(string $nombre, mixed $default = null): mixed
    {
        return $this->metaDatos
            ->firstWhere('DATO_NOMBRE', $nombre)
            ?->DATO_VALOR ?? $default;
    }

    // Relación con galeria
    public function galeria()
    {
        return $this->hasMany(GaleriaImagen::class, 'ID_OBJETO_PADRE', 'ID_PUBLICACION')
                    ->where('TIPO_ARCHIVO', 'imagen')
                    ->where('TIPO', 'publicacion')
                    ->orderBy('ORDEN');
    }

    // Scopes útiles
    public function scopeActivo($query)
    {
        return $query->where('ESTADO', 'activo');
    }

    public function scopeDesTipo($query, string $tipo)
    {
        return $query->where('TIPO', $tipo);
    }

    public function scopeOrdenados($query)
    {
        return $query->orderBy('ORDEN');
    }
}