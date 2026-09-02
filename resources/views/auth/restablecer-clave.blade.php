@extends('layouts.app')

@section('titulo', 'Definir clave nueva | GameGuideX')

@section('contenido')

<div class="gtx-auth-page">

    <section class="gtx-card gtx-auth-card">

        <span class="gtx-item-eyebrow">RECUPERAR ACCESO</span>
        <h1>Define tu clave nueva</h1>
        <p>Elige una clave nueva para tu cuenta.</p>

        <form method="POST" action="{{ route('password.update') }}" novalidate>
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="gtx-auth-field">
                <label for="correo">Correo</label>
                <input
                    id="correo"
                    name="correo"
                    type="email"
                    value="{{ old('correo', $correo) }}"
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
                <label for="clave">Clave nueva</label>
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
                <label for="clave_confirmation">Confirmar clave nueva</label>
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
                Guardar clave nueva
            </button>

        </form>

    </section>

</div>

@endsection
