<footer class="bg-gray-900 text-gray-300">
    <div class="max-w-7xl mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">

            {{-- Columna 1: Logo + descripción --}}
            <div class="flex flex-col items-center md:items-start gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="Tamashi" class="h-16 w-auto">
                <p class="text-sm text-center md:text-left text-gray-400">
                    Ofrecemos ayuda espiritual y un lugar de convivencia
                </p>
            </div>

            {{-- Columna 2: Dirección --}}
            <div>
                <h4 class="text-white font-semibold mb-3">Dirección</h4>
                <p class="text-sm text-gray-400 leading-relaxed">
                    Calle Bosques de Yucatán No 15, Col. Bosques de México.
                    Tlalnepantla, Estado de México. CP 54050
                </p>
            </div>

            {{-- Columna 3: Promociones --}}
            <div>
                <h4 class="text-white font-semibold mb-3">Promociones</h4>
                {{-- Por ahora vacío, se llenará dinámicamente --}}
            </div>

            {{-- Columna 4: Redes Sociales --}}
            <div>
                <h4 class="text-white font-semibold mb-3">Redes Sociales</h4>
                <ul class="flex flex-col gap-2 text-sm">
                    <li>
                        <a href="#" class="flex items-center gap-2 hover:text-white transition">
                            <x-si-facebook class="size-4 fill-current" />
                            Facebook
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center gap-2 hover:text-white transition">
                            <x-si-x class="size-4 fill-current" />
                            Twitter / X
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center gap-2 hover:text-white transition">
                            <x-si-instagram class="size-4 fill-current" />
                            Instagram
                        </a>
                    </li>
                </ul>
            </div>

        </div>

        {{-- Línea inferior --}}
        <div class="mt-10 pt-6 border-t border-gray-700 text-center text-xs text-gray-500">
            © {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.
        </div>
    </div>
</footer>