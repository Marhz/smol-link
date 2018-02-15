<template>
	<div class="result">
		<p v-text="result.url"></p>
		<div class="result-infos">
			<span class="" v-text="result.path"></span>
			<button class="copy btn btn-success" @click="copy">Copy</button>
			<span class="visits">{{result.visits}} visits</span>
		</div>
	</div>
</template>

<script>
	export default {
		props: ['result'],
		methods: {
			copy() {
				const tmp = document.createElement('input');
				document.body.appendChild(tmp);
				tmp.value = this.result.path;
				tmp.select();
				document.execCommand('copy');
				tmp.remove()
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
