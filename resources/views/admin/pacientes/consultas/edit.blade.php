<x-layouts::app :title="__('Editar consulta')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 p-6">

        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('admin.dashboard') }}" wire:navigate>Panel</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('admin.pacientes.index') }}" wire:navigate>Pacientes</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('admin.pacientes.show', $paciente) }}" wire:navigate>{{ $paciente->nombre_completo }}</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Editar consulta</flux:breadcrumbs.item>
        </flux:breadcrumbs>

        <div>
            <flux:heading size="xl">Editar consulta</flux:heading>
            <flux:text class="text-zinc-400">{{ $paciente->nombre_completo }} · {{ $consulta->FECHA_CONSULTA->format('d/m/Y') }}</flux:text>
        </div>

        <form method="POST" action="{{ route('admin.pacientes.consultas.update', [$paciente, $consulta]) }}" class="max-w-2xl space-y-4">
            @csrf
            @method('PUT')

            <flux:field>
                <flux:label>Fecha <span class="text-red-500">*</span></flux:label>
                <flux:input type="date" name="FECHA_CONSULTA" value="{{ old('FECHA_CONSULTA', $consulta->FECHA_CONSULTA->format('Y-m-d')) }}" required />
                @error('FECHA_CONSULTA') <flux:error>{{ $message }}</flux:error> @enderror
            </flux:field>

            <flux:field>
                <flux:label>Síntomas</flux:label>
                <flux:textarea name="SINTOMAS" rows="4">{{ old('SINTOMAS', $consulta->SINTOMAS) }}</flux:textarea>
                @error('SINTOMAS') <flux:error>{{ $message }}</flux:error> @enderror
            </flux:field>

            <flux:field>
                <flux:label>Tratamiento</flux:label>
                <flux:textarea name="TRATAMIENTO" rows="4">{{ old('TRATAMIENTO', $consulta->TRATAMIENTO) }}</flux:textarea>
                @error('TRATAMIENTO') <flux:error>{{ $message }}</flux:error> @enderror
            </flux:field>

            <flux:field>
                <flux:label>Notas adicionales</flux:label>
                <flux:textarea name="NOTAS" rows="3">{{ old('NOTAS', $consulta->NOTAS) }}</flux:textarea>
                @error('NOTAS') <flux:error>{{ $message }}</flux:error> @enderror
            </flux:field>

            <div class="flex gap-2 pt-2">
                <flux:button type="submit" variant="primary">Actualizar consulta</flux:button>
                <flux:button href="{{ route('admin.pacientes.show', $paciente) }}" variant="ghost" wire:navigate>Cancelar</flux:button>
            </div>
        </form>

    </div>
</x-layouts::app>