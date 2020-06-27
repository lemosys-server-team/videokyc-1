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
           {!! Form::model($role, ['method' => 'PATCH','route' => ['admin.roles.update', $role->id],'id'=>'frmrole']) !!}
            <div class="card">
              <div class="card-header py-3 cstm_hdr">
                    <h6 class="m-0 font-weight-bold text-primary">Edit Role</h6>
                </div>
              <div class="card-body ">
                <div class="row">
                  <label class="col-sm-2 col-form-label">Name <span style="color:red">*</span></label>
                  <div class="col-sm-7">

                    <div class="form-group {{$errors->has('name') ? config('constants.ERROR_FORM_GROUP_CLASS') : ''}}">
                    {!! Form::text('name', null, array( 'readonly'=>'readonly', 'placeholder' => 'Name','class' => 'form-control')) !!}
                    @if($errors->has('name'))
                     <span id="name-error" class="error text-danger" for="input-name">{{ $errors->first('name') }}</span>
                    @endif
                    </div>
                  </div>
                </div>
               
                    <strong>Permission:</strong>
                        <br/>
                        @foreach($permission as $value)
                            <label>{{ Form::checkbox('permission[]', $value->id, in_array($value->id, $rolePermissions) ? true : false, array('class' => 'name')) }}
                            {{ $value->name }}</label>
                        <br/>
                        @endforeach
                
              
               </div>
                 
              <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <a href="{{route('admin.roles.index')}}"  class="btn btn-secondary">Cancel</a>
                </div>
              {!! Form::close() !!}
            </div>
          </form>
        </div>
      </div>
        </div>
      </div>
     </div>


@endsection

@section('scipts')
<script type="text/javascript" src="{{ asset('js/jquery-validation/dist/jquery.validate.min.js') }}"></script>
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