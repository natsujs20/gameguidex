@props(['centro'])

{{--
    Tarjeta de un Centro de Información.

    Componente único usado por la portada y por la página de guías, para
    que un Centro se vea igual en todo el sitio. Los datos llegan ya
    resueltos desde App\Services\CentrosInformacion: la vista no consulta
    la base de datos.

    Los contadores que muestra son reales. Las categorías sin contenido
    las descarta el servicio, así que aquí nunca se anuncia una sección
    vacía.
--}}

@php
    $etiqueta = 'gtx-card gtx-centro-card gtx-centro-card-cover'
        . ($centro['disponible'] ? '' : ' gtx-is-upcoming');

    $fondo = $centro['imagen']
        ? "background-image: linear-gradient(180deg, rgba(10,10,12,0.35), rgba(10,10,12,0.92) 75%), url('{$centro['imagen']}');"
        : null;
@endphp

<{{ $centro['disponible'] ? 'a' : 'div' }}
    @if($centro['disponible']) href="{{ $centro['url'] }}" @endif
    class="{{ $etiqueta }}"
    @if($fondo) style="{{ $fondo }}" @endif
>

    <div>
        @unless($centro['disponible'])
            <span class="gtx-item-badge gtx-badge-inline gtx-badge-muted">
                PRÓXIMAMENTE
            </span>
        @endunless

        <h3>{{ $centro['nombre'] }}</h3>
        <p>{{ $centro['descripcion'] }}</p>
    </div>

    <div class="gtx-centro-footer">
        @if(count($centro['categorias']) > 0)
            <div class="gtx-centro-categorias">
                @foreach($centro['categorias'] as $categoria)
                    <span>
                        <strong>{{ $categoria['total'] }}</strong>
                        {{ $categoria['nombre'] }}
                    </span>
                @endforeach
            </div>
        @else
            <strong>En preparación</strong>
        @endif

        @if($centro['disponible'])
            <span class="gtx-centro-entrar">Entrar →</span>
        @endif
    </div>

</{{ $centro['disponible'] ? 'a' : 'div' }}>
