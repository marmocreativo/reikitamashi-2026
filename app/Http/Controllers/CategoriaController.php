<?php

namespace App\Http\Controllers;

use App\Models\Categoria;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::raiz()
            ->activa()
            ->visible()
            ->orderBy('ORDEN')
            ->get();

        return view('public.categorias.index', compact('categorias'));
    }

    public function show(string $url)
    {
        $categoria = Categoria::where('URL', $url)
            ->activa()
            ->firstOrFail();

        $hijas = $categoria->hijas()
            ->activa()
            ->visible()
            ->orderBy('ORDEN')
            ->get();

        $publicaciones = collect();

        if ($hijas->isEmpty()) {
            $publicaciones = $categoria->publicaciones()
                ->where('publicaciones.ESTADO', 'activo')
                ->orderBy('publicaciones.ORDEN')
                ->orderByDesc('publicaciones.FECHA_PUBLICACION')
                ->paginate(12);
        }

        return view('public.categorias.show', compact('categoria', 'hijas', 'publicaciones'));
    }
}