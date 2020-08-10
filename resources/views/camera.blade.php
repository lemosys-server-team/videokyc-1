
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Camera selection</title>
<style type="text/css">
	html,
body {
  margin: 0;
  padding: 0;
}

html {
  height: 100%;
}

body {
  font-family: Helvetica, Arial, sans-serif;
  min-height: 100%;
  display: grid;
  grid-template-rows: 1fr auto;
}

header {
  background: #f0293e;
  color: #fff;
  text-align: center;
}
main {
  background: #ffffff;
  min-height: 80vh;
}

.controls {
  text-align: center;
  padding: 0.5em 0;
  background: #333e5a;
}

video {
  width: 100%;
  max-width: 600px;
  display: block;
  margin: 0 auto;
}

footer {
  background: #333e5a;
  color: #fff;
  text-align: center;
}

footer a {
  color: #fff;
}

</style>

</head>

<body>

  <header>
    <h1>Camera fun</h1>
  </header>

  <main>
    <div class="controls">
      <button id="button">Get camera</button>
      <select id="select">
        <option></option>
      </select>
    </div>

    <video id="video" autoplay playsinline></video>
  </main>

 <script type="text/javascript">
	const video = document.getElementById('video');
  const button = document.getElementById('button');
  const select = document.getElementById('select');
  let currentStream;

  function stopMediaTracks(stream) {
    stream.getTracks().forEach(track => {
      track.stop();
    });
  }

  button.addEventListener('click', event => {
    if (typeof currentStream !== 'undefined') {
    stopMediaTracks(currentStream);
    }
    const videoConstraints = {};
    if (select.value === '') {
      videoConstraints.facingMode = 'environment';
    } else {
      videoConstraints.deviceId = { exact: select.value };
    }
    const constraints = {
      video: videoConstraints,
      audio: false
    };
    navigator.mediaDevices
      .getUserMedia(constraints)
      .then(stream => {
        video.srcObject = stream;
      })
      .catch(error => {
        console.error(error);
      });
  });

  function gotDevices(mediaDevices) {
    select.innerHTML = '';
    select.appendChild(document.createElement('option'));
    let count = 1;
    mediaDevices.forEach(mediaDevice => {
      if (mediaDevice.kind === 'videoinput') {
        const option = document.createElement('option');
        option.value = mediaDevice.deviceId;
        const label = mediaDevice.label || `Camera ${count++}`;
        const textNode = document.createTextNode(label);
        option.appendChild(textNode);
        select.appendChild(option);
      }
    });
  }
  navigator.mediaDevices.enumerateDevices().then(gotDevices);

</script>

</body>

</html>

