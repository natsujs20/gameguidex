@extends('layouts.app')

@section('titulo', 'Monster Hunter | GameGuideX')

@section('contenido')

<div class="container gtx-page">

    <a href="{{ route('guias.index') }}" class="gtx-back">
        ← Volver a Guías y consejos
    </a>

    <div class="gtx-page-header">
        <h1>Monster Hunter</h1>
        <p>
            Consulta monstruos, debilidades, partes rompibles, materiales,
            porcentajes de obtención, armas, armaduras, misiones y builds de
            los principales juegos de la saga.
        </p>

        <div class="gtx-detail-actions">
            <a href="{{ route('monstruos.index') }}" class="gtx-btn gtx-btn-primary">
                Explorar monstruos →
            </a>
        </div>
    </div>

    <div class="gtx-stat-row">
        <div class="gtx-stat-pill">
            <strong>{{ $estadisticas['monstruos'] }}</strong>
            <span>Monstruos</span>
        </div>
        <div class="gtx-stat-pill">
            <strong>{{ $estadisticas['juegos'] }}</strong>
            <span>Videojuegos</span>
        </div>
        <div class="gtx-stat-pill">
            <strong>{{ $estadisticas['guias'] }}</strong>
            <span>Guías</span>
        </div>
    </div>

    {{-- CATEGORÍAS --}}
    <div class="gtx-page-header">
        <h2 class="gtx-section-title">¿Qué necesitas encontrar?</h2>
    </div>

    <div class="gtx-grid gtx-grid-wide gtx-section">
        <a href="{{ route('monstruos.index') }}" class="gtx-card gtx-centro-card">
            <div>
                <span class="gtx-item-eyebrow">BESTIARIO DE LA SAGA</span>
                <h3>Monstruos</h3>
                <p>Debilidades, resistencias, hábitats, partes rompibles y recompensas.</p>
            </div>
            <div class="gtx-centro-footer"><span>Explorar →</span></div>
        </a>

        @foreach(['Materiales', 'Armas', 'Armaduras', 'Misiones', 'Builds'] as $categoria)
            <div class="gtx-card gtx-centro-card gtx-is-upcoming">
                <div>
                    <span class="gtx-item-badge gtx-badge-inline gtx-badge-muted">PRÓXIMA SECCIÓN</span>
                    <h3>{{ $categoria }}</h3>
                </div>
                <div class="gtx-centro-footer"><strong>En preparación</strong></div>
            </div>
        @endforeach
    </div>

    {{-- MONSTRUOS DESTACADOS --}}
    <div class="gtx-page-header">
        <h2 class="gtx-section-title">Monstruos destacados</h2>
    </div>

    @if($monstruosDestacados->isNotEmpty())
        <div class="gtx-grid gtx-section">
            @foreach($monstruosDestacados as $monstruo)
                <a href="{{ route('monstruos.show', $monstruo) }}" class="gtx-card gtx-item-card">
                    <div class="gtx-item-media">
                        @if($monstruo->imagen_url)
                            <img src="{{ $monstruo->imagen_url }}" alt="{{ $monstruo->nombre }}" loading="lazy">
                        @else
                            <div class="gtx-item-media-placeholder">{{ $monstruo->inicial }}</div>
                        @endif
                    </div>
                    <div class="gtx-item-body">
                        <span class="gtx-item-eyebrow">{{ $monstruo->juego->nombre }}</span>
                        <h3>{{ $monstruo->nombre }}</h3>
                        <p>{{ $monstruo->especie }}</p>
                        <strong>Consultar ficha →</strong>
                    </div>
                </a>
            @endforeach
        </div>
    @endif

    {{-- VIDEOJUEGOS --}}
    <div class="gtx-page-header">
        <h2 class="gtx-section-title">Videojuegos de la saga</h2>
    </div>

    <div class="gtx-grid gtx-section">
        @foreach($juegos as $juego)
            <div class="gtx-card gtx-item-card">
                <div class="gtx-item-body">
                    <h3>{{ $juego->nombre }}</h3>
                    <div class="gtx-mini-stats">
                        <span>{{ $juego->monstruos_count }} monstruos</span>
                        <span>{{ $juego->materiales_count }} materiales</span>
                        <span>{{ $juego->guias_count }} guías</span>
                    </div>
                    <a href="{{ route('monstruos.index', ['juego' => $juego->id]) }}"><strong>Ver contenido →</strong></a>
                </div>
            </div>
        @endforeach
    </div>

    {{-- GUÍAS --}}
    <div class="gtx-page-header">
        <h2 class="gtx-section-title">Guías recientes</h2>
    </div>

    @if($guiasDestacadas->isNotEmpty())
        <div class="gtx-guias-grid">
            @foreach($guiasDestacadas as $guia)
                <a href="{{ route('guias.show', $guia) }}" class="gtx-card gtx-guia-card">
                    <span class="gtx-guia-tipo">{{ $guia->tipo ?? 'Guía' }}</span>
                    <h3>{{ $guia->titulo }}</h3>
                    <p>{{ $guia->juego->nombre ?? 'Monster Hunter' }}</p>
                    <strong>Leer guía completa →</strong>
                </a>
            @endforeach
        </div>
    @else
        <div class="gtx-empty-state">
            <strong>Las próximas guías aparecerán aquí</strong>
            <p>El centro ya está preparado para mostrar guías, consejos y rutas de progresión.</p>
        </div>
    @endif

</div>

@endsection
