<x-layouts::app :title="__('Nueva Publicación')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 p-6">

        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('admin.dashboard') }}" wire:navigate>Panel</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('admin.publicaciones.index') }}" wire:navigate>Publicaciones</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Nueva publicación</flux:breadcrumbs.item>
        </flux:breadcrumbs>

        <div>
            <flux:heading size="xl">Nueva publicación</flux:heading>
            <flux:text class="text-zinc-400">Completa los datos para crear una nueva publicación</flux:text>
        </div>

        <form method="POST" action="{{ route('admin.publicaciones.store') }}" enctype="multipart/form-data">
            @csrf
            @include('admin.publicaciones._form', [
                'publicacion'            => null,
                'tipos'                  => $tipos,
                'categorias'             => $categorias,
                'categoriaSeleccionada'  => $categoriaPreseleccionada?->ID_CATEGORIA,
            ])
        </form>

    </div>
</x-layouts::app>