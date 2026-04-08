<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialPago extends Model
{
    protected $table = 'historial_pagos';
    protected $primaryKey = 'ID';
    public $timestamps = false;

    protected $fillable = [
        'NOMBRE',
        'APELLIDOS',
        'CURSO',
        'FECHA',
        'MES',
        'ANIO',
        'NOTAS',
        'IMPORTE',
        'FORMA_PAGO',
    ];

    protected $casts = [
        'FECHA'   => 'date',
        'IMPORTE' => 'decimal:2',
    ];

    // Nombre completo
    public function getNombreCompletoAttribute(): string
    {
        return trim("{$this->NOMBRE} {$this->APELLIDOS}");
    }

    // Scopes
    public function scopePorCurso($query, string $curso)
    {
        return $query->where('CURSO', $curso);
    }

    public function scopePorAnio($query, string $anio)
    {
        return $query->where('ANIO', $anio);
    }

    public function scopePorMes($query, string $mes)
    {
        return $query->where('MES', $mes);
    }
}