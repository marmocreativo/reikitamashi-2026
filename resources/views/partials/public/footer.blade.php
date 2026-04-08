<footer class="bg-primary text-white/80">
    <div class="max-w-7xl mx-auto px-6 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

            {{-- Columna 1: Logo + descripción --}}
            <div class="flex flex-col items-start gap-4">
                <img src="{{ asset('menu_logo.png') }}" alt="Tamashi" class="h-14 w-auto brightness-0 invert">
                <p class="text-sm text-white/70 leading-relaxed max-w-sm">
                    Ofrecemos ayuda espiritual y un lugar de convivencia. Sanación del pasado, para vivir bien el presente y ser mejores en el futuro.
                </p>
            </div>

            {{-- Columna 2: Contacto + redes --}}
            <div class="flex flex-col gap-5">

                {{-- Datos de contacto --}}
                <div class="flex flex-col gap-3">
                    <h4 class="text-white font-semibold text-sm uppercase tracking-widest">Contacto</h4>

                    <div class="flex items-start gap-3 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 mt-0.5 shrink-0 text-white/50" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>
                        <span class="text-white/70">Calle Bosques de Yucatán No 15, Col. Bosques de México, Tlalnepantla, Estado de México. CP 54050</span>
                    </div>

                    <div class="flex items-center gap-3 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-4 shrink-0 text-white/50" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 6.75Z" />
                        </svg>
                        <a href="tel:5516443452" class="text-white/70 hover:text-white transition">55-16-44-34-52</a>
                    </div>
                </div>

                {{-- Redes sociales --}}
                <div class="flex flex-col gap-3">
                    <h4 class="text-white font-semibold text-sm uppercase tracking-widest">Síguenos</h4>
                    <div class="flex items-center gap-4">
                        <a
                            href="https://www.facebook.com/Reiki-Tamashi-393432114077987/"
                            target="_blank"
                            class="flex items-center gap-2 text-sm text-white/70 hover:text-white transition"
                        >
                            <x-si-facebook class="size-5 fill-current" />
                        </a>
                        <a
                            href="https://twitter.com/reikitamashi"
                            target="_blank"
                            class="flex items-center gap-2 text-sm text-white/70 hover:text-white transition"
                        >
                            <x-si-x class="size-5 fill-current" />
                        </a>
                        <a
                            href="https://www.instagram.com/reikitamashioficial/"
                            target="_blank"
                            class="flex items-center gap-2 text-sm text-white/70 hover:text-white transition"
                        >
                            <x-si-instagram class="size-5 fill-current" />
                        </a>
                    </div>
                </div>

            </div>

        </div>

        {{-- Línea inferior --}}
        <div class="mt-10 pt-6 border-t border-white/20 text-center text-xs text-white/40">
            © {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.
        </div>
    </div>
</footer>