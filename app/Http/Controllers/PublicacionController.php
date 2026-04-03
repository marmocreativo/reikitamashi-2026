<?php

namespace App\Http\Controllers;

use App\Models\Publicacion;

class PublicacionController extends Controller
{
    public function show(string $url)
    {
        $publicacion = Publicacion::where('URL', $url)
            ->where('ESTADO', 'activo')
            ->firstOrFail();

        return view('public.publicaciones.show', compact('publicacion'));
    }
}