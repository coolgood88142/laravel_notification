<!DOCTYPE html>
<head>
  <title>Pusher Test</title>
  <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
  <script>

    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('408cd422417d5833d90d', {
      cluster: 'ap3',
      encrypted: true
    });

    var channel = pusher.subscribe('article-channel');
    channel.bind('App\\Events\\SendMessage', function(data) {
      console.log(data);
    });
  </script>
</head>
<body>
  <h1>Pusher Test</h1>
  <p>
    Try publishing an event to channel <code>article-channel</code>
    with event name <code>sendMessage</code>.
  </p>
</body>