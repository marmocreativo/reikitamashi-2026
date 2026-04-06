<x-layouts::app :title="__('Nuevo usuario')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 p-6">

        {{-- Breadcrumbs --}}
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('admin.dashboard') }}" wire:navigate>
                Panel
            </flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('admin.usuarios.index') }}" wire:navigate>
                Usuarios
            </flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Nuevo usuario</flux:breadcrumbs.item>
        </flux:breadcrumbs>

        {{-- Encabezado --}}
        <div>
            <flux:heading size="xl">Nuevo usuario</flux:heading>
            <flux:text class="text-zinc-400">Crea una cuenta con acceso al panel de administración</flux:text>
        </div>

        {{-- Formulario --}}
        <form method="POST" action="{{ route('admin.usuarios.store') }}" class="max-w-lg">
            @csrf

            <div class="flex flex-col gap-4">

                <flux:field>
                    <flux:label>Nombre</flux:label>
                    <flux:input
                        name="name"
                        value="{{ old('name') }}"
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
                        value="{{ old('email') }}"
                        placeholder="correo@ejemplo.com"
                        required
                    />
                    @error('email')
                        <flux:error>{{ $message }}</flux:error>
                    @enderror
                </flux:field>

                <flux:field>
                    <flux:label>Contraseña</flux:label>
                    <flux:input
                        type="password"
                        name="password"
                        placeholder="Mínimo 8 caracteres"
                        required
                    />
                    @error('password')
                        <flux:error>{{ $message }}</flux:error>
                    @enderror
                </flux:field>

                <flux:field>
                    <flux:label>Confirmar contraseña</flux:label>
                    <flux:input
                        type="password"
                        name="password_confirmation"
                        placeholder="Repite la contraseña"
                        required
                    />
                </flux:field>

                <div class="flex gap-2 pt-2">
                    <flux:button type="submit" variant="primary">
                        Crear usuario
                    </flux:button>
                    <flux:button href="{{ route('admin.usuarios.index') }}" variant="ghost" wire:navigate>
                        Cancelar
                    </flux:button>
                </div>

            </div>
        </form>

    </div>
</x-layouts::app>