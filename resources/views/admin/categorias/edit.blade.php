<x-layouts::app :title="__('Editar Categoría')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 p-6">

        {{-- Breadcrumbs --}}
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('admin.dashboard') }}" wire:navigate>Panel</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('admin.categorias.index') }}" wire:navigate>Categorías</flux:breadcrumbs.item>
            @if($padre)
                <flux:breadcrumbs.item href="{{ route('admin.categorias.hijas', $padre) }}" wire:navigate>
                    {{ $padre->CATEGORIA_NOMBRE }}
                </flux:breadcrumbs.item>
            @endif
            <flux:breadcrumbs.item>{{ $categoria->CATEGORIA_NOMBRE }}</flux:breadcrumbs.item>
        </flux:breadcrumbs>

        {{-- Encabezado --}}
        <div>
            <flux:heading size="xl">{{ $categoria->CATEGORIA_NOMBRE }}</flux:heading>
            <flux:text class="text-zinc-400">Editar categoría</flux:text>
        </div>

        {{-- Formulario --}}
        <form method="POST" action="{{ route('admin.categorias.update', $categoria) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.categorias._form', ['categoria' => $categoria, 'padre' => $padre ?? null])

            <div class="mt-6 flex gap-3">
                <flux:button type="submit" variant="primary">Actualizar categoría</flux:button>
                <flux:button href="{{ route('admin.categorias.index') }}" wire:navigate variant="ghost">Cancelar</flux:button>
            </div>
        </form>

    </div>
</x-layouts::app>