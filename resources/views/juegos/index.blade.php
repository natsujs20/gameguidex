@extends('layouts.app')

@section('titulo', 'Catálogo de videojuegos | GameGuideX')

@section('contenido')

<div class="container gtx-page">

    <div class="gtx-page-header">
        <h1>Catálogo de videojuegos</h1>
        <p>
            Explora el catálogo de videojuegos, conoce su año de lanzamiento,
            plataformas, género, desarrollador e historia principal.
        </p>
    </div>

    <div class="gtx-stat-row">
        <div class="gtx-stat-pill">
            <strong>{{ $estadisticas['juegos'] }}</strong>
            <span>Videojuegos</span>
        </div>
        <div class="gtx-stat-pill">
            <strong>{{ $estadisticas['franquicias'] }}</strong>
            <span>Franquicias</span>
        </div>
        <div class="gtx-stat-pill">
            <strong>{{ $estadisticas['disponibles'] }}</strong>
            <span>Con enlace oficial</span>
        </div>
    </div>

    <form action="{{ route('juegos.index') }}" method="GET" class="gtx-filter-bar">

        <div class="gtx-filter-field gtx-filter-grow">
            <label for="buscar">Buscar juego</label>
            <input
                id="buscar"
                type="text"
                name="buscar"
                value="{{ request('buscar') }}"
                placeholder="Ejemplo: The Legend of Zelda"
            >
        </div>

        <div class="gtx-filter-field">
            <label for="franquicia">Franquicia</label>
            <select id="franquicia" name="franquicia">
                <option value="">Todas</option>
                @foreach($franquicias as $opcion)
                    <option value="{{ $opcion }}" @selected(request('franquicia') === $opcion)>
                        {{ $opcion }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="gtx-filter-field">
            <label for="plataforma">Plataforma</label>
            <select id="plataforma" name="plataforma">
                <option value="">Todas</option>
                @foreach($plataformas as $plataforma)
                    <option value="{{ $plataforma }}" @selected(request('plataforma') === $plataforma)>
                        {{ $plataforma }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="gtx-filter-field">
            <label for="anio">Año</label>
            <select id="anio" name="anio">
                <option value="">Todos</option>
                @foreach($anios as $anio)
                    <option value="{{ $anio }}" @selected((string) request('anio') === (string) $anio)>
                        {{ $anio }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="gtx-btn gtx-btn-primary">Buscar</button>

        @if(request()->hasAny(['buscar', 'franquicia', 'plataforma', 'anio']))
            <a href="{{ route('juegos.index') }}" class="gtx-btn gtx-btn-secondary">
                Limpiar
            </a>
        @endif

    </form>

    <div class="gtx-results-summary">
        <span>
            {{ $juegos->total() }}
            {{ $juegos->total() === 1 ? 'resultado' : 'resultados' }}
        </span>
    </div>

    @if($juegos->count() > 0)

        <div class="gtx-grid">
            @foreach($juegos as $juego)
                <a href="{{ route('juegos.show', $juego) }}" class="gtx-card gtx-item-card">
                    <div class="gtx-item-media">
                        @if($juego->imagen_url)
                            <img src="{{ $juego->imagen_url }}" alt="{{ $juego->nombre }}" loading="lazy">
                        @else
                            <div class="gtx-item-media-placeholder">GGX</div>
                        @endif

                        @if($juego->anio)
                            <span class="gtx-item-badge">{{ $juego->anio }}</span>
                        @endif
                    </div>

                    <div class="gtx-item-body">
                        <span class="gtx-item-eyebrow">{{ $juego->genero ?? 'Videojuego' }}</span>
                        <h3>{{ $juego->nombre }}</h3>
                        <p>{{ \Illuminate\Support\Str::limit($juego->descripcion, 90) }}</p>
                        <strong>Ver información →</strong>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="gtx-pagination">
            @if($juegos->onFirstPage())
                <span class="is-disabled">← Anterior</span>
            @else
                <a href="{{ $juegos->previousPageUrl() }}">← Anterior</a>
            @endif

            <span>Página {{ $juegos->currentPage() }} de {{ $juegos->lastPage() }}</span>

            @if($juegos->hasMorePages())
                <a href="{{ $juegos->nextPageUrl() }}">Siguiente →</a>
            @else
                <span class="is-disabled">Siguiente →</span>
            @endif
        </div>

    @else

        <div class="gtx-empty-state">
            <strong>No encontramos videojuegos</strong>
            <p>Prueba con otro nombre, franquicia, plataforma o año.</p>
            <a href="{{ route('juegos.index') }}" class="gtx-btn gtx-btn-primary">
                Ver todos los juegos
            </a>
        </div>

    @endif

</div>

@endsection
