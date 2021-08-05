<html>

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Access-Control-Allow-Origin" content="*" />
</head>

<body>
    <div class="container">
        <div id="app" class="justify-content-center align-items-center">
            <div class="row" style="margin-bottom: 60px;">
                <form id="send" name="send" method="get" action="">
                    <select id="sort" name="sort">
                        <option value="0" disabled>請選擇</option>
                        <option value="1">由新到舊</option>
                        <option value="2">熱門度</option>
                        <option value="3">最長文章</option>
                    </select>
                    <select id="category" name="category">
                        <option value="0" disabled>請選擇</option>
                        <option value="1">文字</option>
                        <option value="2">影片</option>
                        <option value="3">音效</option>
                    </select>
                </form>
                <input type="hidden" id="getSort" name="getSort" value="{{ $sort }}">
                <input type="hidden" id="getCategory" name="getCategory" value="{{ $category }}">
            </div>
        </div>
    </div>
    <script src="{{mix('js/app.js')}}"></script>
    <script>
        let urlParams = new URLSearchParams(window.location);

        $(document).ready(function() {
            let getSort =  $('#getSort').val()
            let getCategory = $('#getCategory').val()

            if(getSort != '' && getSort != null){
                $('#sort').val(getSort)
                urlParams.set('sort', getSort);
            }

            if(getCategory != '' && getCategory != null){
                $('#category').val(getCategory)
                urlParams.set('category', getCategory);
            }

            history.pushState(null,null,'?sort=' + urlParams.get('sort') + '&category=' + urlParams.get('category'));
        });

        $('#sort').on('change', function(){
            let url = '/changeOption?sort=' + $(this).val() + '&category=' + $('#category').val();
            $('#send').attr('action', url);
            $('#send').submit();
        });

        $('#category').on('change', function(){
            let url = '/changeOption?sort=' + $('#sort').val() + '&category=' + $(this).val();
            $('#send').attr('action', url);
            $('#send').submit();
        });
    </script>
</body>

</html>