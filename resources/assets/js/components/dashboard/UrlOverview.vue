<template>
	<div class="col-12 col-md-4">
		<div class=" d-flex links-list-controls align-items-center">
			<input type="text" class="form-control mr-2" placeholder="Search" v-model="search"/>
			<div class="d-flex align-items-center">
				<span>Sort by: </span>
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" name="sort" @click="sortByVisits = ! sortByVisits" :checked="sortByVisits">
					<label class="form-check-label" for="inlineCheckbox1">Visits</label>
				</div>
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" name="sort" @click="sortByVisits = ! sortByVisits" :checked="! sortByVisits">
					<label class="form-check-label" for="inlineCheckbox2">Date</label>
				</div>
			</div>
		</div>
		<div class="links-list overflow-scroll" v-remaining>
			<div v-for="url in sortedUrls" :key="url.slug">
				<router-link :to="'/links/' + url.slug">
					<div class="url-overview" :class="getClass(url)" @click="$emit('open')">
						<h3 v-text="displayedLabel(url)"></h3>
						<div class="flex">
							<p v-text="url.path"></p>
							<p class="ml-auto"><i class="fa fa-chart-bar mr-1"></i>{{ url.visits_count }} Visits</p>
						</div>
					</div>
				</router-link>
			</div>
		</div>
	</div>
</template>

<script>

export default {
	props: ['urls', 'selectedUrl'],

	data() {
		return {
			search: '',
			sortByVisits: false
		}
	},
	created() {

	},
	methods: {
		displayedLabel(url) {
			return url.label === null ? 
				((url.title === null) ? url.url : url.title) 
				: url.label
		},
		getClass(url) {
			return this.selectedUrl.slug === url.slug ? 'selected' : ''
		},
	},
	computed: {
		searchedUrls() {
			if (this.search.length === 0) return this.urls;
			const search = this.search.toLowerCase().trim();
			return this.urls.filter(url => {
				return (
					url.slug.includes(search) || 
					url.url.includes(search) || 
					((url.label !== null) && url.label.includes(search))
				)
			});
		},
		sortedUrls() {
			// return this.urls
			if (! this.sortByVisits) return this.searchedUrls;
			return [...this.searchedUrls].sort((a, b) => (a.visits_count >= b.visits_count) ? -1 : 1)
		}
	}
}
</script>
