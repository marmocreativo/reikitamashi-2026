<x-layouts::app :title="__('Pacientes')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 p-6">

        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('admin.dashboard') }}" wire:navigate>Panel</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Pacientes</flux:breadcrumbs.item>
        </flux:breadcrumbs>

        <div>
            <flux:heading size="xl">Pacientes</flux:heading>
            <flux:text class="text-zinc-400">Gestiona los expedientes de pacientes</flux:text>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-3">
            <form method="GET" action="{{ route('admin.pacientes.index') }}" class="flex flex-wrap gap-2">
                <flux:input
                    name="buscar"
                    value="{{ $buscar }}"
                    placeholder="Buscar por nombre, email o teléfono…"
                    size="sm"
                />
                <flux:button
                    href="{{ route('admin.pacientes.index', array_filter(['buscar' => $buscar, 'estado' => 'activo'])) }}"
                    variant="{{ $estado === 'activo' ? 'primary' : 'ghost' }}"
                    size="sm"
                    wire:navigate
                >
                    Activos
                </flux:button>
                <flux:button
                    href="{{ route('admin.pacientes.index', array_filter(['buscar' => $buscar, 'estado' => 'inactivo'])) }}"
                    variant="{{ $estado === 'inactivo' ? 'primary' : 'ghost' }}"
                    size="sm"
                    wire:navigate
                >
                    Inactivos
                </flux:button>
                @if($buscar || $estado)
                    <flux:button href="{{ route('admin.pacientes.index') }}" variant="ghost" size="sm" wire:navigate>
                        Limpiar
                    </flux:button>
                @endif
            </form>

            <flux:button icon="plus" variant="primary" size="sm" href="{{ route('admin.pacientes.create') }}" wire:navigate>
                Nuevo paciente
            </flux:button>
        </div>

        @if(session('success'))
            <flux:callout variant="success" icon="check-circle" class="py-2">{{ session('success') }}</flux:callout>
        @endif

        @if(session('error'))
            <flux:callout variant="danger" icon="x-circle" class="py-2">{{ session('error') }}</flux:callout>
        @endif

        <div class="overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
            <table class="w-full text-sm">
                <thead class="bg-zinc-50 text-left text-xs uppercase tracking-wider text-zinc-500 dark:bg-zinc-800 dark:text-zinc-400">
                    <tr>
                        <th class="px-3 py-2">Paciente</th>
                        <th class="px-3 py-2">Contacto</th>
                        <th class="px-3 py-2 text-center">Consultas</th>
                        <th class="px-3 py-2 text-center">Estado</th>
                        <th class="px-3 py-2 text-center">Registro</th>
                        <th class="px-3 py-2"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 bg-white dark:divide-zinc-700/60 dark:bg-zinc-900">
                    @forelse ($pacientes as $paciente)
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">

                            <td class="px-3 py-2">
                                <span class="block font-medium leading-tight text-zinc-800 dark:text-white">
                                    {{ $paciente->nombre_completo }}
                                </span>
                                @if($paciente->FECHA_NACIMIENTO)
                                    <span class="text-xs text-zinc-400">{{ $paciente->edad }} años</span>
                                @endif
                            </td>

                            <td class="px-3 py-2 text-zinc-500 dark:text-zinc-400">
                                @if($paciente->TELEFONO)
                                    <span class="block text-xs">{{ $paciente->TELEFONO }}</span>
                                @endif
                                @if($paciente->EMAIL)
                                    <span class="block text-xs">{{ $paciente->EMAIL }}</span>
                                @endif
                            </td>

                            <td class="px-3 py-2 text-center">
                                <flux:badge size="sm" variant="outline">{{ $paciente->consultas_count }}</flux:badge>
                            </td>

                            <td class="px-3 py-2 text-center">
                                @if($paciente->ESTADO === 'activo')
                                    <flux:badge size="sm" color="green">Activo</flux:badge>
                                @else
                                    <flux:badge size="sm" color="red">Inactivo</flux:badge>
                                @endif
                            </td>

                            <td class="px-3 py-2 text-center text-xs text-zinc-400">
                                {{ $paciente->FECHA_REGISTRO->format('d/m/Y') }}
                            </td>

                            <td class="px-3 py-2">
                                <div class="flex items-center justify-end gap-1">
                                    <flux:button size="sm" variant="ghost" icon="eye"
                                        href="{{ route('admin.pacientes.show', $paciente) }}" wire:navigate title="Ver expediente" />
                                    <flux:button size="sm" variant="ghost" icon="pencil"
                                        href="{{ route('admin.pacientes.edit', $paciente) }}" wire:navigate title="Editar" />
                                    <form method="POST" action="{{ route('admin.pacientes.destroy', $paciente) }}">
                                        @csrf
                                        @method('DELETE')
                                        <flux:button size="sm" variant="ghost" icon="trash" type="submit"
                                            class="text-red-400 hover:text-red-600"
                                            onclick="return confirm('¿Eliminar este paciente y todas sus consultas?')" title="Eliminar" />
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-zinc-400">
                                No hay pacientes registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pacientes->hasPages())
            <div>{{ $pacientes->links() }}</div>
        @endif

    </div>
</x-layouts::app>