<template>
	<router-link :to="'/links/' + url.slug">
		<div class="url-overview" :class="getClass" @click="$emit('open')">
			<h3 v-text="displayedLabel"></h3>
			<div class="flex">
				<p v-text="url.path"></p>
				<p>{{ url.label }}</p>
				<p class="ml-auto"><i class="fa fa-chart-bar mr-1"></i>{{ url.visits_count }} Visits</p>
			</div>
		</div>
	</router-link>
</template>

<script>
export default {
	props: ['url', 'selectedUrl'],

	computed: {
		displayedLabel() {
			return this.url.label === null ? this.url.url : this.url.label
		},
		getClass() {
			return this.$route.params.slug === this.url.slug ? 'selected' : ''
		}
	}
}
</script>
