<?php

namespace App\Http\Controllers;

use App\Models\MetaDato;
use App\Models\Publicacion;
use Illuminate\Http\Request;

class AdminMetaDatosController extends Controller
{
    public function store(Request $request, Publicacion $publicacion)
    {
        $request->validate([
            'DATO_NOMBRE' => 'required|string|max:255',
            'DATO_VALOR'  => 'required|string',
        ]);

        // Upsert: si ya existe el nombre para esta publicación lo actualiza
        MetaDato::updateOrCreate(
            [
                'ID_OBJETO'   => $publicacion->ID_PUBLICACION,
                'DATO_NOMBRE' => $request->DATO_NOMBRE,
                'TIPO_OBJETO' => MetaDato::TIPO_PUBLICACION,
            ],
            [
                'DATO_VALOR' => $request->DATO_VALOR,
            ]
        );

        return redirect()
            ->to(route('admin.publicaciones.edit', $publicacion) . '?tab=metadatos')
            ->with('success_meta', 'Metadato guardado correctamente.');
    }

    public function destroy(Request $request, Publicacion $publicacion, string $nombre)
    {
        MetaDato::where('ID_OBJETO', $publicacion->ID_PUBLICACION)
            ->where('DATO_NOMBRE', $nombre)
            ->where('TIPO_OBJETO', MetaDato::TIPO_PUBLICACION)
            ->delete();

        return redirect()
            ->to(route('admin.publicaciones.edit', $publicacion) . '?tab=metadatos')
            ->with('success_meta', 'Metadato eliminado.');
    }
}