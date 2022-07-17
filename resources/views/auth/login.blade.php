@extends('layouts.app')


<link rel="stylesheet" href="{{ asset('css/login.css') }}">
<style>
    
    body {
        background-image: url("{{ asset('images/login-bg.jpg') }}")!important;
    }
    </style>
<body >
<center>
    <br><br><br><br><br><br>
<div class="container-logform" id="container">
	<div class="form-container sign-up-container ">
		<form action="#">
			<h2>Report a problem?</h2>
			<!-- <div class="social-container">
				<i class="fa-light fa-0"></i>
				<a href="#" class="social"><i class="fab fa-google-plus-g"></i></a>
				<a href="#" class="social"><i class="fab fa-linkedin-in"></i></a>
			</div> -->
			<!-- <span>or use your email for registration</span> -->
			<!-- <input type="text" placeholder="Name" /> -->
			<input type="email" placeholder="username@realcognita.com" />
			<input type="password" placeholder="Enter text here"/>
			
			<button>Submit</button>
		</form>
	</div>
	<div class="form-container sign-in-container">
		<form method="POST" action="{{ route('login') }}">
            @csrf
			<h1>Sign in</h1>

			<!-- <div class="social-container">
				<a href="#" class="social"><i class="fa-solid fa-compass-drafting"></i></a>
				<a href="#" class="social"><i class="fa-solid fa-arrow-right-to-city"></i></a>
				<a href="#" class="social"><i class="fa-solid fa-building-flag"></i></a>
			</div> -->
			<!-- <span>or use your account</span> -->
			<h1>&nbsp;</h1>
            <div class="input-group mb-3">
                <span class="input-group-text" id="email"><i class="fa-solid fa-user"></i></span>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email" required autocomplete="email" autofocus/>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>

              <div class="input-group mb-3">
                <span class="input-group-text" id="password"><i class="fa-solid fa-key"></i></span>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password" required autocomplete="current-password">
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
</body>
{{-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> --}}

