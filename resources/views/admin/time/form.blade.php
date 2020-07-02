@extends('admin.layouts.valex_app')
@section('styles')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
@endsection
@section('content')
<div class="container">
    <div class="breadcrumb-header justify-content-between">
      <div class="left-content">
          <div>
            <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1">Times</h2>
          </div>
      </div>
    </div>
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                {!! Form::open(['method' => 'POST', 'route' => isset($time->id)?['admin.times.update',$time->id]:['admin.times.store'],'class' => 'form-horizontal','id' => 'frmTimes', 'files' => true]) !!}
                @csrf
                @if(isset($time->id))     
                @method('PUT')  
                @endif
                
                <div class="card-header py-3 cstm_hdr">
                    <h6 class="m-0 font-weight-bold text-primary">{{ isset($time->id)?'Edit':'Add' }} Time</h6>
                </div>
                <div class="card-body">
                  
                    <div class="col-md-6">
                          <div class="form-group {{$errors->has('title') ? config('constants.ERROR_FORM_GROUP_CLASS') : ''}}">
                            <label class="col-md-12 control-label" for="date">Title <span style="color:red">*</span></label>
                            <div class="col-md-12">
                                {!! Form::text('title',old('title',isset($time->title)?$time->title:''), ['class' => 'form-control', 'placeholder' => 'Title']) !!}
                                @if($errors->has('title'))
                                 <strong for="date" class="help-block">{{ $errors->first('title') }}</strong>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group {{$errors->has('start_time') ? config('constants.ERROR_FORM_GROUP_CLASS') : ''}}">
                                <label class="col-md-12 control-label" for="start_time">Start Time<span style="color:red">*</span></label>
                                <div class="col-md-12">

                                    <?php 
                                    $start_time=isset($time->start_time)?$time->start_time:'';
                                    if($start_time!=''){
                                        $start_time=date(config('constants.TIME_FORMAT'),strtotime($start_time));
                                    }
                                    ?>
                                    {!! Form::text('start_time',old('start_time',$start_time), ['readOnly'=>'readOnly', 'class' => 'form-control worktime', 'placeholder' => 'HH:MM']) !!}
                                    @if($errors->has('start_time'))
                                    <strong for="start_time" class="help-block">{{ $errors->first('start_time') }}</strong>
                                    @endif
                                </div>
                            </div>
                             <div class="col-md-6 form-group {{$errors->has('end_time') ? config('constants.ERROR_FORM_GROUP_CLASS') : ''}}">
                                <label class="col-md-12 control-label" for="end_time">End Time<span style="color:red">*</span></label>
                                <div class="col-md-12">
                                     <?php 
                                    $end_time=isset($time->end_time)?$time->end_time:'';
                                    if($end_time!=''){
                                        $end_time=date(config('constants.TIME_FORMAT'),strtotime($end_time));
                                    }
                                    ?>
                                    {!! Form::text('end_time',old('end_time',$end_time), ['readOnly'=>'readOnly', 'class' => 'form-control worktime', 'placeholder' => 'HH:MM']) !!}
                                    @if($errors->has('end_time'))
                                    <strong for="end_time" class="help-block">{{ $errors->first('end_time') }}</strong>
                                    @endif
                                </div>
                             </div>
                         </div>
                          <div class="row">
                            <div class="col-md-6 form-group {{$errors->has('break_start_time') ? config('constants.ERROR_FORM_GROUP_CLASS') : ''}}">
                                <label class="col-md-12 control-label" for="time">Break Start Time</label>
                                <div class="col-md-12">
                                     <?php 
                                    $break_start_time=isset($time->break_start_time)?$time->break_start_time:'';
                                    if($break_start_time!=''){
                                        $break_start_time=date(config('constants.TIME_FORMAT'),strtotime($break_start_time));
                                    }
                                    ?>
                                    {!! Form::text('break_start_time',old('break_start_time',$break_start_time), ['class' => 'form-control fulltimwe', 'readOnly'=>'readOnly', 'placeholder' => 'HH:MM']) !!}
                                    @if($errors->has('break_end_time'))
                                    <strong for="break_start_time" class="help-block">{{ $errors->first('break_start_time') }}</strong>
                                    @endif
                                </div>
                            </div>
                             <div class="col-md-6 form-group {{$errors->has('break_end_time') ? config('constants.ERROR_FORM_GROUP_CLASS') : ''}}">
                                <label class="col-md-12 control-label" for="time">Break End Time</label>
                                <div class="col-md-12">
                                    <?php 
                                    $break_end_time=isset($time->break_end_time)?$time->break_end_time:'';
                                    if($break_end_time!=''){
                                        $break_end_time=date(config('constants.TIME_FORMAT'),strtotime($break_end_time));
                                    }
                                    ?>
                                    {!! Form::text('break_end_time',old('break_end_time',$break_end_time), [ 'readOnly'=>'readOnly', 'class' => 'form-control fulltimwe', 'placeholder' => 'HH:MM']) !!}
                                    @if($errors->has('break_end_time'))
                                    <strong for="break_end_time" class="help-block">{{ $errors->first('break_end_time') }}</strong>
                                    @endif
                                </div>
                             </div>
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
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>

<script type="text/javascript">
jQuery(document).ready(function(){
    $('.fulltimwe').timepicker({
         'showDuration': true,
         'interval': 60,
         'timeFormat': 'hh:mm p'
    });
 
    $('.worktime').timepicker({
         'showDuration': true,
         'timeFormat': 'hh:mm p'
    });

    jQuery('#frmTimes').validate({
        rules: {
            title: {
                required: true
            },
            end_time: {
                required: true
            },
            start_time: {
                required: true
            },
        }
    });
});
</script>
@endsection