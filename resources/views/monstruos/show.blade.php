@extends('layouts.app')

@section('titulo', $monstruo->nombre . ' | GameGuideX')

@section('contenido')

<div class="container gtx-page">

    <a href="{{ route('monstruos.index') }}" class="gtx-back">
        ← Volver a la enciclopedia
    </a>

    <div class="gtx-detail-layout">

        <div class="gtx-detail-media gtx-card">
            @if($monstruo->imagen_url)
                <img src="{{ $monstruo->imagen_url }}" alt="{{ $monstruo->nombre }}">
            @else
                <div class="gtx-item-media-placeholder">{{ $monstruo->inicial }}</div>
            @endif
        </div>

        <div class="gtx-detail-content">

            <span class="gtx-item-eyebrow">FICHA COMPLETA DEL MONSTRUO</span>
            <h1>{{ $monstruo->nombre }}</h1>

            <div class="gtx-tag-row">
                <span>{{ $monstruo->juego->nombre }}</span>
                @if($monstruo->especie)<span>{{ $monstruo->especie }}</span>@endif
                @if($monstruo->elemento)<span>{{ $monstruo->elemento }}</span>@endif
                @if($monstruo->estado_alterado)<span>{{ $monstruo->estado_alterado }}</span>@endif
            </div>

            <p class="gtx-detail-description">{{ $monstruo->descripcion }}</p>

            <div class="gtx-data-grid">
                <div class="gtx-card gtx-data-item">
                    <span>Nivel de peligro</span>
                    <strong>{{ $monstruo->nivel_peligro ?? '—' }}</strong>
                </div>
                <div class="gtx-card gtx-data-item">
                    <span>Especie</span>
                    <strong>{{ $monstruo->especie ?? '—' }}</strong>
                </div>
                <div class="gtx-card gtx-data-item">
                    <span>Elemento</span>
                    <strong>{{ $monstruo->elemento ?? 'Ninguno' }}</strong>
                </div>
                <div class="gtx-card gtx-data-item">
                    <span>Estado alterado</span>
                    <strong>{{ $monstruo->estado_alterado ?? 'Ninguno' }}</strong>
                </div>
            </div>

            <div class="gtx-detail-actions">
                <a href="#materiales" class="gtx-btn gtx-btn-primary">Ver materiales y porcentajes ↓</a>
                <a href="{{ route('juegos.show', $monstruo->juego) }}" class="gtx-btn gtx-btn-secondary">
                    Ver ficha del juego
                </a>
                <x-favorito-boton :elemento="$monstruo" tipo="monstruo" />
            </div>

        </div>
    </div>

    {{-- INFORMACIÓN GENERAL --}}
    @if($monstruo->habitat || $monstruo->comportamiento || $monstruo->estrategia)
        <section class="gtx-related">
            <div class="gtx-page-header"><h2 class="gtx-section-title">Información general</h2></div>

            <div class="gtx-grid">
                @if($monstruo->habitat)
                    <div class="gtx-card gtx-info-card">
                        <span class="gtx-item-eyebrow">HÁBITAT</span>
                        <h3>Dónde encontrarlo</h3>
                        <p>{!! nl2br(e($monstruo->habitat)) !!}</p>
                    </div>
                @endif

                @if($monstruo->comportamiento)
                    <div class="gtx-card gtx-info-card">
                        <span class="gtx-item-eyebrow">COMPORTAMIENTO</span>
                        <h3>Cómo actúa</h3>
                        <p>{!! nl2br(e($monstruo->comportamiento)) !!}</p>
                    </div>
                @endif

                @if($monstruo->estrategia)
                    <div class="gtx-card gtx-info-card">
                        <span class="gtx-item-eyebrow">ESTRATEGIA</span>
                        <h3>Cómo enfrentarlo</h3>
                        <p>{!! nl2br(e($monstruo->estrategia)) !!}</p>
                    </div>
                @endif
            </div>
        </section>
    @endif

    {{-- DEBILIDADES --}}
    @if($monstruo->debilidades->isNotEmpty())
        <section class="gtx-related">
            <div class="gtx-page-header">
                <h2 class="gtx-section-title">Debilidades y resistencias</h2>
                <p>La intensidad se representa con estrellas. Tres estrellas indican una debilidad alta.</p>
            </div>

            <div class="gtx-grid">
                @foreach($monstruo->debilidades->groupBy('tipo') as $tipo => $debilidades)
                    <div class="gtx-card gtx-info-card">
                        <h3>{{ $tipo }}</h3>

                        @foreach($debilidades as $debilidad)
                            <div class="gtx-weakness-row">
                                <div>
                                    <strong>{{ $debilidad->nombre }}</strong>
                                    @if($debilidad->parte)
                                        <small> · {{ $debilidad->parte }}</small>
                                    @endif
                                </div>
                                <span title="{{ $debilidad->intensidad }} de 3">{{ $debilidad->estrellas }}</span>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- PARTES --}}
    @if($monstruo->partes->isNotEmpty())
        <section class="gtx-related">
            <div class="gtx-page-header">
                <h2 class="gtx-section-title">Partes rompibles y cortables</h2>
                <p>Romper determinadas partes puede cambiar el combate y entregar materiales adicionales.</p>
            </div>

            <div class="gtx-grid">
                @foreach($monstruo->partes as $parte)
                    <div class="gtx-card gtx-info-card">
                        <div class="gtx-tag-row">
                            @if($parte->rompible)<span>Rompible</span>@endif
                            @if($parte->cortable)<span>Cortable</span>@endif
                        </div>

                        <h3>{{ $parte->nombre }}</h3>

                        <div class="gtx-mini-stats">
                            <span>Corte: {{ $parte->debilidad_corte ?? '—' }}</span>
                            <span>Impacto: {{ $parte->debilidad_impacto ?? '—' }}</span>
                            <span>Disparo: {{ $parte->debilidad_disparo ?? '—' }}</span>
                        </div>

                        @if($parte->mejor_tipo_dano)
                            <p>Mejor tipo de daño: <strong>{{ $parte->mejor_tipo_dano }}</strong></p>
                        @endif

                        @if($parte->recompensa_especial)
                            <p>{{ $parte->recompensa_especial }}</p>
                        @endif

                        @if($parte->consejos)
                            <p>{{ $parte->consejos }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- MATERIALES --}}
    <section id="materiales" class="gtx-related">
        <div class="gtx-page-header">
            <h2 class="gtx-section-title">Materiales y porcentajes</h2>
            <p>
                Los porcentajes cambian según el rango, la forma de obtención y
                la parte del monstruo. Cada fila representa una oportunidad
                independiente de recibir el material.
            </p>
        </div>

        @if($materialesPorRango->isNotEmpty())
            @foreach($materialesPorRango as $rango => $fuentes)
                <div class="gtx-card gtx-table-card">
                    <div class="gtx-table-heading">
                        <h3>{{ $rango }}</h3>
                        <span>{{ $fuentes->count() }} {{ $fuentes->count() === 1 ? 'fuente' : 'fuentes' }}</span>
                    </div>

                    <div class="gtx-table-scroll">
                        <table class="gtx-table">
                            <thead>
                                <tr>
                                    <th>Material</th>
                                    <th>Método</th>
                                    <th>Parte o condición</th>
                                    <th>Cantidad</th>
                                    <th>Probabilidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($fuentes as $fuente)
                                    <tr>
                                        <td>
                                            <strong>{{ $fuente->material->nombre }}</strong>
                                            @if($fuente->material->rareza)
                                                <br><small>Rareza {{ $fuente->material->rareza }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $fuente->metodo }}</td>
                                        <td>
                                            {{ $fuente->parte ?: $fuente->condicion ?: '—' }}
                                            @if($fuente->parte && $fuente->condicion)
                                                <br><small>{{ $fuente->condicion }}</small>
                                            @endif
                                        </td>
                                        <td>×{{ $fuente->cantidad }}</td>
                                        <td>
                                            @if($fuente->porcentaje !== null)
                                                {{ number_format((float) $fuente->porcentaje, $fuente->porcentaje == floor($fuente->porcentaje) ? 0 : 2, ',', '.') }}%
                                            @else
                                                Variable
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        @else
            <div class="gtx-empty-state">
                <strong>Materiales en preparación</strong>
                <p>Todavía no hemos registrado las recompensas y porcentajes de este monstruo.</p>
            </div>
        @endif
    </section>

    {{-- RELACIONADOS --}}
    @if($monstruosRelacionados->isNotEmpty())
        <section class="gtx-related">
            <div class="gtx-page-header"><h2 class="gtx-section-title">Monstruos relacionados</h2></div>

            <div class="gtx-grid">
                @foreach($monstruosRelacionados as $relacionado)
                    <a href="{{ route('monstruos.show', $relacionado) }}" class="gtx-card gtx-item-card">
                        <div class="gtx-item-media">
                            @if($relacionado->imagen_url)
                                <img src="{{ $relacionado->imagen_url }}" alt="{{ $relacionado->nombre }}" loading="lazy">
                            @else
                                <div class="gtx-item-media-placeholder">{{ $relacionado->inicial }}</div>
                            @endif
                        </div>
                        <div class="gtx-item-body">
                            <span class="gtx-item-eyebrow">{{ $relacionado->juego->nombre }}</span>
                            <h3>{{ $relacionado->nombre }}</h3>
                            <p>{{ $relacionado->especie }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

</div>

@endsection
