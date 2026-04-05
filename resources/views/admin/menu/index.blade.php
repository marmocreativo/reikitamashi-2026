<x-layouts::app>
    <div class="p-6">
        <flux:heading size="xl" class="mb-6">Administrar Menú</flux:heading>

        <div
            x-data="menuManager()"
            x-init="init()"
            class="flex gap-6 items-start"
        >
            {{-- ===== COLUMNA IZQUIERDA ===== --}}
            <div class="w-80 shrink-0 space-y-4">

                {{-- Tabs --}}
                <div class="flex border-b border-zinc-200 dark:border-zinc-700">
                    <button
                        @click="tab = 'categorias'"
                        :class="tab === 'categorias' ? 'border-b-2 border-zinc-800 dark:border-white font-semibold' : 'text-zinc-500'"
                        class="px-4 py-2 text-sm"
                    >Categorías</button>
                    <button
                        @click="tab = 'publicaciones'"
                        :class="tab === 'publicaciones' ? 'border-b-2 border-zinc-800 dark:border-white font-semibold' : 'text-zinc-500'"
                        class="px-4 py-2 text-sm"
                    >Publicaciones</button>
                    <button
                        @click="tab = 'enlace'"
                        :class="tab === 'enlace' ? 'border-b-2 border-zinc-800 dark:border-white font-semibold' : 'text-zinc-500'"
                        class="px-4 py-2 text-sm"
                    >Enlace</button>
                </div>

                {{-- Panel: Categorías --}}
                <div x-show="tab === 'categorias'" class="space-y-2">
                    <flux:input x-model="buscarCat" placeholder="Buscar categoría…" size="sm" />
                    <div class="max-h-96 overflow-y-auto space-y-1 pr-1">
                        @foreach($categorias as $cat)
                        <div
                            class="flex items-center justify-between rounded-lg border border-zinc-200 dark:border-zinc-700 px-3 py-2 text-sm"
                            x-show="!buscarCat || '{{ strtolower($cat->CATEGORIA_NOMBRE) }}'.includes(buscarCat.toLowerCase())"
                        >
                            <span class="truncate">{{ $cat->CATEGORIA_NOMBRE }}</span>
                            <flux:button
                                size="xs" variant="ghost"
                                @click="agregarItem('{{ $cat->CATEGORIA_NOMBRE }}', '/categoria/{{ $cat->URL }}')"
                            >+ Agregar</flux:button>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Panel: Publicaciones --}}
                <div x-show="tab === 'publicaciones'" class="space-y-2">
                    <flux:input x-model="buscarPub" placeholder="Buscar publicación…" size="sm" />
                    <div class="max-h-96 overflow-y-auto space-y-1 pr-1">
                        @foreach($publicaciones as $pub)
                        <div
                            class="flex items-center justify-between rounded-lg border border-zinc-200 dark:border-zinc-700 px-3 py-2 text-sm"
                            x-show="!buscarPub || '{{ strtolower($pub->PUBLICACION_TITULO) }}'.includes(buscarPub.toLowerCase())"
                        >
                            <span class="truncate">{{ $pub->PUBLICACION_TITULO }}</span>
                            <flux:button
                                size="xs" variant="ghost"
                                @click="agregarItem('{{ $pub->PUBLICACION_TITULO }}', '/{{ $pub->URL }}')"
                            >+ Agregar</flux:button>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Panel: Enlace personalizado --}}
                <div x-show="tab === 'enlace'" class="space-y-3">
                    <flux:field>
                        <flux:label>Etiqueta</flux:label>
                        <flux:input x-model="nuevoEnlace.etiqueta" placeholder="Texto del enlace" />
                    </flux:field>
                    <flux:field>
                        <flux:label>URL</flux:label>
                        <flux:input x-model="nuevoEnlace.url" placeholder="/ruta o https://…" />
                    </flux:field>
                    <flux:button
                        variant="primary" class="w-full"
                        @click="agregarEnlacePersonalizado()"
                    >Agregar al menú</flux:button>
                </div>

            </div>

            {{-- ===== COLUMNA DERECHA ===== --}}
            <div class="flex-1">
                <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-4">
                    <div class="flex items-center justify-between mb-4">
                        <flux:heading size="lg">Estructura del menú</flux:heading>
                        <span class="text-xs text-zinc-400">Arrastra para reordenar</span>
                    </div>
                    <ul id="sortable-root" class="space-y-2 min-h-16"></ul>
                    <p id="menu-vacio" class="text-sm text-zinc-400 text-center py-8 hidden">
                        El menú está vacío. Agrega ítems desde el panel izquierdo.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
    <script>
    window.csrfToken = '{{ csrf_token() }}';

    function menuManager() {
        return {
            tab: 'categorias',
            items: @json($menu),
            buscarCat: '',
            buscarPub: '',
            nuevoEnlace: { etiqueta: '', url: '' },
            sortables: [],

            init() {
                this.renderLista();
            },

            // ── Renderizado manual del DOM ──────────────────────────────

            renderLista() {
                const root = document.getElementById('sortable-root');
                const vacio = document.getElementById('menu-vacio');
                root.innerHTML = '';

                this.sortables.forEach(s => s.destroy());
                this.sortables = [];

                const raiz = this.items
                    .filter(i => i.MENU_PADRE === 0)
                    .sort((a, b) => a.ORDEN - b.ORDEN);

                if (raiz.length === 0) {
                    vacio.classList.remove('hidden');
                    return;
                }
                vacio.classList.add('hidden');

                raiz.forEach(item => {
                    const li = this.crearLiRaiz(item);
                    root.appendChild(li);
                });

                // Sortable raíz
                this.sortables.push(new Sortable(root, {
                    group: 'menu',
                    handle: '.handle',
                    animation: 150,
                    onEnd: () => this.persistirOrden(),
                }));

                // Sortable hijos
                raiz.forEach(item => {
                    const ulHijos = document.getElementById('hijos-' + item.ID_MENU);
                    if (ulHijos) {
                        this.sortables.push(new Sortable(ulHijos, {
                            group: 'menu',
                            handle: '.handle',
                            animation: 150,
                            onEnd: () => this.persistirOrden(),
                        }));
                    }
                });
            },

            crearLiRaiz(item) {
                const li = document.createElement('li');
                li.dataset.id = item.ID_MENU;
                li.className = 'rounded-lg border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800';

                li.appendChild(this.crearFila(item, false));

                const ulHijos = document.createElement('ul');
                ulHijos.id = 'hijos-' + item.ID_MENU;
                ulHijos.dataset.padre = item.ID_MENU;
                ulHijos.className = 'ml-6 mb-2 space-y-1 min-h-8 border-l-2 border-dashed border-zinc-200 dark:border-zinc-600 pl-3';

                const hijos = this.items
                    .filter(i => i.MENU_PADRE === item.ID_MENU)
                    .sort((a, b) => a.ORDEN - b.ORDEN);

                hijos.forEach(hijo => {
                    const liHijo = document.createElement('li');
                    liHijo.dataset.id = hijo.ID_MENU;
                    liHijo.className = 'rounded-md border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900';
                    liHijo.appendChild(this.crearFila(hijo, true));
                    ulHijos.appendChild(liHijo);
                });

                li.appendChild(ulHijos);
                return li;
            },

            crearFila(item, esHijo) {
                const div = document.createElement('div');
                div.className = 'flex items-center gap-2 px-3 py-2';

                // Handle
                const handle = document.createElement('span');
                handle.className = 'handle cursor-grab text-zinc-400 shrink-0';
                handle.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" /></svg>`;
                div.appendChild(handle);

                // Orden badge
                const badge = document.createElement('span');
                badge.className = 'orden-badge text-xs text-zinc-400 w-5 text-center shrink-0';
                badge.textContent = item.ORDEN;
                div.appendChild(badge);

                // Contenido (vista normal)
                const contenido = document.createElement('div');
                contenido.className = 'fila-contenido flex-1 min-w-0';
                contenido.innerHTML = `
                    <span class="font-medium text-sm">${item.MENU_ETIQUETA}</span>
                    <span class="text-xs text-zinc-400 ml-2">${item.MENU_ENLACE}</span>
                `;
                div.appendChild(contenido);

                // Contenido (vista edición) — oculto por defecto
                const edicion = document.createElement('div');
                edicion.className = 'fila-edicion flex-1 flex gap-2 min-w-0 hidden';
                edicion.innerHTML = `
                    <input class="fila-etiqueta flex-1 rounded-md border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-2 py-1 text-sm" value="${item.MENU_ETIQUETA}" />
                    <input class="fila-enlace flex-1 rounded-md border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-2 py-1 text-sm" value="${item.MENU_ENLACE}" />
                `;
                div.appendChild(edicion);

                // Botón editar
                const btnEditar = document.createElement('button');
                btnEditar.className = 'btn-editar shrink-0 p-1 rounded hover:bg-zinc-100 dark:hover:bg-zinc-700 text-zinc-500';
                btnEditar.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" /></svg>`;
                div.appendChild(btnEditar);

                // Botón guardar (oculto)
                const btnGuardar = document.createElement('button');
                btnGuardar.className = 'btn-guardar shrink-0 p-1 rounded bg-zinc-800 dark:bg-white text-white dark:text-zinc-800 hidden';
                btnGuardar.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>`;
                div.appendChild(btnGuardar);

                // Botón eliminar
                const btnEliminar = document.createElement('button');
                btnEliminar.className = 'btn-eliminar shrink-0 p-1 rounded hover:bg-red-50 dark:hover:bg-red-900/20 text-red-500';
                btnEliminar.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>`;
                div.appendChild(btnEliminar);

                // Eventos
                btnEditar.addEventListener('click', () => {
                    contenido.classList.add('hidden');
                    edicion.classList.remove('hidden');
                    btnEditar.classList.add('hidden');
                    btnGuardar.classList.remove('hidden');
                });

                btnGuardar.addEventListener('click', () => {
                    const etiqueta = edicion.querySelector('.fila-etiqueta').value;
                    const enlace   = edicion.querySelector('.fila-enlace').value;

                    fetch(`/admin/menu/${item.ID_MENU}`, {
                        method: 'PATCH',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': window.csrfToken },
                        body: JSON.stringify({ MENU_ETIQUETA: etiqueta, MENU_ENLACE: enlace }),
                    }).then(() => {
                        // Actualizar estado
                        const found = this.items.find(i => i.ID_MENU === item.ID_MENU);
                        if (found) { found.MENU_ETIQUETA = etiqueta; found.MENU_ENLACE = enlace; }

                        // Actualizar DOM sin re-renderizar
                        contenido.innerHTML = `
                            <span class="font-medium text-sm">${etiqueta}</span>
                            <span class="text-xs text-zinc-400 ml-2">${enlace}</span>
                        `;
                        edicion.classList.add('hidden');
                        contenido.classList.remove('hidden');
                        btnGuardar.classList.add('hidden');
                        btnEditar.classList.remove('hidden');
                    });
                });

                btnEliminar.addEventListener('click', () => {
                    if (!confirm(`¿Eliminar "${item.MENU_ETIQUETA}"?`)) return;

                    fetch(`/admin/menu/${item.ID_MENU}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': window.csrfToken },
                    }).then(() => {
                        this.items = this.items.filter(i => i.ID_MENU !== item.ID_MENU);
                        this.items.forEach(i => { if (i.MENU_PADRE === item.ID_MENU) i.MENU_PADRE = 0; });
                        this.renderLista();
                    });
                });

                return div;
            },

            // ── Persistir orden ────────────────────────────────────────

            persistirOrden() {
                const payload = [];
                let orden = 0;

                const root = document.getElementById('sortable-root');
                root.querySelectorAll(':scope > li[data-id]').forEach(li => {
                    payload.push({ id: parseInt(li.dataset.id), orden: orden++, id_padre: 0 });

                    const ulHijos = li.querySelector('ul[data-padre]');
                    if (ulHijos) {
                        let ordenHijo = 0;
                        ulHijos.querySelectorAll(':scope > li[data-id]').forEach(hijo => {
                            payload.push({
                                id: parseInt(hijo.dataset.id),
                                orden: ordenHijo++,
                                id_padre: parseInt(ulHijos.dataset.padre),
                            });
                        });
                    }
                });

                // Actualizar badges de orden en el DOM directamente
                payload.forEach(p => {
                    const li = document.querySelector(`li[data-id="${p.id}"]`);
                    if (li) {
                        const badge = li.querySelector(':scope > div > .orden-badge');
                        if (badge) badge.textContent = p.orden;
                    }

                    const found = this.items.find(i => i.ID_MENU === p.id);
                    if (found) { found.ORDEN = p.orden; found.MENU_PADRE = p.id_padre; }
                });

                fetch('{{ route("admin.menu.reordenar") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': window.csrfToken },
                    body: JSON.stringify({ items: payload }),
                });
            },

            // ── Agregar ítems ──────────────────────────────────────────

            agregarItem(etiqueta, enlace) {
                fetch('{{ route("admin.menu.store") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': window.csrfToken },
                    body: JSON.stringify({ MENU_ETIQUETA: etiqueta, MENU_ENLACE: enlace, MENU_PADRE: 0 }),
                })
                .then(r => r.json())
                .then(item => {
                    this.items.push(item);
                    this.renderLista();
                });
            },

            agregarEnlacePersonalizado() {
                if (!this.nuevoEnlace.etiqueta || !this.nuevoEnlace.url) return;
                this.agregarItem(this.nuevoEnlace.etiqueta, this.nuevoEnlace.url);
                this.nuevoEnlace = { etiqueta: '', url: '' };
            },
        }
    }
    </script>
</x-layouts::app>