@extends('auth.app')
@section('frontend_styles')
<link href="{{ asset('css/bootstrap-datepicker3.standalone.min.css') }}" rel="stylesheet">
@endsection
@section('content')
<div class="container-fluid">
      <div class="row no-gutter">
        <div class="col-md-12 col-lg-12 col-xl-5 bg-white">
          <a href="javascript:void(0);" id="join-room">Join</a>
        </div>
      </div>
</div>

@endsection

@section('scripts')
<script>
  // const { createLocalTracks, connect } = require('twilio-video');

  // // To work around the autoplay policy, use twilio-video.js only
  // // after a button click.
  // document.getElementById('join-room').onclick = async () => {
  //   const tracks = await createLocalTracks({
  //     audio: true,
  //     video: { facingMode: 'user' }
  //   });
  //   const room = await connect('{{ $token }}', {
  //     name: '{{ $chatroom }}',
  //     tracks
  //   });

  //   const cameraTrack = tracks.find(track => track.kind === 'video');

  //   // Switch to the back facing camera.
  //   cameraTrack.restart({ facingMode: 'environment' });
  // };

</script>
@endsection