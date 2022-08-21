<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title id="page_title">{{ config('app.name', 'Laravel') }}</title>
    <!-- Scripts -->

    <style>
        body {
            background-image: url("{{ asset('images/login-bg.jpg') }}");
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
    <link href="{{ asset('bootstrap-5/css/bootstrap.min.css') }}" rel="stylesheet">

</head>

<body>
    <div id="app">
        <center>
            <br><br><br><br><br><br>
            <div class="container-logform" id="container">
                <div class="form-container sign-in-container">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <h1>Sign in</h1>
                        <h1>&nbsp;</h1>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="email"><i class="fa-solid fa-user"></i></span>
                            <input id="email" type="email"
                                class="form-control @error('email') is-invalid @enderror" name="email"
                                value="{{ old('email') }}" placeholder="Outlook Email" required autocomplete="email"
                                autofocus />
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="input-group mb-3">
                            <span class="input-group-text" id="password"><i class="fa-solid fa-key"></i></span>
                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" name="password"
                                placeholder="Password" required autocomplete="current-password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror


                        </div>
                        @if (Route::has('password.request'))
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif
                        <button type="submit" class="button-signin">
                            {{ __('Login') }}
                        </button>
                    </form>
                </div>

                <div class="overlay-container">
                    <div class="overlay">
                        <div class="overlay-panel overlay-right">
                            <img src="{{ asset('images/realcognita-gif-logo.gif') }}" width="300px">
                            {{-- <h1>&nbsp;</h1>
                    <h1>Hello, Friend!</h1>
                    <p>Enter your personal details and start journey with us</p>
                    <button class="ghost" id="signUp">Report a problem</button> --}}
                        </div>
                    </div>
                </div>
        </center>
    </div>
</body>

</html>
