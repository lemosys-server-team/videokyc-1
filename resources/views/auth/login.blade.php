@extends('auth.valex_app')
@section('content')
<div class="container-fluid">
      <div class="row no-gutter">
        <div class="col-md-6 col-lg-6 col-xl-7 d-none d-md-flex bg-primary-transparent">
          <div class="row wd-100p mx-auto text-center">
            <div class="col-md-12 col-lg-12 col-xl-12 my-auto mx-auto wd-100p">
              @php $logo = getSetting('logo'); @endphp
              @if(isset($logo) && $logo!=''  && \Storage::exists(config('constants.SETTING_IMAGE_URL').$logo))
              <img src="{{ \Storage::url(config('constants.SETTING_IMAGE_URL').$logo) }}" class="my-auto ht-xl-80p wd-md-100p wd-xl-80p mx-auto" alt="logo">
              @endif
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-6 col-xl-5 bg-white">
          <div class="login d-flex align-items-center py-2">
            <div class="container p-0">
              <div class="row">
                <div class="col-md-10 col-lg-10 col-xl-9 mx-auto">
                  <div class="card-sigin">
                    <div class="mb-5 d-flex"> 
                      @php $logo = getSetting('logo'); @endphp
                      @if(isset($logo) && $logo!=''  && \Storage::exists(config('constants.SETTING_IMAGE_URL').$logo))
                      <a href="javascript:void(0)">
                      <img src="{{ \Storage::url(config('constants.SETTING_IMAGE_URL').$logo) }}" class="sign-favicon ht-40" alt="logo">
                      @endif

                     </a></div>
                    <!--  <a href="index.html"><img src="{{ asset('template/valex-theme/img/brand/favicon.png') }}" class="sign-favicon ht-40" alt="logo"></a> -->
                    <div class="card-sigin">
                      <div class="main-signup-header">
                        <h2>Welcome back!</h2>
                        <h5 class="font-weight-semibold mb-4">Please sign in to continue.</h5>
                        @if($flash = session('error'))            
                          <div class="alert alert-danger alert-dismissible" role="alert">
                              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                               {{ $flash }}
                          </div>
                        @endif
                        @if($flash = session('success'))      
                            <div class="alert alert-success alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                 {{ $flash }}
                            </div>
                        @endif
                        <form action="{{ route('login') }}" method="POST" >
                          @csrf
                          <div class="form-group">
                            <label>Email</label> 
                            <input id="email" type="email" class="form-control form-control-user @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus  placeholder="Enter {{ __('E-Mail Address') }}..."> 
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                          </div>
                          <div class="form-group">
                            <label>Password</label> <input id="password" type="password" class="form-control form-control-user @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Enter Password...">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                          </div>
                          <button class="btn btn-main-primary btn-block">Sign In</button>
                        </form>
                        <div class="main-signin-footer mt-5">
                          @if (Route::has('password.request'))
                          <p><a href="{{ route('password.request') }}">Forgot password?</a></p>
                          @endif
                          @if (Route::has('register'))
                          <p>Don't have an account? <a href="{{ route('register') }}">Create an Account</a></p>
                          @endif
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
</div>
   
<!-- <div class="container">
    <div class="row justify-content-center">
      <div class="col-xl-10 col-lg-12 col-md-9">
        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <div class="row">
              <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
              <div class="col-lg-6">
                <div class="p-5">
                    <div class="text-center">
                        <h1 class="h4 text-gray-900 mb-4"> {{ __('Login') }}</h1>
                    </div>
                    @if($flash = session('error'))            
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                             {{ $flash }}
                        </div>
                    @endif
                    @if($flash = session('success'))      
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                             {{ $flash }}
                        </div>
                    @endif
                    <form class="user" method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group">
                            <input id="email" type="email" class="form-control form-control-user @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus  placeholder="Enter {{ __('E-Mail Address') }}..."> 
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input id="password" type="password" class="form-control form-control-user @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Enter Password...">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group" hidden>
                            <div class="custom-control custom-checkbox small">
                                <input type="checkbox" class="custom-control-input" {{ old('remember')?'checked':''}} name="remember" id="remember"  value="1">
                                <label class="custom-control-label" for="remember">{{ __('Remember Me') }}</label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-user btn-block">
                          {{ __('Login') }}
                        </button>
                    </form>
                  <hr>
                  @if (Route::has('password.request'))
                  <div class="text-center">
                    <a href="{{ route('password.request') }}" class="small">Forgot Password?</a>
                  </div>
                  @endif
                  @if (Route::has('register'))
                  <div class="text-center">
                    <a href="{{ route('register') }}" class="small">Create an Account!</a>
                  </div>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

</div>  -->
@endsection
