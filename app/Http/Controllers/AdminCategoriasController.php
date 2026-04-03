<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Format;


class AdminCategoriasController extends Controller
{
    // Configuración de imagen — fácil de cambiar después
    const IMG_WIDTH  = 1200;
    const IMG_HEIGHT = 1200;
    const IMG_PATH   = 'public/img/categorias';

    private function procesarImagen($archivo, ?string $nombreActual = null): string
    {
        $nombreArchivo = 'categoria-' . Str::uuid() . '.webp';
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
        $tipo = $request->get('tipo');

        $query = Categoria::query()
            ->where(function ($q) {
                $q->whereNull('CATEGORIA_PADRE')
                ->orWhere('CATEGORIA_PADRE', 0);
            })
            ->withCount('hijas')
            ->orderBy('ORDEN');

        if ($tipo) {
            $query->where('TIPO', $tipo);
        }

        $categorias = $query->get();

        return view('admin.categorias.index', compact('categorias', 'tipo'));
    }

    public function hijas(Categoria $categoria)
    {
        $hijas = $categoria->hijas()
            ->withCount('hijas')
            ->orderBy('ORDEN')
            ->get();

        return view('admin.categorias.index', [
            'categorias' => $hijas,
            'padre'      => $categoria,
        ]);
    }

    public function create(Request $request)
    {
        $padre = $request->filled('padre')
            ? Categoria::findOrFail($request->padre)
            : null;

        return view('admin.categorias.create', compact('padre'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'CATEGORIA_NOMBRE'       => 'required|string|max:255',
            'URL'                    => 'required|string|max:255|unique:categorias,URL',
            'CATEGORIA_DESCRIPCION'  => 'nullable|string',
            'CATEGORIA_PADRE'        => 'nullable|integer',
            'VISIBLE'                => 'required|in:visible,invisible',
            'TIPO'                   => 'required|in:pagina,curso_reiki,cursos,terapia,galeria,promocion,egresado',
            'ESTADO'                 => 'required|in:activo,inactivo',
            'ORDEN'                  => 'nullable|integer',
            'imagen'                 => 'nullable|image|max:5120',
        ]);

        $data['CATEGORIA_PADRE'] = $data['CATEGORIA_PADRE'] ?? 0;
        $data['CATEGORIA_NIVEL'] = $data['CATEGORIA_PADRE'] > 0 ? 2 : 1;
        $data['IMAGEN']          = 'default.jpg';

        if ($request->hasFile('imagen')) {
            $data['IMAGEN'] = $this->procesarImagen($request->file('imagen'));
        }

        $categoria = Categoria::create($data);

        return redirect()
            ->route('admin.categorias.index')
            ->with('success', 'Categoría creada correctamente.');
    }

    public function edit(Categoria $categoria)
    {
        return view('admin.categorias.edit', compact('categoria'));
    }

    public function update(Request $request, Categoria $categoria)
    {
        $data = $request->validate([
            'CATEGORIA_NOMBRE'       => 'required|string|max:255',
            'URL'                    => 'required|string|max:255|unique:categorias,URL,' . $categoria->ID_CATEGORIA . ',ID_CATEGORIA',
            'CATEGORIA_DESCRIPCION'  => 'nullable|string',
            'VISIBLE'                => 'required|in:visible,invisible',
            'TIPO'                   => 'required|in:pagina,curso_reiki,cursos,terapia,galeria,promocion,egresado',
            'ESTADO'                 => 'required|in:activo,inactivo',
            'ORDEN'                  => 'nullable|integer',
            'imagen'                 => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('imagen')) {
            $data['IMAGEN'] = $this->procesarImagen($request->file('imagen'), $categoria->IMAGEN);
        }

        $categoria->update($data);

        return redirect()
            ->route('admin.categorias.index')
            ->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroy(Categoria $categoria)
    {
        if ($categoria->hijas()->exists()) {
            return back()->with('error', 'No puedes eliminar una categoría que tiene subcategorías.');
        }

        if ($categoria->IMAGEN !== 'default.jpg') {
            $ruta = storage_path('app/' . self::IMG_PATH . '/' . $categoria->IMAGEN);
            if (file_exists($ruta)) {
                unlink($ruta);
            }
        }

        $categoria->delete();

        return redirect()
            ->route('admin.categorias.index')
            ->with('success', 'Categoría eliminada correctamente.');
    }
}