import "bootstrap/dist/css/bootstrap.css"
import lessonNotification from "./components/lessonNotification.vue";

var app = new Vue({
	el: "#app",
    components: {
		"lesson_notification": lessonNotification,
	},
	data:{
		"message": ""
	}
})