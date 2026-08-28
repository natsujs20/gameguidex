{{--
    Botón para agregar/quitar un elemento de favoritos.
    Uso: <x-favorito-boton :elemento="$juego" tipo="juego" />
    "tipo" debe ser una de las claves del morphMap (AppServiceProvider).

    Solo se muestra si hay sesión iniciada: favoritos es una función de
    cuenta, no tiene sentido mostrarla a un visitante anónimo.
--}}
@auth
    @php
        $esFavorito = auth()->user()->tieneFavorito($elemento);
    @endphp

    <form method="POST" action="{{ route('favoritos.alternar') }}" class="gtx-favorito-form">
        @csrf
        <input type="hidden" name="tipo" value="{{ $tipo }}">
        <input type="hidden" name="id" value="{{ $elemento->id }}">

        <button
            type="submit"
            class="gtx-btn {{ $esFavorito ? 'gtx-btn-primary' : 'gtx-btn-secondary' }} gtx-favorito-btn"
            aria-pressed="{{ $esFavorito ? 'true' : 'false' }}"
        >
            {{ $esFavorito ? '★ En favoritos' : '☆ Agregar a favoritos' }}
        </button>
    </form>
@else
    <a href="{{ route('login') }}" class="gtx-btn gtx-btn-secondary gtx-favorito-btn">
        ☆ Inicia sesión para guardar
    </a>
@endauth
