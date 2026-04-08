@push('styles')
<style>
    .ck-editor__editable { min-height: 360px; }
</style>
@endpush

@props(['publicacion' => null, 'tipos' => [], 'categorias' => collect(), 'categoriaSeleccionada' => null])

@php
    $categoriaObj   = $categorias->firstWhere('ID_CATEGORIA', $categoriaSeleccionada);
    $tipoActual     = old('TIPO', $categoriaObj?->TIPO ?? $publicacion?->TIPO ?? $tipos[0] ?? 'pagina');
    $raices         = $categorias->where('CATEGORIA_PADRE', 0);
    $hijas          = $categorias->where('CATEGORIA_PADRE', '!=', 0)->groupBy('CATEGORIA_PADRE');
@endphp

@if($errors->any())
    <flux:callout variant="danger" class="mb-4">
        <ul class="list-disc list-inside text-sm">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </flux:callout>
@endif

<div
    class="grid grid-cols-1 gap-6 lg:grid-cols-3"
    x-data="{
        titulo: {{ Js::from(old('PUBLICACION_TITULO', $publicacion?->PUBLICACION_TITULO ?? '')) }},
        url: {{ Js::from(old('URL', $publicacion?->URL ?? '')) }},
        esNuevo: {{ $publicacion ? 'false' : 'true' }},
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
        onTituloInput() {
            if (this.esNuevo) this.url = this.generarSlug(this.titulo);
        },

        {{-- Dropzone imagen destacada --}}
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

        {{-- Dropzone galería --}}
        galeriaFiles: [],
        galeriaDropover: false,
        galeriaOnDrop(e) {
            this.galeriaDropover = false;
            this.addGaleriaFiles(e.dataTransfer.files);
        },
        galeriaOnChange(e) {
            this.addGaleriaFiles(e.target.files);
        },
        addGaleriaFiles(files) {
            Array.from(files).forEach(file => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.galeriaFiles.push({ name: file.name, src: e.target.result });
                };
                reader.readAsDataURL(file);
            });
        },
        removeGaleriaFile(index) {
            this.galeriaFiles.splice(index, 1);
        },

        {{-- Tipo heredado de categoría --}}
        onCategoriaChange(tipo) {
            if (tipo) this.tipoFijo = tipo;
        },
    }"
