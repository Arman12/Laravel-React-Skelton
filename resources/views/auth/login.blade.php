<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admin Login</title>

    <link rel="stylesheet" href="{{asset('assets/vendors/mdi/css/materialdesignicons.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendors/css/vendor.bundle.base.css')}}">

    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
    <link rel="shortcut icon" href="{{asset('assets/images/favicon.ico')}}" />
    <style>
        body {
            background-color: #f2edf3;
        }
    </style>
</head>

<body>
    <!-- <div class="container-scroller"> -->
    <div class="container-fluid">
        <!-- <div class="main-panel"> -->
        <div class="content-wrapper">
            <div class="row justify-content-center">
                <div class="col-md-4"></div>
                <div class="col-md-4 text-center  align-items-center justify-content-center" style="padding: 26px;">
                    <a class="navbar-brand brand-logo" href="{{url('/login')}}">
                        <h1 style="color:#C183FF;font-size:60px;">Energy-Claims </h1>
                    </a>
                </div>
                <div class="col-md-4"></div>
                <div class="row">
                    <div class="col-md-3 grid-margin stretch-card">
                    </div>
                    <div class="col-md-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-header">{{ __('Login') }}</div>

                            <div class="card-body">
                                <form action="{{ route('login') }}" method="POST" class="forms-sample">
                                    @csrf
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">{{ __('Email Address') }}</label>
                                        <input type="text" class="form-control" name="email" id="exampleInputEmail1" placeholder="Email">
                                        @if ($errors->has('email'))
                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">{{ __('Password') }}</label>
                                        <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                                        @if ($errors->has('password'))
                                        <span class="text-danger">{{ $errors->first('password') }}</span>
                                        @endif
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
                                    <!-- <button class="btn btn-light">Cancel</button> -->
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 grid-margin stretch-card">
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('backend.includes.scripts')
</body>

</html>