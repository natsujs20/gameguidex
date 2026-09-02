@extends('layouts.app')

@section('titulo', 'Mi perfil | GameGuideX')

@section('contenido')

<div class="container gtx-page">

    <div class="gtx-page-header">
        <span class="gtx-item-eyebrow">MI CUENTA</span>
        <h1>Perfil</h1>
    </div>

    <div class="gtx-perfil-layout">

        <section class="gtx-card gtx-perfil-datos">
            <h2 class="gtx-section-title">Tus datos</h2>

            <form method="POST" action="{{ route('perfil.actualizar') }}" novalidate>
                @csrf
                @method('PUT')

                <div class="gtx-auth-field">
                    <label for="nombre">Nombre</label>
                    <input
                        id="nombre"
                        name="nombre"
                        type="text"
                        value="{{ old('nombre', auth()->user()->nombre) }}"
                        class="@error('nombre') is-invalid @enderror"
                        maxlength="100"
                        required
                    >
                    @error('nombre')
                        <span class="gtx-auth-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="gtx-auth-field">
                    <label for="correo">Correo</label>
                    <input
                        id="correo"
                        name="correo"
                        type="email"
                        value="{{ old('correo', auth()->user()->correo) }}"
                        class="@error('correo') is-invalid @enderror"
                        maxlength="150"
                        required
                    >
                    @error('correo')
                        <span class="gtx-auth-error">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="gtx-btn gtx-btn-primary">
                    Guardar cambios
                </button>
            </form>

            <div class="gtx-perfil-accesos">
                <a href="{{ route('favoritos.index') }}" class="gtx-btn gtx-btn-secondary">
                    ★ Ver mis favoritos ({{ $totalFavoritos }})
                </a>
                <a href="{{ route('estadisticas.index') }}" class="gtx-btn gtx-btn-secondary">
                    Ver mis estadísticas
                </a>
            </div>
        </section>

        <section class="gtx-card gtx-perfil-peligro">
            <h2 class="gtx-section-title">Eliminar cuenta</h2>
            <p>
                Esta acción es permanente: se borran tu cuenta, tus favoritos,
                tu historial y tus juegos jugados. No se puede deshacer.
            </p>

            <form
                method="POST"
                action="{{ route('perfil.destruir') }}"
                onsubmit="return confirm('¿Seguro que quieres eliminar tu cuenta? Esta acción no se puede deshacer.');"
            >
                @csrf
                @method('DELETE')

                <div class="gtx-auth-field">
                    <label for="clave">Confirma tu clave para continuar</label>
                    <input
                        id="clave"
                        name="clave"
                        type="password"
                        class="@error('clave') is-invalid @enderror"
                        autocomplete="current-password"
                        required
                    >
                    @error('clave')
                        <span class="gtx-auth-error">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="gtx-btn gtx-btn-danger">
                    Eliminar mi cuenta
                </button>
            </form>
        </section>

    </div>

    <div class="gtx-page-header gtx-perfil-jugados-header">
        <span class="gtx-item-eyebrow">TU PROGRESO</span>
        <h2 class="gtx-section-title">Juegos que has jugado ({{ $juegosJugados->count() }})</h2>
    </div>

    @if($juegosJugados->isEmpty())
        <div class="gtx-empty-state">
            <strong>Todavía no has marcado ningún juego</strong>
            <p>
                Entra a la ficha de un videojuego y usa el botón
                "Marcar como jugado" para que aparezca aquí.
            </p>
            <a href="{{ route('juegos.index') }}" class="gtx-btn gtx-btn-primary">
                Explorar catálogo
            </a>
        </div>
    @else
        <div class="gtx-grid gtx-section">
            @foreach($juegosJugados as $juego)
                <x-elemento-card :elemento="$juego" />
            @endforeach
        </div>
    @endif

</div>

@endsection
