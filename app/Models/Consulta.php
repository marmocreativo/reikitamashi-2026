<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consulta extends Model
{
    protected $table = 'consultas';
    protected $primaryKey = 'ID_CONSULTA';
    public $timestamps = false;

    protected $fillable = [
        'ID_PACIENTE',
        'FECHA_CONSULTA',
        'SINTOMAS',
        'TRATAMIENTO',
        'NOTAS',
        'FECHA_REGISTRO',
    ];

    protected $casts = [
        'FECHA_CONSULTA' => 'date',
        'FECHA_REGISTRO' => 'datetime',
    ];

    // Relación inversa hacia paciente
    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'ID_PACIENTE', 'ID_PACIENTE');
    }
}