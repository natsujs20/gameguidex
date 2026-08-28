@extends('layouts.app')

@section('titulo', $guia->titulo . ' | GameGuideX')

@section('contenido')

<div class="container gtx-page">

    <a href="{{ route('guias.index') }}" class="gtx-back">
        ← Volver a guías y consejos
    </a>

    <div class="gtx-page-header">
        <div class="gtx-tag-row">
            <span>{{ $guia->tipo }}</span>
            @if($guia->dificultad)<span>{{ $guia->dificultad }}</span>@endif
            @if($guia->destacada)<span>Destacada</span>@endif
        </div>

        <span class="gtx-item-eyebrow">{{ $guia->juego->nombre }}</span>
        <h1>{{ $guia->titulo }}</h1>
        <p>{{ $guia->descripcion }}</p>

        <div class="gtx-detail-actions">
            <a href="{{ route('juegos.show', $guia->juego) }}" class="gtx-btn gtx-btn-primary">
                Ver ficha del juego →
            </a>
            <a href="{{ route('guias.index', ['categoria' => $guia->tipo]) }}" class="gtx-btn gtx-btn-secondary">
                Más guías de {{ $guia->tipo }}
            </a>
            <x-favorito-boton :elemento="$guia" tipo="guia" />
        </div>
    </div>

    <div class="gtx-guide-layout">

        <main>
            @if($guia->donde_conseguir)
                <div class="gtx-card gtx-info-card gtx-stack">
                    <span class="gtx-item-eyebrow">UBICACIÓN E INFORMACIÓN</span>
                    <h3>Dónde encontrarlo</h3>
                    <p>{!! nl2br(e($guia->donde_conseguir)) !!}</p>
                </div>
            @endif

            @if($guia->requisitos)
                <div class="gtx-card gtx-info-card gtx-stack">
                    <span class="gtx-item-eyebrow">ANTES DE COMENZAR</span>
                    <h3>Requisitos</h3>
                    <p>{!! nl2br(e($guia->requisitos)) !!}</p>
                </div>
            @endif

            @if($guia->pasos)
                <div class="gtx-card gtx-info-card gtx-stack">
                    <span class="gtx-item-eyebrow">GUÍA PASO A PASO</span>
                    <h3>Cómo hacerlo</h3>
                    <p>{!! nl2br(e($guia->pasos)) !!}</p>
                </div>
            @endif

            @if($guia->consejos)
                <div class="gtx-card gtx-info-card">
                    <span class="gtx-item-eyebrow">RECOMENDACIONES</span>
                    <h3>Consejos importantes</h3>
                    <p>{!! nl2br(e($guia->consejos)) !!}</p>
                </div>
            @endif
        </main>

        <aside>
            <div class="gtx-card gtx-panel gtx-stack">
                <span class="gtx-item-eyebrow">INFORMACIÓN DE LA GUÍA</span>

                <div class="gtx-weakness-row">
                    <span>Videojuego</span>
                    <strong>{{ $guia->juego->nombre }}</strong>
                </div>
                <div class="gtx-weakness-row">
                    <span>Categoría</span>
                    <strong>{{ $guia->tipo }}</strong>
                </div>
                <div class="gtx-weakness-row">
                    <span>Dificultad</span>
                    <strong>{{ $guia->dificultad ?? '—' }}</strong>
                </div>
                <div class="gtx-weakness-row">
                    <span>Plataformas</span>
                    <strong>{{ $guia->plataformas ?? '—' }}</strong>
                </div>
            </div>

            @if($guia->palabras_clave)
                <div class="gtx-card gtx-panel">
                    <span class="gtx-item-eyebrow">TEMAS RELACIONADOS</span>
                    <div class="gtx-tag-row">
                        @foreach(array_filter(array_map('trim', explode(',', $guia->palabras_clave))) as $palabra)
                            <a href="{{ route('guias.index', ['buscar' => $palabra]) }}">{{ $palabra }}</a>
                        @endforeach
                    </div>
                </div>
            @endif
        </aside>

    </div>

    @if($guiasRelacionadas->isNotEmpty())
        <section class="gtx-related">
            <div class="gtx-page-header"><h2 class="gtx-section-title">Guías relacionadas</h2></div>

            <div class="gtx-guias-grid">
                @foreach($guiasRelacionadas as $relacionada)
                    <a href="{{ route('guias.show', $relacionada) }}" class="gtx-card gtx-guia-card">
                        <span class="gtx-guia-tipo">{{ $relacionada->tipo }}</span>
                        <h3>{{ $relacionada->titulo }}</h3>
                        <p>{{ $relacionada->juego->nombre }}</p>
                        <strong>Leer guía →</strong>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

</div>

@endsection
