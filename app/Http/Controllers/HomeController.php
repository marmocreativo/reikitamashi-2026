<?php

namespace App\Http\Controllers;

use App\Models\Publicacion;
use App\Models\Categoria;

class HomeController extends Controller
{
    public function index()
    {
        $destacadas = Publicacion::where('DESTACADA', true)
            ->where('ESTADO', 'activo')
            ->orderBy('ORDEN')
            ->get();

        $categoriasDestacadas = Categoria::where('DESTACADA', true)
            ->where('ESTADO', 'activo')
            ->where('VISIBLE', 'visible')
            ->orderBy('ORDEN')
            ->get()
            ->each(function ($cat) {
                $cat->publicacionesDestacadas = \DB::table('publicaciones')
                    ->join('categorias_objetos', 'publicaciones.ID_PUBLICACION', '=', 'categorias_objetos.ID_OBJETO')
                    ->where('categorias_objetos.ID_CATEGORIA', $cat->ID_CATEGORIA)
                    ->where('publicaciones.ESTADO', 'activo')
                    ->orderBy('publicaciones.ORDEN')
                    ->select('publicaciones.*')
                    ->limit(4)
                    ->get();
            });

        return view('home', compact('destacadas', 'categoriasDestacadas'));
    }
}