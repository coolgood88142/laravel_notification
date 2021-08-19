<template>
    <li class="nav-item dropdown">
        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" 
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                通知 <span class="badge badge-danger" id="count-notification">
                    {{ lessons.length }}</span>
                <span class="caret"></span>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" v-for="(lesson, index) in lessons" :key="index">
                {{ lesson.data.title }}
            </a>

            <a class="dropdown-item" v-if="lessons.length != 0"  onClick="showNotification()">
                更多
            </a>

            <a class="dropdown-item" v-if="lessons.length == 0">
                沒通知訊息
            </a>
        </div>
    </li> 
</template>
<script>
export default {
    props: ['lessons'],
    methods: {
        showNotification(){
            $.ajax({
				url: '/showNotification', 
				type: 'POST',
				data:{
                    'nowCount' : $("div[name='notification']").length,
					'count' : $('#notificationsCount').val(),
					'_token':'{{csrf_token()}}'
				},
				success: function(result){
                    
                    console.log(result);
                    $.each(result, function(index, value) {

                        let html = 
                            '<div name="notification" class="row">' +
                                '<div class="col-8">' +
                                    '<input type="button" class="list-group-item list-group-item-action" value="' + value.data.title + '"';
                                    if(value.data.status == 'addChannel'){
                                        html += ' onclick="showChannelContent('+value.data.id+', '+value.id+', '+'Y'+')">';
                                    }else{
                                        html += ' onclick="showArticleContent('+value.data.id+', '+value.id+', '+'Y'+')">';
                                    }

                            html += '</div>' +
                                '<div class="col-4">';
                                 if(value.data.status != 'deleteArticle'){
                                    html += '<input type="button" class="btn btn-primary" name="read" value="已閱讀"  onclick="readArticles(this, '+value.id+')"';
                                    if(value.read_at != null){
                                        html += 'disabled';
                                    }
                                }

                            html += '>'+
                                '</div>'+
                            '</div>';
                        
                        $('#notificationRaw').append(html);
                    });
				},
				error:function(xhr, status, error){
					alert(xhr.statusText);
				}
			});
        }
    }
}
</script>