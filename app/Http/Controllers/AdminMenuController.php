<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Categoria;
use App\Models\Publicacion;
use Illuminate\Http\Request;

class AdminMenuController extends Controller
{
    public function index()
    {
        $menu = Menu::orderBy('ORDEN')->get();

        $categorias = Categoria::where('ESTADO', 'activo')
            ->orderBy('CATEGORIA_NOMBRE')
            ->get(['ID_CATEGORIA', 'CATEGORIA_NOMBRE', 'URL']);

        $publicaciones = Publicacion::where('ESTADO', 'activo')
            ->orderBy('PUBLICACION_TITULO')
            ->get(['ID_PUBLICACION', 'PUBLICACION_TITULO', 'URL']);

        return view('admin.menu.index', compact('menu', 'categorias', 'publicaciones'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'MENU_ETIQUETA' => 'required|string|max:255',
            'MENU_ENLACE'   => 'required|string|max:255',
            'MENU_PADRE'    => 'nullable|integer',
            'MENU_GRUPO'    => 'nullable|string|max:255',
        ]);

        $data['MENU_PADRE'] = $data['MENU_PADRE'] ?? 0;
        $data['MENU_GRUPO'] = $data['MENU_GRUPO'] ?? 'principal';
        $data['ORDEN']      = Menu::max('ORDEN') + 1;

        $item = Menu::create($data);

        return response()->json($item);
    }

    public function update(Request $request, Menu $menu)
    {
        $data = $request->validate([
            'MENU_ETIQUETA' => 'required|string|max:255',
            'MENU_ENLACE'   => 'required|string|max:255',
        ]);

        $menu->update($data);

        return response()->json($menu);
    }

    public function destroy(Menu $menu)
    {
        // Quitar padre a los hijos antes de eliminar
        Menu::where('MENU_PADRE', $menu->ID_MENU)->update(['MENU_PADRE' => 0]);

        $menu->delete();

        return response()->json(['ok' => true]);
    }

    public function reordenar(Request $request)
    {
        $request->validate([
            'items'             => 'required|array',
            'items.*.id'        => 'required|integer',
            'items.*.orden'     => 'required|integer',
            'items.*.id_padre'  => 'required|integer',
        ]);

        foreach ($request->items as $item) {
            Menu::where('ID_MENU', $item['id'])->update([
                'ORDEN'      => $item['orden'],
                'MENU_PADRE' => $item['id_padre'],
            ]);
        }

        return response()->json(['ok' => true]);
    }
}