<html>

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<style>
    .modal-mask {
        position: fixed;
        z-index: 9998;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: table;
        transition: opacity 0.3s ease;
    }

    .modal-wrapper {
        display: table-cell;
        vertical-align: middle;
    }

    .modal-container {
        width: 300px;
        margin: 0px auto;
        padding: 20px 30px;
        background-color: #fff;
        border-radius: 2px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
        transition: all 0.3s ease;
        font-family: Helvetica, Arial, sans-serif;
    }

    .modal-header h3 {
        margin-top: 0;
        color: #42b983;
    }

    .modal-body {
        margin: 20px 0;
    }

    .modal-default-button {
        float: right;
    }

    /*
 * The following styles are auto-applied to elements with
 * transition="modal" when their visibility is toggled
 * by Vue.js.
 *
 * You can easily play with the modal transition by editing
 * these styles.
 */

    .modal-enter {
        opacity: 0;
    }

    .modal-leave-active {
        opacity: 0;
    }

    .modal-enter .modal-container,
    .modal-leave-active .modal-container {
        -webkit-transform: scale(1.1);
        transform: scale(1.1);
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
                                <notification :notifications-length="{{ auth()->user()->notifications->count() }}" 
                                    :broadcast="broadcast" 
                                    :user-id={{ $userId }} 
                                    :article-url={{ json_encode(route('showArticleContent')) }}
                                    :channel-url={{ json_encode(route('showChannelContent')) }}
                                    :get-notification-url={{ json_encode(route('getNotificationDataCount')) }}
                                    :read-url={{ json_encode(route('readArticles')) }}
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
	<script src="{{mix('js/edit.js')}}"></script>
    <script src="./js/notification.js"></script>
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
        }

	</script>
</body>

</html>