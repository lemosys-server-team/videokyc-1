@extends('admin.layouts.valex_app')
@section('styles')
<link href="{{asset('template/valex-theme/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection
@section('content')
<div class="container">
    <div class="breadcrumb-header justify-content-between">
        <div class="left-content">
          <div>
            <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1">Customers</h2>
          </div>
        </div>
    </div>
    <div class="row row-sm">
        <div class="col-xl-12">
        <div class="card">
        {!! Form::open(['method' => 'POST','files'=>true,'route' => ['admin.customers.update',$user->id],'class' => 'form-horizontal','id' => 'frmUser']) !!}
            @method('PUT')
        <div class="card-header py-3 cstm_hdr">
            <h6 class="m-0 font-weight-bold text-primary">Edit Customer</h6>
        </div>
        <div class="card-body">
            <div class="form-group {{$errors->has('name') ? config('constants.ERROR_FORM_GROUP_CLASS') : ''}}">
                <label class="col-md-3 control-label" for="name">Name <span style="color:red">*</span></label>
                 <div class="col-md-6">
                    {!! Form::text('name', old('name',$user->name), ['class' => 'form-control', 'placeholder' => 'First Name']) !!}
                    @if($errors->has('name'))
                    <strong for="name" class="help-block">{{ $errors->first('name') }}</strong>
                    @endif
                </div>
            </div>
            
            <div class="form-group {{$errors->has('email') ? config('constants.ERROR_FORM_GROUP_CLASS') : ''}}">
                <label class="col-md-3 control-label" for="email">Email <span style="color:red">*</span></label>
                <div class="col-md-6">
                    {!! Form::text('email',old('email',$user->email), ['class' => 'form-control autoFillOff', 'placeholder' => 'Email']) !!}
                    @if($errors->has('email'))
                    <strong for="email" class="help-block">{{ $errors->first('email') }}</strong>
                    @endif
                </div>
            </div>
            <div class="form-group {{$errors->has('mobile_number') ? config('constants.ERROR_FORM_GROUP_CLASS') : ''}}">
                <label class="col-md-3 control-label" for="mobile_number">Mobile Number <span style="color:red">*</span></label>
                <div class="col-md-6">
                    {!! Form::text('mobile_number',old('mobile_number',$user->mobile_number), ['class' => 'form-control autoFillOff', 'placeholder' => 'Mobile Number']) !!}
                    @if($errors->has('mobile_number'))
                    <strong for="mobile_number" class="help-block">{{ $errors->first('mobile_number') }}</strong>
                    @endif
                </div>
            </div>
            <div class="form-group {{$errors->has('address1') ? config('constants.ERROR_FORM_GROUP_CLASS') : ''}}">
                <label class="col-md-3 control-label" for="address1">Address1 <span style="color:red">*</span></label>
                <div class="col-md-6">
                <input type="text" name="address1" value="{{old('address1',isset($user->address1)?$user->address1:'')}}"  class="form-control @error('address1') is-invalid @enderror" id="address1" placeholder="address 1" >
                @if($errors->has('address1'))
                    <strong for="address1" class="help-block">{{ $errors->first('address1') }}</strong>
                @endif
                </div>
            </div>

            <div class="form-group {{$errors->has('address2') ? config('constants.ERROR_FORM_GROUP_CLASS') : ''}}">
                <label class="col-md-3 control-label" for="address2">address2 <span style="color:red">*</span></label>
                <div class="col-md-6">
                    <input type="text" name="address2" value="{{old('address2',isset($user->address2)?$user->address2:'')}}"  class="form-control @error('address2') is-invalid @enderror" id="address2" placeholder="address 1" >
                    @if($errors->has('address2'))
                        <strong for="address2" class="help-block">{{ $errors->first('address2') }}</strong>
                    @endif
                </div>
            </div>
            
            <div class="form-group {{$errors->has('state_id') ? config('constants.ERROR_FORM_GROUP_CLASS') : ''}}">
                <label class="col-md-3 control-label" for="state_id">State <span style="color:red">*</span></label>
                <div class="col-md-6">
                   {!! Form::select('state_id', $state, old('state_id', isset($user->state_id)?$user->state_id:''), ['id'=>'state_id', 'class' => 'form-control select2', 'placeholder' => '-Select State-']) !!}

                    @if($errors->has('state_id'))
                        <strong for="state_id" class="help-block">{{ $errors->first('state_id') }}</strong>
                    @endif
                </div>
            </div>

            <div class="form-group {{$errors->has('city_id') ? config('constants.ERROR_FORM_GROUP_CLASS') : ''}}">
                <label class="col-md-3 control-label" for="city_id">City <span style="color:red">*</span></label>
                <div class="col-md-6">
                   {!! Form::select('city_id', [], old('city_id', isset($user->city_id)?$user->city_id:''), ['id'=>'city_id', 'class' => 'form-control select2', 'placeholder' => '-Select City-']) !!}

                    @if($errors->has('city_id'))
                        <strong for="city_id" class="help-block">{{ $errors->first('city_id') }}</strong>
                    @endif
                </div>
            </div>
        </div> 
        <div class="card-footer">
            <button type="submit" class="btn btn-responsive btn-primary">{{ __('Submit') }}</button>
            <a href="{{route('admin.users.index')}}"  class="btn btn-responsive btn-secondary">{{ __('Cancel') }}</a>
        </div>
        {!! Form::close() !!}
    </div>
</div>
<!-- /.container-fluid -->
@endsection
@section('scripts')
<script type="text/javascript" src="{{ asset('js/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery-validation/dist/additional-methods.min.js') }}"></script>
<script src="{{ asset('template/valex-theme/plugins/select2/js/select2.min.js') }}"></script>

<script type="text/javascript">
jQuery(document).ready(function(){

    //$('[name="role_id[]"]').selectpicker();
    $('.select2').select2({
            placeholder: 'Choose one',
            searchInputPlaceholder: 'Search'
    });

    jQuery('#frmUser').validate({
        rules: {
            name: {
                required: true
            },
            mobile_number: {
                required: true,
                number: true         
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
            state_id: {
                required: true
            }, 
            city_id: {
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
$( "#state_id" ).change(function(){
    var state_id = $(this).val();
    var city_id = "{{isset($user->city_id)?$user->city_id:0}}";
    if(state_id){
        $.ajax({
                type : "GET",
                url :'{{route('register.getstatetocity')}}',
                data:{state_id:state_id},
                success:function(res){
                    $("#city_id").empty();
                    $("#city_id").append('<option value="">Select City</option>');
                    $.each(res,function(key,value){
                        var selected = '';
                        if (city_id == key) {
                            selected = 'selected="selected"';
                        }
                        $("#city_id").append('<option value="'+key+'" '+selected+'>'+value+'</option>');
                    });
                }
        });
    }
}).trigger('change');
</script>
@endsection