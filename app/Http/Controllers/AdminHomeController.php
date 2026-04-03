<?php

namespace App\Http\Controllers;

use App\Models\Publicacion;

class AdminHomeController extends Controller
{
    public function index()
    {
        $totales = collect(Publicacion::TIPOS)->mapWithKeys(function ($tipo) {
            return [$tipo => Publicacion::where('TIPO', $tipo)->count()];
        });

        return view('admin.dashboard', compact('totales'));
    }
}