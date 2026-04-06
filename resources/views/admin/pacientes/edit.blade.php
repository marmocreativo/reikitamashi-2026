<x-layouts::app :title="__('Editar paciente')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 p-6">

        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('admin.dashboard') }}" wire:navigate>Panel</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('admin.pacientes.index') }}" wire:navigate>Pacientes</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('admin.pacientes.show', $paciente) }}" wire:navigate>{{ $paciente->nombre_completo }}</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Editar</flux:breadcrumbs.item>
        </flux:breadcrumbs>

        <div>
            <flux:heading size="xl">Editar paciente</flux:heading>
            <flux:text class="text-zinc-400">{{ $paciente->nombre_completo }}</flux:text>
        </div>

        <form method="POST" action="{{ route('admin.pacientes.update', $paciente) }}" class="max-w-2xl space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <flux:field>
                    <flux:label>Nombre <span class="text-red-500">*</span></flux:label>
                    <flux:input name="NOMBRE" value="{{ old('NOMBRE', $paciente->NOMBRE) }}" required />
                    @error('NOMBRE') <flux:error>{{ $message }}</flux:error> @enderror
                </flux:field>

                <flux:field>
                    <flux:label>Apellidos <span class="text-red-500">*</span></flux:label>
                    <flux:input name="APELLIDOS" value="{{ old('APELLIDOS', $paciente->APELLIDOS) }}" required />
                    @error('APELLIDOS') <flux:error>{{ $message }}</flux:error> @enderror
                </flux:field>

                <flux:field>
                    <flux:label>Fecha de nacimiento</flux:label>
                    <flux:input type="date" name="FECHA_NACIMIENTO" value="{{ old('FECHA_NACIMIENTO', $paciente->FECHA_NACIMIENTO?->format('Y-m-d')) }}" />
                    @error('FECHA_NACIMIENTO') <flux:error>{{ $message }}</flux:error> @enderror
                </flux:field>

                <flux:field>
                    <flux:label>Sexo</flux:label>
                    <flux:select name="SEXO">
                        <option value="">— Seleccionar —</option>
                        <option value="femenino" @selected(old('SEXO', $paciente->SEXO) === 'femenino')>Femenino</option>
                        <option value="masculino" @selected(old('SEXO', $paciente->SEXO) === 'masculino')>Masculino</option>
                        <option value="otro" @selected(old('SEXO', $paciente->SEXO) === 'otro')>Otro</option>
                    </flux:select>
                    @error('SEXO') <flux:error>{{ $message }}</flux:error> @enderror
                </flux:field>

                <flux:field>
                    <flux:label>Teléfono</flux:label>
                    <flux:input name="TELEFONO" value="{{ old('TELEFONO', $paciente->TELEFONO) }}" />
                    @error('TELEFONO') <flux:error>{{ $message }}</flux:error> @enderror
                </flux:field>

                <flux:field>
                    <flux:label>Email</flux:label>
                    <flux:input type="email" name="EMAIL" value="{{ old('EMAIL', $paciente->EMAIL) }}" />
                    @error('EMAIL') <flux:error>{{ $message }}</flux:error> @enderror
                </flux:field>

                <flux:field class="sm:col-span-2">
                    <flux:label>Dirección</flux:label>
                    <flux:textarea name="DIRECCION" rows="2">{{ old('DIRECCION', $paciente->DIRECCION) }}</flux:textarea>
                    @error('DIRECCION') <flux:error>{{ $message }}</flux:error> @enderror
                </flux:field>

                <flux:field class="sm:col-span-2">
                    <flux:label>Notas internas</flux:label>
                    <flux:textarea name="NOTAS" rows="3">{{ old('NOTAS', $paciente->NOTAS) }}</flux:textarea>
                    @error('NOTAS') <flux:error>{{ $message }}</flux:error> @enderror
                </flux:field>

                <flux:field>
                    <flux:label>Estado <span class="text-red-500">*</span></flux:label>
                    <flux:select name="ESTADO">
                        <option value="activo" @selected(old('ESTADO', $paciente->ESTADO) === 'activo')>Activo</option>
                        <option value="inactivo" @selected(old('ESTADO', $paciente->ESTADO) === 'inactivo')>Inactivo</option>
                    </flux:select>
                    @error('ESTADO') <flux:error>{{ $message }}</flux:error> @enderror
                </flux:field>
            </div>

            <div class="flex gap-2 pt-2">
                <flux:button type="submit" variant="primary">Actualizar paciente</flux:button>
                <flux:button href="{{ route('admin.pacientes.show', $paciente) }}" variant="ghost" wire:navigate>Cancelar</flux:button>
            </div>
        </form>

    </div>
</x-layouts::app>