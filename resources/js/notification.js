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
	console.log(data)
	let  url =  '/getNotificationData';
	let params = {
		'id' : $('#userId').val(),
		'type' : data.userData.type,
	};

	axios.post(url, params).then((response) => {
        app.notificationData = data.notification;

        let notification = document.getElementsByName('notification')[0];
        let copy = notification.cloneNode(true);

        let div1 =  document.createElement("div");
        div1.setAttribute("name", "notification");
        div1.setAttribute("class", "row");

        let div2 =  document.createElement("div");
        div2.setAttribute("class", "col-8");

        if(data.userData.type != 'deleteArticle'){
            let button1  =  document.createElement("input");
            button1.setAttribute("type", "button");
            button1.setAttribute("class", "list-group-item list-group-item-action text-danger");
            button1.setAttribute("value", data.message);
            button1.onclick = function(){
                if(data.userData.type == 'addChannel'){
                    showChannelContent(data.userData.channelsId, value.id, 'Y');
                }else{
                    showArticleContent(data.userData.articlesId, value.id, 'Y');
                }
            }

            div2.appendChild(button1);
        }

        let div3 =  document.createElement("div");
                    div3.setAttribute("class", "col-4");

                    let button2 =  document.createElement("input");
                    button2.setAttribute("type", "button");
                    button2.setAttribute("class", "btn btn-primary");
                    button2.setAttribute("name", "read");
                    button2.setAttribute("value", "已閱讀");
                    button2.onclick = function(){
                        readArticles(this, response.notificationId);
                    }

                    if(response.read_at != null){
                        button2.disabled = true;
                    }

                    div3.appendChild(button2);

                    div1.appendChild(div2);
                    div1.appendChild(div3);

                    let html = $('#notificationRaw').html();
                    $('#notificationRaw').empty();
                    $('#notificationRaw').append(div1);
                    $('#notificationRaw').append(html);
			
		}).catch((error) => {

		});
    });

var app = new Vue({
	el: "#app",
    components: {
		"notification": notification,
	},
	data:{
		"notificationData": null
	}
})