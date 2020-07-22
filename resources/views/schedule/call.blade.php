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
            <div class="title m-b-md">
                 Video Chat Rooms
            </div>
            <a href="javascript:void(0);" onclick="completeRoomSchedule();" id="completeCall" class="btn btn-danger">Disconnect</a>
            <div id="join-room"></div>
          </div>
        </div>
        </div>
      </div>
</div>

@endsection

@section('scripts')
<script src="//media.twiliocdn.com/sdk/js/video/v1/twilio-video.min.js"></script>
<script>
    Twilio.Video.createLocalTracks({
       audio: true,
       video: { width: 300 }
    }).then(function(localTracks) {
       return Twilio.Video.connect('{{ $token }}', {
           name: '{{ $chatroom }}',
           tracks: localTracks,
           video: { width: 300 }
       });
    }).then(function(room) {
       console.log('Successfully joined a Room: '+room.name);

       room.participants.forEach(participantConnected);

       var previewContainer = document.getElementById(room.localParticipant.sid);
       if (!previewContainer || !previewContainer.querySelector('video')) {
           participantConnected(room.localParticipant,room);
       }

       room.on('participantConnected', function(participant) {
           console.log("Joining: "+participant.identity);
           participantConnected(participant,room);
       });

       room.on('participantDisconnected', function(participant) {
           console.log("Disconnected: "+participant.identity);
           participantDisconnected(participant);
       });
    });
    // additional functions will be added after this point
 
 function participantConnected(participant,room) {
   console.log('Participant "%s" connected '+participant.identity);

   const div = document.createElement('div');
   div.id = participant.sid;
   div.setAttribute("style", "float: left; margin: 10px;");
   div.innerHTML = "<div style='clear:both'>Room Name : "+room.name+"</div>";

   participant.tracks.forEach(function(track) {
       trackAdded(div, track)
   });

   participant.on('trackAdded', function(track) {
       trackAdded(div, track)
   });
   participant.on('trackRemoved', trackRemoved);
   document.getElementById('join-room').appendChild(div);
}

function participantDisconnected(participant) {
   console.log('Participant "%s" disconnected '+ participant.identity);
   participant.tracks.forEach(trackRemoved);
   document.getElementById(participant.sid).remove();
}

function trackAdded(div, track) {
   div.appendChild(track.attach());
   var video = div.getElementsByTagName("video")[0];
   if (video) {
       video.setAttribute("style", "min-width:960px;");
   }
}

function trackRemoved(track) {
   track.detach().forEach( function(element) { element.remove() });
}

function completeRoomSchedule() {
  $.post("{{ route('schedules.completeRoomSchedule') }}",{'schedule_id': "{{ $schedule_id }}"});
}
</script>
@endsection