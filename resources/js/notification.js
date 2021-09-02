import "bootstrap/dist/css/bootstrap.css";
import notification from "./components/notification.vue";
import "./pusher.min.js"

Pusher.logToConsole = true;

var pusher = new Pusher('408cd422417d5833d90d', {
    cluster: 'ap3',
    encrypted: true
});

var channel = pusher.subscribe('article-channel' + $('#userId').val());
channel.bind('App\\Events\\SendMessage', function(data) {
    app.broadcast = data.broadcast;
});

var app = new Vue({
	el: "#app",
    components: {
		"notification": notification,
	},
	data:{
		"broadcast": null
	}
})