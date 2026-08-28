@extends('layouts.app')

@section('titulo', 'Dragon Ball | GameGuideX')

@section('contenido')

<div class="container gtx-page">

    <a href="{{ route('guias.index') }}" class="gtx-back">
        ← Volver a Guías y consejos
    </a>

    <div class="gtx-page-header">
        <h1>Dragon Ball — Archivo de guerreros</h1>
        <p>
            Explora personajes, transformaciones, técnicas, desbloqueos y
            contenido de los videojuegos de Dragon Ball. El primer catálogo
            disponible corresponde a Budokai Tenkaichi 3.
        </p>

        <div class="gtx-detail-actions">
            <a href="{{ route('dragon-ball.personajes.index') }}" class="gtx-btn gtx-btn-primary">
                Explorar {{ $estadisticas['personajes'] }} personajes →
            </a>
        </div>
    </div>

    <div class="gtx-stat-row">
        <div class="gtx-stat-pill">
            <strong>{{ $estadisticas['personajes'] }}</strong>
            <span>Personajes y formas</span>
        </div>
        <div class="gtx-stat-pill">
            <strong>{{ $estadisticas['sagas'] }}</strong>
            <span>Sagas y grupos</span>
        </div>
        <div class="gtx-stat-pill">
            <strong>{{ $estadisticas['transformaciones'] }}</strong>
            <span>Transformaciones</span>
        </div>
        <div class="gtx-stat-pill">
            <strong>{{ $estadisticas['juegos'] }}</strong>
            <span>Videojuegos</span>
        </div>
    </div>

    {{-- CATEGORÍAS RÁPIDAS --}}
    <div class="gtx-grid gtx-grid-wide gtx-section">
        <a href="{{ route('dragon-ball.personajes.index') }}" class="gtx-card gtx-centro-card">
            <div>
                <span class="gtx-item-eyebrow">PLANTEL COMPLETO</span>
                <h3>Personajes</h3>
                <p>{{ $estadisticas['personajes'] }} guerreros, villanos y transformaciones.</p>
            </div>
            <div class="gtx-centro-footer"><span>Explorar →</span></div>
        </a>

        <a href="{{ route('dragon-ball.personajes.index', ['raza' => 'Saiyan']) }}" class="gtx-card gtx-centro-card">
            <div>
                <span class="gtx-item-eyebrow">PODER SAIYAN</span>
                <h3>Saiyans</h3>
                <p>Goku, Vegeta, Bardock, Broly y sus formas.</p>
            </div>
            <div class="gtx-centro-footer"><span>Ver categoría →</span></div>
        </a>

        <a href="{{ route('dragon-ball.personajes.index', ['saga' => 'Fusiones']) }}" class="gtx-card gtx-centro-card">
            <div>
                <span class="gtx-item-eyebrow">GUERREROS COMBINADOS</span>
                <h3>Fusiones</h3>
                <p>Gogeta, Vegetto y Gotenks en distintas formas.</p>
            </div>
            <div class="gtx-centro-footer"><span>Ver fusiones →</span></div>
        </a>

        <a href="{{ route('dragon-ball.personajes.index', ['saga' => 'Dragon Ball GT']) }}" class="gtx-card gtx-centro-card">
            <div>
                <span class="gtx-item-eyebrow">ARCHIVO ALTERNATIVO</span>
                <h3>Dragon Ball GT</h3>
                <p>Super Saiyan 4, Baby Vegeta y Super 17.</p>
            </div>
            <div class="gtx-centro-footer"><span>Explorar GT →</span></div>
        </a>
    </div>

    {{-- DESTACADOS --}}
    <div class="gtx-page-header">
        <h2 class="gtx-section-title">Guerreros destacados</h2>
    </div>

    @if($personajesDestacados->isNotEmpty())
        <div class="gtx-grid gtx-section">
            @foreach($personajesDestacados as $personaje)
                @php
                    $ilustracionExiste = $personaje->ilustracion
                        && file_exists(public_path(ltrim($personaje->ilustracion, '/')));
                @endphp
                <a href="{{ route('dragon-ball.personajes.show', $personaje) }}" class="gtx-card gtx-item-card">
                    <div class="gtx-item-media">
                        @if($ilustracionExiste)
                            <img src="{{ asset(ltrim($personaje->ilustracion, '/')) }}" alt="{{ $personaje->nombre }}" loading="lazy">
                        @else
                            <div class="gtx-item-media-placeholder">{{ mb_strtoupper(mb_substr($personaje->nombre, 0, 1)) }}</div>
                        @endif
                    </div>
                    <div class="gtx-item-body">
                        <span class="gtx-item-eyebrow">{{ $personaje->saga }}</span>
                        <h3>{{ $personaje->nombre }}</h3>
                        <p>{{ $personaje->transformacion ?: $personaje->raza }}</p>
                        <strong>Ver ficha →</strong>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="gtx-empty-state gtx-section">
            <strong>Sin personajes cargados</strong>
            <p>Ejecuta el seeder de Dragon Ball para cargar los 90 personajes del archivo.</p>
        </div>
    @endif

    {{-- JUEGOS INTEGRADOS --}}
    <div class="gtx-page-header">
        <h2 class="gtx-section-title">El archivo por videojuego</h2>
        <p>El catálogo distingue el origen de cada dato para evitar mezclar estadísticas entre entregas.</p>
    </div>

    <div class="gtx-grid">
        @foreach($juegos as $juego)
            @php $disponible = str_contains($juego->nombre, 'Tenkaichi 3'); @endphp
            <div class="gtx-card gtx-item-card {{ $disponible ? '' : 'gtx-is-upcoming' }}">
                <div class="gtx-item-media">
                    @if($juego->imagen_url)
                        <img src="{{ $juego->imagen_url }}" alt="{{ $juego->nombre }}" loading="lazy">
                    @else
                        <div class="gtx-item-media-placeholder">DB</div>
                    @endif
                    <span class="gtx-item-badge">{{ $disponible ? 'CATÁLOGO DISPONIBLE' : 'PRÓXIMA EXPANSIÓN' }}</span>
                </div>
                <div class="gtx-item-body">
                    <h3>{{ $juego->nombre }}</h3>
                    <p>{{ $juego->plataformas }}</p>
                    @if($disponible)
                        <a href="{{ route('dragon-ball.personajes.index') }}"><strong>Entrar al catálogo →</strong></a>
                    @else
                        <strong>En preparación</strong>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

</div>

@endsection
