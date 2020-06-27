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
            <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1">Users</h2>
          </div>
      </div>
  </div>
  <div class="row row-sm">
    <div class="col-xl-12">
      <div class="card">
        <div class="card-header py-3 cstm_hdr">
            <h6 class="m-0 font-weight-bold text-primary">Users List</h6>
            <a href="{{route('admin.users.create')}}" class="btn btn-sm btn-icon-split float-right btn-outline-warning">
                <span class="icon text-white-50">
                  <i class="fas fa-plus"></i>
                </span>
                <span class="text">Add User</span>
            </a>
           
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0" id="users">
                    <thead>
                        <tr>
                          <th>Name</th>
                          <th>Email</th>
                          <th>Mobile</th>                          
                          <th>Registration Date</th>
                          <th>Type</th> 
                          <th>Status</th>
                          <th>Action</th>                          
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                          <th>Name</th>
                          <th>Email</th>
                          <th>Mobile</th>                          
                          <th>Registration Date</th>
                          <th>Type</th> 
                          <th>Status</th>
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
});

function getUsers(){
  var role_id = jQuery('#frmFilter [name=role_id]').val();
  jQuery('#users').dataTable().fnDestroy();
  jQuery('#users tbody').empty();
  jQuery('#users').DataTable({
      processing: true,
      serverSide: true,
      iDisplayLength:50,
      ajax: {
          url: '{{ route('admin.users.getUsers') }}',
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
          {data: 'created_at', name: 'created_at'},
          {data: 'role', name: 'roles.name'},            
          {data: 'is_active', name: 'is_active', class: 'text-center', "width": "5%"},
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