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