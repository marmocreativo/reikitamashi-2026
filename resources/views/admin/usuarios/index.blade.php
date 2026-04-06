<x-layouts::app :title="__('Usuarios')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 p-6">

        {{-- Breadcrumbs --}}
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('admin.dashboard') }}" wire:navigate>
                Panel
            </flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Usuarios</flux:breadcrumbs.item>
        </flux:breadcrumbs>

        {{-- Encabezado --}}
        <div>
            <flux:heading size="xl">Usuarios</flux:heading>
            <flux:text class="text-zinc-400">Gestiona los usuarios con acceso al panel</flux:text>
        </div>

        {{-- Barra de herramientas --}}
        <div class="flex items-center justify-end">
            <flux:button
                icon="plus"
                variant="primary"
                size="sm"
                href="{{ route('admin.usuarios.create') }}"
                wire:navigate
            >
                Nuevo usuario
            </flux:button>
        </div>

        {{-- Mensajes --}}
        @if(session('success'))
            <flux:callout variant="success" icon="check-circle" class="py-2">
                {{ session('success') }}
            </flux:callout>
        @endif

        @if(session('error'))
            <flux:callout variant="danger" icon="x-circle" class="py-2">
                {{ session('error') }}
            </flux:callout>
        @endif

        {{-- Tabla --}}
        <div class="overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
            <table class="w-full text-sm">
                <thead class="bg-zinc-50 text-left text-xs uppercase tracking-wider text-zinc-500 dark:bg-zinc-800 dark:text-zinc-400">
                    <tr>
                        <th class="px-3 py-2">Nombre</th>
                        <th class="px-3 py-2">Correo electrónico</th>
                        <th class="px-3 py-2 text-center">Verificado</th>
                        <th class="px-3 py-2 text-center">Registrado</th>
                        <th class="px-3 py-2"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 bg-white dark:divide-zinc-700/60 dark:bg-zinc-900">
                    @forelse ($usuarios as $usuario)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">

                            {{-- Nombre --}}
                            <td class="px-3 py-2">
                                <div class="flex items-center gap-2">
                                    <flux:avatar size="sm" name="{{ $usuario->name }}" />
                                    <span class="font-medium text-zinc-800 dark:text-white">
                                        {{ $usuario->name }}
                                    </span>
                                    @if($usuario->id === auth()->id())
                                        <flux:badge size="sm" color="blue">Tú</flux:badge>
                                    @endif
                                </div>
                            </td>

                            {{-- Email --}}
                            <td class="px-3 py-2 text-zinc-500 dark:text-zinc-400">
                                {{ $usuario->email }}
                            </td>

                            {{-- Verificado --}}
                            <td class="px-3 py-2 text-center">
                                @if($usuario->email_verified_at)
                                    <flux:badge size="sm" color="green">Sí</flux:badge>
                                @else
                                    <flux:badge size="sm" color="zinc">No</flux:badge>
                                @endif
                            </td>

                            {{-- Fecha registro --}}
                            <td class="px-3 py-2 text-center text-xs text-zinc-500 dark:text-zinc-400">
                                {{ $usuario->created_at?->format('d/m/Y') ?? '—' }}
                            </td>

                            {{-- Acciones --}}
                            <td class="px-3 py-2">
                                <div class="flex items-center justify-end gap-1">

                                    <flux:button
                                        size="sm"
                                        variant="ghost"
                                        icon="pencil"
                                        href="{{ route('admin.usuarios.edit', $usuario) }}"
                                        wire:navigate
                                        title="Editar"
                                    />

                                    @if($usuario->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.usuarios.destroy', $usuario) }}">
                                            @csrf
                                            @method('DELETE')
                                            <flux:button
                                                size="sm"
                                                variant="ghost"
                                                icon="trash"
                                                type="submit"
                                                class="text-red-400 hover:text-red-600"
                                                onclick="return confirm('¿Eliminar este usuario?')"
                                                title="Eliminar"
                                            />
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center text-zinc-400">
                                No hay usuarios registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</x-layouts::app>