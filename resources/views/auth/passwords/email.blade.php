@extends('layout.master2')

@section('content')
{{-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">


                    <form method="POST" action="{{ route('password.email') }}">
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

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Send Password Reset Link') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> --}}
<div class="page-content d-flex align-items-center justify-content-center">
    <div class="row w-100 mx-0 auth-page">
      <div class="col-md-8 col-xl-6 mx-auto">
        <div class="card">
          <div class="row">
            <div class="col-md-4 pr-md-0">
              <div class="auth-left-wrapper" style="background-image: url({{ url('https://via.placeholder.com/219x452') }})">

              </div>
            </div>
            <div class="col-md-8 pl-md-0">
              <div class="auth-form-wrapper px-4 py-5">
                <a href="#" class="noble-ui-logo d-block mb-2">Noble<span>UI</span></a>
                <h5 class="text-muted font-weight-normal mb-4">Reset Password.</h5>
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <form class="forms-sample" method="POST" action="{{ route('password.email') }}">
                  @csrf
                  <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" id="email" name="email" placeholder="Email">
                      @error('email')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                  </div>
                  <div class="mt-3">
                      <button class="btn btn-primary mr-2 mb-2 mb-md-0">{{ __('Send Password Reset Link') }}</button>
                  </div>
                  <a href="{{ route('login') }}" class="d-block mt-3">Login</a>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
@endsection
