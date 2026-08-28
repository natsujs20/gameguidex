@extends('layouts.app')

@section('titulo', 'Crear cuenta | GameGuideX')

@section('contenido')

<div class="gtx-auth-page">

    <section class="gtx-card gtx-auth-card">

        <span class="gtx-item-eyebrow">CREA TU PERFIL</span>
        <h1>Crear cuenta</h1>
        <p>Regístrate para guardar tus avances y administrar tus proyectos dentro de GameGuideX.</p>

        <form method="POST" action="{{ route('register.store') }}" novalidate>
            @csrf

            <div class="gtx-auth-field">
                <label for="nombre">Nombre</label>
                <input
                    id="nombre"
                    name="nombre"
                    type="text"
                    value="{{ old('nombre') }}"
                    class="@error('nombre') is-invalid @enderror"
                    autocomplete="name"
                    maxlength="100"
                    required
                    autofocus
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
                    value="{{ old('correo') }}"
                    class="@error('correo') is-invalid @enderror"
                    autocomplete="email"
                    maxlength="150"
                    required
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
                    autocomplete="new-password"
                    minlength="8"
                    required
                >
                <span class="gtx-auth-help">Utiliza al menos 8 caracteres.</span>
                @error('clave')
                    <span class="gtx-auth-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="gtx-auth-field">
                <label for="clave_confirmation">Confirmar clave</label>
                <input
                    id="clave_confirmation"
                    name="clave_confirmation"
                    type="password"
                    autocomplete="new-password"
                    minlength="8"
                    required
                >
            </div>

            <button type="submit" class="gtx-btn gtx-btn-primary">
                Crear mi cuenta
            </button>

        </form>

        <p class="gtx-auth-footer">
            ¿Ya tienes una cuenta?
            <a href="{{ route('login') }}">Inicia sesión</a>
        </p>

    </section>

</div>

@endsection
