{{--
    FOOTER

    Minimalista y solo con enlaces a rutas que existen. No se incluyen
    redes sociales, newsletter ni enlaces legales porque todavía no hay
    ninguna página real detrás de ellos.
--}}
<footer class="footer">

    <div class="container footer-content">

        <div class="footer-brand">
            <strong>GameGuideX</strong>
            <p>
                Guías, consejos y recursos para encontrar lo que necesitas
                en tus videojuegos.
            </p>
        </div>

        <nav class="footer-links" aria-label="Navegación del pie de página">
            <span>Navegación</span>
            <a href="{{ route('inicio') }}">Explorar</a>
            <a href="{{ route('juegos.index') }}">Juegos</a>
            <a href="{{ route('guias.index') }}">Guías</a>
        </nav>

        <nav class="footer-links" aria-label="Centros de información">
            <span>Centros</span>
            <a href="{{ route('guias.monster-hunter') }}">Monster Hunter</a>
            <a href="{{ route('guias.dragon-ball') }}">Dragon Ball</a>
            <a href="{{ route('monstruos.index') }}">Monstruos</a>
        </nav>

    </div>

    <div class="container footer-bottom">
        © {{ date('Y') }} GameGuideX
    </div>

</footer>
