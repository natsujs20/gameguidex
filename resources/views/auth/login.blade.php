@extends('layouts.app')

@section('titulo', 'Iniciar sesión | GameGuideX')

@section('contenido')

<div class="gtx-auth-page">

    <section class="gtx-card gtx-auth-card">

        <span class="gtx-item-eyebrow">BIENVENIDO DE NUEVO</span>
        <h1>Iniciar sesión</h1>
        <p>Ingresa a tu cuenta para administrar tus proyectos y continuar explorando GameGuideX.</p>

        <form method="POST" action="{{ route('login.store') }}" novalidate>
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

            <div class="gtx-auth-field">
                <label for="clave">Clave</label>
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

            <label class="gtx-auth-options">
                <input type="checkbox" name="remember" value="1" @checked(old('remember'))>
                <span>Mantener mi sesión iniciada</span>
            </label>

            <button type="submit" class="gtx-btn gtx-btn-primary">
                Entrar a GameGuideX
            </button>

        </form>

        <p class="gtx-auth-footer">
            ¿Todavía no tienes una cuenta?
            <a href="{{ route('register') }}">Regístrate</a>
        </p>

    </section>

</div>

@endsection
