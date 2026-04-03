<x-layouts::app :title="__('Nueva Categoría')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">

        <div class="flex items-center gap-3">
            <flux:button
                icon="arrow-left"
                variant="ghost"
                href="{{ route('admin.categorias.index') }}"
                wire:navigate
            />
            <flux:heading size="xl">
                Nueva categoría
                @isset($padre)
                    <span class="text-zinc-400"> en {{ $padre->CATEGORIA_NOMBRE }}</span>
                @endisset
            </flux:heading>
        </div>

        <form method="POST" action="{{ route('admin.categorias.store') }}" enctype="multipart/form-data">
            @csrf
            @include('admin.categorias._form', ['padre' => $padre ?? null])

            <div class="mt-6 flex gap-3">
                <flux:button type="submit" variant="primary">Guardar</flux:button>
                <flux:button href="{{ route('admin.categorias.index') }}" wire:navigate>Cancelar</flux:button>
            </div>
        </form>

    </div>
</x-layouts::app>