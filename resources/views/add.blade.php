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
    <script src="{{mix('js/edit.js')}}"></script>
    <script>
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