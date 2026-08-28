{{--
    Tarjeta genérica para mostrar un elemento favorito o visitado sin
    saber de antemano su tipo (juego, monstruo, guía o personaje de
    Dragon Ball). Se usa en /favoritos y /estadisticas, donde varios
    tipos de contenido conviven en una misma lista.
--}}
@php
    $datos = match (true) {
        $elemento instanceof \App\Models\Juego => [
            'url' => route('juegos.show', $elemento),
            'titulo' => $elemento->nombre,
            'etiqueta' => $elemento->franquicia,
            'descripcion' => $elemento->descripcion,
        ],
        $elemento instanceof \App\Models\Monstruo => [
            'url' => route('monstruos.show', $elemento),
            'titulo' => $elemento->nombre,
            'etiqueta' => $elemento->especie,
            'descripcion' => $elemento->descripcion,
        ],
        $elemento instanceof \App\Models\Guia => [
            'url' => route('guias.show', $elemento),
            'titulo' => $elemento->titulo,
            'etiqueta' => $elemento->tipo,
            'descripcion' => $elemento->descripcion,
        ],
        $elemento instanceof \App\Models\PersonajeDragonBall => [
            'url' => route('dragon-ball.personajes.show', $elemento),
            'titulo' => $elemento->nombre,
            'etiqueta' => $elemento->saga,
            'descripcion' => $elemento->descripcion,
        ],
        default => null,
    };
@endphp

@if($datos)
    <a href="{{ $datos['url'] }}" class="gtx-card gtx-item-card">
        <div class="gtx-item-body">
            @if($datos['etiqueta'])
                <span class="gtx-item-eyebrow">{{ $datos['etiqueta'] }}</span>
            @endif
            <h3>{{ $datos['titulo'] }}</h3>
            @if($datos['descripcion'])
                <p>{{ \Illuminate\Support\Str::limit($datos['descripcion'], 110) }}</p>
            @endif
            <strong>Ver →</strong>
        </div>
    </a>
@endif
