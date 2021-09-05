<template>
    <li class="nav-item dropdown">
        <!-- <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" 
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                通知 <span class="badge badge-danger" id="count-notification">
                    {{ notificationsCount }}</span>
                <span class="caret"></span>
        </a> -->
        <div class="notification">
            <a v-on:click="show = !show" class="tooltip-bell">
                <i class="far fa-2x fa-bell"></i>
                <span id="circle">{{ this.notificationsCount }}</span>
            </a>
            <transition name="fadeStart" v-cloak>
                <div v-if="!show" class="tooltip">
                    <div id="heading">
                            <div class="heading-left">
                                <h6 class="heading-title">通知列表</h6>
                            </div>
                            <div class="heading-right">
                                <a class="notification-link" href="http://127.0.0.1:8000/articles" v-on:click.prevent="readNotification()">已閱讀全部</a>
                            </div>
                        </div>
                        <ul class="notification-list" @wheel="wheel" style="height: 240px; width:369px; overflow: scroll !important; ">
                            <li class="notification-item" v-for="(notification, index) in notificationsData" :key="index" 
                                            :style="[notification.read_at !== null ? isRead : '']">
                                <div class="img-left">
                                    <img alt="User Photo" src="https://randomuser.me/api/portraits/thumb/women/6.jpg" class="user-photo">
                                </div>
                                <div class="user-content">
                                    <a v-if="notification.data.type === 'deleteArticle'" class="user-info">
                                        {{ notification.data.title }}
                                    </a>
                                    <a v-else-if="notification.data.type === 'addChannel'" 
                                        :href="channelUrl+'?id=' + notification.data.id +'&userId='+ userId +'&notificationId=' + notification.id " class="user-info">
                                         {{ notification.data.title }}
                                    </a>
                                    <a v-else :href="articleUrl+'?id=' + notification.data.id +'&userId='+ userId +'&notificationId=' + notification.id " class="user-info">
                                         {{ notification.data.title }}
                                    </a>
                                    <!-- <input type="button" style="width:205px;" :value="notification.data.title" onclick="showArticleContent('27', '8d6c0595-a86a-4ead-b9e1-57e5bd0df5fa')" class="list-group-item "> -->
                                    <p class="time">{{ getDateDiff(new Date(notification.created_at)) }}</p>
                                </div>
                            </li>
                        </ul>
                    </div>
            </transition>
        </div>
        <!-- <div class="dropdown-menu dropdown-menu-right overflow-auto" aria-labelledby="navbarDropdown" @wheel="wheel" style="height: 110px;">
            <div class="tooltip">
                <div id="heading">
                    <div class="heading-left">
                        <h6 class="heading-title">通知列表</h6>
                    </div>
                    <div class="heading-right">
                        <a class="notification-link" href="#" onClick="readNotification()">已閱讀全部</a>
                    </div>
                </div>
            </div>
        </div> -->
        <!-- <div class="dropdown-menu dropdown-menu-right overflow-auto" aria-labelledby="navbarDropdown" @wheel="wheel" style="height: 110px;">
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
            </div> -->

            
                <!-- <div class="notification">
    <a v-on:click="show = !show" class="tooltip-bell">
      <i class="far fa-2x fa-bell"></i>
      <span id="circle"></span>
    </a>
    <transition name="fadeStart" v-cloak>
      <div v-if="show" class="tooltip">
        <div id="heading">
          <div class="heading-left">
            <h6 class="heading-title">通知列表</h6>
          </div>
          <div class="heading-right">
            <a class="notification-link" @click="readNotification()" >已閱讀全部</a>
          </div>
        </div>
        <ul class="notification-list">
          <li class="notification-item"  v-for="(notification, index) in notificationsData" :key="index">
            <div class="img-left">
              <img class="user-photo" alt="User Photo" v-bind:src="user.picture.thumbnail" />
            </div>
            <div class="user-content" v-if="notification.data.type === 'deleteArticle'" :style="[notification.read_at !== null ? isRead : '']" >
              <p class="user-info">{{ notification.data.title }}</p>
              <p class="time">{{ getDateDiff(new Date(notification.created_at)) }}</p>
            </div>
            <div class="user-content" v-else-if="notification.data.type === 'addChannel'" :href="urlData.channel+'?id=' + notification.data.id +'&userId='+ userId +'&notificationId=' + notification.id " :style="[notification.read_at !== null ? isRead : '']" >
              <p class="user-info">{{ notification.data.title }}</p>
              <p class="time">{{ getDateDiff(new Date(notification.created_at)) }}</p>
            </div>
            <div class="user-content" :href="urlData.article+'?id=' + notification.data.id +'&userId='+ userId +'&notificationId=' + notification.id " :style="[notification.read_at !== null ? isRead : '']" >
              <p class="user-info">{{ notification.data.title }}</p>
              <p class="time">{{ getDateDiff(new Date(notification.created_at)) }}</p>
            </div>
          </li>
        </ul>
      </div>
    </transition>
  </div> -->
            

            <!-- <div v-show="isNotification">
                <a class="dropdown-item">
                    無通知訊息
                </a>
            </div>
        </div> -->
    </li> 
</template>
<script>
// import Loading from 'vue-loading-overlay';
// import 'vue-loading-overlay/dist/vue-loading.css' ;

export default {
    props:  {
		notificationsLength: {
			type: String
		},
        broadcast: {
			type: Object
		},
        userId: {
            type: String
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
            },
            'isNotification' : false,
            'users': [],
            'errors': [],
            'show': true,
            'test' : 'test',
            'url': this.urlData

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
                if(response.data.length != 0 && !this.isNotification){
                    let dataArray = Object.values(response.data);
                    let notificationsArray = this.notificationsData;
                    this.notificationsData = notificationsArray.concat(dataArray);
                    this.page++;
                    this.count = 3;

                    //這裡是為了防範，沒通知訊息時，突然又發一則通知，要即時隱藏【無通知訊息】
                    this.isNotification = false
                }else{
                    this.isNotification = true
                }
				
			}).catch((error) => {

            });
        },
        wheel(e){
            console.log(e)
            let box = e.path[3];
            var clientHeight = box.clientHeight 
            var scrollTop = box.scrollTop 
            var scrollHeight = box.scrollHeight 
            if (scrollTop + clientHeight == scrollHeight && e.deltaY == 100) { 
                this.showThreeNotification();
                // _.debounce(this.showThreeNotification(), 1000);
                // setInterval(this.showThreeNotification(), 1000);
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
                        return parseInt(days / 1000) + ' 秒前'
                    }
                    return day + ' 分前'
                }
                return day + ' 時前'
            }else if(day < 7){
                day = day + ' 天前'
            }else{
                let month = sDate.getMonth();
                if(month < 10){
                    month = "0" + month.toString();
                }

                day = sDate.getFullYear() + '-' + month + '-' + sDate.getDate();
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
     filters: {
    capitalize: function (value) {
      if (!value) return ''
      value = value.toString()
      return value.charAt(0).toUpperCase() + value.slice(1)
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