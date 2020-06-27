@extends('admin.layouts.valex_app')
@section('styles')
<link href="{{ asset('css/bootstrap-datepicker3.standalone.min.css') }}" rel="stylesheet">
@endsection
@section('content')
<div class="container"> 
    <div class="breadcrumb-header justify-content-between">
        <div class="left-content">
            <div>
              <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1">Roles</h2>
            </div>
        </div>
    </div>
     <div class="row row-sm">
        <div class="col-xl-12">
    <div class="card ">
        <div class="card-header py-3">
             <h5 class="m-0 font-weight-bold text-primary">Role View</h5>
         </div>
         <div class="card-body frm_show">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group mt-0">
                        <strong>Name</strong>:<span>
                        {{ $role->name }}</span>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Permissions</strong>:<span>
                        @if(!empty($rolePermissions))
                            @foreach($rolePermissions as $v)
                                <label class="label label-success">{{ $v->name }},</label>
                            @endforeach
                        @endif
                        </span>
                    </div>
                </div>
                
            </div>
        </div>
         <div class="card-footer">
                    <a href="{{route('admin.roles.index')}}"  class="btn btn-secondary">Cancel</a>
                </div>
    </div>
</div>
</div>
</div>
@endsection
