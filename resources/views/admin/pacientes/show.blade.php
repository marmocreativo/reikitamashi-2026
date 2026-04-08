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
            <flux:button icon="pencil" variant="ghost" size="sm"
                href="{{ route('admin.pacientes.edit', $paciente) }}" wire:navigate>
                Editar datos
            </flux:button>
            <flux:button icon="printer" variant="ghost" size="sm" onclick="window.print()">
                Imprimir
            </flux:button>
        </div>

        @if(session('success'))
            <flux:callout variant="success" icon="check-circle" class="py-2">{{ session('success') }}</flux:callout>
        @endif
        @if(session('error'))
            <flux:callout variant="danger" icon="x-circle" class="py-2">{{ session('error') }}</flux:callout>
        @endif

        {{-- Layout dos columnas --}}
        <div class="flex gap-6 items-start">

            {{-- Columna izquierda: datos del paciente --}}
            <div class="w-1/4 shrink-0 rounded-xl border border-zinc-200 bg-white p-5 dark:border-zinc-700 dark:bg-zinc-900">
                <flux:heading size="sm" class="mb-4">Datos personales</flux:heading>

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

            {{-- Columna derecha: consultas --}}
            <div class="flex-1 min-w-0 flex flex-col gap-4">

                {{-- Encabezado + botón --}}
                <div class="flex items-center justify-between">
                    <flux:heading size="lg">
                        Consultas
                        <flux:badge size="sm" variant="outline" class="ml-2">{{ $paciente->consultas->count() }}</flux:badge>
                    </flux:heading>
                    <flux:modal.trigger name="nueva-consulta">
                        <flux:button icon="plus" variant="primary" size="sm">Nueva consulta</flux:button>
                    </flux:modal.trigger>
                </div>

                {{-- Línea de tiempo --}}
                @forelse($paciente->consultas as $consulta)
                    <div class="relative flex gap-4">
                        {{-- Línea y punto --}}
                        <div class="flex flex-col items-center">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-violet-100 dark:bg-violet-900/40">
                                <flux:icon.calendar-days class="size-4 text-violet-500" />
                            </div>
                            @if(!$loop->last)
                                <div class="mt-1 w-px flex-1 bg-zinc-200 dark:bg-zinc-700"></div>
                            @endif
                        </div>

                        {{-- Contenido --}}
                        <div class="mb-6 flex-1 rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-900">
                            <div class="mb-3 flex items-center justify-between">
                                <span class="text-sm font-semibold text-zinc-800 dark:text-white">
                                    {{ $consulta->FECHA_CONSULTA->format('d/m/Y') }}
                                </span>
                                <div class="flex gap-1">
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
    /* Ocultar elementos de UI */
    nav, aside, [data-flux-sidebar], flux-sidebar, .flux-sidebar,
    header, footer, [data-flux-button], [data-flux-breadcrumbs],
    [data-flux-modal], dialog, .flex.items-center.justify-between > [data-flux-button] {
        display: none !important;
    }

    body {
        font-size: 10pt;
        color: #000 !important;
        background: #fff !important;
    }

    .flex.h-full.w-full {
        padding: 0 !important;
        gap: 0.5rem !important;
    }

    /* Layout una columna */
    .flex.gap-6.items-start {
        display: block !important;
    }

    /* ── Datos personales: fila horizontal compacta ── */
    .w-1\/4 {
        width: 100% !important;
        padding: 0.5rem 0.75rem !important;
        margin-bottom: 0.75rem;
        border: 1px solid #ccc !important;
        border-radius: 6px;
    }

    .w-1\/4 [class*="space-y"] {
        display: flex !important;
        flex-wrap: wrap !important;
        gap: 0.25rem 1.5rem !important;
    }

    .w-1\/4 [class*="space-y"] > div {
        min-width: 140px;
    }

    .w-1\/4 flux\:heading,
    .w-1\/4 [data-flux-heading] {
        font-size: 8pt !important;
        margin-bottom: 0.35rem !important;
    }

    .w-1\/4 span.text-xs {
        font-size: 7pt !important;
        color: #666 !important;
    }

    .w-1\/4 span.text-zinc-800 {
        font-size: 9pt !important;
    }

    /* ── Consultas: tabla compacta ── */
    .flex-1.min-w-0 {
        width: 100% !important;
    }

    /* Ocultar encabezado "Consultas + botón nueva" */
    .flex-1.min-w-0 > .flex.items-center.justify-between {
        display: none !important;
    }

    /* Ocultar línea de tiempo */
    .flex.flex-col.items-center {
        display: none !important;
    }

    /* Convertir timeline en tabla */
    .flex-1.min-w-0 > .relative.flex.gap-4 {
        display: table-row !important;
    }

    .flex-1.min-w-0 {
        display: table !important;
        width: 100% !important;
        border-collapse: collapse;
    }

    /* Cada tarjeta de consulta como fila de tabla */
    .mb-6.flex-1.rounded-xl {
        display: table-cell !important;
        border: none !important;
        border-bottom: 1px solid #ddd !important;
        padding: 0.4rem 0.5rem !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        background: transparent !important;
    }

    /* Fecha en negrita, pequeña */
    .mb-6.flex-1 .flex.items-center.justify-between {
        margin-bottom: 0.2rem !important;
    }

    .mb-6.flex-1 .text-sm.font-semibold {
        font-size: 9pt !important;
        font-weight: bold;
    }

    /* Ocultar botones editar/eliminar dentro de consultas */
    .mb-6.flex-1 .flex.gap-1 {
        display: none !important;
    }

    /* Etiquetas de sección (Síntomas, Tratamiento, Notas) */
    .space-y-3 .text-xs.font-medium {
        font-size: 7pt !important;
        color: #555 !important;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .space-y-3 p {
        font-size: 9pt !important;
        margin: 0 0 0.2rem 0 !important;
        color: #000 !important;
    }

    .space-y-3 {
        gap: 0.2rem !important;
    }

    /* Encabezado de tabla simulado */
    .flex-1.min-w-0::before {
        content: "Fecha  |  Síntomas / Tratamiento / Notas";
        display: table-caption !important;
        font-size: 8pt;
        font-weight: bold;
        text-align: left;
        padding: 0.3rem 0.5rem;
        background: #f0f0f0;
        border: 1px solid #ccc;
        border-bottom: 2px solid #999;
        caption-side: top;
    }

    @page {
        margin: 1.5cm;
    }
}
</style>
</x-layouts::app>