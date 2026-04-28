@props(['titulo' => '', 'url' => ''])

<button
    type="button"
    onclick="compartir({{ Js::from($titulo) }}, {{ Js::from($url) }})"
    class="inline-flex items-center gap-2 rounded-full border border-white/30 bg-white/10 hover:bg-white/20 text-white text-sm px-4 py-2 transition backdrop-blur-sm"
>
    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 1 0 0 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186 9.566-5.314m-9.566 7.5 9.566 5.314m0 0a2.25 2.25 0 1 0 3.935 2.186 2.25 2.25 0 0 0-3.935-2.186Zm0-12.814a2.25 2.25 0 1 0 3.935-2.186 2.25 2.25 0 0 0-3.935 2.186Z" />
    </svg>
    Compartir
</button>

<script>
function compartir(titulo, url) {
    if (navigator.share) {
        navigator.share({ title: titulo, url: url }).catch(() => {});
    } else {
        navigator.clipboard.writeText(url).then(function() {
            alert('Enlace copiado al portapapeles');
        });
    }
}
</script>