@extends('auth.valex_app')
@section('frontend_styles')
<link href="{{asset('css/jquery-ui.css')}}" rel="stylesheet">
@endsection
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
                  <div class="card-sigin">
                      <div class="main-signup-header">
                       <!-- <h2>Welcome back!</h2> -->
                        <h5 class="font-weight-semibold mb-4">Please Enter the OTP to Verify your account</h5>
                          @if($flash = (session('error') ?: session('danger')))               
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
                          {!! Form::open(['method' => 'POST', 'route' =>['verifyOTP'],'class' => 'form-horizontal','id' => 'frmVerification']) !!}
                              @csrf
                              <div class="form-group {{$errors->has('password') ? config('constants.ERROR_FORM_GROUP_CLASS') : ''}}">
                                  <input type="password" name="password" value="" id="password" class="form-control" placeholder="Enter your OTP" >
                                  @if($errors->has('password'))
                                <p class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </p>
                                @endif
                              </div>
                              <input type="hidden" name="email" value="{{ $user->email }}">
                              <button type="submit" class="btn btn-main-primary btn-block">{{ __('Submit') }}</button>
                          {!! Form::close() !!}
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
@endsection
<!-- custom script -->
@section('frontend_script')
<script type="text/javascript" src="{{ asset('js/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script type="text/javascript">
jQuery(document).ready(function(){

    jQuery('#frmVerification').validate({
        rules: {
            password: {
                required: true
            },
            email: {
                required: true    
            },     
        }
    });
});
</script>

@endsection


