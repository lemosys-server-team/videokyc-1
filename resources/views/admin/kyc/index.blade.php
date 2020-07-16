@extends('admin.layouts.valex_app')
@section('styles')
<link href="{{asset('template/valex-theme/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{asset('template/valex-theme/plugins/datatable/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{asset('template/valex-theme/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
<link href="{{asset('template/valex-theme/plugins/datatable/css/jquery.dataTables.min.css')}}" rel="stylesheet">
<link href="{{asset('template/valex-theme/plugins/datatable/css/responsive.dataTables.min.css')}}" rel="stylesheet">
<link href="{{ asset('css/bootstrap-datepicker3.standalone.min.css') }}" rel="stylesheet">
@endsection
@section('content')
<!-- Begin Page Content -->
<div class="container">
  <div class="breadcrumb-header justify-content-between">
      <div class="left-content">
          <div>
            <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1">KYC Details</h2>
          </div>
      </div>
  </div>
  <div class="row row-sm">
    <div class="col-xl-12">
      <div class="card">
        <div class="card-header py-3 cstm_hdr">
            <h6 class="m-0 font-weight-bold text-primary">KYC Details</h6>
           <!--  <a href="{{route('admin.schedules.create')}}" class="btn btn-sm btn-icon-split float-right btn-outline-warning">
                <span class="icon text-white-50">
                  <i class="fas fa-plus"></i>
                </span>
                <span class="text">Add Schedule</span>
            </a> -->
           
        </div>
        <div class="card-body">
              <div class="well mb-3">
                            {!! Form::open(['method' => 'POST', 'class' => 'form-inline', 'id' => 'frmFilter']) !!}
                            <?php if(Auth::user()->roles->first()->id == config('constants.ROLE_TYPE_SUPERADMIN_ID')){   ?>
                            <div class="form-group mr-sm-2 mb-2">
                                {!! Form::select('sales_id', $sales, old('sales_id'), ['id'=>'sales_id', 'class' => 'form-control', 'placeholder' => '-Select Sales-']) !!}                   
                            </div>
                            <div class="form-group mr-sm-2 mb-2">
                                {!! Form::select('user_id', $users, old('user_id'), ['id'=>'user_id', 'class' => 'form-control', 'placeholder' => '-Select Customer-']) !!}                   
                            </div> 

                            <?php } ?>   
                            <div class="form-group mr-sm-2 mb-2">
                                  {!! Form::text('date', old('date', isset($market->date)?$market->date:''), ['id'=>'date', 'class' => 'form-control datepicker', 'placeholder' => 'MM/DD/YYYY','readOnly'=>'readOnly' ]) !!}                  
                            </div>  
                           <!--  <div class="form-group mr-sm-2 mb-2">
                                <select name="status" id="status" class="form-control">
                                    <option value="">-Select Status-</option>
                                    <option value="{{ config('constants.PENDING') }}">{{ ucwords(config('constants.PENDING')) }}</option>
                                    <option value="{{ config('constants.COMPLETED') }}">{{ ucwords(config('constants.COMPLETED')) }}</option>
                                    <option value="{{ config('constants.FAILED') }}">{{ ucwords(config('constants.FAILED')) }}</option>
                                </select>                
                            </div> -->  
                            <button type="submit" class="btn btn-responsive btn-primary mr-sm-2 mb-2">{{ __('Filter') }}</button>
                            <a href="javascript:;" onclick="resetFilter();" class="btn btn-responsive btn-danger mb-2">{{ __('Reset') }}</a>
                            {!! Form::close() !!}
              </div> 
              <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0" id="schedules">
                    <thead>
                        <tr>
                          <th>Customer</th>
                          <th>Sale</th>
                          <th>Date</th>
                          <th>Aadhar</th>
                          <th>Pan</th>
                          <th>Photo</th>
                          <th>SS01</th>
                          <th>SS02</th>
                          <th>SS03</th>
                          <th>Status</th>
                          <th>KYC Status</th>
                          <th>Admin Status</th>
                          <th>Action</th>                          
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                          <th>Customer</th>
                          <th>Sale</th>
                          <th>Date</th>
                          <th>Aadhar</th>
                          <th>Pan</th>
                          <th>Photo</th>
                          <th>SS01</th>
                          <th>SS02</th>
                          <th>SS03</th>
                          <th>Status</th>
                          <th>KYC Status</th>
                          <th>Admin Status</th>
                          <th>Action</th>                        
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>    
<!-- /.container-fluid -->
@endsection
@section('scripts')
<!-- Page level plugins -->
<script src="{{ asset('template/valex-theme/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template/valex-theme/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
<script src="{{ asset('template/valex-theme/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('template/valex-theme/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
<script src="{{ asset('template/valex-theme/plugins/datatable/js/jquery.dataTables.js') }}"></script>
<script src="{{ asset('template/valex-theme/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{ asset('template/valex-theme/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('template/valex-theme/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('template/valex-theme/plugins/datatable/js/vfs_fonts.js') }}"></script>
<script src="{{ asset('js/datepicker/bootstrap-datepicker.min.js') }}"></script>
<!-- export btn -->
<script type="text/javascript">
jQuery(document).ready(function(){
    getkyc();
    
    jQuery('#frmFilter').submit(function(){
        getkyc();
        return false;
    });
    $('.datepicker').datepicker({
            format: 'mm/dd/yyyy',
            orientation: 'bottom',
            autoclose: true
    });
});

function resetFilter(){
    jQuery('#frmFilter :input:not(:button, [type="hidden"])').val('');
    getkyc();
}

function getkyc(){
  var sales_id = jQuery('#frmFilter [name=sales_id]').val(); 
  var date = jQuery('#frmFilter [name=date]').val(); 
  var user_id = jQuery('#frmFilter [name=user_id]').val();  
  jQuery('#schedules').dataTable().fnDestroy();
  jQuery('#schedules tbody').empty();
  jQuery('#schedules').DataTable({

      processing: true,
      serverSide: true,
      iDisplayLength:50,
      ajax: {
          url: '{{ route('admin.kyc.getkyc') }}',
          method: 'POST',
          data: {  
              sales_id:sales_id,
              date:date,
              user_id:user_id
              }
      },
      lengthMenu: [
          [10, 25, 50, 100,1000, -1],
          [10, 25, 50,100,1000,"All"]
      ],

      columns: [
          {data: 'user.name', name: 'user.name'},
          {data: 'sale.name', name: 'sale.name'},
          {data: 'datetime', name: 'datetime'},
          {data: 'image_adhar', name: 'image_adhar'},
          {data: 'image_pen', name: 'image_pen'},
          {data: 'image_photo', name: 'image_photo'},
          {data: 'ss01', name: 'ss01'},
          {data: 'ss02', name: 'ss02'},
          {data: 'ss03', name: 'ss03'},
          {data: 'final_status', name: 'final_status'},
          {data: 'kyc_status', name: 'kyc_status'},
          {data: 'admin_by_status', name: 'admin_by_status',"width": "5%"},
          {data: 'action', name: 'action', orderable: false, searchable: false, "width": "2%"},            
      ],           
      order: [[2, 'desc']],
      language: {
            searchPlaceholder: 'Search...',
            sSearch: '',
            lengthMenu: '_MENU_',
      },        
    });
}
</script>
@endsection