/*
 * Steam entrega los tráilers como streams HLS (.m3u8), no como mp4
 * directos. Safari los reproduce nativo; el resto de navegadores
 * necesita hls.js para poder mostrarlos en un <video> normal.
 *
 * hls.js (~180KB) solo se descarga en páginas que realmente tienen
 * un tráiler, para no pesarle la carga al resto del sitio.
 */
document.querySelectorAll('[data-hls-src]').forEach(async (contenedor) => {
    const video = contenedor.querySelector('video');
    const url = contenedor.dataset.hlsSrc;

    if (!video || !url) {
        return;
    }

    const { default: Hls } = await import('hls.js');

    /*
     * Hls.isSupported() comprueba Media Source Extensions, que es lo
     * que usan Chrome/Firefox/Edge. canPlayType() para HLS no es
     * confiable ahí: algunos Chromium devuelven "maybe" sin poder
     * reproducirlo en realidad. Por eso hls.js va primero, y el
     * <video src> nativo queda solo como respaldo para navegadores
     * sin MSE (Safari/iOS, que sí reproducen HLS de forma nativa).
     */
    if (Hls.isSupported()) {
        const hls = new Hls();
        hls.loadSource(url);
        hls.attachMedia(video);
        return;
    }

    if (video.canPlayType('application/vnd.apple.mpegurl')) {
        video.src = url;
        return;
    }

    /*
     * Navegador sin soporte HLS ni hls.js disponible: ocultar el
     * reproductor y dejar visible el enlace de respaldo.
     */
    contenedor.hidden = true;
});
