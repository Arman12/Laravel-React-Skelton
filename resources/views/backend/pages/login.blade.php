<!DOCTYPE html>
<html lang="en">

<head>
    @include('backend.includes.commonhead')
    
    <title>Admin Login</title>
    <style>
        body {
            background-color: #f2edf3;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4 text-center  align-items-center justify-content-center" style="padding: 26px;">
                    <a class="navbar-brand brand-logo" href="{{url('/login')}}">
                        <h3 style="color:#C183FF;">Energy-Claims </h3>
                    </a>
                </div>
                <div class="col-md-4"></div>
            </div>
            <div class="row">
                <div class="col-md-3 grid-margin stretch-card">
                </div>
                <div class="col-md-6 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Login Form </h4>
                            <form action="{{ route('login') }}" method="POST" class="forms-sample">
                                @csrf
                                <div class="form-group">
                                    <label for="exampleInputEmail1">{{ __('Email Address') }}</label>
                                    <input type="text" class="form-control" name="email" id="exampleInputEmail1" placeholder="Email">
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">{{ __('Password') }}</label>
                                    <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-check form-check-flat form-check-primary">
                                    <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}> {{ __('Remember Me') }} </label>
                                </div>
                                <button type="submit" class="btn btn-gradient-primary me-2">{{ __('Login') }}</button>
                                @if (Route::has('password.request'))
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 grid-margin stretch-card">
                </div>
            </div>
        </div>
    </div>
    @include('backend.includes.scripts')
</body>

</html>