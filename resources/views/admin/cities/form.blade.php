@extends('admin.layouts.valex_app')

@section('content')
<div class="container">
    <!-- Page Heading -->
    <div class="breadcrumb-header justify-content-between">
        <div class="left-content">
            <div>
              <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1">Cities</h2>
            </div>
        </div>
    </div>
    <div class="row row-sm">
        <div class="col-xl-12">
    <!-- Content Row -->
    <div class="card">
        {!! Form::open(['method' => 'POST', 'route' => isset($city->id)?['admin.cities.update',$city->id]:['admin.cities.store'],'class' => 'form-horizontal','id' => 'frmCity', 'files' => true]) !!}
        @csrf
        @if(isset($city->id))
        @method('PUT')
        @endif
        <div class="card-header py-3 cstm_hdr">
            <h6 class="m-0 font-weight-bold text-primary">{{ isset($city->id)?'Edit':'Add' }} City</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                     <div class="col-md-12 form-group {{$errors->has('country_id') ? config('constants.ERROR_FORM_GROUP_CLASS') : ''}}">
                        <label for="country_id">Country <span style="color:red">*</span></label>
                       
                        {!! Form::select('country_id', $countries, old('country_id', isset($city->country_id)?$city->country_id:''), ['id'=>'country_id', 'class' => 'form-control', 'placeholder' => 'Select Country']) !!}

                        @if($errors->has('country_id'))
                        <p class="help-block">
                            <strong>{{ $errors->first('country_id') }}</strong>
                        </p>
                        @endif                       

                    </div>
                    <div class="col-md-12 form-group {{$errors->has('title') ? config('constants.ERROR_FORM_GROUP_CLASS') : ''}}">
                        <label for="title">Title <span style="color:red">*</span></label>
                        {!! Form::text('title', old('title', isset($city->title)?$city->title:''), ['id'=>'title', 'class' => 'form-control', 'placeholder' => 'Title']) !!}

                        @if($errors->has('title'))
                        <p class="help-block">
                            <strong>{{ $errors->first('title') }}</strong>
                        </p>
                        @endif
                    </div> 

                   
                   <!--  <div class="col-md-12 form-group {{$errors->has('latitude') ? config('constants.ERROR_FORM_GROUP_CLASS') : ''}}">
                                <label for="latitude">Latitude <span style="color:red">*</span></label>
                                {!! Form::text('latitude', old('latitude', isset($city->latitude)?$city->latitude:''), ['id'=>'latitude', 'class' => 'form-control', 'placeholder' => 'Latitude']) !!}

                                @if($errors->has('latitude'))
                                <p class="help-block">
                                    <strong>{{ $errors->first('latitude') }}</strong>
                                </p>
                                @endif


 </div>
                    <div class="col-md-12 form-group {{$errors->has('longitude') ? config('constants.ERROR_FORM_GROUP_CLASS') : ''}}">
                                <label for="longitude">Longitude <span style="color:red">*</span></label>
                                {!! Form::text('longitude', old('longitude', isset($city->longitude)?$city->longitude:''), ['id'=>'longitude', 'class' => 'form-control', 'placeholder' => 'Longitude']) !!}


                                @if($errors->has('longitude'))
                                <p class="help-block">
                                    <strong>{{ $errors->first('longitude') }}</strong>
                                </p>
                                @endif
                            </div>  

               <div class="col-md-12 form-group {{$errors->has('timezone') ? config('constants.ERROR_FORM_GROUP_CLASS') : ''}}">                                       
               <label for="timezone">Timezone <span style="color:red">*</span></label> 
                     {!! Form::select('timezone', $timezones, old('timezone', isset($city->timezone)?$city->timezone:''), ['id'=>'timezone', 'class' => 'form-control', 'placeholder' => 'Select timezone']) !!}   

                @if($errors->has('timezone'))
                <p class="help-block">
                    <strong>{{ $errors->first('timezone') }}</strong>
                </p>
                @endif
             </div> -->
         </div>
        </div>
        </div>  
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="{{route('admin.cities.index')}}"  class="btn btn-secondary">Cancel</a>
        </div>
        {!! Form::close() !!}
    </div>
    </div>
    </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript" src="{{ asset('js/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery('#frmCity').validate({
        rules: {
            country_id: {
                required: true
            },
            title: {
                required: true
            },
          /*  latitude:{
                required: true,
                min: -90,
                max: 90                
            },
            longitude:{ 
                required: true,               
                min: -180,
                max: 180                
            },
            timezone:{ 
                required: true
            }*/
        }
    });
});
</script>
@endsection
