@extends('layouts.app')

@section('titulo', 'Guías y consejos | GameGuideX')

@section('contenido')

<div class="container gtx-page">

    <div class="gtx-page-header">
        <h1>Guías y consejos</h1>
        <p>
            Encuentra estrategias, ubicaciones, materiales, misiones, secretos
            y recomendaciones para tus videojuegos favoritos.
        </p>
    </div>

    {{-- CENTROS DE SAGAS --}}
    <div class="gtx-page-header">
        <h2 class="gtx-section-title">Centros de información</h2>
    </div>

    {{-- Se muestran todos, incluidos los que aún no tienen contenido. --}}
    <div class="gtx-grid gtx-grid-wide gtx-section">
        @foreach($centros as $centro)
            <x-centro-card :centro="$centro" />
        @endforeach
    </div>

    {{-- CATEGORÍAS --}}
    <div class="gtx-page-header">
        <h2 class="gtx-section-title">¿Qué necesitas encontrar?</h2>
    </div>

    <div class="gtx-tabs gtx-section">
        <a href="{{ route('guias.index') }}" class="{{ $categoria === '' ? 'active' : '' }}">Todas</a>
        <a href="{{ route('guias.index', ['categoria' => 'Consejo']) }}" class="{{ $categoria === 'Consejo' ? 'active' : '' }}">Consejos</a>
        <a href="{{ route('guias.index', ['categoria' => 'Jefe']) }}" class="{{ $categoria === 'Jefe' ? 'active' : '' }}">Jefes y combate</a>
        <a href="{{ route('guias.index', ['categoria' => 'Material']) }}" class="{{ $categoria === 'Material' ? 'active' : '' }}">Objetos y materiales</a>
        <a href="{{ route('guias.index', ['categoria' => 'Misión']) }}" class="{{ $categoria === 'Misión' ? 'active' : '' }}">Misiones</a>
        <a href="{{ route('guias.index', ['categoria' => 'Coleccionable']) }}" class="{{ $categoria === 'Coleccionable' ? 'active' : '' }}">Coleccionables</a>
        <a href="{{ route('guias.index', ['categoria' => 'Personaje']) }}" class="{{ $categoria === 'Personaje' ? 'active' : '' }}">Personajes</a>
    </div>

    {{-- GUÍAS DESTACADAS --}}
    @if($guiasDestacadas->isNotEmpty())
        <div class="gtx-page-header">
            <h2 class="gtx-section-title">Guías destacadas</h2>
        </div>

        <div class="gtx-guias-grid gtx-section">
            @foreach($guiasDestacadas as $guiaDestacada)
                <a href="{{ route('guias.show', $guiaDestacada) }}" class="gtx-card gtx-guia-card">
                    <span class="gtx-guia-tipo">{{ $guiaDestacada->tipo }}</span>
                    <h3>{{ $guiaDestacada->titulo }}</h3>
                    @if($guiaDestacada->juego)<p>{{ $guiaDestacada->juego->nombre }}</p>@endif
                    <p>{{ \Illuminate\Support\Str::limit($guiaDestacada->descripcion, 110) }}</p>
                    <strong>Leer guía completa →</strong>
                </a>
            @endforeach
        </div>
    @endif

    {{-- TODAS LAS GUÍAS --}}
    <div class="gtx-page-header">
        <h2 class="gtx-section-title">Todas las guías</h2>
    </div>

    <form action="{{ route('guias.index') }}" method="GET" class="gtx-filter-bar">
        <div class="gtx-filter-field gtx-filter-grow">
            <label for="buscar">Buscar</label>
            <input id="buscar" type="search" name="buscar" value="{{ $buscar }}" placeholder="Buscar una guía, videojuego o consejo...">
        </div>

        @if($categoria !== '')
            <input type="hidden" name="categoria" value="{{ $categoria }}">
        @endif

        <div class="gtx-filter-field">
            <label for="orden">Ordenar por</label>
            <select id="orden" name="orden">
                <option value="recientes" @selected($orden === 'recientes')>Más recientes</option>
                <option value="populares" @selected($orden === 'populares')>Destacadas</option>
                <option value="nombre" @selected($orden === 'nombre')>Nombre A-Z</option>
            </select>
        </div>

        <button type="submit" class="gtx-btn gtx-btn-primary">Buscar</button>

        @if($buscar !== '' || $categoria !== '')
            <a href="{{ route('guias.index') }}" class="gtx-btn gtx-btn-secondary">Limpiar</a>
        @endif
    </form>

    <div class="gtx-results-summary">
        <span>{{ $guias->total() }} {{ $guias->total() === 1 ? 'resultado' : 'resultados' }}</span>
    </div>

    @if($guias->isNotEmpty())

        <div class="gtx-grid">
            @foreach($guias as $guia)
                <a href="{{ route('guias.show', $guia) }}" class="gtx-card gtx-item-card">
                    <div class="gtx-item-body">
                        <div class="gtx-tag-row gtx-tag-row-flush">
                            <span>{{ $guia->tipo }}</span>
                            @if($guia->dificultad)<span>{{ $guia->dificultad }}</span>@endif
                        </div>
                        <span class="gtx-item-eyebrow">{{ $guia->juego->nombre }}</span>
                        <h3>{{ $guia->titulo }}</h3>
                        <p>{{ \Illuminate\Support\Str::limit($guia->descripcion, 120) }}</p>
                        <strong>Leer guía completa →</strong>
                    </div>
                </a>
            @endforeach
        </div>

        @if($guias->hasPages())
            <div class="gtx-pagination">
                @if($guias->onFirstPage())
                    <span class="is-disabled">← Anterior</span>
                @else
                    <a href="{{ $guias->previousPageUrl() }}">← Anterior</a>
                @endif

                <span>Página {{ $guias->currentPage() }} de {{ $guias->lastPage() }}</span>

                @if($guias->hasMorePages())
                    <a href="{{ $guias->nextPageUrl() }}">Siguiente →</a>
                @else
                    <span class="is-disabled">Siguiente →</span>
                @endif
            </div>
        @endif

    @else

        <div class="gtx-empty-state">
            <strong>No encontramos guías</strong>
            <p>Prueba con otro nombre o elimina los filtros aplicados.</p>
            <a href="{{ route('guias.index') }}" class="gtx-btn gtx-btn-primary">Ver todas las guías</a>
        </div>

    @endif

</div>

@endsection
