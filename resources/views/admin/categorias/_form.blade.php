@push('styles')
<style>
    .ck-editor__editable { min-height: 360px; }
</style>
@endpush

@props(['categoria' => null, 'padre' => null])

{{-- Valor pre-resuelto del tipo para Alpine --}}
@php
    $tipoActual = old('TIPO', request('tipo', $categoria?->TIPO ?? $padre?->TIPO ?? \App\Models\Publicacion::TIPOS[0]));
@endphp

<div
    class="grid grid-cols-1 gap-6 lg:grid-cols-3"
    x-data="{
        nombre: {{ Js::from(old('CATEGORIA_NOMBRE', $categoria?->CATEGORIA_NOMBRE ?? '')) }},
        url: {{ Js::from(old('URL', $categoria?->URL ?? '')) }},
        esNuevo: {{ $categoria ? 'false' : 'true' }},
        tipoFijo: {{ Js::from($tipoActual) }},
        mostrarSelectTipo: false,

        generarSlug(texto) {
            return texto
                .toLowerCase()
                .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                .replace(/[^a-z0-9\s-]/g, '')
                .trim()
                .replace(/\s+/g, '-');
        },

        onNombreInput() {
            if (this.esNuevo) {
                this.url = this.generarSlug(this.nombre);
            }
        },

        dropover: false,
        preview: null,
        onDrop(e) {
            this.dropover = false;
            const file = e.dataTransfer.files[0];
            if (file) this.setPreview(file);
        },
        onChange(e) {
            const file = e.target.files[0];
            if (file) this.setPreview(file);
        },
        setPreview(file) {
            const reader = new FileReader();
            reader.onload = (e) => { this.preview = e.target.result; };
            reader.readAsDataURL(file);
        },
    }"
