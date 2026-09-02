{{--
    HEADER PRINCIPAL DE GAMEGUIDEX

    Contiene marca, navegación de primer nivel, buscador y área de
    cuenta. La navegación por Centros de Información y categorías vive
    en el sidebar (partials/sidebar.blade.php), no aquí.

    Solo se enlazan rutas que existen realmente en routes/web.php.
    Secciones de la referencia visual como "Herramientas" o "Noticias"
    no se incluyen porque todavía no tienen página detrás.
--}}
<header class="gtx-header">

    <div class="gtx-header-inner">

        <div class="gtx-header-start">
            <button
                type="button"
                class="gtx-header-menu"
                id="gtx-sidebar-toggle"
                aria-label="Abrir menú de navegación"
                aria-expanded="false"
                aria-controls="gtx-sidebar"
            >
                ☰
            </button>

            <a href="{{ route('inicio') }}" class="gtx-brand">
                {{--
                    En pantallas angostas el logo completo (ícono +
                    texto) choca con el resto del header, así que se
                    usa solo el ícono cuadrado por debajo de 480px.
                --}}
                <picture>
                    <source
                        srcset="{{ asset('imagenes/logo-icono.png') }}"
                        media="(max-width: 480px)"
                    >
                    <img
                        src="{{ asset('imagenes/logo.png') }}"
                        alt="GameGuideX"
                        class="gtx-brand-mark"
                    >
                </picture>
            </a>

            <nav class="gtx-header-nav" aria-label="Navegación principal">
                <a
                    href="{{ route('inicio') }}"
                    class="{{ request()->routeIs('inicio') ? 'active' : '' }}"
                >
                    Explorar
                </a>

                <a
                    href="{{ route('juegos.index') }}"
                    class="{{ request()->routeIs('juegos.*') ? 'active' : '' }}"
                >
                    Juegos
                </a>

                <a
                    href="{{ route('guias.index') }}"
                    class="{{ request()->routeIs('guias.*') ? 'active' : '' }}"
                >
                    Guías
                </a>
            </nav>
        </div>

        {{--
            Buscador global: consulta juegos, guías, monstruos y
            personajes a la vez (BusquedaController@index).
        --}}
        <form
            action="{{ route('busqueda.index') }}"
            method="GET"
            class="gtx-header-search"
            role="search"
        >
            <span aria-hidden="true">⌕</span>

            <label for="busqueda-global" class="gtx-sr-only">
                Buscar en GameGuideX
            </label>

            <input
                id="busqueda-global"
                type="search"
                name="buscar"
                value="{{ request()->routeIs('busqueda.index') ? request('buscar') : '' }}"
                placeholder="Buscar en GameGuideX..."
            >
        </form>

        <div class="gtx-header-account">

            @auth

                <a href="{{ route('perfil.index') }}" class="gtx-user">
                    <span class="gtx-user-avatar" aria-hidden="true">
                        {{ mb_strtoupper(mb_substr(auth()->user()->nombre, 0, 1)) }}
                    </span>

                    <span class="gtx-user-name">
                        {{ auth()->user()->nombre }}
                    </span>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit" class="gtx-header-link">
                        Salir
                    </button>
                </form>

            @else

                <a href="{{ route('login') }}" class="gtx-header-link">
                    Iniciar sesión
                </a>

                <a href="{{ route('register') }}" class="gtx-btn gtx-btn-primary gtx-btn-sm">
                    Crear cuenta
                </a>

            @endauth

        </div>

    </div>

</header>
