@extends('layouts.admin')
@section('content')
<div class="container-fluid">
     <h1 class="h3 mb-2 text-gray-800">Products</h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
             <h5 class="m-0 font-weight-bold text-primary">Product View</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Name:</strong>
                        {{ $product->name }}
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Details:</strong>
                        {{ $product->detail }}
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <a class="btn btn-danger" href="{{ route('products.index') }}">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection