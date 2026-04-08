<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Format;


class AdminCategoriasController extends Controller
{
    const IMG_WIDTH  = 800;
    const IMG_HEIGHT = 1200;
    const IMG_PATH   = 'img/categorias';

    private function procesarImagen($archivo, ?string $nombreActual = null): string
    {
        $nombreArchivo = 'categoria-' . Str::uuid() . '.webp';

        $manager = ImageManager::usingDriver(Driver::class);

        $encoded = $manager->decodePath($archivo->getRealPath())
            ->cover(self::IMG_WIDTH, self::IMG_HEIGHT)
            ->encodeUsingFormat(Format::WEBP, quality: 85);

        Storage::disk('public')->put(self::IMG_PATH . '/' . $nombreArchivo, $encoded->toString());

        if ($nombreActual && $nombreActual !== 'default.jpg') {
            Storage::disk('public')->delete(self::IMG_PATH . '/' . $nombreActual);
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
            'CATEGORIA_NOMBRE'      => 'required|string|max:255',
            'URL'                   => 'required|string|max:255|unique:categorias,URL',
            'CATEGORIA_DESCRIPCION' => 'nullable|string',
            'CATEGORIA_PADRE'       => 'nullable|integer',
            'VISIBLE'               => 'required|in:visible,invisible',
            'TIPO'                  => 'required|in:pagina,curso_reiki,cursos,terapia,galeria,promocion,egresado',
            'ESTADO'                => 'required|in:activo,inactivo',
            'ORDEN'                 => 'nullable|integer',
            'DESTACADA'             => 'boolean',
            'imagen'                => 'nullable|image|max:5120',
        ]);

        $data['CATEGORIA_PADRE'] = $data['CATEGORIA_PADRE'] ?? 0;
        $data['CATEGORIA_NIVEL'] = $data['CATEGORIA_PADRE'] > 0 ? 2 : 1;
        $data['DESTACADA']       = $request->boolean('DESTACADA');
        $data['IMAGEN']          = 'default.jpg';

        if ($request->hasFile('imagen')) {
            $data['IMAGEN'] = $this->procesarImagen($request->file('imagen'));
        }

        Categoria::create($data);

        return redirect()
            ->route('admin.categorias.index')
            ->with('success', 'Categoría creada correctamente.');
    }

    public function edit(Categoria $categoria)
    {
        $padre = $categoria->CATEGORIA_PADRE ? Categoria::find($categoria->CATEGORIA_PADRE) : null;

        return view('admin.categorias.edit', compact('categoria', 'padre'));
    }

    public function update(Request $request, Categoria $categoria)
    {
        $data = $request->validate([
            'CATEGORIA_NOMBRE'      => 'required|string|max:255',
            'URL'                   => 'required|string|max:255|unique:categorias,URL,' . $categoria->ID_CATEGORIA . ',ID_CATEGORIA',
            'CATEGORIA_DESCRIPCION' => 'nullable|string',
            'VISIBLE'               => 'required|in:visible,invisible',
            'TIPO'                  => 'required|in:pagina,curso_reiki,cursos,terapia,galeria,promocion,egresado',
            'ESTADO'                => 'required|in:activo,inactivo',
            'ORDEN'                 => 'nullable|integer',
            'DESTACADA'             => 'boolean',
            'imagen'                => 'nullable|image|max:5120',
        ]);

        $data['DESTACADA'] = $request->boolean('DESTACADA');

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
            Storage::disk('public')->delete(self::IMG_PATH . '/' . $categoria->IMAGEN);
        }

        $categoria->delete();

        return redirect()
            ->route('admin.categorias.index')
            ->with('success', 'Categoría eliminada correctamente.');
    }

    public function toggleDestacada(Categoria $categoria)
    {
        $categoria->update(['DESTACADA' => !$categoria->DESTACADA]);

        return back()->with('success', $categoria->DESTACADA ? 'Categoría destacada.' : 'Categoría quitada de destacadas.');
    }
}