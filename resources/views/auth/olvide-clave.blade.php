@extends('layouts.app')

@section('titulo', 'Recuperar clave | GameGuideX')

@section('contenido')

<div class="gtx-auth-page">

    <section class="gtx-card gtx-auth-card">

        <span class="gtx-item-eyebrow">RECUPERAR ACCESO</span>
        <h1>¿Olvidaste tu clave?</h1>
        <p>Ingresa el correo de tu cuenta y te enviaremos un enlace para definir una clave nueva.</p>

        <form method="POST" action="{{ route('password.email') }}" novalidate>
            @csrf

            <div class="gtx-auth-field">
                <label for="correo">Correo</label>
                <input
                    id="correo"
                    name="correo"
                    type="email"
                    value="{{ old('correo') }}"
                    class="@error('correo') is-invalid @enderror"
                    autocomplete="email"
                    required
                    autofocus
                >
                @error('correo')
                    <span class="gtx-auth-error">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="gtx-btn gtx-btn-primary">
                Enviar enlace de recuperación
            </button>

        </form>

        <p class="gtx-auth-footer">
            <a href="{{ route('login') }}">← Volver a iniciar sesión</a>
        </p>

    </section>

</div>

@endsection
