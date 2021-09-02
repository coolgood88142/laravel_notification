<html>

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<style>
@import url('https://fonts.googleapis.com/css?family=Roboto:400,500');

* {
  -webkit-box-sizing: border-box;
  box-sizing: border-box;
  &:before, &:after {
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
  }
}
.container {
  width: 20rem;
  height: 20rem;
  grid-area: main;
  -ms-flex-item-align: center;
  align-self: center;
  justify-self: center;
}

h2 {
  font-size: 1.6rem;
  font-weight: 400;
  line-height: 2rem;
  color: #1f2022;
}

ul {
  list-style-type: none;
  padding: 0.5rem 1rem;
  margin: 0 0 0.5rem;
}

li {
  display: inline-block;
  padding: 0.85rem 1rem;
  color: #1f2022;
}

h6 {
  font-size: 1rem;
  font-weight: 500;
  line-height: 1.1;
  margin: 0.45rem 0;
}

a {
  color: #4aaee7;
  background: transparent;
  outline: 0;
  text-decoration: none;
  cursor: pointer;
}

.notification {
  position: absolute;
}

.tooltip-bell {
  position: absolute;
  display: block;
  left: 9rem;
  color: #a5a6a8;
}

.tooltip {
  &::before {
    content: '';
    position: absolute;
    top: -0.4rem;
    right: 8.3rem;
    border-left: 2rem solid transparent;
    border-right: 2rem solid transparent;
    border-bottom: 1.5rem solid #fff;
  }
  position: absolute;
  top: 2.5rem;
  line-height: 1.5;
  color: #27303d;
  width: 20rem;
  background: #fff;
  border-radius: 5px;
  -webkit-box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.1);
  box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.1);
}

#circle {
  position: absolute;
  top: 0;
  left: 0.75rem;
  width: 0.75rem;
  height: 0.75rem;
  border-radius: 100%;
  background: #f07379;
}

.notification-item {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: start;
  -ms-flex-align: start;
  align-items: flex-start;
  display: -ms-grid;
  display: grid;
  padding: 0.65rem 0;
}

#heading {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: start;
  -ms-flex-align: start;
  align-items: flex-start;
  display: -ms-grid;
  display: grid;
  padding: 0.5rem 1rem;
  border-bottom: 0.01rem solid #eee;
}

.notification-link {
  position: absolute;
  margin: 0.4rem 0;
}

.heading-left, .img-left {
  -ms-flex-preferred-size: auto;
  flex-basis: auto;
  -webkit-box-flex: 0;
  -ms-flex-positive: 0;
  flex-grow: 0;
  -ms-flex-negative: 0;
  flex-shrink: 0;
  -ms-grid-row: 1;
  grid-row: 1;
  margin: 0 1rem 0 0;
}

.heading-left, .user-content {
  grid-column: span 9;
  width: 14rem;
}

.heading-right, .img-left {
  grid-column: auto;
}

.heading-right, .user-content {
  -ms-flex-preferred-size: auto;
  flex-basis: auto;
  -webkit-box-flex: 1;
  -ms-flex-positive: 1;
  flex-grow: 1;
  -ms-flex-negative: 1;
  flex-shrink: 1;
  -ms-grid-row: 1;
  grid-row: 1;
}

.heading-right {
  width: 2.5rem;
}

.img-left {
  width: 3rem;
}

.user-photo {
  display: inline-block;
  vertical-align: middle;
  height: 3rem;
  width: 3rem;
  margin: 0 0.5rem 0 0;
  border-radius: 50%;
  max-width: 100%;
}

p {
  &.user-info {
    margin: 0.15rem 0 0;
  }
  &.time {
    margin: 0;
    color: #9da4ae;
  }
}

span.name {
  font-weight: 500;
}

.fadeStart-enter-active {
  -webkit-animation: fadeStart .2s both ease-in-out;
  animation: fadeStart .2s both ease-in-out;
}

.fadeStart-leave-active {
  -webkit-animation: fadeEnd .2s both ease-in-out;
  animation: fadeEnd .2s both ease-in-out;
}

[v-cloak] > * {
  display: none;
}

@-webkit-keyframes fadeStart {
  0% {
    opacity: 0;
    -webkit-transform: translate3d(0, 5px, 0);
    transform: translate3d(0, 5px, 0);
  }

  to {
    opacity: 1;
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
    display: block;
  }
}


@keyframes fadeStart {
  0% {
    opacity: 0;
    -webkit-transform: translate3d(0, 5px, 0);
    transform: translate3d(0, 5px, 0);
  }

  to {
    opacity: 1;
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
    display: block;
  }
}


@-webkit-keyframes fadeEnd {
  0% {
    opacity: 1;
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
  }

  to {
    opacity: 0;
    -webkit-transform: translate3d(0, 5px, 0);
    transform: translate3d(0, 5px, 0);
  }
}


