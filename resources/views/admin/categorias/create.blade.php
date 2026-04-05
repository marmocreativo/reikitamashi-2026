<x-layouts::app :title="__('Nueva Categoría')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 p-6">

        {{-- Breadcrumbs --}}
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('admin.dashboard') }}" wire:navigate>Panel</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('admin.categorias.index') }}" wire:navigate>Categorías</flux:breadcrumbs.item>
            @isset($padre)
                <flux:breadcrumbs.item href="{{ route('admin.categorias.hijas', $padre) }}" wire:navigate>
                    {{ $padre->CATEGORIA_NOMBRE }}
                </flux:breadcrumbs.item>
            @endisset
            <flux:breadcrumbs.item>Nueva categoría</flux:breadcrumbs.item>
        </flux:breadcrumbs>

        {{-- Encabezado --}}
        <div>
            <flux:heading size="xl">Nueva categoría</flux:heading>
            <flux:text class="text-zinc-400">
                @isset($padre)
                    Subcategoría de <strong>{{ $padre->CATEGORIA_NOMBRE }}</strong>
                @else
                    Completa los datos para crear una nueva categoría
                @endisset
            </flux:text>
        </div>

        {{-- Formulario --}}
        <form method="POST" action="{{ route('admin.categorias.store') }}" enctype="multipart/form-data">
            @csrf
            @include('admin.categorias._form', ['padre' => $padre ?? null])

            <div class="mt-6 flex gap-3">
                <flux:button type="submit" variant="primary">Guardar categoría</flux:button>
                <flux:button href="{{ route('admin.categorias.index') }}" wire:navigate variant="ghost">Cancelar</flux:button>
            </div>
        </form>

    </div>
</x-layouts::app>