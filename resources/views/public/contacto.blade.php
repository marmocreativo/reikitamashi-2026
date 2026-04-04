@extends('layouts.public')

@section('title', 'Contacto')

@section('content')
<section class="py-16 px-4 max-w-6xl mx-auto">

    <div class="text-center mb-12">
        <h1 class="text-3xl font-bold text-zinc-800">Contáctanos</h1>
        <p class="text-zinc-500 mt-2">Estamos para atenderte. Escríbenos o visítanos.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">

        {{-- Columna izquierda: datos + mapa --}}
        <div class="space-y-8">

            {{-- Datos de contacto --}}
            <div class="space-y-4">
                <div class="flex items-start gap-3">
                    <div class="mt-1 w-8 h-8 flex-shrink-0 text-violet-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 6.75Z" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-zinc-700">Llamadas y WhatsApp</p>
                        <a href="tel:5516443452" class="text-zinc-500 hover:text-violet-600 transition">55-16-44-34-52</a>
                        <span class="mx-2 text-zinc-300">·</span>
                        <a href="https://wa.me/5215516443452" target="_blank" class="text-zinc-500 hover:text-violet-600 transition">Abrir WhatsApp</a>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <div class="mt-1 w-8 h-8 flex-shrink-0 text-violet-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-zinc-700">Correo electrónico</p>
                        <a href="mailto:reikitamashi@hotmail.com" class="text-zinc-500 hover:text-violet-600 transition">reikitamashi@hotmail.com</a>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <div class="mt-1 w-8 h-8 flex-shrink-0 text-violet-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-zinc-700">Dirección</p>
                        <p class="text-zinc-500">Calle Bosques de Yucatán No 15</p>
                        <p class="text-zinc-500">Col. Bosques de México</p>
                        <p class="text-zinc-500">Tlalnepantla, Estado de México. CP 54050</p>
                    </div>
                </div>
            </div>

            {{-- Mapa --}}
            <div class="rounded-xl overflow-hidden shadow-md h-72">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3761.5!2d-99.2080!3d19.5450!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2sBosques%20de%20Yucat%C3%A1n%2015%2C%20Bosques%20de%20M%C3%A9xico%2C%20Tlalnepantla!5e0!3m2!1ses!2smx!4v1"
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
        <div class="bg-white rounded-2xl shadow-md p-8">
            <h2 class="text-xl font-semibold text-zinc-800 mb-6">Envíanos un mensaje</h2>

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('contacto.send') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1" for="nombre">Nombre</label>
                    <input
                        type="text"
                        id="nombre"
                        name="nombre"
                        value="{{ old('nombre') }}"
                        placeholder="Tu nombre"
                        class="w-full rounded-lg border border-zinc-300 px-4 py-2.5 text-sm text-zinc-800 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-violet-400 focus:border-transparent @error('nombre') border-red-400 @enderror"
                    >
                    @error('nombre') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1" for="email">Correo electrónico</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="tu@correo.com"
                        class="w-full rounded-lg border border-zinc-300 px-4 py-2.5 text-sm text-zinc-800 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-violet-400 focus:border-transparent @error('email') border-red-400 @enderror"
                    >
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1" for="telefono">Teléfono <span class="text-zinc-400 font-normal">(opcional)</span></label>
                    <input
                        type="tel"
                        id="telefono"
                        name="telefono"
                        value="{{ old('telefono') }}"
                        placeholder="55 0000 0000"
                        class="w-full rounded-lg border border-zinc-300 px-4 py-2.5 text-sm text-zinc-800 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-violet-400 focus:border-transparent"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1" for="asunto">Asunto</label>
                    <input
                        type="text"
                        id="asunto"
                        name="asunto"
                        value="{{ old('asunto') }}"
                        placeholder="¿En qué podemos ayudarte?"
                        class="w-full rounded-lg border border-zinc-300 px-4 py-2.5 text-sm text-zinc-800 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-violet-400 focus:border-transparent @error('asunto') border-red-400 @enderror"
                    >
                    @error('asunto') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1" for="mensaje">Mensaje</label>
                    <textarea
                        id="mensaje"
                        name="mensaje"
                        rows="5"
                        placeholder="Escribe tu mensaje aquí..."
                        class="w-full rounded-lg border border-zinc-300 px-4 py-2.5 text-sm text-zinc-800 placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-violet-400 focus:border-transparent @error('mensaje') border-red-400 @enderror"
                    >{{ old('mensaje') }}</textarea>
                    @error('mensaje') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <button
                    type="submit"
                    class="w-full bg-violet-600 hover:bg-violet-700 text-white font-semibold py-3 px-6 rounded-lg transition text-sm"
                >
                    Enviar mensaje
                </button>
            </form>
        </div>

    </div>
</section>
@endsection