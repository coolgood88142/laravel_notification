<template>
    <div class="form-group">
        <div class="card-header">
            通知列表
             <input type="button" class="btn btn-primary" name="readAll" value="已閱讀全部"
                    onCLick="readArticles(this, '')" />
		</div>
        <div id="notificationRaw"></div>
        <input type="button" id="moreArticles" name="moreArticles" class="btn btn-primary" style="margin-top: 10px;" value="更多" onClick="showNotification()">
    </div>
</template>
<script>
export default {
    props: {
		channels: {
			type: Array,
		},
		userId: {
			type: String,
		},
	},
}
</script>