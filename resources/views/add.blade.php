<html>

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Access-Control-Allow-Origin" content="*" />
</head>
<style>
    
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
                    <h2 id="title" class="text-center font-weight-bold" style="margin-bottom:20px;">新增文章</h2>
                    <div class="card">
                        <div class="card-body">
                            <form id="save" action="/addArticles" method="POST">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label for="InputTitle">標題</label>
                                    <input type="text" class="form-control" id="InputTitle" name="InputTitle" value=" ">
                                </div>
                                <div class="form-group">
                                    <label for="InputContent">內容</label>
                                    <textarea  type="text" class="form-control" id="InputContent" name="InputContent" maxlength="500"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="onlineDate">上限日期</label>
                                    <input type="text" class="form-control" id="onlineDate" name="onlineDate" data-provide="datepicker">
                                </div>
                                <div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input" id="sendNotice" name="sendNotice" value="Y">
                                    <label class="form-check-label" for="sendNotice">發通知</label>
                                </div>
                                <div class="form-group">
                                    <input type="hidden" id="userId" name="userId" value="{{ $userId }}">
                                    <input type="button" class="btn btn-primary" value="儲存" onClick="addArticles()">
                                </div>
                            <form>
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

        function addArticles(){
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