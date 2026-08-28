@extends('layouts.app')

@section('titulo', ($buscar !== '' ? '"'.$buscar.'" — Búsqueda' : 'Búsqueda').' | GameGuideX')

@section('contenido')

<div class="container gtx-page">

    <div class="gtx-page-header">
        <h1>Búsqueda</h1>
        <p>
            Busca en todo GameGuideX a la vez: videojuegos, guías, monstruos
            y personajes de Dragon Ball.
        </p>
    </div>

    <form action="{{ route('busqueda.index') }}" method="GET" class="gtx-filter-bar">
        <div class="gtx-filter-field gtx-filter-grow">
            <label for="buscar">Buscar</label>
            <input id="buscar" type="search" name="buscar" value="{{ $buscar }}" placeholder="Goku, Rathalos, cómo derrotar a...">
        </div>

        <button type="submit" class="gtx-btn gtx-btn-primary">Buscar</button>

        @if($buscar !== '')
            <a href="{{ route('busqueda.index') }}" class="gtx-btn gtx-btn-secondary">Limpiar</a>
        @endif
    </form>

    @if($buscar === '')

        <div class="gtx-empty-state">
            <strong>Escribe algo para empezar</strong>
            <p>Por ejemplo un personaje, un monstruo, un videojuego o el nombre de una guía.</p>
        </div>

    @elseif($totalResultados === 0)

        <div class="gtx-empty-state">
            <strong>No encontramos nada para "{{ $buscar }}"</strong>
            <p>Prueba con otro nombre o revisa que esté bien escrito.</p>
        </div>

    @else

        <div class="gtx-results-summary">
            <span>{{ $totalResultados }} {{ $totalResultados === 1 ? 'resultado' : 'resultados' }} para "{{ $buscar }}"</span>
        </div>

        @if($juegos->isNotEmpty())
            <div class="gtx-page-header">
                <h2 class="gtx-section-title">Videojuegos</h2>
            </div>

            <div class="gtx-grid gtx-section">
                @foreach($juegos as $juego)
                    <a href="{{ route('juegos.show', $juego) }}" class="gtx-card gtx-item-card">
                        <div class="gtx-item-body">
                            <span class="gtx-item-eyebrow">{{ $juego->franquicia }}</span>
                            <h3>{{ $juego->nombre }}</h3>
                            <p>{{ \Illuminate\Support\Str::limit($juego->descripcion, 110) }}</p>
                            <strong>Ver ficha completa →</strong>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif

        @if($guias->isNotEmpty())
            <div class="gtx-page-header">
                <h2 class="gtx-section-title">Guías</h2>
            </div>

            <div class="gtx-grid gtx-section">
                @foreach($guias as $guia)
                    <a href="{{ route('guias.show', $guia) }}" class="gtx-card gtx-item-card">
                        <div class="gtx-item-body">
                            <div class="gtx-tag-row gtx-tag-row-flush">
                                <span>{{ $guia->tipo }}</span>
                            </div>
                            @if($guia->juego)<span class="gtx-item-eyebrow">{{ $guia->juego->nombre }}</span>@endif
                            <h3>{{ $guia->titulo }}</h3>
                            <p>{{ \Illuminate\Support\Str::limit($guia->descripcion, 110) }}</p>
                            <strong>Leer guía completa →</strong>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif

        @if($monstruos->isNotEmpty())
            <div class="gtx-page-header">
                <h2 class="gtx-section-title">Monstruos</h2>
            </div>

            <div class="gtx-grid gtx-section">
                @foreach($monstruos as $monstruo)
                    <a href="{{ route('monstruos.show', $monstruo) }}" class="gtx-card gtx-item-card">
                        <div class="gtx-item-body">
                            <div class="gtx-tag-row gtx-tag-row-flush">
                                @if($monstruo->especie)<span>{{ $monstruo->especie }}</span>@endif
                                @if($monstruo->elemento)<span>{{ $monstruo->elemento }}</span>@endif
                            </div>
                            <h3>{{ $monstruo->nombre }}</h3>
                            <p>{{ \Illuminate\Support\Str::limit($monstruo->descripcion, 110) }}</p>
                            <strong>Ver ficha completa →</strong>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif

        @if($personajesDragonBall->isNotEmpty())
            <div class="gtx-page-header">
                <h2 class="gtx-section-title">Personajes de Dragon Ball</h2>
            </div>

            <div class="gtx-grid gtx-section">
                @foreach($personajesDragonBall as $personaje)
                    <a href="{{ route('dragon-ball.personajes.show', $personaje) }}" class="gtx-card gtx-item-card">
                        <div class="gtx-item-body">
                            <div class="gtx-tag-row gtx-tag-row-flush">
                                @if($personaje->saga)<span>{{ $personaje->saga }}</span>@endif
                                @if($personaje->raza)<span>{{ $personaje->raza }}</span>@endif
                            </div>
                            <h3>{{ $personaje->nombre }}</h3>
                            <p>{{ \Illuminate\Support\Str::limit($personaje->descripcion, 110) }}</p>
                            <strong>Ver ficha →</strong>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif

    @endif

</div>

@endsection
