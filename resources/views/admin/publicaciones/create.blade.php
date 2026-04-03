<x-layouts::app>
    <div class="flex items-center gap-4 mb-6">
        <flux:button href="{{ route('admin.publicaciones.index') }}" variant="ghost" icon="arrow-left" />
        <flux:heading size="xl">Nueva publicación</flux:heading>
    </div>

    @include('admin.publicaciones._form', [
        'action' => route('admin.publicaciones.store'),
        'method' => 'POST',
        'publicacion' => null,
        'tipos' => $tipos,
    ])
</x-layouts::app>