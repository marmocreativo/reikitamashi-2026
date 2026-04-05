<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menu';
    protected $primaryKey = 'ID_MENU';
    public $timestamps = false;

    protected $fillable = [
        'MENU_ETIQUETA',
        'MENU_ENLACE',
        'MENU_PADRE',
        'MENU_GRUPO',
        'ORDEN',
    ];

    public function scopeRaiz($query)
    {
        return $query->where('MENU_PADRE', 0);
    }

    public function hijos()
    {
        return $this->hasMany(Menu::class, 'MENU_PADRE', 'ID_MENU')
                    ->orderBy('ORDEN');
    }

    public function padre()
    {
        return $this->belongsTo(Menu::class, 'MENU_PADRE', 'ID_MENU');
    }
}