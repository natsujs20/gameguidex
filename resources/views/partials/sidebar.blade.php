{{--
    Navegación principal de la aplicación. Vive aquí (y no en el header)
    porque en el nuevo layout el header queda para marca + cuenta, y el
    sidebar concentra la navegación real hacia las secciones que existen
    hoy. Solo se listan enlaces con una página real detrás.
--}}
<div class="gtx-sidebar-backdrop" id="gtx-sidebar-backdrop"></div>

<aside class="gtx-sidebar" id="gtx-sidebar">

    <nav class="gtx-sidebar-group">
        <a
            href="{{ route('inicio') }}"
            class="gtx-sidebar-link {{ request()->routeIs('inicio') ? 'active' : '' }}"
        >
            <span class="gtx-icon">⌂</span>
            Inicio
        </a>

        <a
            href="{{ route('guias.index') }}"
            class="gtx-sidebar-link {{ request()->routeIs('guias.index') ? 'active' : '' }}"
        >
            <span class="gtx-icon">▤</span>
            Guías y consejos
        </a>

        <a
            href="{{ route('juegos.index') }}"
            class="gtx-sidebar-link {{ request()->routeIs('juegos.*') ? 'active' : '' }}"
        >
            <span class="gtx-icon">▣</span>
            Juegos
        </a>

        <a
            href="{{ route('favoritos.index') }}"
            class="gtx-sidebar-link {{ request()->routeIs('favoritos.*') ? 'active' : '' }}"
        >
            <span class="gtx-icon">♥</span>
            Favoritos
        </a>
    </nav>

    {{--
        Los Centros no se escriben aquí a mano: llegan del registro
        config/centros.php a través de un View Composer definido en
        AppServiceProvider. Añadir un Centro nuevo no requiere tocar
        esta vista.
    --}}
    @if($centrosDisponibles->isNotEmpty())
        <div class="gtx-sidebar-group">
            <span class="gtx-sidebar-label">Centros de información</span>

            @foreach($centrosDisponibles as $centro)
                <a
                    href="{{ $centro['url'] }}"
                    class="gtx-sidebar-link {{ request()->fullUrlIs($centro['url'].'*') ? 'active' : '' }}"
                >
                    <span class="gtx-icon">▪</span>
                    {{ $centro['nombre'] }}
                </a>
            @endforeach
        </div>
    @endif

</aside>
