<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $table = 'pacientes';
    protected $primaryKey = 'ID_PACIENTE';
    public $timestamps = false;

    protected $fillable = [
        'NOMBRE',
        'APELLIDOS',
        'FECHA_NACIMIENTO',
        'SEXO',
        'TELEFONO',
        'EMAIL',
        'DIRECCION',
        'NOTAS',
        'ESTADO',
        'FECHA_REGISTRO',
    ];

    protected $casts = [
        'FECHA_NACIMIENTO' => 'date',
        'FECHA_REGISTRO'   => 'datetime',
    ];

    // Nombre completo
    public function getNombreCompletoAttribute(): string
    {
        return trim("{$this->NOMBRE} {$this->APELLIDOS}");
    }

    // Edad calculada
    public function getEdadAttribute(): ?int
    {
        return $this->FECHA_NACIMIENTO?->age;
    }

    // Relación con consultas
    public function consultas()
    {
        return $this->hasMany(Consulta::class, 'ID_PACIENTE', 'ID_PACIENTE')
                    ->orderByDesc('FECHA_CONSULTA');
    }

    // Scopes
    public function scopeActivo($query)
    {
        return $query->where('ESTADO', 'activo');
    }

    public function scopeBuscar($query, string $termino)
    {
        return $query->where(function ($q) use ($termino) {
            $q->where('NOMBRE', 'like', "%{$termino}%")
              ->orWhere('APELLIDOS', 'like', "%{$termino}%")
              ->orWhere('EMAIL', 'like', "%{$termino}%")
              ->orWhere('TELEFONO', 'like', "%{$termino}%");
        });
    }
}