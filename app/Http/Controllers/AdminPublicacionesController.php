<?php

namespace App\Http\Controllers;

use App\Models\Publicacion;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Format;

class AdminPublicacionesController extends Controller
{
    const IMG_WIDTH  = 1200;
    const IMG_HEIGHT = 1200;
    const IMG_PATH   = 'public/img/publicaciones';

    private function procesarImagen($archivo, ?string $nombreActual = null): string
    {
        $nombreArchivo = 'publicacion-' . Str::uuid() . '.webp';
        $ruta = storage_path('app/' . self::IMG_PATH . '/' . $nombreArchivo);

        $manager = ImageManager::usingDriver(Driver::class);

        $encoded = $manager->decodePath($archivo->getRealPath())
            ->cover(self::IMG_WIDTH, self::IMG_HEIGHT)
            ->encodeUsingFormat(Format::WEBP, quality: 85);

        $encoded->save($ruta);

        if ($nombreActual && $nombreActual !== 'default.jpg') {
            $rutaAnterior = storage_path('app/' . self::IMG_PATH . '/' . $nombreActual);
            if (file_exists($rutaAnterior)) {
                unlink($rutaAnterior);
            }
        }

        return $nombreArchivo;
    }

    public function index(Request $request)
    {
        $tipo       = $request->get('tipo');
        $estado     = $request->get('estado');
        $buscar     = $request->get('buscar');
        $categoriaId = $request->get('categoria');

        $query = Publicacion::query()->orderBy('ORDEN')->orderByDesc('FECHA_REGISTRO');

        if ($tipo) {
            $query->where('TIPO', $tipo);
        }

        if ($estado) {
            $query->where('ESTADO', $estado);
        }

        if ($buscar) {
            $query->where('PUBLICACION_TITULO', 'like', "%{$buscar}%");
        }

        if ($categoriaId) {
            $query->whereExists(function ($q) use ($categoriaId) {
                $q->select(\DB::raw(1))
                    ->from('categorias_objetos')
                    ->whereColumn('categorias_objetos.ID_OBJETO', 'publicaciones.ID_PUBLICACION')
                    ->where('categorias_objetos.ID_CATEGORIA', $categoriaId);
            });
        }

        $publicaciones = $query->paginate(20)->withQueryString();

        $categoria  = $categoriaId ? \App\Models\Categoria::find($categoriaId) : null;
        $categorias = \App\Models\Categoria::orderBy('CATEGORIA_NOMBRE')->get(['ID_CATEGORIA', 'CATEGORIA_NOMBRE', 'TIPO']);

        return view('admin.publicaciones.index', compact('publicaciones', 'tipo', 'estado', 'buscar', 'categoria', 'categorias', 'categoriaId'));
    }

    public function create(Request $request)
    {
        $tipos = Publicacion::TIPOS;
        $categoriaPreseleccionada = $request->filled('categoria')
            ? \App\Models\Categoria::find($request->categoria)
            : null;
        $categorias = \App\Models\Categoria::orderBy('CATEGORIA_NOMBRE')
            ->get(['ID_CATEGORIA', 'CATEGORIA_NOMBRE', 'CATEGORIA_PADRE', 'TIPO']);

        return view('admin.publicaciones.create', compact('tipos', 'categorias', 'categoriaPreseleccionada'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'PUBLICACION_TITULO'   => 'required|string|max:255',
            'URL'                  => 'required|string|max:255|unique:publicaciones,URL',
            'PUBLICACION_RESUMEN'  => 'nullable|string',
            'PUBLICACION_CONTENIDO'=> 'nullable|string',
            'TIPO'                 => 'required|in:' . implode(',', Publicacion::TIPOS),
            'ESTADO'               => 'required|in:activo,inactivo',
            'ORDEN'                => 'nullable|integer',
            'DESTACADA'             => 'boolean',
            'PUBLICACION_PADRE'    => 'nullable|integer',
            'FECHA_PUBLICACION'    => 'nullable|date',
            'imagen'               => 'nullable|image|max:5120',
        ]);

        $data['IMAGEN']           = 'default.jpg';
        $data['FECHA_REGISTRO']   = now();
        $data['FECHA_PUBLICACION'] = $data['FECHA_PUBLICACION'] ?? now();
        $data['FECHA_ACTUALIZACION'] = now();
        $data['PUBLICACION_PADRE'] = $data['PUBLICACION_PADRE'] ?? 0;
        $data['ORDEN']            = $data['ORDEN'] ?? 0;
        $data['DESTACADA'] = $request->boolean('DESTACADA');

        if ($request->hasFile('imagen')) {
            $data['IMAGEN'] = $this->procesarImagen($request->file('imagen'));
        }

        $publicacion = Publicacion::create($data);

        if ($request->filled('ID_CATEGORIA')) {
            \DB::table('categorias_objetos')->insert([
                'ID_CATEGORIA' => $request->ID_CATEGORIA,
                'ID_OBJETO'    => $publicacion->ID_PUBLICACION,
                'TIPO'         => $publicacion->TIPO,
            ]);
        }

        return redirect()
            ->route('admin.publicaciones.index')
            ->with('success', 'Publicación creada correctamente.');
    }

