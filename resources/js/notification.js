import "bootstrap/dist/css/bootstrap.css"
import lessonNotification from "./components/lessonNotification.vue";

let app = new Vue({
	el: "#app",
    components: {
		"lesson_notification": lessonNotification,
	},
	data:{
		"message": $('#message').val()
	}
})