@extends('auth.valex_app')
@section('styles')
<link href="{{asset('template/valex-theme/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
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
                         {!! Form::open(['method' => 'POST', 'route' =>['register'],'class' => 'form-horizontal','id' => 'frmregister']) !!}
                        @csrf
                
                        <form action="{{ route('register') }}" method="POST" id="register">
                          @csrf
                          <div class="form-group {{$errors->has('name') ? config('constants.ERROR_FORM_GROUP_CLASS') : ''}}">
                          <input type="text" name="name" value="{{old('name')}}" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Enter your name" required>
                          @if($errors->has('name'))
                        <p class="help-block">
                            <strong>{{ $errors->first('name') }}</strong>
                        </p>
                        @endif
                          </div>

                          <div class="form-group {{$errors->has('mobile_number') ? config('constants.ERROR_FORM_GROUP_CLASS') : ''}}">
                          <input type="text" name="mobile_number" value="{{old('mobile_number')}}" class="form-control @error('mobile_number') is-invalid @enderror" id="mobile" placeholder="Enter your mobile" required>

                          @if($errors->has('mobile_number'))
                        <p class="help-block">
                            <strong>{{ $errors->first('mobile_number') }}</strong>
                        </p>
                        @endif
                          </div>
                          <div class="form-group {{$errors->has('email') ? config('constants.ERROR_FORM_GROUP_CLASS') : ''}}">
                          <input type="text" name="email" value="{{old('email')}}"  class="form-control @error('email') is-invalid @enderror" id="email" placeholder="Enter your email" required>

                          @if($errors->has('email'))
                        <p class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </p>
                        @endif
                          </div>
                          <div class="form-group {{$errors->has('address1') ? config('constants.ERROR_FORM_GROUP_CLASS') : ''}}">
                          <input type="text" name="address1" value="{{old('address1')}}"  class="form-control @error('address1') is-invalid @enderror" id="address1" placeholder="Enter your address" required>
                          @if($errors->has('address1'))
                        <p class="help-block">
                            <strong>{{ $errors->first('address1') }}</strong>
                        </p>
                        @endif
                          </div>
                          <div class="form-group {{$errors->has('address2') ? config('constants.ERROR_FORM_GROUP_CLASS') : ''}}">
                          <input type="text" name="address2" value="{{old('address2')}}"  class="form-control @error('address2') is-invalid @enderror" id="address2" placeholder="Enter your address" required>
                          @if($errors->has('address2'))
                        <p class="help-block">
                            <strong>{{ $errors->first('address2') }}</strong>
                        </p>
                        @endif
                          </div>

                          <div class="form-group {{$errors->has('state_id') ? config('constants.ERROR_FORM_GROUP_CLASS') : ''}}">
                       {!! Form::select('state_id', $state, old('state_id', isset($state->state_id)?$state->state_id:''), ['id'=>'state', 'class' => 'form-control', 'placeholder' => '-Select State-']) !!}

                       @if($errors->has('state_id'))
                       <p class="help-block">
                           <strong>{{ $errors->first('state_id') }}</strong>
                       </p>
                       @endif 
                          </div>

                          <div class="form-group {{$errors->has('city_id') ? config('constants.ERROR_FORM_GROUP_CLASS') : ''}}">
                          {!! Form::select('city_id', [], old('city_id', isset($city->city_id)?$city->city_id:''), ['id'=>'city', 'class' => 'form-control', 'placeholder' => '-Select City-']) !!}
                            
                            @if($errors->has('city_id'))
                        <p class="help-block">
                            <strong>{{ $errors->first('city_id') }}</strong>
                        </p>
                        @endif
                          </div>
                          <div class="form-group {{$errors->has('dates') ? config('constants.ERROR_FORM_GROUP_CLASS') : ''}}">
                            {!! Form::text('dates',old('dates'), ['readOnly'=>'readOnly' ,'class' => 'form-control datepicker', 'placeholder' => 'MM/DD/YYYY']) !!}                         
                            @if($errors->has('dates'))
                        <p class="help-block">
                            <strong>{{ $errors->first('dates') }}</strong>
                        </p>
                        @endif
                          </div>
                          <div class="form-group">
                            {!! Form::time('time',old('time'), ['class' => 'form-control', 'placeholder' => 'Time']) !!}
                            @if($errors->has('time'))
                        <p class="help-block">
                            <strong>{{ $errors->first('time') }}</strong>
                        </p>
                        @endif
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
<script src="{{ asset('js/datepicker/bootstrap-datepicker.min.js') }}"></script>

<script type="text/javascript">
 $('.datepicker').datepicker({
            format: 'mm/dd/yyyy',
            orientation: 'bottom',
            autoclose: true
    });



jQuery(document).ready(function(){
    jQuery('#frmregister').validate({
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

// get city
$( "#state" ).change(function(){
  alert('hello');
    var state_id = $(this).val();
    if(state_id){
        $.ajax({
        type : "GET",
        url :'{{route('register.getstatetocity')}}',
        data:{state_id:state_id},
        success:function(res){
        $("#city").empty();
        $("#city").append('<option value="">Select City</option>');
        $.each(res,function(key,value){
        $("#city").append('<option value="'+key+'">'+value+'</option>');
        });
        }
});
}
});





</script>
@endsection