>

    {{-- ═══════════════════════════════════════════
         COLUMNA IZQUIERDA — 2/3
    ════════════════════════════════════════════ --}}
    <div class="flex flex-col gap-5 lg:col-span-2">

        {{-- Nombre --}}
        <flux:field>
            <flux:label>Nombre <span class="text-red-500">*</span></flux:label>
            <flux:input
                name="CATEGORIA_NOMBRE"
                x-model="nombre"
                x-on:input="onNombreInput()"
                required
            />
            <flux:error name="CATEGORIA_NOMBRE" />
        </flux:field>

        {{-- URL --}}
        <flux:field>
            <flux:label>URL (slug) <span class="text-red-500">*</span></flux:label>
            <flux:input
                name="URL"
                x-model="url"
                x-on:focus="esNuevo = false"
                required
            />
            <flux:description>Se genera automáticamente desde el nombre. Puedes editarla manualmente.</flux:description>
            <flux:error name="URL" />
        </flux:field>

        {{-- Descripción CKEditor --}}
        <div>
            <flux:label class="mb-1 block">Descripción</flux:label>
            <div
                x-data="editorCKEditor({{ Js::from(old('CATEGORIA_DESCRIPCION', $categoria?->CATEGORIA_DESCRIPCION ?? '')) }})"
                class="rounded-lg overflow-hidden border border-zinc-200 dark:border-zinc-700"
            >
                <div x-ref="editorEl"></div>
                <input type="hidden" name="CATEGORIA_DESCRIPCION" x-bind:value="contenido" />
            </div>
        </div>

    </div>

    {{-- ═══════════════════════════════════════════
         COLUMNA DERECHA — 1/3
    ════════════════════════════════════════════ --}}
    <div class="flex flex-col gap-5">

        {{-- Dropzone imagen --}}
        <div>
            <flux:label class="mb-1 block">Imagen</flux:label>

            <label
                class="relative flex flex-col items-center justify-center gap-2 rounded-xl border-2 border-dashed border-zinc-300 dark:border-zinc-600 bg-zinc-50 dark:bg-zinc-800/50 cursor-pointer transition hover:border-zinc-400 dark:hover:border-zinc-500 overflow-hidden"
                :class="dropover ? 'border-blue-400 bg-blue-50 dark:bg-blue-900/20' : ''"
                style="min-height: 180px;"
                x-on:dragover.prevent="dropover = true"
                x-on:dragleave.prevent="dropover = false"
                x-on:drop.prevent="onDrop($event)"
            >
                {{-- Preview --}}
                <template x-if="preview">
                    <img :src="preview" class="absolute inset-0 h-full w-full object-cover" />
                </template>

                {{-- Imagen actual (edición sin preview aún) --}}
                @if($categoria && $categoria->IMAGEN !== 'default.jpg' && !old('imagen'))
                    <template x-if="!preview">
                        <img
                            src="{{ asset('storage/img/categorias/' . $categoria->IMAGEN) }}"
                            class="absolute inset-0 h-full w-full object-cover"
                        />
                    </template>
                @endif

                {{-- Placeholder --}}
                <template x-if="!preview">
                    <div class="flex flex-col items-center gap-1 p-6 text-center z-10">
                        <flux:icon.photo class="size-8 text-zinc-400" />
                        <span class="text-sm text-zinc-500 dark:text-zinc-400">
                            Arrastra una imagen o <span class="text-blue-500 underline">selecciona</span>
                        </span>
                        <span class="text-xs text-zinc-400">JPG, PNG, WebP — máx. 5 MB</span>
                    </div>
                </template>

                <input
                    type="file"
                    name="imagen"
                    accept="image/*"
                    class="absolute inset-0 opacity-0 cursor-pointer"
                    x-on:change="onChange($event)"
                />
            </label>
            @error('imagen')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>
        {{-- Categoría padre --}}
        <div>
            <flux:label class="mb-1 block">Categoría padre</flux:label>
            @if($padre)
                <div class="flex items-center justify-between rounded-lg border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 px-3 py-2">
                    <span class="text-sm font-medium text-zinc-700 dark:text-zinc-200">
                        {{ $padre->CATEGORIA_NOMBRE }}
                    </span>
                    <a
                        href="{{ route('admin.categorias.create', array_filter(['tipo' => $tipoActual])) }}"
                        class="text-xs text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300 underline"
                        wire:navigate
                    >Quitar</a>
                </div>
                <input type="hidden" name="CATEGORIA_PADRE" value="{{ $padre->ID_CATEGORIA }}" />
            @elseif($categoria?->CATEGORIA_PADRE)
                <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 px-3 py-2">
                    <span class="text-sm text-zinc-500">
                        ID: {{ $categoria->CATEGORIA_PADRE }}
                    </span>
                </div>
                <input type="hidden" name="CATEGORIA_PADRE" value="{{ $categoria->CATEGORIA_PADRE }}" />
            @else
                <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 px-3 py-2">
                    <span class="text-sm text-zinc-400">Sin categoría padre (raíz)</span>
                </div>
            @endif
        </div>

        {{-- Tipo — oculto/fijo con opción de desbloquear --}}
        <div>
            <flux:label class="mb-1 block">Tipo</flux:label>

            <template x-if="!mostrarSelectTipo">
                <div class="flex items-center justify-between rounded-lg border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 px-3 py-2">
                    <span class="text-sm font-medium text-zinc-700 dark:text-zinc-200 capitalize" x-text="tipoFijo.replace('_', ' ')"></span>
                    <button
                        type="button"
                        class="text-xs text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300 underline"
                        x-on:click="mostrarSelectTipo = true"
                    >Cambiar</button>
                </div>
            </template>

            <template x-if="mostrarSelectTipo">
                <flux:select name="TIPO" x-model="tipoFijo">
                    @foreach(\App\Models\Publicacion::TIPOS as $t)
                        <option value="{{ $t }}" @selected($tipoActual === $t)>
                            {{ ucfirst(str_replace('_', ' ', $t)) }}
                        </option>
                    @endforeach
                </flux:select>
            </template>

            {{-- Hidden siempre presente cuando no se muestra el select --}}
            <template x-if="!mostrarSelectTipo">
                <input type="hidden" name="TIPO" :value="tipoFijo" />
            </template>
        </div>

        {{-- Visibilidad — siempre visible, campo oculto --}}
        <input type="hidden" name="VISIBLE" value="visible" />

        {{-- Estado --}}
        <flux:field>
            <flux:label>Estado</flux:label>
            <flux:select name="ESTADO">
                <option value="activo" @selected(old('ESTADO', $categoria?->ESTADO ?? 'activo') === 'activo')>Activo</option>
                <option value="inactivo" @selected(old('ESTADO', $categoria?->ESTADO) === 'inactivo')>Inactivo</option>
            </flux:select>
            <flux:error name="ESTADO" />
        </flux:field>

        {{-- Orden — oculto, valor 0 por defecto --}}
        <input type="hidden" name="ORDEN" value="{{ old('ORDEN', $categoria?->ORDEN ?? 0) }}" />

    </div>

</div>