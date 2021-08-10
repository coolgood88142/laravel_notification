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
                                                        onclick="showArticleContent('{{ $notification['articlesId'] }}', '{{ $notification['id'] }}', '{{ $notification['read'] ? 'Y' : 'N' }}')"
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
	<script src="{{mix('js/edit.js')}}"></script>
    <script>
        window.laravel_echo_port='{{env("LARAVEL_ECHO_PORT")}}';
    </script>
    <script src="//{{ Request::getHost() }}:{{env('LARAVEL_ECHO_PORT')}}/socket.io/socket.io.js"></script>
    <script src="{{ url('/js/laravel-echo-setup.js') }}" type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            var i = 0;
            window.Echo.channel('article-channel' + $('#userId').val())
            .listen('.SendMessage', (data) => {
                console.log(data.userData);
                let message = data.message;
                let notification = document.getElementsByName('notification')[0];
                let copy = notification.cloneNode(true);

                let div1 =  document.createElement("div");
                div1.setAttribute("name", "notification");
                div1.setAttribute("class", "row");

                if(data.userData.status == 'deleteArticle'){
                    div1.innerHTML = "<div class='col-8'><input type='button' class='list-group-item list-group-item-action text-danger'"
                        + " value='" + message + "' /></div>"
                        + " <div class='col-4'><input type='button' class='btn btn-primary' name='read' value='已閱讀' onClick=readArticles(this" + ",'" + data.userData.notificationId + "'" + ") />" + "</div></div> ";
                }else{
                    div1.innerHTML = "<div class='col-8'><input type='button' class='list-group-item list-group-item-action text-danger'"
                        + " value='" + message + "'  onClick=showArticleContent('" + data.userData.articleId + "',"+"'" + data.userData.notificationId + "'," + "'N'" + ") /></div>"
                        + " <div class='col-4'><input type='button' class='btn btn-primary' name='read' value='已閱讀' onClick=readArticles(this" + ",'" + data.userData.notificationId + "'" + ") />" + "</div></div> ";
                }

                let html = $('#notificationRaw').html();
                $('#notificationRaw').empty();
                $('#notificationRaw').append(div1);
                $('#notificationRaw').append(html);
            });
        });

        function showNotification(){
            $.ajax({
				url: '/showNotification', 
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

                        if(value.data.status != 'delete'){
                            if(value.read_at != null){
                                div1.innerHTML = "<div class='col-8'><input type='button' class='list-group-item list-group-item-action'"
                                    + " value='您有一篇新訊息【" + value.data.title + "】'  onClick=showArticleContent('" + value.data.articlesId + "',"+"'" + value.id + "'," + "'Y'" + ") /></div>"
                                    + " <div class='col-4'><input type='button' class='btn btn-primary' name='read' value='已閱讀' onClick=readArticles(this" + ",'" + value.id + "'" + ") disabled />" + "</div></div> ";
                            }else{
                                div1.innerHTML = "<div class='col-8'><input type='button' class='list-group-item list-group-item-action'"
                                    + " value='您有一篇新訊息【" + value.data.title + "】'  onClick=showArticleContent('" + value.data.articlesId + "',"+"'" + value.id + "'," + "'N'" + ") /></div>"
                                    + " <div class='col-4'><input type='button' class='btn btn-primary' name='read' value='已閱讀' onClick=readArticles(this" + ",'" + value.id + "'" + ") disabled />" + "</div></div> ";
                            }
                        }else{
                            if(value.read_at != null){
                                div1.innerHTML = "<div class='col-8'><input type='button' class='list-group-item list-group-item-action'"
                                    + " value='您有一篇新訊息【" + value.data.title + "】'  onClick=showArticleContent('" + value.data.articlesId + "',"+"'" + value.id + "'," + "'Y'" + ") /></div>"
                                    + " <div class='col-4'><input type='button' class='btn btn-primary' name='read' value='已閱讀' onClick=readArticles(this" + ",'" + value.id + "'" + ") />" + "</div></div> ";
                            }else{
                                div1.innerHTML = "<div class='col-8'><input type='button' class='list-group-item list-group-item-action'"
                                    + " value='您有一篇新訊息【" + value.data.title + "】'  onClick=showArticleContent('" + value.data.articlesId + "',"+"'" + value.id + "'," + "'N'" + ") /></div>"
                                    + " <div class='col-4'><input type='button' class='btn btn-primary' name='read' value='已閱讀' onClick=readArticles(this" + ",'" + value.id + "'" + ") />" + "</div></div> ";
                            }
                        }

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
				url: '/readArticles', 
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
				url: '/deleteArticles', 
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