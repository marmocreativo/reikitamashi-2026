<x-layouts::app :title="__('Editar Categoría')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">

        <div class="flex items-center gap-3">
            <flux:button
                icon="arrow-left"
                variant="ghost"
                href="{{ route('admin.categorias.index') }}"
                wire:navigate
            />
            <flux:heading size="xl">Editar: {{ $categoria->CATEGORIA_NOMBRE }}</flux:heading>
        </div>

        <form method="POST" action="{{ route('admin.categorias.update', $categoria) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.categorias._form', ['categoria' => $categoria])

            <div class="mt-6 flex gap-3">
                <flux:button type="submit" variant="primary">Actualizar</flux:button>
                <flux:button href="{{ route('admin.categorias.index') }}" wire:navigate>Cancelar</flux:button>
            </div>
        </form>

    </div>
</x-layouts::app>