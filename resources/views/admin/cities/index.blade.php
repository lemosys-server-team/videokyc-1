@extends('admin.layouts.valex_app')
@section('styles')
<link href="{{asset('template/valex-theme/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{asset('template/valex-theme/plugins/datatable/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{asset('template/valex-theme/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
<link href="{{asset('template/valex-theme/plugins/datatable/css/jquery.dataTables.min.css')}}" rel="stylesheet">
<link href="{{asset('template/valex-theme/plugins/datatable/css/responsive.dataTables.min.css')}}" rel="stylesheet">
@endsection

@section('content')

<div class="container">
  <div class="breadcrumb-header justify-content-between">
      <div class="left-content">
          <div>
            <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1">Cities</h2>
          </div>
      </div>
  </div>
  <div class="row row-sm">
    <div class="col-xl-12">
      <div class="card">
        <div class="card-header py-3 cstm_hdr">
            <h6 class="m-0 font-weight-bold text-primary">Cities</h6>
            <a href="{{route('admin.cities.create')}}" class="btn btn-sm btn-icon-split float-right btn-outline-warning">
                <span class="icon text-white-50">
                  <i class="fas fa-plus"></i>
                </span>
                <span class="text">Add City</span>
            </a>
        </div>
        <div class="card-body">
             <div class="well mb-3">
                {!! Form::open(['method' => 'POST', 'class' => 'form-inline', 'id' => 'frmFilter']) !!}
                <div class="form-group mr-sm-2 mb-2">
                    {!! Form::select('country_id', $countries, old('country_id', isset($city->country_id)?$city->country_id:''), ['id'=>'country_id', 'class' => 'form-control', 'placeholder' => 'Select Country']) !!}                   
                </div>   

                <button type="submit" class="btn btn-responsive btn-primary mr-sm-2 mb-2">{{ __('Filter') }}</button>
                <a href="javascript:;" onclick="resetFilter();" class="btn btn-responsive btn-danger mb-2">{{ __('Reset') }}</a>
                {!! Form::close() !!}
            </div> 
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0" id="cities">
                    <thead>
                        <tr>
                           <th>City Name</th> 
                            <th>Country Name</th>  
                            <!-- <th>Latitude</th>
                            <th>Longitude</th>
                            <th>Timezone</th> 
                            <th>Apply DLS</th>  -->                         
                            <th>Status</th>           
                            <th>Action</th>                      
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                           <th>City Name</th> 
                            <th>Country Name</th>  
                            <!-- <th>Latitude</th>
                            <th>Longitude</th>
                            <th>Timezone</th> 
                            <th>Apply DLS</th>  -->                         
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
    jQuery(document).ready(function(){
        getCities();
        jQuery('#frmFilter').submit(function(){
            getCities();
            return false;
        });
    });
    function resetFilter(){
        jQuery('#frmFilter :input:not(:button, [type="hidden"])').val('');
        getCities();
    }
    function getCities(){        
        var country_id = jQuery('#frmFilter [name=country_id]').val();
        jQuery('#cities').dataTable().fnDestroy();
        jQuery('#cities tbody').empty();
        var table=jQuery('#cities').DataTable({
            processing: true,
            serverSide: true,
            iDisplayLength:50,
            ajax: {
                url: '{{ route('admin.cities.getCities') }}',
                method: 'POST',
                data: {  
                 country_id: country_id   
                }
            },
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50,100,"All"]
            ],
            columns: [              
                {data: 'title', name: 'title'}, 
                {data: 'country.title', name: 'country.title'},
              /*  {data: 'latitude', name: 'latitude'},
                {data: 'longitude', name: 'longitude'},
                {data: 'timezone', name: 'timezone'},      
                {data: 'is_daylight_saving', name: 'is_daylight_saving', class: 'text-center', "width": "10%"},        */     
                {data: 'is_active', name: 'is_active', class: 'text-center', "width": "10%"},             
                {data: 'action', name: 'action', orderable: false, searchable: false, "width": "15%"}    
            ],           
            order: [[0, 'asc']], 
            language: {
                searchPlaceholder: 'Search...',
                sSearch: '',
                lengthMenu: '_MENU_',
            }, 
        }); 
    
    }      
</script>
@endsection