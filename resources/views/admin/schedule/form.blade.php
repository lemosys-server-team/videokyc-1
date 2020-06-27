@extends('admin.layouts.valex_app')
@section('styles')
<link href="{{asset('template/valex-theme/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/bootstrap-datepicker3.standalone.min.css') }}" rel="stylesheet">
@endsection
@section('content')
<div class="container">
    <div class="breadcrumb-header justify-content-between">
      <div class="left-content">
          <div>
            <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1">Schedules</h2>
          </div>
      </div>
    </div>
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                {!! Form::open(['method' => 'POST', 'route' => isset($schedule->id)?['admin.schedules.update',$schedule->id]:['admin.schedules.store'],'class' => 'form-horizontal','id' => 'frmSchedules', 'files' => true]) !!}
                @csrf
                @if(isset($schedule->id))     
                @method('PUT')  
                @endif
                
                <div class="card-header py-3 cstm_hdr">
                    <h6 class="m-0 font-weight-bold text-primary">{{ isset($schedule->id)?'Edit':'Add' }} Schedule</h6>
                </div>
                <div class="card-body">
                    <div class="form-group {{$errors->has('user_id') ? config('constants.ERROR_FORM_GROUP_CLASS') : ''}}">
                        <label class="col-md-3 control-label" for="sale_id">Customer Name <span style="color:red">*</span></label>
                        
                         <div class="col-md-6">
                             {!! Form::select('user_id', $users, old('user_id', isset($schedule->user_id)?$schedule->user_id:''), ['id'=>'user_id', 'data-error-container'=>'#user_id_error', 'class' => 'form-control user_id', 'placeholder' => '-Select User-']) !!}
                             <span id="user_id_error"></span>
                            @if($errors->has('user_id'))
                            <strong for="sale_id" class="help-block">{{ $errors->first('user_id') }}</strong>
                            @endif
                         </div>
                    </div>
                    
                    <div class="form-group {{$errors->has('date') ? config('constants.ERROR_FORM_GROUP_CLASS') : ''}}">
                        <label class="col-md-3 control-label" for="date">Date <span style="color:red">*</span></label>
                        <div class="col-md-6">
                            <?php $date=isset($schedule->datetime)?$schedule->datetime:date(config('constants.MYSQL_STORE_DATE_FORMAT'));
                                $date=date(config('constants.SITE_DATE_FORMAT'),strtotime($date)); ?>
                            {!! Form::text('date',old('dates',$date), ['readOnly'=>'readOnly' ,'class' => 'form-control datepicker', 'placeholder' => 'MM/DD/YYYY']) !!}
                            @if($errors->has('date'))

                            <strong for="date" class="help-block">{{ $errors->first('date') }}</strong>
                            @endif
                        </div>
                    </div>
                    <div class="form-group {{$errors->has('time') ? config('constants.ERROR_FORM_GROUP_CLASS') : ''}}">
                        <label class="col-md-3 control-label" for="time">Time<span style="color:red">*</span></label>
                        <div class="col-md-6">
                            <?php $time=isset($schedule->datetime)?$schedule->datetime:'';
                                  if($time!=''){
                                        $time=date('H:i',strtotime($time)); 
                                } ?>
                            {!! Form::time('time',old('time',$time), ['class' => 'form-control', 'placeholder' => 'Time']) !!}
                            @if($errors->has('time'))
                            <strong for="time" class="help-block">{{ $errors->first('time') }}</strong>
                            @endif
                        </div>
                    </div>
                    <div class="form-group {{$errors->has('sale_id') ? config('constants.ERROR_FORM_GROUP_CLASS') : ''}}">
                       <label class="col-md-3 control-label" for="user_id">Sales <span style="color:red">*</span></label>
                        <div class="col-md-6">
                            {!! Form::select('sale_id', $sales, old('sale_id', isset($schedule->sale_id)?$schedule->sale_id:''), ['data-error-container'=>'#sale_id_error', 'id'=>'sale_id', 'class' => 'select2 form-control', 'placeholder' => '-Select Customer-']) !!}
                            <span id="sale_id_error"></span>
                            @if($errors->has('sale_id'))
                            <strong for="sale_id" class="help-block">{{ $errors->first('sale_id') }}</strong>
                            @endif
                        </div>
                    </div>
                    
                </div>  
                <div class="card-footer">
                    <button type="submit" class="btn btn-responsive btn-primary">{{ __('Submit') }}</button>
                    <a href="{{route('admin.schedules.index')}}"  class="btn btn-responsive btn-secondary">{{ __('Cancel') }}</a>
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
<script src="{{ asset('template/valex-theme/plugins/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('js/datepicker/bootstrap-datepicker.min.js') }}"></script>

<script type="text/javascript">
jQuery(document).ready(function(){
    //$('[name="role_id[]"]').selectpicker();
    $('.select2').select2({
        placeholder: '-Select Customer-'
    });

    $('.user_id').select2({
        placeholder: '-Select User-'
    });

    $('.datepicker').datepicker({
            format: 'mm/dd/yyyy',
            orientation: 'bottom',
            autoclose: true
    });

    jQuery('#frmSchedules').validate({
        rules: {
            time: {
                required: true
            },
            date: {
                required: true
            },
            sale_id: {
                required: true
            },
            user_id: {
                required: true
            },
        }
    });
});
</script>
@endsection