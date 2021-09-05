import "bootstrap/dist/css/bootstrap.css";
import notification from "./components/notification.vue";
import "./pusher.min.js"

var app = new Vue({
	el: "#app",
    components: {
		"notification": notification,
	},
	data:{
		"broadcast": null
	},
	template:'{{ broadcast }}'
})

Pusher.logToConsole = true;

var pusher = new Pusher('408cd422417d5833d90d', {
    cluster: 'ap3',
    encrypted: true
});

// app.broadcast = null;

var channel = pusher.subscribe('article-channel' + $('#userId').val());
channel.bind('App\\Events\\SendMessage', function(data) {
    app.broadcast = data.broadcast;
});