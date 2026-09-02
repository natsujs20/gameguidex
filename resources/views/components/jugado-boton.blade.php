{{--
    Botón para marcar/desmarcar un juego como jugado.
    Uso: <x-jugado-boton :juego="$juego" />

    Solo aplica a juegos (a diferencia de favoritos, que cubre los 4
    tipos de contenido), por eso no recibe un parámetro "tipo".
--}}
@auth
    @php
        $estaJugado = auth()->user()->marcoJugado($juego);
    @endphp

    <form method="POST" action="{{ route('perfil.jugados.alternar') }}" class="gtx-jugado-form">
        @csrf
        <input type="hidden" name="juego_id" value="{{ $juego->id }}">

        <button
            type="submit"
            class="gtx-btn {{ $estaJugado ? 'gtx-btn-primary' : 'gtx-btn-secondary' }} gtx-jugado-btn"
            aria-pressed="{{ $estaJugado ? 'true' : 'false' }}"
        >
            {{ $estaJugado ? '✓ Jugado' : '+ Marcar como jugado' }}
        </button>
    </form>
@else
    <a href="{{ route('login') }}" class="gtx-btn gtx-btn-secondary gtx-jugado-btn">
        + Inicia sesión para marcar
    </a>
@endauth
