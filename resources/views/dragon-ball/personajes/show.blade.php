@extends('layouts.app')

@section('titulo', $personajeDragonBall->nombre.' | GameGuideX')

@section('contenido')

@php
    $retratoExiste = $personajeDragonBall->retrato
        && file_exists(public_path(ltrim($personajeDragonBall->retrato, '/')));

    $ilustracionExiste = $personajeDragonBall->ilustracion
        && file_exists(public_path(ltrim($personajeDragonBall->ilustracion, '/')));
@endphp

<div class="container gtx-page">

    <a href="{{ route('dragon-ball.personajes.index') }}" class="gtx-back">
        ← Volver al catálogo
    </a>

    <div class="gtx-detail-layout">

        <div class="gtx-detail-media gtx-card">
            @if($retratoExiste)
                <img src="{{ asset(ltrim($personajeDragonBall->retrato, '/')) }}" alt="{{ $personajeDragonBall->nombre }}">
            @elseif($ilustracionExiste)
                <img src="{{ asset(ltrim($personajeDragonBall->ilustracion, '/')) }}" alt="{{ $personajeDragonBall->nombre }}">
            @else
                <div class="gtx-item-media-placeholder">
                    {{ mb_strtoupper(mb_substr($personajeDragonBall->nombre, 0, 1)) }}
                </div>
            @endif
        </div>

        <div class="gtx-detail-content">

            <span class="gtx-item-eyebrow">
                ARCHIVO N.º {{ str_pad($personajeDragonBall->orden, 2, '0', STR_PAD_LEFT) }}
            </span>

            <h1>{{ $personajeDragonBall->nombre }}</h1>

            <div class="gtx-tag-row">
                <span>{{ $personajeDragonBall->saga }}</span>
                <span>{{ $personajeDragonBall->alineacion }}</span>
            </div>

            <p class="gtx-detail-description">{{ $personajeDragonBall->descripcion }}</p>

            <div class="gtx-data-grid">
                <div class="gtx-card gtx-data-item">
                    <span>Personaje base</span>
                    <strong>{{ $personajeDragonBall->personaje_base }}</strong>
                </div>
                <div class="gtx-card gtx-data-item">
                    <span>Transformación</span>
                    <strong>{{ $personajeDragonBall->transformacion ?: 'Forma base' }}</strong>
                </div>
                <div class="gtx-card gtx-data-item">
                    <span>Raza</span>
                    <strong>{{ $personajeDragonBall->raza }}</strong>
                </div>
                <div class="gtx-card gtx-data-item">
                    <span>Estilo</span>
                    <strong>{{ $personajeDragonBall->estilo_combate }}</strong>
                </div>
                <div class="gtx-card gtx-data-item">
                    <span>Videojuego</span>
                    <strong>{{ $personajeDragonBall->juego->nombre }}</strong>
                </div>
                <div class="gtx-card gtx-data-item">
                    <span>Puntos DP</span>
                    <strong>{{ $personajeDragonBall->puntos_dp ?: 'Por verificar' }}</strong>
                </div>
            </div>

            <div class="gtx-detail-actions">
                <x-favorito-boton :elemento="$personajeDragonBall" tipo="personaje_dragon_ball" />
            </div>

        </div>
    </div>

    {{-- TÉCNICAS --}}
    <section class="gtx-related">
        <div class="gtx-page-header">
            <h2 class="gtx-section-title">Técnicas y habilidades</h2>
        </div>

        @if($personajeDragonBall->tecnicas->isNotEmpty())
            <div class="gtx-grid">
                @foreach($personajeDragonBall->tecnicas as $tecnica)
                    <div class="gtx-card gtx-info-card">
                        <span class="gtx-item-eyebrow">{{ $tecnica->tipo }}</span>
                        <h3>{{ $tecnica->nombre }}</h3>
                        <p>{{ $tecnica->descripcion }}</p>
                        @if($tecnica->comando || $tecnica->coste_ki)
                            <p class="gtx-note">
                                {{ $tecnica->comando }}
                                {{ $tecnica->coste_ki ? '· '.$tecnica->coste_ki.' barras de Ki' : '' }}
                            </p>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="gtx-empty-state">
                <p>Las técnicas específicas de esta versión se incorporarán después de verificarlas directamente en el juego.</p>
            </div>
        @endif
    </section>

    {{-- TRANSFORMACIONES --}}
    @if($transformaciones->isNotEmpty())
        <section class="gtx-related">
            <div class="gtx-page-header">
                <h2 class="gtx-section-title">Otras transformaciones</h2>
            </div>

            <div class="gtx-grid">
                @foreach($transformaciones as $forma)
                    @php
                        $iconoExiste = $forma->icono
                            && file_exists(public_path(ltrim($forma->icono, '/')));
                    @endphp
                    <a href="{{ route('dragon-ball.personajes.show', $forma) }}" class="gtx-card gtx-item-card">
                        <div class="gtx-item-media gtx-item-media-sm">
                            @if($iconoExiste)
                                <img src="{{ asset(ltrim($forma->icono, '/')) }}" alt="{{ $forma->nombre }}" loading="lazy">
                            @else
                                <div class="gtx-item-media-placeholder">{{ mb_strtoupper(mb_substr($forma->nombre, 0, 1)) }}</div>
                            @endif
                        </div>
                        <div class="gtx-item-body">
                            <span class="gtx-item-eyebrow">{{ $forma->transformacion ?: 'Forma base' }}</span>
                            <h3>{{ $forma->nombre }}</h3>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- DESBLOQUEO --}}
    <section class="gtx-related">
        <div class="gtx-page-header">
            <h2 class="gtx-section-title">Cómo desbloquearlo</h2>
        </div>
        <div class="gtx-card gtx-info-card">
            <p>{{ $personajeDragonBall->desbloqueo }}</p>
        </div>
    </section>

    {{-- RELACIONADOS --}}
    @if($relacionados->isNotEmpty())
        <section class="gtx-related">
            <div class="gtx-page-header">
                <h2 class="gtx-section-title">Personajes relacionados</h2>
            </div>

            <div class="gtx-grid">
                @foreach($relacionados as $relacionado)
                    @php
                        $ilustracionRelExiste = $relacionado->ilustracion
                            && file_exists(public_path(ltrim($relacionado->ilustracion, '/')));
                    @endphp
                    <a href="{{ route('dragon-ball.personajes.show', $relacionado) }}" class="gtx-card gtx-item-card">
                        <div class="gtx-item-media">
                            @if($ilustracionRelExiste)
                                <img src="{{ asset(ltrim($relacionado->ilustracion, '/')) }}" alt="{{ $relacionado->nombre }}" loading="lazy">
                            @else
                                <div class="gtx-item-media-placeholder">{{ mb_strtoupper(mb_substr($relacionado->nombre, 0, 1)) }}</div>
                            @endif
                        </div>
                        <div class="gtx-item-body">
                            <span class="gtx-item-eyebrow">{{ $relacionado->saga }}</span>
                            <h3>{{ $relacionado->nombre }}</h3>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

</div>

@endsection
