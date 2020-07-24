@extends('auth.app')
@section('frontend_styles')
<link href="{{ asset('css/bootstrap-datepicker3.standalone.min.css') }}" rel="stylesheet">
<style>
  .bg-white{
    margin: auto;
    top: 100px;
    min-height: 300px;
    height: auto;
  }
</style>
@endsection
@section('content')
<div class="container-fluid">
      <div class="row no-gutter">
        <div class="col-md-8 col-lg-8 col-xl-8 bg-white">
          <div class="row wd-100p mx-auto text-center">
            <div class="col-md-12 col-lg-12 col-xl-12 my-auto mx-auto wd-100p">
              @php $logo = getSetting('logo'); @endphp
              @if(isset($logo) && $logo!=''  && \Storage::exists(config('constants.SETTING_IMAGE_URL').$logo))
              <img src="{{ \Storage::url(config('constants.SETTING_IMAGE_URL').$logo) }}" height="50px" alt="logo">
              @endif
            </div>
          </div>
          @if($flash = session('error'))            
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                 {{ $flash }}
            </div>
          @endif
          @if($flash = session('success'))      
              <div class="alert alert-success alert-dismissible" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                   {{ $flash }}
              </div>
          @endif
          <div class="col-md-12 col-lg-12 col-xl-12 my-auto mx-auto wd-100p">
            <a class="btn btn-sm btn-primary" style="margin-top: 5px;" href="{{ route('schedules.index') }}" >Back</a>

            <div class="title m-b-md">
                Hii, Thank You for calling with Us
            </div>
        </div>
        </div>
      </div>
</div>
@endsection