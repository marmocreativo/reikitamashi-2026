<div class="mt-6">
    <h3 class="text-white font-bold text-base mb-4">¿Estás interesado?</h3>
    <div
        class="flex flex-col gap-3"
        x-data="{
            nombre: '',
            correo: '',
            telefono: '',
            contacto: '{{ $publicacion->PUBLICACION_TITULO }}',
            enviar() {
                const msg = `Hola, me llamo ${this.nombre}. Estoy interesado en: ${this.contacto}. Mi correo es: ${this.correo}. Mi teléfono es: ${this.telefono}.`
                const url = `https://wa.me/5215516443452?text=${encodeURIComponent(msg)}`
                window.open(url, '_blank')
            }
        }"
    >
        <input
            type="text"
            x-model="nombre"
            placeholder="Tu nombre"
            class="w-full rounded-lg px-3 py-2 text-sm bg-white/10 text-white placeholder-white/50 border border-white/20 focus:outline-none focus:border-white/50"
        >
        <input
            type="email"
            x-model="correo"
            placeholder="Tu correo"
            class="w-full rounded-lg px-3 py-2 text-sm bg-white/10 text-white placeholder-white/50 border border-white/20 focus:outline-none focus:border-white/50"
        >
        <input
            type="tel"
            x-model="telefono"
            placeholder="Tu teléfono"
            class="w-full rounded-lg px-3 py-2 text-sm bg-white/10 text-white placeholder-white/50 border border-white/20 focus:outline-none focus:border-white/50"
        >
        <input type="hidden" x-model="contacto">
        <button
            type="button"
            @click="enviar()"
            class="w-full flex items-center justify-center gap-2 bg-green-500 hover:bg-green-600 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 24 24" fill="currentColor">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                <path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.126 1.532 5.861L.057 23.571a.75.75 0 0 0 .921.921l5.71-1.475A11.943 11.943 0 0 0 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.75a9.715 9.715 0 0 1-5.003-1.386l-.36-.214-3.733.964.993-3.648-.235-.374A9.715 9.715 0 0 1 2.25 12C2.25 6.615 6.615 2.25 12 2.25S21.75 6.615 21.75 12 17.385 21.75 12 21.75z"/>
            </svg>
            Enviar por WhatsApp
        </button>
    </div>
</div>