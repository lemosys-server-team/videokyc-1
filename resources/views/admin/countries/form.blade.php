@extends('admin.layouts.valex_app')
@section('content')
<div class="container">
    <!-- Page Heading -->
    <div class="breadcrumb-header justify-content-between">
        <div class="left-content">
            <div>
              <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1">Market Survey</h2>
            </div>
        </div>
    </div>
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                {!! Form::open(['method' => 'POST', 'route' => isset($country->id)?['admin.countries.update',$country->id]:['admin.countries.store'],'class' => 'form-horizontal','id' => 'frmCountry', 'files' => true]) !!}
                @csrf
                @if(isset($country->id))
                @method('PUT')
                @endif
                <div class="card-header py-3 cstm_hdr">
                    <h6 class="m-0 font-weight-bold text-primary">{{ isset($country->id)?'Edit':'Add' }} Country</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="col-md-12 form-group {{$errors->has('title') ? config('constants.ERROR_FORM_GROUP_CLASS') : ''}}">
                                <label for="title">Title <span style="color:red">*</span></label>
                                {!! Form::text('title', old('title', isset($country->title)?$country->title:''), ['id'=>'title', 'class' => 'form-control', 'placeholder' => 'Title']) !!}

                                @if($errors->has('title'))
                                <p class="help-block">
                                    <strong>{{ $errors->first('title') }}</strong>
                                </p>
                                @endif
                            </div>                    
                                             
                        </div>
                    </div>
                </div>  
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <a href="{{route('admin.countries.index')}}"  class="btn btn-secondary">Cancel</a>
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
    jQuery('#frmCountry').validate({
        rules: {
            title: {
                required: true
            }          
        }
    });
});
</script>
@endsection
