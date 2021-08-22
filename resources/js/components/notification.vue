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
            <a class="dropdown-item" v-for="(notification, index) in notifications" :key="index">
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
            'nowCount': 0,
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
                'count' : this.count
            }

            axios.post(url, params).then((response) => {
                if(response.data.length != 0){
                    let dataArray = Object.values(response.data);
                    let array = this.notifications;
                    this.notifications = array.concat(dataArray);
                    this.page++;
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
    },
    watch:{
        notificationData(newVal, oldVal){
            if(newVal != '' || newVal != null){
                this.notificationsCount += 1
                this.page = 0
                this.notifications = [];
                this.defaultNotification()

                // this.notifications.push(newVal)
                // this.page -= 1
                // this.count -= 1
            }
        }
    }
}
</script>