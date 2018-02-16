<template>
	<div class="result">
		<p v-text="result.url"></p>
		<div class="result-infos">
			<span class="" v-text="result.path"></span>
			<v-copy :data="result.path" class="copy"></v-copy>
			<span class="visits">{{result.visits_count}} visits</span>
			<a :href="statsPath">Stats</a>
		</div>
	</div>
</template>

<script>
	export default {
		props: ['result'],
		computed: {
			statsPath() {
				return this.result.path + '/stats';
			}
		}
	}
</script>

<style scoped>
	.copy {
		margin-left: 15px;
	}

	.visits {
		margin-left: auto;
	}

	.result {
		width: 100%;
		background: white;
		padding: 20px;
	}
	.result-infos {
		display: flex;
		align-items: center;
	}
</style>
