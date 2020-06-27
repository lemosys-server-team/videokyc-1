@extends('admin.layouts.valex_app')
@section('content')
<!-- Page Heading -->
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
    <div class="card">
        <div class="card-header py-3 cstm_hdr">
             <h5 class="m-0 font-weight-bold text-primary">Add Role</h5>
         </div>
         <div class="card-body">
            
             @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                    </ul>
                </div>
            @endif


            {!! Form::open(array('route' => 'roles.store','method'=>'POST','id'=>'frmrole')) !!}
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="col-xs-6 col-sm-6 col-md-6">
                    <div class="form-group">
                        <strong>Name: <span class="red">*</span></strong>
                        {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6">
                    <div class="form-group">
                       <strong>Active: </strong>
                        {{Form::checkbox('is_active', TRUE, TRUE,['id'=>'is_active'])}}
                    </div>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6">
                    <div class="form-group">
                        <strong>Permission:</strong>
                        <br/>
                        @foreach($permission as $value)
                            <label>{{ Form::checkbox('permission[]', $value->id, false, array('class' => 'name')) }}
                            {{ $value->name }}</label>
                        <br/>
                        @endforeach
                    </div>
                </div>
                
                <div class="col-xs-6 col-sm-6 col-md-6">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <a class="btn btn-danger" href="{{ route('roles.index') }}">Cancel</a>
                </div>
             </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
{!! Form::close() !!}
@endsection
@section('admin_scipts')

<script type="text/javascript">
$(document).ready(function(){
    $('#frmrole').validate({
        rules: {
            name: {
                required: true
            }
        },
    });
})
</script>
@endsection