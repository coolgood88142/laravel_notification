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
            </div>
        </div>
    </div>
    <script src="{{mix('js/app.js')}}"></script>
    <script>
        let getSort = '{{ $sort }}';
        let getCategory = '{{ $category }}';

        let url = new URL(window.location);
        let urlParams = new URLSearchParams({sort: getSort, category: getCategory});

        $(document).ready(function() {
            if(getSort != '' && getSort != null){
                $('#sort').val(getSort)
                urlParams.set('sort', getSort);
            }

            if(getCategory != '' && getCategory != null){
                $('#category').val(getCategory)
                urlParams.set('category', getCategory);
            }

            url.search = urlParams;
        });

        $('#sort').on('change', function(){
            urlParams.set('sort', $(this).val())
            send();
        });

        $('#category').on('change', function(){
            urlParams.set('category', $(this).val())
            send();
        });

        function send(){
            url.search = urlParams;
            window.location.href = url.href

            $('#send').attr('action', url.href);
            $('#send').submit();
        }
    </script>
</body>

</html>