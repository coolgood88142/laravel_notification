<template>
    <li class="nav-item dropdown">
        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" 
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                通知 <span class="badge badge-danger" id="count-notification">
                    {{ notificationsCount }}</span>
                <span class="caret"></span>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" @wheel="wheel" style="height: 100px; overflow-y: scroll;">
           
            <div v-for="(notification, index) in notificationsData" :key="index">
                <a v-if="notification.data.type === 'deleteArticle'" class="dropdown-item" :style="[notification.read_at !== null ? isRead : '']" >
                    {{ notification.data.title }}
                </a>
                <a v-else-if="notification.data.type === 'addChannel'" class="dropdown-item" :href="'/showChannelContent?id=' + notification.data.id +'&userId='+ userId +'&notificationId=' + notification.id " :style="[notification.read_at !== null ? isRead : '']" >
                    {{ notification.data.title }}
                </a>
                <a v-else class="dropdown-item" :href="'/showArticleContent?id=' + notification.data.id +'&userId='+ userId +'&notificationId=' + notification.id " :style="[notification.read_at !== null ? isRead : '']" >
                    {{ notification.data.title }}
                </a>
            </div>

            <!-- <a class="dropdown-item" v-if="lessons.length != 0"  onClick="showThreeNotification()">
                更多
            </a> -->

            <a class="dropdown-item" v-if="notificationsData.length == 0">
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
        broadcast: {
			type: Object,
		},
        userId: {
            type: Number
        }
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
            'notificationsData' : [],
            'notificationsCount' : this.notificationsLength,
            'broadcastData' : [],
            'isRead' : { 
                background: '#e9ecef'
            }
        }
    },
    mounted(){
        this.showThreeNotification()
    },
    methods: {
        showThreeNotification(){
            let sumCount = 0;
            if(this.broadcastData.length > 0){
                let sum = this.broadcastData.length + this.notificationsCount;
                let sumCount = parseInt(sum / this.count);
                if(sumCount > this.count){
                    sumCount = sumCount - this.count;
                }else{
                    sumCount = this.count - sumCount;
                }
                
                this.page = sumCount;
            }

            let url = './showNotification'
            let params = {
                'page' : this.page,
                'count' : this.count - sumCount
            }

            axios.post(url, params).then((response) => {
                if(response.data.length != 0){
                    let dataArray = Object.values(response.data);
                    let notificationsArray = this.notificationsData;
                    this.notificationsData = notificationsArray.concat(dataArray);
                    this.page++;
                    this.count = 3;
                    this.reduceCount = 0;
                }
				
			}).catch((error) => {

            });
        },
        wheel(e){
            let box = e.path[2];
            var clientHeight = box.clientHeight 
            var scrollTop = box.scrollTop 
            var scrollHeight = box.scrollHeight 
            if (scrollTop + clientHeight == scrollHeight && e.deltaY == 100) { 
                setInterval(this.showThreeNotification(), 1000);
            }
            // console.log(box);
            // if(e.deltaY == 100){
            //     // console.log(this.page);
            //     this.showThreeNotification();
            // }
        },
        showArticleContent(id, notificationId){
            console.log(id);
            console.log(notificationId);
            let userId = $('#userId').val();
            let url = '/showArticleContent?id='+ id +'&userId='+ userId +'&notificationId=';
            
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
        },
        getNotificationDataCount(){
            let url = '/getNotificationDataCount';
             axios.post(url).then((response) => {
                this.notificationsCount =response.data;
			}).catch((error) => {

            });
        },
    },
    watch:{
        broadcast(newVal, oldVal){
            if(newVal != '' || newVal != null){
                this.getNotificationDataCount()
                // this.page = 0
                // this.notifications = [];
                // this.showThreeNotification()
                
                // this.notificationsData.unshift(newVal)
                // let broadcastArray = []
                // broadcastArray.push(newVal)
                this.broadcastData.push(newVal)
                this.notificationsData.unshift(newVal);
                this.reduceCount++
                this.count -= 1
            }
        }
    }
}
</script>