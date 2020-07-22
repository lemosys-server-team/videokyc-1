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

          <div class="col-md-12 col-lg-12 col-xl-12 my-auto mx-auto wd-100p">
          <p class="font-weight-bolder">Schedules</p>

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

          <table class="table table-bordered">
            <thead class="thead-dark">
              <tr>
                <th>Call Schedules for Today</th>
              </tr>
            </thead>
            <tbody>
              @if(isset($user->schedules) && $user->schedules!=NULL)
                @foreach($user->schedules as $schedules)
                  <tr>
                    <td><a href="{{ route('schedules.call',['id'=>$schedules->id]) }}" class="btn btn-success">Join Call at {{ date('H:i A', strtotime($schedules->datetime)) }}</a></td>
                  </tr>
                @endforeach
              @else
              <tr><td>No schedules available for today</td></tr>
              @endif
            </tbody>
          </table>
        </div>
        </div>
      </div>
</div>

@endsection