<template>
	<div class="result">
		<p v-text="titleOrUrl"></p>
		<div class="row">
			<div class="col-12 col-md">
				<span class="" v-text="result.path"></span>
				<v-copy :data="result.path" class="copy"></v-copy>
			</div>
			<div class="col-12 col-md result-stats">
				<i class="fa fa-chart-bar"></i>
				<a :href="statsPath">
					{{result.visits_count}} visits
				</a>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		props: ['result'],
		computed: {
			statsPath() {
				return this.result.path + '/stats';
			},
			titleOrUrl() {
				return (this.result.title === null) ? this.result.url : this.result.title
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
		font-size: 1rem;
	}
	.result-infos {
		display: flex;
		align-items: center;
	}
	@media (min-width: 768px) {
		.result{
			font-size: 1.5rem;
		}
		.result-stats {
			text-align: right;
		}
	}
</style>