@keyframes fadeEnd {
  0% {
    opacity: 1;
    -webkit-transform: translateZ(0);
    transform: translateZ(0);
  }

  to {
    opacity: 0;
    -webkit-transform: translate3d(0, 5px, 0);
    transform: translate3d(0, 5px, 0);
  }
}
</style>
<body>
    <div class="container">
        <div id="app" class="justify-content-center align-items-center">
            <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
                <div class="container">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <span class="navbar-toggler-icon"></span>
                    </button>
    
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Left Side Of Navbar -->
                        <ul class="navbar-nav mr-auto">
    
                        </ul>
    
                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ml-auto">
                            <!-- Authentication Links -->
                            @guest
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                    </li>
                                @endif
                            @else
                            @if (Auth::check())
                                <notification :notifications-length = "{{ auth()->user()->notifications->count() }}" 
                                    :broadcast = "broadcast"
                                    :user-id = "{{ $userId }}" 
                                    :url-data = "{{ json_encode($urlData) }}"
                                    >
                                </notification> 
                            @endif
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        {{ Auth::user()->name }} <span class="caret"></span>
                                    </a>
    
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>
    
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>
            <div class="row" style="margin-bottom: 60px;">
                <div class="col">
                    <h2 id="title" class="text-center font-weight-bold" style="margin-bottom:20px;">文章資訊</h2>
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <div class="card-header">
                                    通知列表
                                    <input type="button" class="btn btn-primary" name="readAll" value="已閱讀全部"
                                            onCLick="readArticles(this, '')" />
								</div>
                                <div id="notificationRaw">
                                </div>
                                <input type="button" id="moreArticles" name="moreArticles" class="btn btn-primary" style="margin-top: 10px;" value="更多" onClick="showNotification()">
                            </div>
                            <div class="form-group">
								<div class="card-header">
									文章列表
								</div>
								@foreach ($articles as $key => $article)
                                    <div class="row">
                                        <div class="col-8">
                                            <input type="button" class="list-group-item list-group-item-action" value="{{ $article->title }}" onclick="showArticleContent('{{ $article->id }}', null, null)" />
                                        </div>
                                        @if($article->author_id == $userId)
                                            <div class="col-4">
                                                <input type="button" class="btn btn-primary" name="read" value="刪除" onClick="deleteArticles(this, {{ $article->id }})"/>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                                <input type="button" class="btn btn-primary" value="新增文章" style="margin-top: 10px;" onclick="window.location.href='/add?userId={{ $userId }}'">
                            </div>
                            <div class="form-group">
                                <div class="card-header">
									頻道列表
								</div>
                                @foreach ($channels as $key => $channel)
                                    <div class="row">
                                        <div class="col-8">
                                            <input type="button" class="list-group-item list-group-item-action" value="{{ $channel->name }}" onclick="showChannelContent('{{ $channel->id }}')" />
                                        </div>
                                    </div>
                                @endforeach
                                <input type="button" class="btn btn-primary" value="新增頻道" style="margin-top: 10px;" onclick="window.location.href='/addChannels?userId={{ $userId }}'">
                            </div>
                            <div class="form-group">
                                <input type="hidden" id="userId" name="userId" value="{{ $userId }}">
                                <input type="hidden" id="notificationsCount" name="notificationsCount" value={{ $notificationsCount }}>
                                <input type="hidden" id="message" name="message" value="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{mix('js/app.js')}}"></script>
    <script src="{{mix('js/notification.js')}}"></script>
    <link rel="stylesheet" href="./css/notification.css">
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script>
        $(document).ready(function() {
            showNotification();
            Pusher.logToConsole = true;

            var pusher = new Pusher('408cd422417d5833d90d', {
                cluster: 'ap3',
                encrypted: true
            });

            var channel = pusher.subscribe('article-channel' + $('#userId').val());
            channel.bind('App\\Events\\SendMessage', function(data) {
                $.ajax({
                    url: "{{ route('getNotificationData') }}", 
                    type: 'POST',
                    data:{
                        'id' : $('#userId').val(),
                        'type' : data.userData.type,
                        '_token' : '{{csrf_token()}}'
                    },
                    success: function(result){
                        let date = new Date(data.broadcast.updated_at);
                        let diffDay = getDateDiff(date); 
                        let html = 
                            '<div name="notification" class="row">' +
                                '<div class="col-5">' +
                                    '<input type="button" class="list-group-item list-group-item-action text-danger" value="' + data.message + '"';
                                    if(data.userData.type == 'addChannel'){
                                        html += ' onclick="showChannelContent('+"'"+data.userData.id+"'"+', '+"'"+result.notificationId+"'"+')">';
                                    }else{
                                        html += ' onclick="showArticleContent('+"'"+data.userData.id+"'"+', '+"'"+result.notificationId+"'"+')">';
                                    }
                            html += '</div>';
                            html += '<div class="col-2">'
                                    if(diffDay < 7){
                                        if(diffDay == 0){
                                            
                                        }else{
                                            html += '已通知' + diffDay + '天';
                                        }
                                    }else{
                                        html += date.getFullYear() + '-' + date.getMonth() + '-' + date.getDate();
                                    }
                            html += '</div>';
                            html += '<div class="col-4">';
                                    if(data.userData.type != 'deleteArticle'){
                                        html += '<input type="button" class="btn btn-primary" name="read" value="已閱讀"  onclick="readArticles(this, '+"'"+result.notificationId+"'"+')"';
                                        if(result.read_at != null){
                                            html += 'disabled';
                                        }
                                        html += '>';
                                    }

                           
                            html +='</div>'+
                            '</div>';
                        
                        let notification = $('#notificationRaw').html();
                        $('#notificationRaw').empty();
                        $('#notificationRaw').append(html);
                        $('#notificationRaw').append(notification);

                    },
                    error:function(xhr, status, error){
                        alert(xhr.statusText);
                    }
                })
            });
        });

        function showNotification(){
            $.ajax({
				url: "{{ route('showNotification') }}", 
				type: 'POST',
				data:{
                    'nowCount' : $("div[name='notification']").length,
					'count' : $('#notificationsCount').val(),
					'_token':'{{csrf_token()}}'
				},
				success: function(result){
                    // console.log(getDateDiff('2021-08-21'))
                    console.log(result);
                    $.each(result, function(index, value) {
                        let date = new Date(value.updated_at);

                        let html = 
                            '<div name="notification" class="row">' +
                                '<div class="col-5">' +
                                    '<input type="button" class="list-group-item list-group-item-action" value="' + value.data.title + '"';
                                    if(value.data.type == 'addChannel'){
                                        html += ' onclick="showChannelContent('+"'"+value.data.id+"'"+', '+"'"+value.id+"'"+')">';
                                    }else{
                                        html += ' onclick="showArticleContent('+"'"+value.data.id+"'"+', '+"'"+value.id+"'"+')">';
                                    }
                            html += '</div>';
                            html += '<div class="col-2">';
                            html +=  getDateDiff(date);
                            html += '</div>';
                            html += '<div class="col-4">';

                                if(value.data.type != 'deleteArticle'){
                                    html += '<input type="button" class="btn btn-primary" name="read" value="已閱讀"  onclick="readArticles(this, '+"'"+value.id+"'"+')"';
                                    if(value.read_at != null){
                                        html += 'disabled';
                                    }
                                    html += '>';
                                }

                            html +='</div>';

                            html += '</div>';
                        
                        $('#notificationRaw').append(html);
                    });
				},
				error:function(xhr, status, error){
					alert(xhr.statusText);
				}
			});
        }

        function showArticleContent(id, notificationId){
            let userId = $('#userId').val();
            let url = "{{ route('showArticleContent') }}" + '?id='+ id +'&userId='+ userId +'&isAdd=N';
            
            if(notificationId != null){
                url = url + '&notificationId=' + notificationId
            }

            window.location.href = url;
        }

        function showChannelContent(id, notificationId){
            let userId = $('#userId').val();
            let url = "{{ route('showChannelContent') }}" + '?channelsId='+ id +'&userId='+ userId;
            
            if(notificationId != null){
                url = url + '&notificationId=' + notificationId
            }

            window.location.href = url;
        }

		function readArticles(el, id){
			$.ajax({
				url: "{{ route('readArticles') }}", 
				type: 'POST',
				data:{
					"id" : id,
                    "userId" : $('#userId').val(),
					'_token':'{{csrf_token()}}'
				},
				success: function(result){
                    console.log(id);
                    //點選已讀之後，按鈕要新增disabled
                    if(result.trim() == 'success'){
                        $(el).prop("disabled", true);
                        if(id == ''){
                            $("input[name='read']").prop("disabled", true);
                        }
                    }
				},
				error:function(xhr, status, error){
					alert(xhr.statusText);
				}
			});
		}

        function deleteArticles(el, id){
			$.ajax({
				url: "{{ route('deleteArticles') }}", 
				type: 'POST',
				data:{
					"id" : id,
                    "isEven" : id % 2 == 0 ? true : false,
					'_token':'{{csrf_token()}}'
				},
				success: function(result){
                    //點選已讀之後，按鈕要新增disabled
                    if(result.trim() == 'success'){
                        $(el).parent().parent().remove();
                    }
				},
				error:function(xhr, status, error){
					alert(xhr.statusText);
				}
			});
		}

        function getDateDiff(sDate) {
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
                day = sDate.getFullYear() + '-' + sDate.getMonth() + '-' + sDate.getDate();
            }

            return day;
        }

	</script>
</body>

</html>