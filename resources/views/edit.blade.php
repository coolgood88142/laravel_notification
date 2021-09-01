<html>

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Access-Control-Allow-Origin" content="*" />
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
                                <lesson_notification :lessons="{{ auth()->user()->unreadNotifications }}"></lesson_notification> 
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
                    <h2 id="title" class="text-center font-weight-bold" style="margin-bottom:20px;">文章資料</h2>
                    <div class="card">
                        <div class="card-body">
                            <form id="save" action="/queryAuthor" method="POST">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label for="InputTitle">標題</label>
                                    <input type="text" class="form-control" id="InputTitle" name="InputTitle" value="{{ $title }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="InputContent">內容</label>
                                    <textarea  type="text" class="form-control" id="InputContent" name="InputContent" maxlength="500" readonly>{{ $content }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label for="InputAuthor">作者</label>
                                    <input  type="text" class="form-control" id="InputAuthor" name="InputAuthor" value="{{ $userName }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="onlineDate">上限日期</label>
                                    <input type="text" class="form-control" id="onlineDate" name="onlineDate" data-provide="datepicker" value="{{ $onlineDate }}" readonly>
                                </div>
                                <div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input" id="sendNotice" name="sendNotice" value="{{ $sendNotice }}" disabled>
                                    <label class="form-check-label" for="sendNotice">發通知</label>
                                </div>
                                <div class="form-group">
                                    <input type="hidden" id="isAdd" name="isAdd" value="{{ $isAdd }}">
                                    <input type="button" class="btn btn-primary" id="back" name="back" value="回上一頁" onClick="window.location.href='/articles'">
                                    <input type="button" class="btn btn-primary" id="showAritcles" name="showAritcles" style="display:none;" value="回文章列表" onClick="window.location.href='/articles'">
                                    <input type="button" class="btn btn-primary" value="新增留言" onclick="window.location.href='/editComment?articleId={{ $articleId }}&userId={{ $userId }}'">
                                </div>
                            <form>
                            <div class="form-group">
                                <div class="card-header">
                                    留言列表
                                </div>
                                @foreach ($comment as $key => $value)
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $value->name }}</h5>
                                        <p class="card-text">{{ $value->text }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    <script src="{{mix('js/app.js')}}"></script>
    <script src="{{mix('js/notification.js')}}"></script>
    <link rel="stylesheet" href="./css/datepicker3.css"/>
    <script src="https://cdn.jsdelivr.net/bootstrap.datepicker-fork/1.3.0/js/bootstrap-datepicker.js"></script>
    <script type="text/javascript" src="./js/bootstrap-datetimepicker.zh-TW.js" charset="UTF-8"></script>
    <script>
        $( document ).ready(function() {
            let isAdd = $('#isAdd').val();
            if(isAdd == 'Y'){
                $('#back').hide();
                $('#showAritcles').show();
            }

            if($("#sendNotice").val() == 'Y'){
                $("#sendNotice").prop("checked", true);
            }
        });

        $("input[name='onlineDate']").datepicker({
            uiLibrary: 'bootstrap4',
            format: "yyyy-mm-dd",
            language:"zh-TW",
            weekStart: 1,
            daysOfWeekHighlighted: "6,0",
            autoclose: true,
            todayHighlight: true,
        });

        $("#sendNotice").click(function () {
            if($(this).prop("checked")){
                $("#sendNotice").val('Y');
            } else {
                $("#sendNotice").val('N');
            }
        });

        function saveArticles(){
            let InputTitle = $('#InputTitle').val();
            let InputContent = $('#InputContent').val();
            let message = '';

            if(InputTitle == '' || InputTitle == null){
                message += '請輸入標題' + '<br/>';
            }

            if(InputContent == '' || InputContent == null){
                message += '請輸入內容' + '<br/>';
            }

            if(message != ''){
                alert(message);
            }else{
                $('#save').submit();
            }
        }
    </script>
</body>

</html>