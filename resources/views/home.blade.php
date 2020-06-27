@extends('auth.valex_app')
@section('styles')
<link href="{{ asset('css/bootstrap-datepicker3.standalone.min.css') }}" rel="stylesheet">
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
                        <h5 class="font-weight-semibold mb-4">Kindly fill the registration form for Digital KYC</h5>
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
                         {!! Form::open(['method' => 'POST', 'route' =>['register'],'class' => 'form-horizontal','id' => 'frmSchedules', 'files' => true]) !!}
                @csrf
                
                        <form action="{{ route('register') }}" method="POST" id="register">
                          @csrf
                          <div class="form-group">
                          <!-- <input type="hidden" value="1" name="role_id"> -->
                          <input type="text" name="name" value="{{old('name')}}" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Enter your name">
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                          </div>

                          <div class="form-group">
                          <input type="text" name="mobile_number" value="{{old('mobile_number')}}" class="form-control @error('mobile_number') is-invalid @enderror" id="mobile" placeholder="Enter your mobile">

                            @error('mobile_number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                          </div>
                          <div class="form-group">
                          <input type="text" name="email" value="{{old('email')}}"  class="form-control @error('email') is-invalid @enderror" id="email" placeholder="Enter your email">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                          </div>
                          <div class="form-group">
                          <input type="text" name="address1" value="{{old('address1')}}"  class="form-control @error('address1') is-invalid @enderror" id="address1" placeholder="Enter your address">
                            @error('address1')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                          </div>

                          <div class="form-group">
                          <input type="text" name="address2" value="{{old('address2')}}"  class="form-control @error('address2') is-invalid @enderror" id="address2" placeholder="Enter your address">

                            @error('address2')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                          </div>

                          <div class="form-group">
                          <select name="state_id" id="state" value="{{old('state_id')}}"  class="form-control @error('state_id') is-invalid @enderror" placeholder="Select state">
                            <option value="">-Select-</option>
                            <option value="1">Madhya Pradesh</option>
                            <option value="1">Rajasthan</option>
                            <option value="1">Maharashtra</option>
                            </select>                            
                            @error('state_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                          </div>

                          <div class="form-group">
                          <select name="city_id" id="city" value="{{old('city_id')}}"  class="form-control @error('city_id') is-invalid @enderror" placeholder="Select City">
                          <option value="">-Select-</option>
                            <option value="1">Indore</option>
                            </select>                             
                            @error('city_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                          </div>
                          <div class="form-group">
                          <?php $date=isset($schedule->datetime)?$schedule->datetime:date(config('constants.MYSQL_STORE_DATE_FORMAT'));
                                $date=date(config('constants.SITE_DATE_FORMAT'),strtotime($date)); ?>
                            {!! Form::text('dates',old('dates',$date), ['readOnly'=>'readOnly' ,'class' => 'form-control datepicker', 'placeholder' => 'MM/DD/YYYY']) !!}                         
                            @error('date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                          </div>
                          <div class="form-group">
                          <?php $time=isset($schedule->datetime)?$schedule->datetime:'';
                                  if($time!=''){
                                        $time=date('H:i',strtotime($time)); 
                                } ?>
                            {!! Form::time('time',old('time',$time), ['class' => 'form-control', 'placeholder' => 'Time']) !!}
                          @error('time')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                          </div>
                         <button type="submit" class="btn btn-responsive btn-primary">{{ __('Submit') }}</button>
                          <!-- <button type="Submit" class="btn btn-main-primary btn-block">Submit</button> -->
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
@section('scripts')
<script src="{{ asset('js/datepicker/bootstrap-datepicker.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('js/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery-validation/dist/additional-methods.min.js') }}"></script>
<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery('#frmSchedules').validate({
        rules: {
            name: {
                required: true
            },
            mobile: {
                required: true            
            }, 
            email: {
                required: true
            }, 
            date: {
                required: true
            },
            time: {
                required: true
            }, 
            state: {
                required: true
            }, 
            city: {
                required: true
            }, 
            address1: {
                required: true
            }, 
            address2: {
                required: true
            }, 
                
        }
    });
});
</script>
@endsection
