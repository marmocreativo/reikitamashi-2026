<x-layouts::app :title="__('Categorías')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 p-6">

        {{-- Encabezado --}}
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                @isset($padre)
                    <flux:button
                        icon="arrow-left"
                        variant="ghost"
                        href="{{ route('admin.categorias.index') }}"
                        wire:navigate
                    />
                    <div>
                        <flux:heading size="xl">{{ $padre->CATEGORIA_NOMBRE }}</flux:heading>
                        <flux:text class="text-zinc-400">Subcategorías</flux:text>
                    </div>
                @else
                    <flux:heading size="xl">Categorías</flux:heading>
                @endisset
            </div>

            <flux:button
                icon="plus"
                variant="primary"
                href="{{ route('admin.categorias.create', isset($padre) ? ['padre' => $padre->ID_CATEGORIA] : []) }}"
                wire:navigate
            >
                Nueva categoría
            </flux:button>
        </div>

        {{-- Filtro por tipo --}}
        <div class="flex flex-wrap gap-2">
            <flux:button
                href="{{ route('admin.categorias.index') }}"
                variant="{{ !$tipo ? 'primary' : 'ghost' }}"
                size="sm"
                wire:navigate
            >
                Todos
            </flux:button>

            @foreach(\App\Models\Publicacion::TIPOS as $t)
                <flux:button
                    href="{{ route('admin.categorias.index', ['tipo' => $t]) }}"
                    variant="{{ $tipo === $t ? 'primary' : 'ghost' }}"
                    size="sm"
                    wire:navigate
                >
                    {{ $t }}
                </flux:button>
            @endforeach
        </div>

        {{-- Mensajes --}}
        @if(session('success'))
            <flux:callout variant="success" icon="check-circle">
                {{ session('success') }}
            </flux:callout>
        @endif

        @if(session('error'))
            <flux:callout variant="danger" icon="x-circle">
                {{ session('error') }}
            </flux:callout>
        @endif

        {{-- Tabla --}}
        <div class="overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
            <table class="w-full text-sm">
                <thead class="bg-zinc-50 text-left text-xs uppercase tracking-wider text-zinc-500 dark:bg-zinc-800 dark:text-zinc-400">
                    <tr>
                        <th class="px-4 py-3">Imagen</th>
                        <th class="px-4 py-3">Nombre</th>
                        <th class="px-4 py-3">Tipo</th>
                        <th class="px-4 py-3">Visible</th>
                        <th class="px-4 py-3">Estado</th>
                        <th class="px-4 py-3">Orden</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 bg-white dark:divide-zinc-700 dark:bg-zinc-900">
                    @forelse ($categorias as $categoria)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800">
                            <td class="px-4 py-3">
                                <img
                                    src="{{ asset('storage/img/categorias/' . $categoria->IMAGEN) }}"
                                    alt="{{ $categoria->CATEGORIA_NOMBRE }}"
                                    class="h-12 w-12 rounded-lg object-cover"
                                />
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-col">
                                    <span class="font-medium text-zinc-800 dark:text-white">
                                        {{ $categoria->CATEGORIA_NOMBRE }}
                                    </span>
                                    <span class="text-xs text-zinc-400">/{{ $categoria->URL }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <flux:badge variant="outline">{{ $categoria->TIPO }}</flux:badge>
                            </td>
                            <td class="px-4 py-3">
                                @if($categoria->VISIBLE === 'visible')
                                    <flux:badge color="green">Visible</flux:badge>
                                @else
                                    <flux:badge color="zinc">Invisible</flux:badge>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($categoria->ESTADO === 'activo')
                                    <flux:badge color="green">Activo</flux:badge>
                                @else
                                    <flux:badge color="red">Inactivo</flux:badge>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-zinc-600 dark:text-zinc-300">
                                {{ $categoria->ORDEN }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    @if($categoria->hijas_count > 0)
                                        <flux:button
                                            size="sm"
                                            variant="ghost"
                                            icon="folder-open"
                                            href="{{ route('admin.categorias.hijas', $categoria) }}"
                                            wire:navigate
                                        >
                                            {{ $categoria->hijas_count }}
                                        </flux:button>
                                    @endif

                                    <flux:button
                                        size="sm"
                                        variant="ghost"
                                        icon="pencil"
                                        href="{{ route('admin.categorias.edit', $categoria) }}"
                                        wire:navigate
                                    />

                                    <form method="POST" action="{{ route('admin.categorias.destroy', $categoria) }}">
                                        @csrf
                                        @method('DELETE')
                                        <flux:button
                                            size="sm"
                                            variant="ghost"
                                            icon="trash"
                                            type="submit"
                                            class="text-red-500 hover:text-red-700"
                                            onclick="return confirm('¿Eliminar esta categoría?')"
                                        />
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-10 text-center text-zinc-400">
                                No hay categorías registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</x-layouts::app>