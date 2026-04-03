@push('styles')
<style>
    .ck-editor__editable {
        min-height: 400px;
    }
</style>
@endpush
@props(['categoria' => null, 'padre' => null])


<div class="flex flex-col gap-6 max-w-3xl">

    {{-- Nombre --}}
    <flux:field>
        <flux:label>Nombre</flux:label>
        <flux:input
            name="CATEGORIA_NOMBRE"
            value="{{ old('CATEGORIA_NOMBRE', $categoria?->CATEGORIA_NOMBRE) }}"
            required
        />
        <flux:error name="CATEGORIA_NOMBRE" />
    </flux:field>

    {{-- URL --}}
    <flux:field>
        <flux:label>URL (slug)</flux:label>
        <flux:input
            name="URL"
            value="{{ old('URL', $categoria?->URL) }}"
            required
        />
        <flux:error name="URL" />
    </flux:field>

    {{-- Descripción --}}
    <div x-data="editorCKEditor({{ Js::from(old('CATEGORIA_DESCRIPCION', $categoria?->CATEGORIA_DESCRIPCION ?? '')) }})">
        <div x-ref="editorEl"></div>
        <input type="hidden" name="CATEGORIA_DESCRIPCION" x-bind:value="contenido" />
    </div>

    {{-- Imagen --}}
    <flux:field>
        <flux:label>Imagen</flux:label>
        @if($categoria && $categoria->IMAGEN !== 'default.jpg')
            <img
                src="{{ asset('storage/img/categorias/' . $categoria->IMAGEN) }}"
                class="mb-2 h-24 w-24 rounded-lg object-cover"
            />
        @endif
        <flux:input type="file" name="imagen" accept="image/*" />
        <flux:error name="imagen" />
    </flux:field>

    {{-- Tipo --}}
    <flux:field>
        <flux:label>Tipo</flux:label>
        <flux:select name="TIPO">
            @foreach(\App\Models\Publicacion::TIPOS as $tipo)
                <option value="{{ $tipo }}" @selected(old('TIPO', $categoria?->TIPO) === $tipo)>
                    {{ $tipo }}
                </option>
            @endforeach
        </flux:select>
        <flux:error name="TIPO" />
    </flux:field>

    {{-- Visible / Estado --}}
    <div class="grid grid-cols-2 gap-4">
        <flux:field>
            <flux:label>Visibilidad</flux:label>
            <flux:select name="VISIBLE">
                <option value="visible" @selected(old('VISIBLE', $categoria?->VISIBLE) === 'visible')>Visible</option>
                <option value="invisible" @selected(old('VISIBLE', $categoria?->VISIBLE) === 'invisible')>Invisible</option>
            </flux:select>
            <flux:error name="VISIBLE" />
        </flux:field>

        <flux:field>
            <flux:label>Estado</flux:label>
            <flux:select name="ESTADO">
                <option value="activo" @selected(old('ESTADO', $categoria?->ESTADO) === 'activo')>Activo</option>
                <option value="inactivo" @selected(old('ESTADO', $categoria?->ESTADO) === 'inactivo')>Inactivo</option>
            </flux:select>
            <flux:error name="ESTADO" />
        </flux:field>
    </div>

    {{-- Orden --}}
    <flux:field>
        <flux:label>Orden</flux:label>
        <flux:input
            type="number"
            name="ORDEN"
            value="{{ old('ORDEN', $categoria?->ORDEN ?? 0) }}"
        />
        <flux:error name="ORDEN" />
    </flux:field>

    {{-- Campo oculto padre --}}
    @if($padre)
        <input type="hidden" name="CATEGORIA_PADRE" value="{{ $padre->ID_CATEGORIA }}" />
    @elseif($categoria?->CATEGORIA_PADRE)
        <input type="hidden" name="CATEGORIA_PADRE" value="{{ $categoria->CATEGORIA_PADRE }}" />
    @endif

</div>