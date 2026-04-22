<x-layouts::app :title="$paciente->nombre_completo">
    <div class="flex h-full w-full flex-1 flex-col gap-4 p-6">

        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('admin.dashboard') }}" wire:navigate>Panel</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('admin.pacientes.index') }}" wire:navigate>Pacientes</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>{{ $paciente->nombre_completo }}</flux:breadcrumbs.item>
        </flux:breadcrumbs>

        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <flux:heading size="xl">{{ $paciente->nombre_completo }}</flux:heading>
                <flux:text class="text-zinc-400">Expediente del paciente</flux:text>
            </div>
            <div class="flex gap-2">
                <flux:button icon="pencil" variant="ghost" size="sm"
                    href="{{ route('admin.pacientes.edit', $paciente) }}" wire:navigate>
                    Editar datos
                </flux:button>
                <flux:button icon="printer" variant="ghost" size="sm" onclick="window.print()">
                    Imprimir
                </flux:button>
            </div>
        </div>

        @if(session('success'))
            <flux:callout variant="success" icon="check-circle" class="py-2">{{ session('success') }}</flux:callout>
        @endif
        @if(session('error'))
            <flux:callout variant="danger" icon="x-circle" class="py-2">{{ session('error') }}</flux:callout>
        @endif

        {{-- Layout: apilado en mobile, dos columnas en desktop --}}
        <div class="flex flex-col gap-4 md:flex-row md:items-start md:gap-6">

            {{-- Datos del paciente --}}
            <div class="w-full md:w-1/4 md:shrink-0 rounded-xl border border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900"
                x-data="{ abierto: window.innerWidth >= 768 }">

                <button
                    type="button"
                    class="flex w-full items-center justify-between p-5"
                    x-on:click="abierto = !abierto"
                >
                    <flux:heading size="sm">Datos personales</flux:heading>
                    <flux:icon.chevron-down class="size-4 text-zinc-400 transition-transform duration-200" x-bind:class="abierto ? 'rotate-180' : ''" />
                </button>

                <div x-show="abierto" x-collapse class="px-5 pb-5">
                    <div class="space-y-3 text-sm">
                        <div>
                            <span class="block text-xs text-zinc-400">Estado</span>
                            @if($paciente->ESTADO === 'activo')
                                <flux:badge size="sm" color="green">Activo</flux:badge>
                            @else
                                <flux:badge size="sm" color="red">Inactivo</flux:badge>
                            @endif
                        </div>
                        <div>
                            <span class="block text-xs text-zinc-400">Sexo</span>
                            <span class="text-zinc-800 dark:text-white">{{ $paciente->SEXO ? ucfirst($paciente->SEXO) : '—' }}</span>
                        </div>
                        <div>
                            <span class="block text-xs text-zinc-400">Fecha de nacimiento</span>
                            <span class="text-zinc-800 dark:text-white">
                                {{ $paciente->FECHA_NACIMIENTO ? $paciente->FECHA_NACIMIENTO->format('d/m/Y') : '—' }}
                            </span>
                            @if($paciente->FECHA_NACIMIENTO)
                                <span class="block text-xs text-zinc-400">{{ $paciente->edad }} años</span>
                            @endif
                        </div>
                        <div>
                            <span class="block text-xs text-zinc-400">Teléfono</span>
                            <span class="text-zinc-800 dark:text-white">{{ $paciente->TELEFONO ?: '—' }}</span>
                        </div>
                        <div>
                            <span class="block text-xs text-zinc-400">Email</span>
                            <span class="break-all text-zinc-800 dark:text-white">{{ $paciente->EMAIL ?: '—' }}</span>
                        </div>
                        <div>
                            <span class="block text-xs text-zinc-400">Dirección</span>
                            <span class="text-zinc-800 dark:text-white">{{ $paciente->DIRECCION ?: '—' }}</span>
                        </div>
                        @if($paciente->NOTAS)
                            <div>
                                <span class="block text-xs text-zinc-400">Notas internas</span>
                                <span class="text-zinc-800 dark:text-white">{{ $paciente->NOTAS }}</span>
                            </div>
                        @endif
                        <div>
                            <span class="block text-xs text-zinc-400">Registro</span>
                            <span class="text-zinc-800 dark:text-white">{{ $paciente->FECHA_REGISTRO->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        
            {{-- Consultas --}}
            <div class="flex-1 min-w-0 flex flex-col gap-4">

                <div class="flex items-center justify-between">
                    <flux:heading size="lg">
                        Consultas
                        <flux:badge size="sm" variant="outline" class="ml-2">{{ $paciente->consultas->count() }}</flux:badge>
                    </flux:heading>
                    <flux:modal.trigger name="nueva-consulta">
                        <flux:button icon="plus" variant="primary" size="sm">Nueva consulta</flux:button>
                    </flux:modal.trigger>
                </div>

                @forelse($paciente->consultas as $consulta)
                    <div class="relative flex gap-4">
                        {{-- Línea y punto (solo desktop) --}}
                        <div class="hidden md:flex flex-col items-center">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-violet-100 dark:bg-violet-900/40">
                                <flux:icon.calendar-days class="size-4 text-violet-500" />
                            </div>
                            @if(!$loop->last)
                                <div class="mt-1 w-px flex-1 bg-zinc-200 dark:bg-zinc-700"></div>
                            @endif
                        </div>

                        {{-- Tarjeta de consulta --}}
                        <div class="mb-4 flex-1 rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-900">

                            {{-- Fecha + acciones --}}
                            <div class="mb-3 flex items-center justify-between gap-2">
                                <div class="flex items-center gap-2">
                                    {{-- Ícono solo en mobile --}}
                                    <div class="flex md:hidden h-7 w-7 shrink-0 items-center justify-center rounded-full bg-violet-100 dark:bg-violet-900/40">
                                        <flux:icon.calendar-days class="size-3.5 text-violet-500" />
                                    </div>
                                    <span class="text-sm font-semibold text-zinc-800 dark:text-white">
                                        {{ $consulta->FECHA_CONSULTA->format('d/m/Y') }}
                                    </span>
                                </div>
                                <div class="flex gap-1 shrink-0">
                                    <flux:button size="sm" variant="ghost" icon="pencil"
                                        href="{{ route('admin.pacientes.consultas.edit', [$paciente, $consulta]) }}"
                                        wire:navigate title="Editar" />
                                    <form method="POST" action="{{ route('admin.pacientes.consultas.destroy', [$paciente, $consulta]) }}">
                                        @csrf
                                        @method('DELETE')
                                        <flux:button size="sm" variant="ghost" icon="trash" type="submit"
                                            class="text-red-400 hover:text-red-600"
                                            onclick="return confirm('¿Eliminar esta consulta?')" title="Eliminar" />
                                    </form>
                                </div>
                            </div>

                            <div class="space-y-3 text-sm">
                                @if($consulta->SINTOMAS)
                                    <div>
                                        <span class="text-xs font-medium uppercase tracking-wide text-zinc-400">Síntomas</span>
                                        <p class="mt-0.5 text-zinc-700 dark:text-zinc-300">{{ $consulta->SINTOMAS }}</p>
                                    </div>
                                @endif
                                @if($consulta->TRATAMIENTO)
                                    <div>
                                        <span class="text-xs font-medium uppercase tracking-wide text-zinc-400">Tratamiento</span>
                                        <p class="mt-0.5 text-zinc-700 dark:text-zinc-300">{{ $consulta->TRATAMIENTO }}</p>
                                    </div>
                                @endif
                                @if($consulta->NOTAS)
                                    <div>
                                        <span class="text-xs font-medium uppercase tracking-wide text-zinc-400">Notas</span>
                                        <p class="mt-0.5 text-zinc-700 dark:text-zinc-300">{{ $consulta->NOTAS }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-xl border border-zinc-200 px-4 py-10 text-center text-zinc-400 dark:border-zinc-700">
                        No hay consultas registradas aún.
                    </div>
                @endforelse

            </div>
        </div>

    </div>

    {{-- Modal nueva consulta --}}
    <flux:modal name="nueva-consulta" class="max-w-lg">
        <flux:heading size="lg" class="mb-4">Nueva consulta</flux:heading>

        <form method="POST" action="{{ route('admin.pacientes.consultas.store', $paciente) }}" class="space-y-4">
            @csrf

            <flux:field>
                <flux:label>Fecha <span class="text-red-500">*</span></flux:label>
                <flux:input type="date" name="FECHA_CONSULTA" value="{{ old('FECHA_CONSULTA', now()->format('Y-m-d')) }}" required />
                @error('FECHA_CONSULTA') <flux:error>{{ $message }}</flux:error> @enderror
            </flux:field>

            <flux:field>
                <flux:label>Síntomas</flux:label>
                <flux:textarea name="SINTOMAS" rows="3">{{ old('SINTOMAS') }}</flux:textarea>
                @error('SINTOMAS') <flux:error>{{ $message }}</flux:error> @enderror
            </flux:field>

            <flux:field>
                <flux:label>Tratamiento</flux:label>
                <flux:textarea name="TRATAMIENTO" rows="3">{{ old('TRATAMIENTO') }}</flux:textarea>
                @error('TRATAMIENTO') <flux:error>{{ $message }}</flux:error> @enderror
            </flux:field>

            <flux:field>
                <flux:label>Notas adicionales</flux:label>
                <flux:textarea name="NOTAS" rows="2">{{ old('NOTAS') }}</flux:textarea>
                @error('NOTAS') <flux:error>{{ $message }}</flux:error> @enderror
            </flux:field>

            <div class="flex gap-2 pt-1">
                <flux:button type="submit" variant="primary">Guardar consulta</flux:button>
                <flux:modal.close>
                    <flux:button variant="ghost">Cancelar</flux:button>
                </flux:modal.close>
            </div>
        </form>
    </flux:modal>

    <style>
    @media print {
        nav, aside, [data-flux-sidebar], flux-sidebar, .flux-sidebar,
        header, footer, [data-flux-button], [data-flux-breadcrumbs],
        [data-flux-modal], dialog, .flex.items-center.justify-between > [data-flux-button] {
            display: none !important;
        }
        body { font-size: 10pt; color: #000 !important; background: #fff !important; }
        .flex.h-full.w-full { padding: 0 !important; gap: 0.5rem !important; }
        .flex.gap-6.items-start { display: block !important; }
        .w-1\/4 { width: 100% !important; padding: 0.5rem 0.75rem !important; margin-bottom: 0.75rem; border: 1px solid #ccc !important; border-radius: 6px; }
        .w-1\/4 [class*="space-y"] { display: flex !important; flex-wrap: wrap !important; gap: 0.25rem 1.5rem !important; }
        .w-1\/4 [class*="space-y"] > div { min-width: 140px; }
        .flex-1.min-w-0 { width: 100% !important; }
        .flex-1.min-w-0 > .flex.items-center.justify-between { display: none !important; }
        .flex.flex-col.items-center { display: none !important; }
        .mb-6.flex-1 .flex.gap-1 { display: none !important; }
        .space-y-3 .text-xs.font-medium { font-size: 7pt !important; color: #555 !important; }
        .space-y-3 p { font-size: 9pt !important; margin: 0 0 0.2rem 0 !important; color: #000 !important; }
        @page { margin: 1.5cm; }
    }
    </style>

</x-layouts::app>