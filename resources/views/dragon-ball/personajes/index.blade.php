@extends('layouts.app')

@section('titulo', 'Personajes de Dragon Ball | GameGuideX')

@section('contenido')

<div class="container gtx-page">

    <a href="{{ route('guias.dragon-ball') }}" class="gtx-back">
        ← Volver al centro de Dragon Ball
    </a>

    <div class="gtx-page-header">
        <h1>Archivo de personajes</h1>
        <p>Busca entre 90 personajes y transformaciones utilizando nombre, saga o raza.</p>
    </div>

    <form method="GET" action="{{ route('dragon-ball.personajes.index') }}" class="gtx-filter-bar">

        <div class="gtx-filter-field gtx-filter-grow">
            <label for="buscar">Buscar personaje</label>
            <input id="buscar" type="search" name="buscar" value="{{ $buscar }}" placeholder="Goku, Cell, Super Saiyan...">
        </div>

        <div class="gtx-filter-field">
            <label for="saga">Saga o grupo</label>
            <select id="saga" name="saga">
                <option value="">Todas las sagas</option>
                @foreach($sagas as $opcion)
                    <option value="{{ $opcion }}" @selected($saga === $opcion)>{{ $opcion }}</option>
                @endforeach
            </select>
        </div>

        <div class="gtx-filter-field">
            <label for="raza">Raza</label>
            <select id="raza" name="raza">
                <option value="">Todas las razas</option>
                @foreach($razas as $opcion)
                    <option value="{{ $opcion }}" @selected($raza === $opcion)>{{ $opcion }}</option>
                @endforeach
            </select>
        </div>

        <div class="gtx-filter-field">
            <label for="orden">Orden</label>
            <select id="orden" name="orden">
                <option value="catalogo" @selected($orden === 'catalogo')>Orden del juego</option>
                <option value="nombre" @selected($orden === 'nombre')>Nombre A-Z</option>
            </select>
        </div>

        <button type="submit" class="gtx-btn gtx-btn-primary">Aplicar filtros</button>

        @if($buscar !== '' || $saga !== '' || $raza !== '')
            <a href="{{ route('dragon-ball.personajes.index') }}" class="gtx-btn gtx-btn-secondary">Limpiar</a>
        @endif

    </form>

    <div class="gtx-results-summary">
        <span>{{ $personajes->total() }} {{ $personajes->total() === 1 ? 'resultado' : 'resultados' }}</span>
    </div>

    @if($personajes->isNotEmpty())

        <div class="gtx-grid">
            @foreach($personajes as $personaje)
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
                        <span class="gtx-item-badge">{{ str_pad($personaje->orden, 2, '0', STR_PAD_LEFT) }}</span>
                    </div>

                    <div class="gtx-item-body">
                        <span class="gtx-item-eyebrow">{{ $personaje->saga }}</span>
                        <h3>{{ $personaje->nombre }}</h3>
                        <p>{{ $personaje->transformacion ?: $personaje->raza }}</p>

                        <div class="gtx-mini-stats">
                            <span>{{ $personaje->estilo_combate }}</span>
                        </div>

                        <strong>Ver ficha →</strong>
                    </div>
                </a>
            @endforeach
        </div>

        @if($personajes->hasPages())
            <div class="gtx-pagination">
                @if($personajes->onFirstPage())
                    <span class="is-disabled">← Anterior</span>
                @else
                    <a href="{{ $personajes->previousPageUrl() }}">← Anterior</a>
                @endif

                <span>Página {{ $personajes->currentPage() }} de {{ $personajes->lastPage() }}</span>

                @if($personajes->hasMorePages())
                    <a href="{{ $personajes->nextPageUrl() }}">Siguiente →</a>
                @else
                    <span class="is-disabled">Siguiente →</span>
                @endif
            </div>
        @endif

    @else

        <div class="gtx-empty-state">
            <strong>No encontramos personajes</strong>
            <p>Prueba con otro nombre o elimina algunos filtros.</p>
            <a href="{{ route('dragon-ball.personajes.index') }}" class="gtx-btn gtx-btn-primary">
                Mostrar el catálogo completo
            </a>
        </div>

    @endif

</div>

@endsection
