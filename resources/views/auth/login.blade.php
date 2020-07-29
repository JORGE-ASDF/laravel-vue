<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Document</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="login-body">
<div class="container" id="container">
    <div class="form-container sign-up-container">
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <h4>Crear una nueva cuenta</h4>
            <div>
                <input id="name" type="text" class="form-control @error('name') is-invalid
                    @enderror" name="name" value="{{ old('name') }}" required autocomplete="name"
                    autofocus placeholder="Nombre">
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                <input id="adress" type="text" class="form-control @error('adress') is-invalid
                    @enderror" name="direccion" value="{{ old('direccion') }}" required autocomplete="adress"
                    autofocus placeholder="Direccion">
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <input id="telefono" type="text" class="form-control @error('telefono') is-invalid
                    @enderror" name="telefono" value="{{ old('telefono') }}" required autocomplete="telefono"
                    autofocus placeholder="Telefono">
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                <input id="email" type="email" class="form-control @error('email') is-invalid
                    @enderror" name="email" value="{{ old('email') }}" required
                    autocomplete="email" placeholder="Email">
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                <input id="password" type="password" class="form-control @error('password')
                    is-invalid @enderror" name="password" required
                    autocomplete="new-password" placeholder="Password">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                <input id="password-confirm" type="password" class="form-control"
                    name="password_confirmation"
                    required autocomplete="new-password" placeholder="Confirma tu password">
                <button type="submit">
                    {{ __('Register') }}
                </button>
            </div>
        </form>
    </div>
    <div class="form-container sign-in-container">
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <h1>Iniciar Sesion</h1>
            <input id="email" type="email" class="@error('email') is-invalid @enderror"
            placeholder="Email" name="email" value="{{ old('email') }}" required autocomplete="email"
            autofocus>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            <input id="password" type="password" class="@error('password') is-invalid @enderror"
            name="password" placeholder="Password" required autocomplete="current-password">
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            <div class="row">
                <input class=" form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">
                        {{ __('Remember Me') }}
                    </label>
            </div>
            @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}">
                {{ __('Forgot Your Password?') }}
            </a>
            @endif
            <button>Entrar</button>
        </form>
    </div>
    <div class="overlay-container">
        <div class="overlay">
            <div class="overlay-panel overlay-left">
                <h1>Ya tienes una cuenta</h1>
                <p>Aprovecha las mejores ofertas solo para ti</p>
                <button class="ghost" id="signIn">Iniciar Sesion</button>
            </div>
            <div class="overlay-panel overlay-right">
                <h1 class="singUp-text">Eres nuevo?</h1>
                <p>Registrate ahora y descubre las mejores promociones</p>
                <button class="ghost" id="signUp">Crear Cuenta</button>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('js/app.js') }}" defer></script>
</body>
</html>
