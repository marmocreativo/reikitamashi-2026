<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categorias';
    protected $primaryKey = 'ID_CATEGORIA';
    public $timestamps = false;

    protected $fillable = [
        'CATEGORIA_NOMBRE',
        'URL',
        'CATEGORIA_DESCRIPCION',
        'IMAGEN',
        'CATEGORIA_PADRE',
        'CATEGORIA_NIVEL',
        'VISIBLE',
        'TIPO',
        'ESTADO',
        'ORDEN',
        'DESTACADA',
    ];

    // Publicaciones relacionadas a través de categorias_objetos
    public function publicaciones()
    {
        return $this->belongsToMany(
            Publicacion::class,
            'categorias_objetos',
            'ID_CATEGORIA',
            'ID_OBJETO'
        )->wherePivot('TIPO', $this->TIPO);
    }

    // Categoría padre (self-referential)
    public function padre()
    {
        return $this->belongsTo(Categoria::class, 'CATEGORIA_PADRE', 'ID_CATEGORIA');
    }

    // Categorías hijas
    public function hijas()
    {
        return $this->hasMany(Categoria::class, 'CATEGORIA_PADRE', 'ID_CATEGORIA');
    }

    // Metadatos de esta categoría
    public function metaDatos()
    {
        return $this->hasMany(MetaDato::class, 'ID_OBJETO', 'ID_CATEGORIA')
                    ->where('TIPO_OBJETO', 'categoria');
    }

    // Acceso directo a un metadato por nombre
    public function getMeta(string $nombre, mixed $default = null): mixed
    {
        return $this->metaDatos
            ->firstWhere('DATO_NOMBRE', $nombre)
            ?->DATO_VALOR ?? $default;
    }

    // Scopes útiles
    public function scopeActiva($query)
    {
        return $query->where('ESTADO', 'activo');
    }

    public function scopeVisible($query)
    {
        return $query->where('VISIBLE', 'visible');
    }

    public function scopeDesTipo($query, string $tipo)
    {
        return $query->where('TIPO', $tipo);
    }

    public function scopeRaiz($query)
    {
        return $query->where('CATEGORIA_PADRE', 0);
    }
}