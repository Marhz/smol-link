<template>
	<div class="container-fluid dashboard">
		<div class="row no-gutters">
			<div class="col-12 infos">
				{{ user.email }}
			</div>
		</div>
		<div class="row no-gutters">
			<div class="col-12 col-md-4 links-list">
				<url-overview :url="url" :selectedUrl="selectedUrl" v-for="url in urls" :key="url.id" @open="showStats = true"/>
			</div>
			<div class="col-md-8 col-12 link-info" v-show='showStats'>
				<router-view
					:url="selectedUrl"
					:smallscreen="smallScreen"
					@close="showStats = false"
					@updateUrl="updateUrl"
					v-if="selectedUrl !== undefined"
				></router-view>
			</div>
		</div>
	</div>
</template>

<script>
import UrlOverview from './UrlOverview.vue';
import { mapGetters } from 'vuex';

export default {
	components: {
		UrlOverview,
	},
	props: ['user'],
	data() {
		return {
			showStats: window.innerWidth >= 768 || this.$route.params.slug.length > 0
		}
	},
	mounted() {
		this.$store.dispatch('getUrls');
	},
	methods: {
		selectUrl(url) {
			// this.selectedUrl = url
		},
		updateUrl(slug, newUrl) {
			const url = this.urls.find(url => url.slug === slug)
			url.label = newUrl.label;
			url.slug = newUrl.slug;
		}
	},
	computed:{
		...mapGetters(['urls', 'visits']),
		selectedUrl() {
			return this.urls.find(url => url.slug === this.$route.params.slug);
			return this.urls[this.$route.params.slug];
			if (url !== undefined )
				url.selected = (url.slug) === this.$route.params.slug;
			console.log(url);
			return url;
		},
		smallScreen() {
			return (window.innerWidth <= 768);
		}
	}
}
</script>

<style scoped>
	.container-fluid {
		padding: 0;
	}
</style>
