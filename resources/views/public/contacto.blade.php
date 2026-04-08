@extends('layouts.public')

@section('title', 'Contacto')

@section('content')

    {{-- ============================================
         MINI HERO
    ============================================ --}}
    <section
        class="relative flex items-end min-h-[40vh] pt-16"
        style="background-image: url('{{ asset('hero_bg.jpg') }}'); background-size: cover; background-position: center;"
    >
        <div class="absolute inset-0 bg-black/50"></div>
        <div class="relative z-10 w-full max-w-6xl mx-auto px-6 pb-10 flex flex-col gap-2">
            <p class="text-white/70 text-sm font-semibold uppercase tracking-widest">Tamashi</p>
            <h1 class="text-4xl md:text-5xl font-bold text-white drop-shadow" style="font-family: 'Georgia', serif;">
                Contáctanos
            </h1>
            <p class="text-white/80 text-sm leading-relaxed max-w-xl">
                Estamos para atenderte. Escríbenos o visítanos.
            </p>
        </div>
    </section>

    {{-- ============================================
         CONTENIDO
    ============================================ --}}
    <section class="py-16 bg-background">
        <div class="max-w-6xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-2 gap-12">

            {{-- Columna izquierda: datos + mapa --}}
            <div class="flex flex-col gap-8">

                {{-- Datos de contacto --}}
                <div class="flex flex-col gap-5">

                    <div class="flex items-start gap-4">
                        <div class="shrink-0 w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 6.75Z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-primary">Llamadas y WhatsApp</p>
                            <a href="tel:5516443452" class="text-gray-500 hover:text-accent transition text-sm">55-16-44-34-52</a>
                            <span class="mx-2 text-gray-300">·</span>
                            <a href="https://wa.me/5215516443452" target="_blank" class="text-gray-500 hover:text-accent transition text-sm">Abrir WhatsApp</a>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="shrink-0 w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-primary">Dirección</p>
                            <p class="text-gray-500 text-sm">Calle Bosques de Yucatán No 15</p>
                            <p class="text-gray-500 text-sm">Col. Bosques de México</p>
                            <p class="text-gray-500 text-sm">Tlalnepantla, Estado de México. CP 54050</p>
                        </div>
                    </div>

                </div>

                {{-- Mapa --}}
                <div class="rounded-2xl overflow-hidden shadow-md h-72">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3760.1358333891!2d-99.23135122410127!3d19.53578108176584!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x85d21d25d5e7ffcb%3A0xa62a19701a5140dc!2sReiki%20Tamashi!5e0!3m2!1ses!2smx!4v1775347713371!5m2!1ses!2smx"
                        width="100%"
                        height="100%"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Ubicación Tamashi">
                    </iframe>
                </div>

            </div>

            {{-- Columna derecha: formulario --}}
            <div
                class="bg-white rounded-2xl shadow-sm p-8 flex flex-col gap-5"
                x-data="{
                    nombre: '',
                    correo: '',
                    telefono: '',
                    asunto: '',
                    mensaje: '',
                    enviar() {
                        const msg = `Hola, me llamo ${this.nombre}.\n\nAsunto: ${this.asunto}\n\nMensaje: ${this.mensaje}\n\nCorreo: ${this.correo}\nTeléfono: ${this.telefono}`
                        const url = `https://wa.me/5215516443452?text=${encodeURIComponent(msg)}`
                        window.open(url, '_blank')
                    }
                }"
            >
                <h2 class="text-xl font-bold text-primary" style="font-family: 'Georgia', serif;">Envíanos un mensaje</h2>

                <div class="flex flex-col gap-1">
                    <label class="text-sm font-semibold text-primary">Nombre</label>
                    <input
                        type="text"
                        x-model="nombre"
                        placeholder="Tu nombre completo"
                        class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition"
                    >
                </div>

                <div class="flex flex-col gap-1">
                    <label class="text-sm font-semibold text-primary">Correo electrónico</label>
                    <input
                        type="email"
                        x-model="correo"
                        placeholder="tu@correo.com"
                        class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition"
                    >
                </div>

                <div class="flex flex-col gap-1">
                    <label class="text-sm font-semibold text-primary">Teléfono <span class="text-gray-400 font-normal">(opcional)</span></label>
                    <input
                        type="tel"
                        x-model="telefono"
                        placeholder="55 0000 0000"
                        class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition"
                    >
                </div>

                <div class="flex flex-col gap-1">
                    <label class="text-sm font-semibold text-primary">Asunto</label>
                    <input
                        type="text"
                        x-model="asunto"
                        placeholder="¿En qué podemos ayudarte?"
                        class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition"
                    >
                </div>

                <div class="flex flex-col gap-1">
                    <label class="text-sm font-semibold text-primary">Mensaje</label>
                    <textarea
                        x-model="mensaje"
                        rows="4"
                        placeholder="Escribe tu mensaje aquí..."
                        class="w-full rounded-lg border border-gray-200 px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition"
                    ></textarea>
                </div>

                <button
                    type="button"
                    @click="enviar()"
                    class="w-full flex items-center justify-center gap-2 bg-green-500 hover:bg-green-600 text-white font-semibold py-3 px-6 rounded-lg transition text-sm"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                        <path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.126 1.532 5.861L.057 23.571a.75.75 0 0 0 .921.921l5.71-1.475A11.943 11.943 0 0 0 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.75a9.715 9.715 0 0 1-5.003-1.386l-.36-.214-3.733.964.993-3.648-.235-.374A9.715 9.715 0 0 1 2.25 12C2.25 6.615 6.615 2.25 12 2.25S21.75 6.615 21.75 12 17.385 21.75 12 21.75z"/>
                    </svg>
                    Enviar por WhatsApp
                </button>

            </div>

        </div>
    </section>

@endsection