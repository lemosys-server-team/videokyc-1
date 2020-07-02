@extends('admin.layouts.valex_app')
@section('styles')
<link href="{{ asset('css/bootstrap-datepicker3.standalone.min.css') }}" rel="stylesheet">
@endsection
@section('content')
<div class="container">
    <div class="breadcrumb-header justify-content-between">
      <div class="left-content">
          <div>
            <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1">Holidays</h2>
          </div>
      </div>
    </div>
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                {!! Form::open(['method' => 'POST', 'route' => isset($holiday->id)?['admin.holidays.update',$holiday->id]:['admin.holidays.store'],'class' => 'form-horizontal','id' => 'frmHToliday', 'files' => true]) !!}
                @csrf
                @if(isset($holiday->id))     
                @method('PUT')  
                @endif
                
                <div class="card-header py-3 cstm_hdr">
                    <h6 class="m-0 font-weight-bold text-primary">{{ isset($holiday->id)?'Edit':'Add' }} Time</h6>
                </div>
                <div class="card-body">
                        <div class="form-group {{$errors->has('title') ? config('constants.ERROR_FORM_GROUP_CLASS') : ''}}">
                            <label class="col-md-6 control-label" for="date">Title <span style="color:red">*</span></label>
                            <div class="col-md-6">
                                {!! Form::text('title',old('title',isset($holiday->title)?$holiday->title:''), ['class' => 'form-control', 'placeholder' => 'Title']) !!}
                                @if($errors->has('title'))
                                 <strong for="date" class="help-block">{{ $errors->first('title') }}</strong>
                                @endif
                            </div>
                        </div>

                        <div class="form-group {{$errors->has('date') ? config('constants.ERROR_FORM_GROUP_CLASS') : ''}}">
                                <label class="col-md-6 control-label" for="date">Date<span style="color:red">*</span></label>
                                <div class="col-md-6">
                                    <?php 
                                    $date=isset($holiday->date)?$holiday->date:'';
                                    if($date!=''){
                                        $date=date(config('constants.SITE_DATE_FORMAT'),strtotime($date));
                                    }
                                    ?>
                                    {!! Form::text('date',old('date',$date), ['readOnly'=>'readOnly', 'class' => 'form-control datepicker', 'placeholder' => 'MM/DD/YYYY']) !!}
                                    @if($errors->has('date'))
                                    <strong for="date" class="help-block">{{ $errors->first('date') }}</strong>
                                    @endif
                                </div>
                            </div>
                  </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-responsive btn-primary">{{ __('Submit') }}</button>
                    <a href="{{route('admin.times.index')}}"  class="btn btn-responsive btn-secondary">{{ __('Cancel') }}</a>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>        
</div>
<!-- /.container-fluid -->
@endsection

@section('scripts')
<script type="text/javascript" src="{{ asset('js/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery-validation/dist/additional-methods.min.js') }}"></script>
<script src="{{ asset('js/datepicker/bootstrap-datepicker.min.js') }}"></script>

<script type="text/javascript">
jQuery(document).ready(function(){
    $('.datepicker').datepicker({
            format: 'mm/dd/yyyy',
            orientation: 'bottom',
            autoclose: true
    });

    jQuery('#frmHToliday').validate({
        rules: {
            title: {
                required: true
            },
            date: {
                required: true
            },
        }
    });
});
</script>
@endsection