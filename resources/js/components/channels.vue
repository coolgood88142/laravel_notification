<template>
    <div class="form-group">
        <div class="card-header">
            頻道列表
		</div>
        <div class="row" v-for="(channel, index) in channels" :key="index">
            <div class="col-8">
                <input type="button" class="list-group-item list-group-item-action" value="{{ channel.name }}" onclick="showChannelContent('{{ channel.id }}')" />
            </div>
        </div>
        <input type="button" class="btn btn-primary" value="新增頻道" style="margin-top: 10px;" onclick="window.location.href='/addChannels?userId={{ userId }}'">
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