>

    {{-- ══════════════════════════════
         COLUMNA IZQUIERDA (2/3)
    ══════════════════════════════ --}}
    <div class="flex flex-col gap-5 lg:col-span-2">

        {{-- Título --}}
        <flux:field>
            <flux:label>Título <span class="text-red-500">*</span></flux:label>
            <flux:input
                name="PUBLICACION_TITULO"
                x-model="titulo"
                x-on:input="onTituloInput()"
                required
            />
            <flux:error name="PUBLICACION_TITULO" />
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
            <flux:description>Se genera automáticamente desde el título.</flux:description>
            <flux:error name="URL" />
        </flux:field>

        {{-- Resumen --}}
        <flux:field>
            <flux:label>Resumen</flux:label>
            <flux:textarea name="PUBLICACION_RESUMEN" rows="3">{{ old('PUBLICACION_RESUMEN', $publicacion?->PUBLICACION_RESUMEN) }}</flux:textarea>
            <flux:error name="PUBLICACION_RESUMEN" />
        </flux:field>

        {{-- Contenido CKEditor --}}
        <div>
            <flux:label class="mb-1 block">Contenido</flux:label>
            <div
                x-data="editorCKEditor({{ Js::from(old('PUBLICACION_CONTENIDO', $publicacion?->PUBLICACION_CONTENIDO ?? '')) }})"
                class="rounded-lg overflow-hidden border border-zinc-200 dark:border-zinc-700"
            >
                <div x-ref="editorEl"></div>
                <input type="hidden" name="PUBLICACION_CONTENIDO" x-bind:value="contenido" />
            </div>
        </div>

    </div>

    {{-- ══════════════════════════════
         COLUMNA DERECHA (1/3)
    ══════════════════════════════ --}}
    <div class="flex flex-col gap-5">

        {{-- Botones --}}
        <div class="flex gap-2">
            <flux:button type="submit" variant="primary" class="flex-1">
                {{ $publicacion ? 'Actualizar' : 'Guardar' }}
            </flux:button>
            <flux:button href="{{ route('admin.publicaciones.index') }}" variant="ghost" wire:navigate>
                Cancelar
            </flux:button>
        </div>

        {{-- Imagen destacada dropzone --}}
        <div>
            <flux:label class="mb-1 block">Imagen destacada</flux:label>
            <label
                class="relative flex flex-col items-center justify-center gap-2 rounded-xl border-2 border-dashed border-zinc-300 dark:border-zinc-600 bg-zinc-50 dark:bg-zinc-800/50 cursor-pointer transition hover:border-zinc-400 overflow-hidden"
                :class="dropover ? 'border-blue-400 bg-blue-50 dark:bg-blue-900/20' : ''"
                style="min-height: 160px;"
                x-on:dragover.prevent="dropover = true"
                x-on:dragleave.prevent="dropover = false"
                x-on:drop.prevent="onDrop($event)"
            >
                <template x-if="preview">
                    <img :src="preview" class="absolute inset-0 h-full w-full object-cover" />
                </template>
                @if($publicacion?->IMAGEN && $publicacion->IMAGEN !== 'default.jpg')
                    <template x-if="!preview">
                        <img src="{{ asset('storage/img/publicaciones/' . $publicacion->IMAGEN) }}"
                            class="absolute inset-0 h-full w-full object-cover" />
                    </template>
                @endif
                <template x-if="!preview">
                    <div class="flex flex-col items-center gap-1 p-4 text-center z-10">
                        <flux:icon.photo class="size-7 text-zinc-400" />
                        <span class="text-xs text-zinc-500">Arrastra o <span class="text-blue-500 underline">selecciona</span></span>
                        <span class="text-xs text-zinc-400">Máx. 5 MB</span>
                    </div>
                </template>
                <input type="file" name="imagen" accept="image/*"
                    class="absolute inset-0 opacity-0 cursor-pointer"
                    x-on:change="onChange($event)" />
            </label>
            @error('imagen') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        {{-- Tipo --}}
        <div>
            <flux:label class="mb-1 block">Tipo <span class="text-red-500">*</span></flux:label>
            <template x-if="!mostrarSelectTipo">
                <div class="flex items-center justify-between rounded-lg border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 px-3 py-2">
                    <span class="text-sm font-medium text-zinc-700 dark:text-zinc-200 capitalize" x-text="tipoFijo.replace('_', ' ')"></span>
                    <button type="button"
                        class="text-xs text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300 underline"
                        x-on:click="mostrarSelectTipo = true"
                    >Cambiar</button>
                </div>
            </template>
            <template x-if="mostrarSelectTipo">
                <flux:select name="TIPO" x-model="tipoFijo">
                    @foreach($tipos as $t)
                        <flux:select.option value="{{ $t }}" :selected="$tipoActual === $t">
                            {{ ucfirst(str_replace('_', ' ', $t)) }}
                        </flux:select.option>
                    @endforeach
                </flux:select>
            </template>
            <template x-if="!mostrarSelectTipo">
                <input type="hidden" name="TIPO" :value="tipoFijo" />
            </template>
        </div>

        {{-- Estado --}}
        <flux:field>
            <flux:label>Estado <span class="text-red-500">*</span></flux:label>
            <flux:select name="ESTADO">
                <flux:select.option value="activo" :selected="old('ESTADO', $publicacion?->ESTADO ?? 'activo') === 'activo'">Activo</flux:select.option>
                <flux:select.option value="inactivo" :selected="old('ESTADO', $publicacion?->ESTADO) === 'inactivo'">Inactivo</flux:select.option>
            </flux:select>
            <flux:error name="ESTADO" />
        </flux:field>

        {{-- Fecha publicación --}}
        <flux:field>
            <flux:label>Fecha de publicación</flux:label>
            <flux:input type="date" name="FECHA_PUBLICACION"
                value="{{ old('FECHA_PUBLICACION', $publicacion?->FECHA_PUBLICACION?->format('Y-m-d')) }}" />
            <flux:error name="FECHA_PUBLICACION" />
        </flux:field>

        {{-- Destacada --}}
        <flux:field>
            <flux:label>Destacada</flux:label>
            <flux:select name="DESTACADA">
                <option value="0" @selected(!(bool) old('DESTACADA', $publicacion?->DESTACADA ?? false))>No</option>
                <option value="1" @selected((bool) old('DESTACADA', $publicacion?->DESTACADA ?? false))>Sí</option>
            </flux:select>
        </flux:field>

        <input type="hidden" name="ORDEN" value="{{ old('ORDEN', $publicacion?->ORDEN ?? 0) }}" />

        {{-- Árbol de categorías --}}
        <div>
            <flux:label class="mb-1 block">Categoría</flux:label>
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 overflow-hidden max-h-72 overflow-y-auto divide-y divide-zinc-100 dark:divide-zinc-800 text-sm">

                <label class="flex items-center gap-2 px-3 py-2 cursor-pointer hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                    <input type="radio" name="ID_CATEGORIA" value=""
                        class="accent-zinc-400"
                        {{ old('ID_CATEGORIA', $categoriaSeleccionada) === null ? 'checked' : '' }}
                        x-on:change="onCategoriaChange(null)"
                    />
                    <span class="text-zinc-400 italic text-xs">Sin categoría</span>
                </label>

                @foreach($raices as $raiz)
                    <div>
                        <label class="flex items-center gap-2 px-3 py-2 cursor-pointer hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                            <input type="radio" name="ID_CATEGORIA" value="{{ $raiz->ID_CATEGORIA }}"
                                class="accent-blue-500"
                                {{ (string)old('ID_CATEGORIA', $categoriaSeleccionada) === (string)$raiz->ID_CATEGORIA ? 'checked' : '' }}
                                x-on:change="onCategoriaChange('{{ $raiz->TIPO }}')"
                            />
                            <span class="font-medium text-zinc-800 dark:text-zinc-100 leading-tight">{{ $raiz->CATEGORIA_NOMBRE }}</span>
                            <flux:badge size="sm" variant="outline" class="ml-auto shrink-0">{{ $raiz->TIPO }}</flux:badge>
                        </label>

                        @if(isset($hijas[$raiz->ID_CATEGORIA]))
                            @foreach($hijas[$raiz->ID_CATEGORIA] as $hija)
                                <label class="flex items-center gap-2 pl-7 pr-3 py-1.5 cursor-pointer hover:bg-zinc-50 dark:hover:bg-zinc-800/50 bg-zinc-50/50 dark:bg-zinc-800/20">
                                    <input type="radio" name="ID_CATEGORIA" value="{{ $hija->ID_CATEGORIA }}"
                                        class="accent-blue-500"
                                        {{ (string)old('ID_CATEGORIA', $categoriaSeleccionada) === (string)$hija->ID_CATEGORIA ? 'checked' : '' }}
                                        x-on:change="onCategoriaChange('{{ $hija->TIPO }}')"
                                    />
                                    <span class="text-zinc-600 dark:text-zinc-300 leading-tight">{{ $hija->CATEGORIA_NOMBRE }}</span>
                                    <flux:badge size="sm" variant="outline" class="ml-auto shrink-0">{{ $hija->TIPO }}</flux:badge>
                                </label>
                            @endforeach
                        @endif
                    </div>
                @endforeach
            </div>
            @error('ID_CATEGORIA') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>


    </div>

</div>