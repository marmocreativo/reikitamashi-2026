<nav class="bg-purple-800 text-white" x-data="{ open: false }">
    <div class="max-w-7xl mx-auto px-4 flex items-center justify-between h-16">

        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0">
            <img src="{{ asset('images/logo.png') }}" alt="Tamashi" class="h-10 w-auto">
        </a>

        {{-- Links centrados (desktop) --}}
        <ul class="hidden md:flex items-center gap-6 text-sm font-medium">
            <li><a href="#" class="hover:text-purple-200 transition">Cursos Reiki</a></li>
            <li><a href="#" class="hover:text-purple-200 transition">Más Cursos</a></li>
            <li><a href="#" class="hover:text-purple-200 transition">Terapias</a></li>
            <li><a href="#" class="hover:text-purple-200 transition">Galerías</a></li>
            <li><a href="#" class="hover:text-purple-200 transition">Historias de vida</a></li>
            <li><a href="#" class="hover:text-purple-200 transition">Acerca de Nosotros</a></li>
            <li><a href="#" class="hover:text-purple-200 transition">Contacto</a></li>
        </ul>

        {{-- Botón Usuarios + menú mobile --}}
        <div class="flex items-center gap-3">

            {{-- Dropdown Usuarios (Flux) --}}
            <flux:dropdown>
                <flux:button variant="filled" class="bg-green-500! hover:bg-green-600! border-0! text-white! text-sm">
                    <flux:icon.user variant="micro" class="mr-1" />
                    Usuarios
                </flux:button>
                <flux:menu>
                    @auth
                        <flux:menu.item href="{{ route('admin.dashboard') }}" icon="squares-2x2">Dashboard</flux:menu.item>
                        <flux:menu.separator />
                        <flux:menu.item
                            wire:click="logout"
                            x-on:click="
                                document.getElementById('logout-form').submit()
                            "
                            icon="arrow-right-start-on-rectangle"
                        >
                            Cerrar sesión
                        </flux:menu.item>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    @else
                        <flux:menu.item href="{{ route('login') }}" icon="arrow-right-end-on-rectangle">Iniciar sesión</flux:menu.item>
                        <flux:menu.item href="{{ route('register') }}" icon="user-plus">Registrarse</flux:menu.item>
                    @endauth
                </flux:menu>
            </flux:dropdown>

            {{-- Botón hamburguesa (mobile) --}}
            <button
                class="md:hidden p-2 rounded hover:bg-purple-700 transition"
                @click="open = !open"
                :aria-expanded="open"
            >
                <flux:icon.bars-3 class="size-5" />
            </button>

        </div>
    </div>

    {{-- Menú mobile --}}
    <div class="md:hidden" x-show="open" x-cloak>
        <ul class="flex flex-col px-4 pb-4 gap-3 text-sm font-medium border-t border-purple-700">
            <li><a href="#" class="block py-1 hover:text-purple-200 transition">Cursos Reiki</a></li>
            <li><a href="#" class="hover:text-purple-200 transition">Más Cursos</a></li>
            <li><a href="#" class="hover:text-purple-200 transition">Terapias</a></li>
            <li><a href="#" class="hover:text-purple-200 transition">Galerías</a></li>
            <li><a href="#" class="hover:text-purple-200 transition">Historias de vida</a></li>
            <li><a href="#" class="hover:text-purple-200 transition">Acerca de Nosotros</a></li>
            <li><a href="#" class="hover:text-purple-200 transition">Contacto</a></li>
        </ul>
    </div>
</nav>