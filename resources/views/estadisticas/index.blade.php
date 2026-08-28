@extends('layouts.app')

@section('titulo', 'Estadísticas | GameGuideX')

@section('contenido')

<div class="container gtx-page">

    <div class="gtx-page-header">
        <span class="gtx-item-eyebrow">CENTRO DE DATOS</span>
        <h1>Estadísticas</h1>
    </div>

    @if(! $autenticado)

        <div class="gtx-empty-state">
            <strong>Inicia sesión para ver tu actividad</strong>
            <p>
                Tus favoritos y las últimas fichas que visitaste aparecerán
                aquí una vez que tengas sesión iniciada.
            </p>
            <a href="{{ route('login') }}" class="gtx-btn gtx-btn-primary">
                Iniciar sesión
            </a>
        </div>

    @elseif($totalFavoritos === 0 && $ultimasVisitas->isEmpty())

        <div class="gtx-empty-state">
            <strong>Sin datos todavía</strong>
            <p>
                Todavía no marcaste favoritos ni visitaste ninguna ficha.
                Cuando lo hagas, aquí verás tu actividad real dentro de
                GameGuideX.
            </p>
            <a href="{{ route('juegos.index') }}" class="gtx-btn gtx-btn-primary">
                Explorar catálogo
            </a>
        </div>

    @else

        <div class="gtx-data-grid gtx-section">
            <div class="gtx-card gtx-data-item">
                <span>Favoritos totales</span>
                <strong>{{ $totalFavoritos }}</strong>
            </div>

            @foreach($favoritosPorTipo as $etiqueta => $total)
                <div class="gtx-card gtx-data-item">
                    <span>{{ $etiqueta }}</span>
                    <strong>{{ $total }}</strong>
                </div>
            @endforeach
        </div>

        @if($ultimasVisitas->isNotEmpty())
            <div class="gtx-page-header">
                <h2 class="gtx-section-title">Últimas fichas visitadas</h2>
            </div>

            <div class="gtx-grid gtx-section">
                @foreach($ultimasVisitas as $visita)
                    <x-elemento-card :elemento="$visita->elemento" />
                @endforeach
            </div>
        @endif

    @endif

</div>

@endsection
