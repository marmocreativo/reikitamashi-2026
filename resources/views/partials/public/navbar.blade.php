<nav
    class="fixed top-0 left-0 right-0 z-40 transition-all duration-300"
    x-data="{
        open: false,
        scrolled: false,
        init() {
            window.addEventListener('scroll', () => {
                this.scrolled = window.scrollY > 20
            })
        }
    }"
    :class="scrolled ? 'bg-primary shadow-sm' : 'bg-primary/80'"
>
    <div class="max-w-7xl mx-auto px-4 flex items-center justify-between h-16">

        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0">
            <img src="{{ asset('menu_logo.png') }}" alt="Tamashi" class="h-10 w-auto">
        </a>

        {{-- Links centrados (desktop) --}}
        <ul class="hidden md:flex items-center gap-6 text-sm font-medium">
            @foreach($menuPublico as $item)
                @if($menuHijos->has($item->ID_MENU))
                    <li class="relative" x-data="{ submenu: false }">
                        <button
                            @click="submenu = !submenu"
                            @click.outside="submenu = false"
                            class="flex items-center gap-1 text-white hover:text-accent transition"
                        >
                            {{ $item->MENU_ETIQUETA }}
                            <flux:icon.chevron-down class="size-3" />
                        </button>
                        <ul
                            x-show="submenu"
                            x-cloak
                            x-transition
                            class="absolute top-full left-0 mt-2 w-48 bg-white dark:bg-zinc-900 rounded-lg shadow-lg py-1 text-zinc-800 dark:text-white"
                        >
                            @foreach($menuHijos[$item->ID_MENU] as $hijo)
                                <li>
                                    <a href="{{ $hijo->MENU_ENLACE }}" class="block px-4 py-2 text-sm hover:bg-zinc-100 dark:hover:bg-zinc-800 transition">
                                        {{ $hijo->MENU_ETIQUETA }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @else
                    <li>
                        <a href="{{ $item->MENU_ENLACE }}" class="text-white hover:text-accent transition">
                            {{ $item->MENU_ETIQUETA }}
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>

        {{-- User dropdown + menú mobile --}}
        <div class="flex items-center gap-3">

            {{-- User Dropdown --}}
            <flux:dropdown>
                <flux:button variant="ghost" class="text-white! hover:text-accent! text-sm">
                    <flux:icon.user-circle variant="outline" class="size-6" />
                    @auth
                        <span class="hidden sm:inline ml-1">{{ Auth::user()->name }}</span>
                    @else
                        <span class="hidden sm:inline ml-1">Mi cuenta</span>
                    @endauth
                    <flux:icon.chevron-down variant="micro" class="ml-1" />
                </flux:button>
                <flux:menu>
                    @auth
                        <flux:menu.item href="{{ route('admin.dashboard') }}" icon="squares-2x2">Dashboard</flux:menu.item>
                        <flux:menu.separator />
                        <flux:menu.item
                            x-on:click="document.getElementById('logout-form').submit()"
                            icon="arrow-right-start-on-rectangle"
                        >
                            Cerrar sesión
                        </flux:menu.item>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    @else
                        <flux:menu.item href="{{ route('login') }}" icon="arrow-right-end-on-rectangle">Iniciar sesión</flux:menu.item>
                    @endauth
                </flux:menu>
            </flux:dropdown>

            {{-- Botón hamburguesa (mobile) --}}
            <button
                class="md:hidden p-2 rounded text-white hover:text-accent transition"
                @click="open = !open"
                :aria-expanded="open"
            >
                <flux:icon.bars-3 class="size-5" />
            </button>

        </div>
    </div>

    {{-- Offcanvas mobile --}}
    <div
        class="fixed inset-0 z-50 md:hidden"
        x-show="open"
        x-cloak
    >
        {{-- Backdrop --}}
        <div
            class="absolute inset-0 bg-black/40"
            @click="open = false"
        ></div>

        {{-- Panel --}}
        <div
            class="absolute top-0 left-0 h-full w-72 bg-primary text-white flex flex-col shadow-xl"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
        >
            {{-- Header del offcanvas --}}
            <div class="flex items-center justify-between px-5 h-16 border-b border-white/20 shrink-0">
                <img src="{{ asset('menu_logo.png') }}" alt="Tamashi" class="h-9 w-auto brightness-0 invert">
                <button @click="open = false" class="text-white/70 hover:text-white transition">
                    <flux:icon.x-mark class="size-5" />
                </button>
            </div>

            {{-- Links --}}
            <ul class="flex flex-col px-5 py-6 gap-1 text-sm font-medium flex-1 overflow-y-auto">
                @foreach($menuPublico as $item)
                    <li>
                        <a href="{{ $item->MENU_ENLACE }}" class="block py-2 px-3 rounded hover:bg-white/10 transition">
                            {{ $item->MENU_ETIQUETA }}
                        </a>
                    </li>
                    @if($menuHijos->has($item->ID_MENU))
                        @foreach($menuHijos[$item->ID_MENU] as $hijo)
                            <li>
                                <a href="{{ $hijo->MENU_ENLACE }}" class="block py-2 px-3 pl-6 rounded hover:bg-white/10 transition text-white/80">
                                    {{ $hijo->MENU_ETIQUETA }}
                                </a>
                            </li>
                        @endforeach
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
</nav>