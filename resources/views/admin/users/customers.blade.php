@extends('admin.layouts.valex_app')
@section('styles')
<link href="{{asset('template/valex-theme/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{asset('template/valex-theme/plugins/datatable/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{asset('template/valex-theme/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
<link href="{{asset('template/valex-theme/plugins/datatable/css/jquery.dataTables.min.css')}}" rel="stylesheet">
<link href="{{asset('template/valex-theme/plugins/datatable/css/responsive.dataTables.min.css')}}" rel="stylesheet">
@endsection
@section('content')
<!-- Begin Page Content -->
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
        <div class="card-header py-3 cstm_hdr">
            <h6 class="m-0 font-weight-bold text-primary">Customers List</h6>
        </div>
        <div class="card-body">
         <!--  <div class="well mb-3">
                {!! Form::open(['method' => 'POST', 'class' => 'form-inline', 'id' => 'frmFilter']) !!}
                <div class="form-group mr-sm-2 mb-2">
                    {!! Form::select('role_id', $roles, old('role_id'), ['id'=>'role_id', 'class' => 'form-control', 'placeholder' => '-Select Type-']) !!}                   
                </div>   

                <button type="submit" class="btn btn-responsive btn-primary mr-sm-2 mb-2">{{ __('Filter') }}</button>
                <a href="javascript:;" onclick="resetFilter();" class="btn btn-responsive btn-danger mb-2">{{ __('Reset') }}</a>
                {!! Form::close() !!}
            </div> --> 
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0" id="users">
                    <thead>
                        <tr>
                          <th>Name</th>
                          <th>Email</th>
                          <th>Mobile</th>                          
                          <th>State</th>
                          <th>City</th>
                          <th>Registration Date</th>
                          <th>Action</th>                          
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                          <th>Name</th>
                          <th>Email</th>
                          <th>Mobile</th>                          
                          <th>State</th>
                          <th>City</th>                         
                          <th>Registration Date</th>
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
<!-- export btn -->
<script type="text/javascript">
jQuery(document).ready(function(){
    getUsers();
    jQuery('#frmFilter').submit(function(){
        getUsers();
        return false;
    });
});
function resetFilter(){
  jQuery('#frmFilter :input:not(:button, [type="hidden"])').val('');
  getUsers();
}

function getUsers(){
  var role_id = jQuery('#frmFilter [name=role_id]').val();
  jQuery('#users').dataTable().fnDestroy();
  jQuery('#users tbody').empty();
  jQuery('#users').DataTable({
      processing: true,
      serverSide: true,
      iDisplayLength:50,
      ajax: {
          url: '{{ route('admin.users.getCustomers') }}',
          method: 'POST',
          data: {  
               role_id:role_id   
              }
      },
      lengthMenu: [
          [10, 25, 50, 100,1000, -1],
          [10, 25, 50,100,1000,"All"]
      ],

      columns: [
          {data: 'name', name: 'name'},
          {data: 'email', name: 'email'},    
          {data: 'mobile_number', name: 'mobile_number'}, 
          {data: 'state', name: 'state.title'}, 
          {data: 'city', name: 'city.title'}, 
          {data: 'created_at', name: 'created_at'},
          {data: 'action', name: 'action', orderable: false, searchable: false, "width": "10%"},            
      ],           
      order: [[0, 'desc']],
      language: {
            searchPlaceholder: 'Search...',
            sSearch: '',
            lengthMenu: '_MENU_',
      },        
    });
}
</script>
@endsection