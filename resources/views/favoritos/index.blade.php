@extends('layouts.app')

@section('titulo', 'Favoritos | GameGuideX')

@section('contenido')

<div class="container gtx-page">

    <div class="gtx-page-header">
        <span class="gtx-item-eyebrow">COLECCIÓN PERSONAL</span>
        <h1>Mis favoritos</h1>
    </div>

    @if($total === 0)

        <div class="gtx-empty-state">
            <strong>Todavía no hay favoritos guardados</strong>
            <p>
                Usa el botón "Agregar a favoritos" en la ficha de un videojuego,
                monstruo, guía o personaje para verlo aquí.
            </p>
            <a href="{{ route('juegos.index') }}" class="gtx-btn gtx-btn-primary">
                Explorar catálogo
            </a>
        </div>

    @else

        <div class="gtx-results-summary">
            <span>{{ $total }} {{ $total === 1 ? 'favorito' : 'favoritos' }}</span>
        </div>

        @if($juegos->isNotEmpty())
            <div class="gtx-page-header">
                <h2 class="gtx-section-title">Videojuegos</h2>
            </div>
            <div class="gtx-grid gtx-section">
                @foreach($juegos as $juego)
                    <x-elemento-card :elemento="$juego" />
                @endforeach
            </div>
        @endif

        @if($monstruos->isNotEmpty())
            <div class="gtx-page-header">
                <h2 class="gtx-section-title">Monstruos</h2>
            </div>
            <div class="gtx-grid gtx-section">
                @foreach($monstruos as $monstruo)
                    <x-elemento-card :elemento="$monstruo" />
                @endforeach
            </div>
        @endif

        @if($guias->isNotEmpty())
            <div class="gtx-page-header">
                <h2 class="gtx-section-title">Guías</h2>
            </div>
            <div class="gtx-grid gtx-section">
                @foreach($guias as $guia)
                    <x-elemento-card :elemento="$guia" />
                @endforeach
            </div>
        @endif

        @if($personajesDragonBall->isNotEmpty())
            <div class="gtx-page-header">
                <h2 class="gtx-section-title">Personajes de Dragon Ball</h2>
            </div>
            <div class="gtx-grid gtx-section">
                @foreach($personajesDragonBall as $personaje)
                    <x-elemento-card :elemento="$personaje" />
                @endforeach
            </div>
        @endif

    @endif

</div>

@endsection
