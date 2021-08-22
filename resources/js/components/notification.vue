<template>
    <li class="nav-item dropdown">
        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" 
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                通知 <span class="badge badge-danger" id="count-notification">
                    {{ notificationsCount }}</span>
                <span class="caret"></span>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" @mousewheel="wheel" style="height: 110px; overflow-y: scroll;">
            <!-- <loading :active.sync="isLoading"></loading> -->
            <!-- <input type="button" class="list-group-item list-group-item-action" v-for="(notification, index) in notifications" :key="index" :value="notification.data.title" onclick="showArticleContent(28, ae7278b9-0b76-4035-9d24-4771bc8c7295, Y)"> -->
            <a class="dropdown-item" v-for="(notification, index) in notifications" :key="index" @click="showArticleContent(notification.data.id, notification.id)">
                {{ notification.data.title }}
            </a>

            <!-- <a class="dropdown-item" v-if="lessons.length != 0"  onClick="showThreeNotification()">
                更多
            </a> -->

            <a class="dropdown-item" v-if="notifications.length == 0">
                沒通知訊息
            </a>
        </div>
    </li> 
</template>
<script>
// import Loading from 'vue-loading-overlay';
// import 'vue-loading-overlay/dist/vue-loading.css' ;

export default {
    props:  {
		notificationsLength: {
			type: Number,
		},
        notificationData: {
			type: Object,
		},
	},
    // components: {
    //     'Loading' :　Loading
    // },
    data(){
        return {
            'count': 3,
            'reduceCount': 0,
            'page' : 0,
            'scroll' : 0,
            'notifications' : [],
            'notificationsCount' : this.notificationsLength
        }
    },
    mounted(){
        this.showThreeNotification()
    },
    methods: {
        showThreeNotification(){
            let url = './showNotification'
            let params = {
                'page' : this.page,
                'count' : this.count,
                'reduceCount' : this.reduceCount
            }

            axios.post(url, params).then((response) => {
                if(response.data.length != 0){
                    let dataArray = Object.values(response.data);
                    let notificationsArray = this.notifications;
                    this.notifications = notificationsArray.concat(dataArray);
                    this.page++;
                    this.count = 3;
                    this.reduceCount = 0;
                }
				
			}).catch((error) => {

            });
        },
        wheel(e){
            if(e.deltaY == 100){
                console.log(this.page);
                this.showThreeNotification();
            }
        },
        showArticleContent(id, notificationId){
            console.log(id);
            console.log(notificationId);
            let userId = $('#userId').val();
            let url = '/showArticleContent?id='+ id +'&userId='+ userId +'&isAdd=N';
            
            if(notificationId != null){
                url = url + '&notificationId=' + notificationId
            }

            window.location.href = url;
        },
        showChannelContent(id, notificationId){
            let userId = $('#userId').val();
            let url = '/showChannelContent?channelsId='+ id +'&userId='+ userId;
            
            if(notificationId != null){
                url = url + '&notificationId=' + notificationId
            }

            window.location.href = url;
        }
    },
    watch:{
        notificationData(newVal, oldVal){
            if(newVal != '' || newVal != null){
                this.notificationsCount += 1
                // this.page = 0
                // this.notifications = [];
                // this.showThreeNotification()

                let newNotificationsData = []
                newNotificationsData.push(newVal)
                this.notifications = newNotificationsData.concat(this.notifications);
                this.reduceCount++
                this.count -= 1
            }
        }
    }
}
</script>