    public function edit(Publicacion $publicacion)
    {
        $tipos = Publicacion::TIPOS;
        $publicacion->load('galeria', 'metaDatos');

        $categorias = \App\Models\Categoria::orderBy('CATEGORIA_NOMBRE')
            ->get(['ID_CATEGORIA', 'CATEGORIA_NOMBRE', 'CATEGORIA_PADRE', 'TIPO']);

        $categoriaSeleccionada = \DB::table('categorias_objetos')
            ->where('ID_OBJETO', $publicacion->ID_PUBLICACION)
            ->value('ID_CATEGORIA');

        return view('admin.publicaciones.edit', compact('publicacion', 'tipos', 'categorias', 'categoriaSeleccionada'));
    }

    public function update(Request $request, Publicacion $publicacion)
    {
        $data = $request->validate([
            'PUBLICACION_TITULO'   => 'required|string|max:255',
            'URL'                  => 'required|string|max:255|unique:publicaciones,URL,' . $publicacion->ID_PUBLICACION . ',ID_PUBLICACION',
            'PUBLICACION_RESUMEN'  => 'nullable|string',
            'PUBLICACION_CONTENIDO'=> 'nullable|string',
            'TIPO'                 => 'required|in:' . implode(',', Publicacion::TIPOS),
            'ESTADO'               => 'required|in:activo,inactivo',
            'ORDEN'                => 'nullable|integer',
            'DESTACADA'             => 'boolean',
            'PUBLICACION_PADRE'    => 'nullable|integer',
            'FECHA_PUBLICACION'    => 'nullable|date',
            'imagen'               => 'nullable|image|max:5120',
        ]);

        $data['FECHA_ACTUALIZACION'] = now();
        $data['DESTACADA'] = $request->boolean('DESTACADA');
        $data['FECHA_PUBLICACION'] = $data['FECHA_PUBLICACION'] ?? $publicacion->FECHA_PUBLICACION ?? now();

        if ($request->hasFile('imagen')) {
            $data['IMAGEN'] = $this->procesarImagen($request->file('imagen'), $publicacion->IMAGEN);
        }

        $publicacion->update($data);

        \DB::table('categorias_objetos')
            ->where('ID_OBJETO', $publicacion->ID_PUBLICACION)
            ->delete();

        if ($request->filled('ID_CATEGORIA')) {
            \DB::table('categorias_objetos')->insert([
                'ID_CATEGORIA' => $request->ID_CATEGORIA,
                'ID_OBJETO'    => $publicacion->ID_PUBLICACION,
                'TIPO'         => $publicacion->TIPO,
            ]);
        }

        return redirect()
            ->route('admin.publicaciones.index')
            ->with('success', 'Publicación actualizada correctamente.');
    }

    public function destroy(Publicacion $publicacion)
    {
        if ($publicacion->IMAGEN !== 'default.jpg') {
            $ruta = storage_path('app/' . self::IMG_PATH . '/' . $publicacion->IMAGEN);
            if (file_exists($ruta)) {
                unlink($ruta);
            }
        }

        $publicacion->delete();

        return redirect()
            ->route('admin.publicaciones.index')
            ->with('success', 'Publicación eliminada correctamente.');
    }
    
    public function toggleDestacada(Publicacion $publicacion)
    {
        $publicacion->update(['DESTACADA' => !$publicacion->DESTACADA]);

        return back()->with('success', $publicacion->DESTACADA ? 'Publicación destacada.' : 'Publicación quitada de destacadas.');
    }
}