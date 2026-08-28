@extends('layouts.app')

@section('titulo', 'GameGuideX | Guías para videojuegos')

@section('contenido')

    {{--
        Se muestra el juego más reciente del catálogo real (mismo dato que
        ya se consulta para la grilla de abajo) como fondo del hero, en vez
        de un juego fijo en el código.
    --}}
    @php
        $juegoHero = $juegosDestacados->first();
    @endphp

    <section
        class="gtx-hero"
        @if($juegoHero?->imagen_url)
            style="background-image: url('{{ $juegoHero->imagen_url }}');"
        @endif
    >
        <div class="gtx-hero-scrim"></div>

        <div class="container gtx-hero-content">

            @if($juegoHero)
                <span class="gtx-hero-label">DESTACADO · {{ $juegoHero->genero ?? 'VIDEOJUEGO' }}</span>
                <h1>{{ $juegoHero->nombre }}</h1>
            @else
                <span class="gtx-hero-label">TU CENTRO DE GUÍAS GAMER</span>
                <h1>Juega mejor. Encuentra todo.</h1>
            @endif

            <p>
                Guías, materiales, objetos, misiones y consejos explicados
                de forma clara para que pases menos tiempo buscando y más
                tiempo jugando.
            </p>

            <div class="gtx-hero-actions">
                @if($juegoHero)
                    <a href="{{ route('juegos.show', $juegoHero) }}" class="gtx-btn gtx-btn-primary">
                        Ver ficha completa
                    </a>
                @endif

                <a href="{{ route('guias.index') }}" class="gtx-btn gtx-btn-secondary">
                    Explorar guías
                </a>
            </div>
        </div>
    </section>

    <div class="container gtx-page">

        {{-- CENTROS DE INFORMACIÓN --}}
        <div class="gtx-page-header">
            <h2 class="gtx-section-title">Centros de información</h2>
        </div>

        <div class="gtx-centros-grid gtx-section">
            @foreach($centros as $centro)
                <x-centro-card :centro="$centro" />
            @endforeach
        </div>

        {{-- GUÍAS DESTACADAS --}}
        <div class="gtx-page-header">
            <h2 class="gtx-section-title">Guías destacadas</h2>
        </div>

        @if($guiasDestacadas->isNotEmpty())
            <div class="gtx-guias-grid gtx-section">
                @foreach($guiasDestacadas as $guia)
                    <a href="{{ route('guias.show', $guia) }}" class="gtx-card gtx-guia-card">
                        <span class="gtx-guia-tipo">{{ $guia->tipo }}</span>
                        <h3>{{ $guia->titulo }}</h3>
                        @if($guia->juego)<p>{{ $guia->juego->nombre }}</p>@endif
                        <strong>Leer guía →</strong>
                    </a>
                @endforeach
            </div>
        @else
            <div class="gtx-empty-state gtx-section">
                <strong>Todavía no hay guías destacadas</strong>
                <p>Estamos preparando el contenido de las próximas guías.</p>
            </div>
        @endif

        {{-- VIDEOJUEGOS --}}
        <div class="gtx-page-header">
            <h2 class="gtx-section-title">Videojuegos destacados</h2>
            <a href="{{ route('juegos.index') }}" class="gtx-heading-link">
                Ver catálogo completo →
            </a>
        </div>

        @if($juegosDestacados->isNotEmpty())
            <div class="gtx-grid">
                @foreach($juegosDestacados as $juego)
                    <a href="{{ route('juegos.show', $juego) }}" class="gtx-card gtx-item-card">
                        <div class="gtx-item-media">
                            @if($juego->imagen_url)
                                <img src="{{ $juego->imagen_url }}" alt="{{ $juego->nombre }}">
                            @else
                                <div class="gtx-item-media-placeholder">GGX</div>
                            @endif
                            @if($juego->anio)<span class="gtx-item-badge">{{ $juego->anio }}</span>@endif
                        </div>
                        <div class="gtx-item-body">
                            <span class="gtx-item-eyebrow">{{ $juego->genero ?? 'Videojuego' }}</span>
                            <h3>{{ $juego->nombre }}</h3>
                            <strong>Ver videojuego →</strong>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="gtx-empty-state">
                <strong>Próximamente más videojuegos</strong>
                <p>Estamos preparando nuevos títulos para el catálogo.</p>
            </div>
        @endif

    </div>

@endsection
