@extends('layouts.app')

@section('titulo', 'Monstruos de Monster Hunter | GameGuideX')

@section('contenido')

<div class="container gtx-page">

    <div class="gtx-page-header">
        <h1>Enciclopedia de monstruos</h1>
        <p>
            Consulta debilidades, partes rompibles, estrategias, materiales y
            porcentajes de obtención de los monstruos disponibles en cada
            videojuego de Monster Hunter.
        </p>
    </div>

    @if($juegos->isNotEmpty())
        <div class="gtx-tabs">
            <a href="{{ route('monstruos.index') }}" class="{{ !$juegoId ? 'active' : '' }}">
                Todos <span>{{ $juegos->sum('monstruos_count') }}</span>
            </a>

            @foreach($juegos as $juego)
                <a
                    href="{{ route('monstruos.index', ['juego' => $juego->id]) }}"
                    class="{{ $juegoId === $juego->id ? 'active' : '' }}"
                >
                    {{ $juego->nombre }} <span>{{ $juego->monstruos_count }}</span>
                </a>
            @endforeach
        </div>
    @endif

    @if($monstruosDestacados->isNotEmpty() && !$juegoId && $buscar === '' && $especie === '')
        <div class="gtx-page-header gtx-page-header-spaced">
            <h2 class="gtx-section-title">Monstruos destacados</h2>
        </div>

        <div class="gtx-grid gtx-section">
            @foreach($monstruosDestacados as $destacado)
                <a href="{{ route('monstruos.show', $destacado) }}" class="gtx-card gtx-item-card">
                    <div class="gtx-item-media">
                        @if($destacado->imagen_url)
                            <img src="{{ $destacado->imagen_url }}" alt="{{ $destacado->nombre }}" loading="lazy">
                        @else
                            <div class="gtx-item-media-placeholder">{{ $destacado->inicial }}</div>
                        @endif
                        <span class="gtx-item-badge">{{ $destacado->juego->nombre }}</span>
                    </div>
                    <div class="gtx-item-body">
                        <span class="gtx-item-eyebrow">{{ $destacado->especie }}</span>
                        <h3>{{ $destacado->nombre }}</h3>
                        <p>{{ \Illuminate\Support\Str::limit($destacado->descripcion, 90) }}</p>
                        <strong>Consultar monstruo →</strong>
                    </div>
                </a>
            @endforeach
        </div>
    @endif

    <form action="{{ route('monstruos.index') }}" method="GET" class="gtx-filter-bar">

        <div class="gtx-filter-field gtx-filter-grow">
            <label for="buscar">Buscar</label>
            <input
                id="buscar"
                type="search"
                name="buscar"
                value="{{ $buscar }}"
                placeholder="Nombre, especie o material"
            >
        </div>

        @if($juegoId)
            <input type="hidden" name="juego" value="{{ $juegoId }}">
        @endif

        <div class="gtx-filter-field">
            <label for="especie">Especie</label>
            <select id="especie" name="especie">
                <option value="">Todas</option>
                @foreach($especies as $opcionEspecie)
                    <option value="{{ $opcionEspecie }}" @selected($especie === $opcionEspecie)>
                        {{ $opcionEspecie }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="gtx-filter-field">
            <label for="orden">Ordenar</label>
            <select id="orden" name="orden">
                <option value="destacados" @selected($orden === 'destacados')>Destacados</option>
                <option value="nombre" @selected($orden === 'nombre')>Nombre A-Z</option>
                <option value="peligro" @selected($orden === 'peligro')>Mayor peligro</option>
            </select>
        </div>

        <button type="submit" class="gtx-btn gtx-btn-primary">Aplicar filtros</button>

        @if($buscar !== '' || $juegoId || $especie !== '')
            <a href="{{ route('monstruos.index') }}" class="gtx-btn gtx-btn-secondary">Limpiar</a>
        @endif

    </form>

    <div class="gtx-results-summary">
        <span>
            {{ $monstruos->total() }}
            {{ $monstruos->total() === 1 ? 'resultado' : 'resultados' }}
        </span>
    </div>

    @if($monstruos->isNotEmpty())

        <div class="gtx-grid">
            @foreach($monstruos as $monstruo)
                <a href="{{ route('monstruos.show', $monstruo) }}" class="gtx-card gtx-item-card">
                    <div class="gtx-item-media">
                        @if($monstruo->imagen_url)
                            <img src="{{ $monstruo->imagen_url }}" alt="{{ $monstruo->nombre }}" loading="lazy">
                        @else
                            <div class="gtx-item-media-placeholder">{{ $monstruo->inicial }}</div>
                        @endif
                        <span class="gtx-item-badge">Peligro {{ $monstruo->nivel_peligro ?? '—' }}</span>
                    </div>

                    <div class="gtx-item-body">
                        <span class="gtx-item-eyebrow">{{ $monstruo->juego->nombre }}</span>
                        <h3>{{ $monstruo->nombre }}</h3>
                        <p>{{ $monstruo->especie }}</p>

                        <div class="gtx-mini-stats">
                            <span>{{ $monstruo->materiales_count }} materiales</span>
                            <span>{{ $monstruo->partes_count }} partes</span>
                            <span>{{ $monstruo->debilidades_count }} debilidades</span>
                        </div>

                        <strong>Ver ficha completa →</strong>
                    </div>
                </a>
            @endforeach
        </div>

        @if($monstruos->hasPages())
            <div class="gtx-pagination">
                @if($monstruos->onFirstPage())
                    <span class="is-disabled">← Anterior</span>
                @else
                    <a href="{{ $monstruos->previousPageUrl() }}">← Anterior</a>
                @endif

                <span>Página {{ $monstruos->currentPage() }} de {{ $monstruos->lastPage() }}</span>

                @if($monstruos->hasMorePages())
                    <a href="{{ $monstruos->nextPageUrl() }}">Siguiente →</a>
                @else
                    <span class="is-disabled">Siguiente →</span>
                @endif
            </div>
        @endif

    @else

        <div class="gtx-empty-state">
            <strong>No encontramos monstruos</strong>
            <p>Prueba con otro nombre, material, juego o especie.</p>
            <a href="{{ route('monstruos.index') }}" class="gtx-btn gtx-btn-primary">
                Mostrar todos los monstruos
            </a>
        </div>

    @endif

</div>

@endsection
