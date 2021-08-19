<template>
    <div class="form-group">
        <div class="card-header">
            文章列表
		</div>
        <div class="row" v-for="(article, index) in articles" :key="index">
            <div class="col-8">
                <input type="button" class="list-group-item list-group-item-action" value="{{ article.title }}" onclick="showArticleContent('{{ article.id }}', null, null)" />
            </div>
            <div class="col-4" v-if="article.author_id == userId">
                <input type="button" class="btn btn-primary" name="read" value="刪除" onClick="deleteArticles(this, {{ article.id }})"/>
            </div>
        </div>
        <input type="button" class="btn btn-primary" value="新增文章" style="margin-top: 10px;" onclick="window.location.href='/add?userId={{ userId }}'">
    </div>
</template>
<script>
export default {
    props: {
		articles: {
			type: Array,
		},
		userId: {
			type: String,
		},
	},
    methods: {
    }
}
</script>