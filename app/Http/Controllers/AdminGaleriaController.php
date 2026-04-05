<?php

namespace App\Http\Controllers;

use App\Models\GaleriaImagen;
use App\Models\Publicacion;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Format;

class AdminGaleriaController extends Controller
{
    const IMG_PATH = 'public/img/publicaciones';
    const IMG_WIDTH  = 1200;
    const IMG_HEIGHT = 1200;

    private function procesarImagen($archivo): string
    {
        $nombreArchivo = 'galeria-' . Str::uuid() . '.webp';
        $ruta = storage_path('app/' . self::IMG_PATH . '/' . $nombreArchivo);

        $manager = ImageManager::usingDriver(Driver::class);

        $manager->decodePath($archivo->getRealPath())
            ->cover(self::IMG_WIDTH, self::IMG_HEIGHT)
            ->encodeUsingFormat(Format::WEBP, quality: 85)
            ->save($ruta);

        return $nombreArchivo;
    }

    public function store(Request $request, Publicacion $publicacion)
    {
        $request->validate([
            'imagenes'   => 'required|array|min:1',
            'imagenes.*' => 'image|max:5120',
        ]);

        $orden = $publicacion->galeria()->max('ORDEN') ?? 0;

        foreach ($request->file('imagenes') as $archivo) {
            $nombre = $this->procesarImagen($archivo);
            $orden++;

            GaleriaImagen::create([
                'ID_OBJETO_PADRE'  => $publicacion->ID_PUBLICACION,
                'GALERIA_ARCHIVO'  => $nombre,
                'TIPO_ARCHIVO'     => 'imagen',
                'TIPO'             => 'publicacion',
                'ESTADO'           => 'activo',
                'ORDEN'            => $orden,
            ]);
        }

        return redirect()
        ->to(route('admin.publicaciones.edit', $publicacion) . '?tab=galeria')
        ->with('success_galeria', 'Imágenes subidas correctamente.');
    }

    public function destroy(Publicacion $publicacion, GaleriaImagen $imagen)
    {
        $ruta = storage_path('app/' . self::IMG_PATH . '/' . $imagen->GALERIA_ARCHIVO);
        if (file_exists($ruta)) {
            unlink($ruta);
        }

        $imagen->delete();

        return redirect()
            ->to(route('admin.publicaciones.edit', $publicacion) . '?tab=galeria')
            ->with('success_galeria', 'Imagen eliminada.');
    }

    public function orden(Request $request, Publicacion $publicacion, GaleriaImagen $imagen)
    {
        $request->validate(['orden' => 'required|integer|min:0']);
        $imagen->update(['ORDEN' => $request->orden]);

        return response()->json(['ok' => true]);
    }
}