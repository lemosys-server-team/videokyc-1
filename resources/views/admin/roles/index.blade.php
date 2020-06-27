@extends('admin.layouts.valex_app')
@section('styles')
<link href="{{asset('template/valex-theme/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{asset('template/valex-theme/plugins/datatable/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{asset('template/valex-theme/plugins/datatable/css/jquery.dataTables.min.css')}}" rel="stylesheet">
<link href="{{asset('template/valex-theme/plugins/datatable/css/responsive.dataTables.min.css')}}" rel="stylesheet">
@endsection

@section('content')
<div class="container">
    <!-- Content Row -->
    <div class="breadcrumb-header justify-content-between">
        <div class="left-content">
            <div>
                <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1">Roles</h2>
            </div>
        </div>
    </div>
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header py-3 cstm_hdr">
                     <h6 class="m-0 font-weight-bold text-primary">Roles</h6>            
                </div>
                <div class="card-body">           
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0" id="roles">
                            <thead>
                                <tr>                            
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $key => $role)
                                <tr>
                                    <td>{{ ucwords($role->name) }}</td>
                                    <td class="center">
                                    <a href="javascript:void(0)"> <span class="badge badge-success">Active</span> </a></td>
                                    <td>
                                      <a href="{{route('admin.roles.show',[$role->id])}}" class="btn btn-warning btn-circle btn-sm" title="view"><i class="fa fa-eye"></i></a>
                                      <a href="{{route('admin.roles.edit',[$role->id])}}" class="btn btn-success btn-circle btn-sm" title="edit"><i class="fas fa-edit"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                             <tfoot>
                                <tr>                            
                                   <th>Name</th>
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
@endsection
@section('scripts')
<script src="{{ asset('template/valex-theme/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template/valex-theme/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
<script src="{{ asset('template/valex-theme/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('template/valex-theme/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
<script src="{{ asset('template/valex-theme/plugins/datatable/js/jquery.dataTables.js') }}"></script>
<script src="{{ asset('template/valex-theme/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{ asset('template/valex-theme/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('template/valex-theme/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('template/valex-theme/plugins/datatable/js/vfs_fonts.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {
  jQuery('#roles').DataTable();
});
</script>
@endsection


