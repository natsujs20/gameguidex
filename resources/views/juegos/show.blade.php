@extends('layouts.app')

@section('titulo', $juego->nombre . ' | GameGuideX')

@section('contenido')

<div class="container gtx-page">

    <a href="{{ route('juegos.index') }}" class="gtx-back">
        ← Volver al catálogo de juegos
    </a>

    <div class="gtx-detail-layout">

        <div class="gtx-detail-col">
            <div class="gtx-detail-media gtx-card">
                @if($juego->imagen_url)
                    <img src="{{ $juego->imagen_url }}" alt="{{ $juego->nombre }}">
                @else
                    <div class="gtx-item-media-placeholder">GAME</div>
                @endif
            </div>

            @if($juego->trailer_url)
                <div class="gtx-trailer gtx-card" data-hls-src="{{ $juego->trailer_url }}">
                    <video controls playsinline preload="none"
                        @if($juego->imagen_url) poster="{{ $juego->imagen_url }}" @endif
                    ></video>
                </div>
            @endif
        </div>

        <div class="gtx-detail-content">

            <span class="gtx-item-eyebrow">FICHA DE VIDEOJUEGO</span>
            <h1>{{ $juego->nombre }}</h1>

            <div class="gtx-tag-row">
                @if($juego->franquicia)<span>{{ $juego->franquicia }}</span>@endif
                @if($juego->genero)<span>{{ $juego->genero }}</span>@endif
                @if($juego->anio)<span>{{ $juego->anio }}</span>@endif
            </div>

            <p class="gtx-detail-description">{{ $juego->descripcion }}</p>

            <div class="gtx-data-grid">
                <div class="gtx-card gtx-data-item">
                    <span>Año de lanzamiento</span>
                    <strong>{{ $juego->anio ?? '—' }}</strong>
                </div>
                <div class="gtx-card gtx-data-item">
                    <span>Plataformas</span>
                    <strong>{{ $juego->plataformas ?? '—' }}</strong>
                </div>
                <div class="gtx-card gtx-data-item">
                    <span>Género</span>
                    <strong>{{ $juego->genero ?? '—' }}</strong>
                </div>
                <div class="gtx-card gtx-data-item">
                    <span>Desarrollador</span>
                    <strong>{{ $juego->desarrollador ?? '—' }}</strong>
                </div>
                <div class="gtx-card gtx-data-item">
                    <span>Disponibilidad</span>
                    <strong>{{ $juego->estado_disponibilidad ?? '—' }}</strong>
                </div>
            </div>

            <div class="gtx-detail-actions">
                @if($juego->enlace_oficial)
                    <a
                        href="{{ $juego->enlace_oficial }}"
                        class="gtx-btn gtx-btn-primary"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        ↗ {{ $juego->texto_enlace ?? 'Ver página oficial' }}
                    </a>
                @else
                    <div class="gtx-empty-state">
                        <strong>Sin descarga oficial</strong>
                        <p>Este juego actualmente no posee una venta o descarga digital oficial.</p>
                    </div>
                @endif

                @if($juego->trailer_url)
                    <a
                        href="{{ $juego->trailer_url }}"
                        class="gtx-btn gtx-btn-secondary"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        ▶ Ver tráiler
                    </a>
                @endif

                <x-favorito-boton :elemento="$juego" tipo="juego" />
                <x-jugado-boton :juego="$juego" />
            </div>

            @if($juego->enlace_emulador)
                <div class="gtx-card gtx-emulation-box">
                    <span class="gtx-item-eyebrow">EMULACIÓN Y JUEGOS CLÁSICOS</span>
                    <h2>Juega mediante un emulador compatible</h2>
                    <p>
                        Este juego fue publicado originalmente para
                        {{ $juego->plataforma_emulada }}. Puedes visitar la página
                        oficial de {{ $juego->nombre_emulador }} para conocer sus
                        versiones disponibles y requisitos.
                    </p>

                    <a
                        href="{{ $juego->enlace_emulador }}"
                        class="gtx-btn gtx-btn-secondary"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        ⬇ Descargar {{ $juego->nombre_emulador }} desde su sitio oficial
                    </a>

                    <p class="gtx-legal-note">
                        ⓘ GameGuideX no aloja ni distribuye ROM, ISO, BIOS ni otros
                        archivos protegidos. El emulador no incluye este juego.
                        Utiliza únicamente copias de seguridad obtenidas legalmente
                        desde juegos que poseas y respeta la legislación aplicable
                        en tu país.
                    </p>
                </div>
            @endif

        </div>
    </div>

    @if($relacionados->isNotEmpty())
        <section class="gtx-related">
            <div class="gtx-page-header">
                <h2 class="gtx-section-title">Videojuegos relacionados</h2>
            </div>

            <div class="gtx-grid">
                @foreach($relacionados as $relacionado)
                    <a href="{{ route('juegos.show', $relacionado) }}" class="gtx-card gtx-item-card">
                        <div class="gtx-item-media">
                            @if($relacionado->imagen_url)
                                <img src="{{ $relacionado->imagen_url }}" alt="{{ $relacionado->nombre }}" loading="lazy">
                            @else
                                <div class="gtx-item-media-placeholder">GGX</div>
                            @endif
                        </div>
                        <div class="gtx-item-body">
                            <span class="gtx-item-eyebrow">{{ $relacionado->genero ?? 'Videojuego' }}</span>
                            <h3>{{ $relacionado->nombre }}</h3>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

</div>

@endsection
