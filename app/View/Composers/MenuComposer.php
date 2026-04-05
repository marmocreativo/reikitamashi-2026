<?php

namespace App\View\Composers;

use App\Models\Menu;
use Illuminate\View\View;

class MenuComposer
{
    public function compose(View $view): void
    {
        $menuItems = Menu::where('MENU_GRUPO', 'principal')
            ->orderBy('ORDEN')
            ->get();

        $raiz  = $menuItems->where('MENU_PADRE', 0)->values();
        $hijos = $menuItems->where('MENU_PADRE', '!=', 0)->groupBy('MENU_PADRE');

        $view->with('menuPublico', $raiz)->with('menuHijos', $hijos);
    }
}