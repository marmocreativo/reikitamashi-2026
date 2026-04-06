<x-layouts::app :title="__('Editar usuario')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 p-6">

        {{-- Breadcrumbs --}}
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('admin.dashboard') }}" wire:navigate>
                Panel
            </flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('admin.usuarios.index') }}" wire:navigate>
                Usuarios
            </flux:breadcrumbs.item>
            <flux:breadcrumbs.item>{{ $usuario->name }}</flux:breadcrumbs.item>
        </flux:breadcrumbs>

        {{-- Encabezado --}}
        <div>
            <flux:heading size="xl">Editar usuario</flux:heading>
            <flux:text class="text-zinc-400">Modifica los datos de acceso. Deja la contraseña en blanco para no cambiarla.</flux:text>
        </div>

        {{-- Mensajes --}}
        @if(session('success'))
            <flux:callout variant="success" icon="check-circle" class="py-2">
                {{ session('success') }}
            </flux:callout>
        @endif

        {{-- Formulario --}}
        <form method="POST" action="{{ route('admin.usuarios.update', $usuario) }}" class="max-w-lg">
            @csrf
            @method('PUT')

            <div class="flex flex-col gap-4">

                <flux:field>
                    <flux:label>Nombre</flux:label>
                    <flux:input
                        name="name"
                        value="{{ old('name', $usuario->name) }}"
                        placeholder="Nombre completo"
                        required
                        autofocus
                    />
                    @error('name')
                        <flux:error>{{ $message }}</flux:error>
                    @enderror
                </flux:field>

                <flux:field>
                    <flux:label>Correo electrónico</flux:label>
                    <flux:input
                        type="email"
                        name="email"
                        value="{{ old('email', $usuario->email) }}"
                        placeholder="correo@ejemplo.com"
                        required
                    />
                    @error('email')
                        <flux:error>{{ $message }}</flux:error>
                    @enderror
                </flux:field>

                <flux:separator />

                <flux:field>
                    <flux:label>Nueva contraseña <span class="text-zinc-400 text-xs font-normal">(opcional)</span></flux:label>
                    <flux:input
                        type="password"
                        name="password"
                        placeholder="Dejar en blanco para no cambiar"
                    />
                    @error('password')
                        <flux:error>{{ $message }}</flux:error>
                    @enderror
                </flux:field>

                <flux:field>
                    <flux:label>Confirmar nueva contraseña</flux:label>
                    <flux:input
                        type="password"
                        name="password_confirmation"
                        placeholder="Repite la nueva contraseña"
                    />
                </flux:field>

                <div class="flex gap-2 pt-2">
                    <flux:button type="submit" variant="primary">
                        Guardar cambios
                    </flux:button>
                    <flux:button href="{{ route('admin.usuarios.index') }}" variant="ghost" wire:navigate>
                        Cancelar
                    </flux:button>
                </div>

            </div>
        </form>

    </div>
</x-layouts::app>