<template>
    <li class="nav-item dropdown">
        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" 
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                通知 <span class="badge badge-danger" id="count-notification">
                    {{ notificationsCount }}</span>
                <span class="caret"></span>
        </a>
        <div class="dropdown-menu dropdown-menu-right overflow-auto" aria-labelledby="navbarDropdown" @wheel="wheel" style="height: 110px;">
            <div class="col">
                <input type="button" name="readAll" value="已閱讀全部" @click="readNotification()" class="btn btn-primary">
            </div>
            <div v-for="(notification, index) in notificationsData" :key="index">
                <a v-if="notification.data.type === 'deleteArticle'" class="dropdown-item" :style="[notification.read_at !== null ? isRead : '']" >
                    {{ notification.data.title }}
                    <span>{{ getDateDiff(new Date(notification.created_at)) }}</span>
                </a>
                <a v-else-if="notification.data.type === 'addChannel'" class="dropdown-item" :href="channelUrl+'?id=' + notification.data.id +'&userId='+ userId +'&notificationId=' + notification.id " :style="[notification.read_at !== null ? isRead : '']" >
                    {{ notification.data.title }}
                    <span>{{ getDateDiff(new Date(notification.created_at)) }}</span>
                </a>
                <a v-else class="dropdown-item" :href="articleUrl+'?id=' + notification.data.id +'&userId='+ userId +'&notificationId=' + notification.id " :style="[notification.read_at !== null ? isRead : '']" >
                    {{ notification.data.title }}
                    <span>{{ getDateDiff(new Date(notification.created_at)) }}</span>
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
        },
        articleUrl: {
            type: String
        },
        channelUrl: {
            type: String
        },
        getNotificationUrl: {
            type: String
        },
        readUrl: {
            type: String
        }
	},
    // components: {
    //     'Loading' :　Loading
    // },
    data(){
        return {
            'count': 3,
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
            //判斷有沒有通知資料
            if(this.broadcastData.length > 0){
                //計算目前有多少個分頁
                let sum = this.notificationsData.length;
                this.page = parseInt(sum / this.count);

                //計算需要新增多少個通知
                let newCount = this.page * this.count;
                let num = sum - newCount;
                this.count = this.count - num;
            }

            let url = './showNotification'
            let params = {
                'page' : this.page,
                'count' : this.count
            }

            axios.post(url, params).then((response) => {
                if(response.data.length != 0){
                    let dataArray = Object.values(response.data);
                    let notificationsArray = this.notificationsData;
                    this.notificationsData = notificationsArray.concat(dataArray);
                    this.page++;
                    this.count = 3;
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
        getNotificationDataCount(){
            let url = this.getNotificationUrl;
             axios.post(url).then((response) => {
                this.notificationsCount = response.data;
			}).catch((error) => {

            });
        },
        getDateDiff(sDate) {
            let now = new Date();
            let days = now.getTime() - sDate.getTime();
            let day = parseInt(days / parseInt(1000 * 60 * 60 * 24));

            if(day == 0){
                day = parseInt(days / parseInt(1000 * 60 * 60));
                if(day == 0){
                    day = parseInt(days / parseInt(1000 * 60));
                    if(day == 0){
                        return '已通知' + parseInt(days / 1000) + '秒'
                    }
                    return '已通知' + day + '分'
                }
                return '已通知' + day + '時'
            }else if(day < 7){
                day = '已通知' + day + '天'
            }else{
                day = sDate.getFullYear() + '-' + sDate.getMonth() + '-' + sDate.getDate();
            }

            return day;
        },
        readNotification(){
            let url = this.readUrl;
            let params = {
                'id' : '',
                'userId' : $('#userId').val()
            }

            axios.post(url, params).then((response) => {
                if(response.data == 'success'){
                    this.notificationsData = []
                    this.showThreeNotification()
                }
			}).catch((error) => {

            });
        }
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
            }
        }
    }
}
</script>