<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="description"
        content="GameGuideX, guías y catálogo de videojuegos."
    >

    <title>
        @yield('titulo', 'GameGuideX')
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
</head>

<body>

    {{--
        Intro animada del logo. Se muestra una sola vez por sesión de
        pestaña (sessionStorage), no en cada página — esto es una app
        de varias páginas con recarga completa, así que repetirla en
        cada navegación sería muy molesto. También se omite por
        completo si el visitante prefiere movimiento reducido.
    --}}
    <div id="gtx-intro" class="gtx-intro" hidden>
        <video
            id="gtx-intro-video"
            src="{{ asset('videos/intro-logo.mp4') }}"
            autoplay
            muted
            playsinline
        ></video>

        <button type="button" id="gtx-intro-skip" class="gtx-intro-skip">
            Saltar intro ↦
        </button>
    </div>

    <script>
        (function () {
            var YA_VISTA = 'gtx-intro-vista';
            var prefiereMenosMovimiento = window.matchMedia(
                '(prefers-reduced-motion: reduce)'
            ).matches;

            if (prefiereMenosMovimiento || sessionStorage.getItem(YA_VISTA)) {
                return;
            }

            sessionStorage.setItem(YA_VISTA, '1');

            var intro = document.getElementById('gtx-intro');
            var video = document.getElementById('gtx-intro-video');
            var skip = document.getElementById('gtx-intro-skip');

            intro.hidden = false;
            intro.classList.add('is-visible');
            document.body.classList.add('gtx-intro-activa');

            function cerrar() {
                intro.classList.add('is-hiding');
                document.body.classList.remove('gtx-intro-activa');

                setTimeout(function () {
                    intro.remove();
                }, 400);
            }

            video.addEventListener('ended', cerrar);
            skip.addEventListener('click', cerrar);

            /*
             * Si el video falla en cargar (bloqueo del navegador,
             * archivo no disponible, etc.) no debe dejar al visitante
             * atrapado detrás de la pantalla de intro.
             */
            video.addEventListener('error', cerrar);
        })();
    </script>

    @include('partials.navbar')

    <div class="gtx-shell">

        @include('partials.sidebar')

        <main>

            @if(session('success'))
                <div class="flash-message">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="flash-message flash-message-error">
                    {{ session('error') }}
                </div>
            @endif

            @yield('contenido')

        </main>

        @include('partials.footer')

    </div>

    <script>
        // Abre/cierra el sidebar como drawer en pantallas angostas.
        // Vanilla JS a propósito: es la única interacción del layout
        // que lo necesita, no justifica añadir una librería.
        (function () {
            var toggle = document.getElementById('gtx-sidebar-toggle');
            var sidebar = document.getElementById('gtx-sidebar');
            var backdrop = document.getElementById('gtx-sidebar-backdrop');

            if (!toggle || !sidebar || !backdrop) {
                return;
            }

            function cerrar() {
                sidebar.classList.remove('is-open');
                backdrop.classList.remove('is-open');
                toggle.setAttribute('aria-expanded', 'false');
            }

            toggle.addEventListener('click', function () {
                var abierto = sidebar.classList.toggle('is-open');
                backdrop.classList.toggle('is-open', abierto);
                toggle.setAttribute('aria-expanded', String(abierto));
            });

            backdrop.addEventListener('click', cerrar);
        })();
    </script>

</body>

</html>