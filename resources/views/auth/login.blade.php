@extends('layouts.pwa')

@section('content')
<!-- Internet Connection Status -->
<!-- # This code for showing internet connection status -->
<div class="internet-connection-status" id="internetStatus"></div>
<!-- Back Button -->

<!-- Login Wrapper Area -->
<div class="login-wrapper d-flex align-items-center justify-content-center">
    <div class="custom-container">
        <div class="text-center px-4"><img class="login-intro-img" src="{{asset('img/test3.png')}}" alt=""></div>
        <!-- Register Form -->
        <div class="register-form mt-4">
            <h6 class="mb-3 text-center">Smart Monitoring Listrik.</h6>
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <input class="form-control" name="email" value="{{ old('email') }}" type="text" placeholder="Email">
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="form-group position-relative">
                    <input class="form-control" id="psw-input" type="password" placeholder="Enter Password" name="password" required autocomplete="current-password">
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                    <div class="position-absolute" id="password-visibility"><i class="bi bi-eye"></i><i class="bi bi-eye-slash"></i></div>
                </div>
                <button class="btn w-100" style="background-color:#34e0c2;border-color:#34e0c2;color:#275057" type="submit">Sign In</button>
            </form>
        </div>
        <!-- Login Meta -->
    </div>
</div>
</div>
@endsection