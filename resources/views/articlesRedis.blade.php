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
                                <lesson_notification :lessons="{{ auth()->user()->unreadNotifications }} :broadcast="broadcast" "></lesson_notification> 
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
                                <div id="showPusher">
                                    
                                </div>
                                <div class="card-header">
                                    通知列表
                                    <input type="button" class="btn btn-primary" name="readAll" value="已閱讀全部"
                                            onCLick="readArticles(this, '')" />
								</div>
                                <div id="notificationRaw">
								@foreach ($notifications as $key => $notification)
                                    @if($notification['notThreeDay'])
                                        <div class="row" name="notification">
                                            <div class="col-8">
                                                <input type="button" class="list-group-item list-group-item-action" value="您有一篇新訊息【{{ $notification['title'] }}】" 
                                                    @if ($notification['status'] != 'deleteArticle')
                                                        onclick="showArticleContent('{{ $notification['categoryId'] }}', '{{ $notification['id'] }}', '{{ $notification['read'] ? 'Y' : 'N' }}')"
                                                    @endif
                                                />
                                            </div>
                                            <div class="col-4">
                                                <input type="button" class="btn btn-primary" name="read" value="已閱讀"
                                                onCLick="readArticles(this, '{{ $notification['id'] }}')" {{ $notification['read'] ? 'disabled' : '' }} />
                                            </div>
                                        </div>
                                    @endif
								@endforeach
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
                            </div>
                            <div class="form-group">
                                <input type="hidden" id="userId" name="userId" value="{{ $userId }}">
                                <input type="hidden" id="notificationsCount" name="notificationsCount" value={{ $notificationsCount }}>
                                <input type="button" class="btn btn-primary" value="新增文章" onclick="window.location.href='/add?userId={{ $userId }}'">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{mix('js/app.js')}}"></script>
    <script src="{{mix('js/notification.js')}}"></script>
    <script>
        window.laravel_echo_port='{{env("LARAVEL_ECHO_PORT")}}';
    </script>
    <script src="//{{ Request::getHost() }}:{{env('LARAVEL_ECHO_PORT')}}/socket.io/socket.io.js"></script>
    <script src="{{ url('/js/laravel-echo-setup.js') }}" type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            showNotification();
            window.Echo.channel('article-channel')
            .listen('.RedisMessage', (data) => {
                let userId = $('#userId').val();
                let users = data.users;
                // let id = users.indexOf(userId);
                
                let index = $.map(users, function(item, value) {
                    return item.id.toString();
                }).indexOf(userId);

                if(index != -1){
                    $.ajax({
                        url: "{{ route('getNotificationData') }}", 
                        type: 'POST',
                        data:{
                            'id' : users[index].id,
                            'status' : data.userData.status,
                            '_token':'{{csrf_token()}}'
                        },
                        success: function(result){
                            let message = data.message;
                            let notification = document.getElementsByName('notification')[0];
                            let copy = notification.cloneNode(true);

                            let div1 =  document.createElement("div");
                            div1.setAttribute("name", "notification");
                            div1.setAttribute("class", "row");

                            let div2 =  document.createElement("div");
                            div2.setAttribute("class", "col-8");

                            let button1  =  document.createElement("input");
                            button1.setAttribute("type", "button");
                            button1.setAttribute("class", "list-group-item list-group-item-action text-danger");
                            button1.setAttribute("value", message);
                            button1.onclick = function(){
                                if(data.userData.status != 'deleteArticle'){
                                    if(data.userData.status == 'addChannel'){
                                        showChannelContent(data.userData.channelsId, value.id, 'Y');
                                    }else{
                                        showArticleContent(data.userData.articlesId, value.id, 'Y');
                                    }
                                }
                            }

                            div2.appendChild(button1);

                            let div3 =  document.createElement("div");
                            div3.setAttribute("class", "col-4");

                            let button2 =  document.createElement("input");
                            button2.setAttribute("type", "button");
                            button2.setAttribute("class", "btn btn-primary");
                            button2.setAttribute("name", "read");
                            button2.setAttribute("value", "已閱讀");
                            button2.onclick = function(){
                                readArticles(this, result.notificationId);
                            }

                            if(result.read_at != null){
                                button2.disabled = true;
                            }

                            div3.appendChild(button2);

                            div1.appendChild(div2);
                            div1.appendChild(div3);

                            let html = $('#notificationRaw').html();
                            $('#notificationRaw').empty();
                            $('#notificationRaw').append(div1);
                            $('#notificationRaw').append(html);
                            

                            // if(data.userData.status == 'deleteArticle'){
                            //     div1.innerHTML = "<div class='col-8'><input type='button' class='list-group-item list-group-item-action text-danger'"
                            //         + " value='" + message + "' /></div>"
                            //         + " <div class='col-4'><input type='button' class='btn btn-primary' name='read' value='已閱讀' onClick=readArticles(this" + ",'" + result.notificationId + "'" + ") />" + "</div></div> ";
                            // }else{
                            //     div1.innerHTML = "<div class='col-8'><input type='button' class='list-group-item list-group-item-action text-danger'"
                            //         + " value='" + message + "'  onClick=showArticleContent('" + data.userData.articleId + "',"+"'" + result.notificationId + "'," + "'N'" + ") /></div>"
                            //         + " <div class='col-4'><input type='button' class='btn btn-primary' name='read' value='已閱讀' onClick=readArticles(this" + ",'" + result.notificationId + "'" + ") />" + "</div></div> ";
                            // }

                            // let html = $('#notificationRaw').html();
                            // $('#notificationRaw').empty();
                            // $('#notificationRaw').append(div1);
                            // $('#notificationRaw').append(html);

                        },
                        error:function(xhr, status, error){
                            alert(xhr.statusText);
                        }
                    });
                }
            });
        });

        function getNotificationData(user, status){
            let data = [];
            $.ajax({
				url: "{{ route('getNotificationData') }}", 
				type: 'POST',
				data:{
                    'user' : user,
					'status' : status,
					'_token':'{{csrf_token()}}'
				},
				success: function(result){
                    return result;
                },
				error:function(xhr, status, error){
					alert(xhr.statusText);
				}
			});
            return data;
        }

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
                    
                    
                    $.each(result, function(index, value) {
                        let div1 =  document.createElement("div");
                        div1.setAttribute("name", "notification");
                        div1.setAttribute("class", "row");

                        let div2 =  document.createElement("div");
                        div2.setAttribute("class", "col-8");

                        let button1 =  document.createElement("input");
                        button1.setAttribute("type", "button");
                        button1.setAttribute("class", "list-group-item list-group-item-action");
                        button1.setAttribute("value", "您有一篇新訊息【" + value.data.title + "】");
                        button1.onclick = function(){
                            if(value.data.status != 'deleteArticle'){
                                if(value.data.status == 'addChannel'){
                                    showChannelContent(value.data.channelsId, value.id, 'Y');
                                }else{
                                    showArticleContent(value.data.articlesId, value.id, 'Y');
                                }
                            }
                        }

                        div2.appendChild(button1);

                        let div3 =  document.createElement("div");
                        div3.setAttribute("class", "col-4");

                        let button2 =  document.createElement("input");
                        button2.setAttribute("type", "button");
                        button2.setAttribute("class", "btn btn-primary");
                        button2.setAttribute("name", "read");
                        button2.setAttribute("value", "已閱讀");
                        button2.onclick = function(){
                            readArticles(this, value.id);
                        }

                        if(value.read_at != null){
                            button2.disabled = true;
                        }

                        div3.appendChild(button2);

                        div1.appendChild(div2);
                        div1.appendChild(div3);

                        $('#notificationRaw').append(div1);
                    });
				},
				error:function(xhr, status, error){
					alert(xhr.statusText);
				}
			});
        }

        function showArticleContent(id, notificationId, isRead){
            let userId = $('#userId').val();
            let url = '/showArticleContent?id='+ id +'&userId='+ userId +'&isAdd=N';
            
            if(notificationId != null){
                url = url + '&notificationId=' + notificationId
            }

            if(isRead != null){
                url = url + '&isRead=' + isRead
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

	</script>
</body>

</html>