<template>
    <div class="container">
        <div class="notification">
            <a v-on:click="show = !show" class="tooltip-bell">
            <i class="far fa-2x fa-bell"></i>
            <span id="circle"></span>
            </a>
            <transition name="fadeStart" v-cloak>
            <div v-if="show" class="tooltip">
                <div id="heading">
                <div class="heading-left">
                    <h6 class="heading-title">Notifications</h6>
                </div>
                <div class="heading-right">
                    <a class="notification-link" href="#">See all</a>
                </div>
                </div>
                <ul class="notification-list">
                <li class="notification-item" v-for="(user, index) in users" :key="index">
                    <div class="img-left">
                    <img class="user-photo" alt="User Photo" v-bind:src="user.picture.thumbnail" />
                    </div>
                    <div class="user-content">
                    <p class="user-info"><span class="name">{{user.name.first | capitalize}} {{user.name.last | capitalize}}</span> left a comment.</p>
                    <p class="time">1 hour ago</p>
                    </div>
                </li>
                </ul>
            </div>
            </transition>
        </div>
        </div>
</template>
<script>

export default {
    data(){
        return {
            'users' : [],
            'errors' : [],
            'show' : true
        }
    },
    mounted(){
        this.getUsers()
    },
    methods: {
        getUsers () {
      axios.get('https://randomuser.me/api/?results=3')
        .then(response => {
          console.log(JSON.stringify(response.data.results))
          this.users = response.data.results
        })
        .catch(e => {
          this.errors.push(e)
        })
        }
    },
     filters: {
        capitalize: function (value) {
        if (!value) return ''
        value = value.toString()
        return value.charAt(0).toUpperCase() + value.slice(1)
        }
    }
}
</script>