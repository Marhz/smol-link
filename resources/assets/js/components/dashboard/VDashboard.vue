<template>
	<div class="container-fluid dashboard">
		<div class="row no-gutters">
			<div class="col-12 infos">
				{{ user.email }}
			</div>
		</div>
		<div class="row no-gutters">
			<div class="col-12 col-md-4 links-list">
				<url-overview :url="url" :selectedUrl="selectedUrl" @selectUrl="selectUrl" v-for="url in user.urls" :key="url.slug"/>
			</div>
			<div class="col-8 d-none d-md-block link-info">
				<router-view></router-view>
			</div>
		</div>
	</div>
</template>

<script>
	import UrlOverview from './UrlOverview.vue';

	export default {
		components: {
			UrlOverview,
		},
		props: ['user'],

		data() {
			return {
				selectedUrl: this.$route.params.slug
			}
		},
		watch: {
			$route (to, from) {
				this.selectedUrl = this.$route.params.slug
			}
		},
		methods: {
			selectUrl(url) {
				// this.selectedUrl = url
			}
		},
		computed: {
		}
	}
</script>

<style scoped>
	.container-fluid {
		padding: 0;
	}
</style>
