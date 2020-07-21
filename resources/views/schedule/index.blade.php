@extends('auth.app')
@section('frontend_styles')
<link href="{{ asset('css/bootstrap-datepicker3.standalone.min.css') }}" rel="stylesheet">
@endsection
@section('content')
<div class="container-fluid">
      <div class="row no-gutter">
        <div class="col-md-12 col-lg-12 col-xl-5 bg-white">
          <table class="table table-border">
            <thead>
              <tr>
                <th>Schedule Date</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              @if($user->schedules)
                @foreach($user->schedules as $schedules)
                  <tr>
                    <td>{{ $schedules->datetime }}</td>
                    <td><a href="{{ route('schedules.call',['id'=>$schedules->id]) }}">Call</a></td>
                    <td></td>
                  </tr>
                @endforeach
              @endif
            </tbody>
          </table>
        </div>
      </div>
</div>

@endsection