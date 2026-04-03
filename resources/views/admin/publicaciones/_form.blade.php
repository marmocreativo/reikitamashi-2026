@push('styles')
<style>
    .ck-editor__editable {
        min-height: 400px;
    }
</style>
@endpush
<form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="space-y-6 max-w-3xl">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    @if ($errors->any())
        <flux:callout variant="danger">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </flux:callout>
    @endif

    {{-- Título --}}
    <flux:field>
        <flux:label>Título <span class="text-red-500">*</span></flux:label>
        <flux:input name="PUBLICACION_TITULO"
                    value="{{ old('PUBLICACION_TITULO', $publicacion?->PUBLICACION_TITULO) }}"
                    required />
        <flux:error name="PUBLICACION_TITULO" />
    </flux:field>

    {{-- URL / Slug --}}
    <flux:field>
        <flux:label>URL (slug)</flux:label>
        <flux:input name="URL"
                    value="{{ old('URL', $publicacion?->URL) }}"
                    placeholder="mi-publicacion" />
        <flux:description>Sin espacios ni caracteres especiales. Se usa en la URL pública.</flux:description>
        <flux:error name="URL" />
    </flux:field>

    {{-- Tipo y Estado en la misma fila --}}
    <div class="grid grid-cols-2 gap-4">
        <flux:field>
            <flux:label>Tipo <span class="text-red-500">*</span></flux:label>
            <flux:select name="TIPO">
                @foreach ($tipos as $t)
                    <flux:select.option value="{{ $t }}"
                        :selected="old('TIPO', $publicacion?->TIPO) === $t">
                        {{ $t }}
                    </flux:select.option>
                @endforeach
            </flux:select>
            <flux:error name="TIPO" />
        </flux:field>

        <flux:field>
            <flux:label>Estado <span class="text-red-500">*</span></flux:label>
            <flux:select name="ESTADO">
                <flux:select.option value="activo"   :selected="old('ESTADO', $publicacion?->ESTADO ?? 'activo') === 'activo'">Activo</flux:select.option>
                <flux:select.option value="inactivo" :selected="old('ESTADO', $publicacion?->ESTADO) === 'inactivo'">Inactivo</flux:select.option>
            </flux:select>
            <flux:error name="ESTADO" />
        </flux:field>
    </div>

    {{-- Orden y Fecha publicación --}}
    <div class="grid grid-cols-2 gap-4">
        <flux:field>
            <flux:label>Orden</flux:label>
            <flux:input type="number" name="ORDEN"
                        value="{{ old('ORDEN', $publicacion?->ORDEN ?? 0) }}" />
            <flux:error name="ORDEN" />
        </flux:field>

        <flux:field>
            <flux:label>Fecha de publicación</flux:label>
            <flux:input type="date" name="FECHA_PUBLICACION"
                        value="{{ old('FECHA_PUBLICACION', $publicacion?->FECHA_PUBLICACION?->format('Y-m-d')) }}" />
            <flux:error name="FECHA_PUBLICACION" />
        </flux:field>
    </div>

    {{-- Resumen --}}
    <flux:field>
        <flux:label>Resumen</flux:label>
        <flux:textarea name="PUBLICACION_RESUMEN" rows="3">{{ old('PUBLICACION_RESUMEN', $publicacion?->PUBLICACION_RESUMEN) }}</flux:textarea>
        <flux:error name="PUBLICACION_RESUMEN" />
    </flux:field>

    {{-- Contenido --}}
    <div>
        <flux:label>Contenido</flux:label>
        <div x-data="editorCKEditor({{ Js::from(old('PUBLICACION_CONTENIDO', $publicacion?->PUBLICACION_CONTENIDO ?? '')) }})">
            <div x-ref="editorEl"></div>
            <input type="hidden" name="PUBLICACION_CONTENIDO" x-bind:value="contenido" />
        </div>
    </div>

    {{-- Imagen --}}
    <flux:field>
        <flux:label>Imagen destacada</flux:label>
        @if ($publicacion?->IMAGEN && $publicacion->IMAGEN !== 'default.jpg')
            <div class="mb-2">
                <img src="{{ asset('storage/img/publicaciones/' . $publicacion->IMAGEN) }}"
                     alt="Imagen actual" class="h-32 w-auto rounded object-cover" />
                <p class="text-xs text-zinc-400 mt-1">Imagen actual. Sube una nueva para reemplazarla.</p>
            </div>
        @endif
        <flux:input type="file" name="imagen" accept="image/*" />
        <flux:description>Máximo 5 MB. Se convertirá a WebP automáticamente.</flux:description>
        <flux:error name="imagen" />
    </flux:field>

    <div class="flex gap-3">
        <flux:button type="submit" variant="primary">
            {{ $publicacion ? 'Actualizar' : 'Crear publicación' }}
        </flux:button>
        <flux:button href="{{ route('admin.publicaciones.index') }}" variant="ghost">
            Cancelar
        </flux:button>
    </div>
</form>