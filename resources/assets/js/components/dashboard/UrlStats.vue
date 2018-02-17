<template>
	<div>
		<h1>{{ $route.params.slug }}</h1>
		<period-select :period="period" @newPeriod="newPeriod" />
		<div class="graph-container">
			<visits-graph-container
				:url="{slug: $route.params.slug}"
				:visits="visitsData"
				:options="{responsive: true, maintainAspectRatio: false}"
				:period="period"
			/>
		</div>
 	</div>
</template>

<script>
import PeriodSelect from '../PeriodSelect.vue';

export default {
	components: {
		PeriodSelect
	},
	props: ['url'],

	data() {
		return {
			visitsData: [],
			period: 'day'
		}
	},

	watch: {
		'$route.params.slug': function (slug) {
			this.getStats();
			this.period = "day";
		}
	},
	mounted() {
		this.getStats();
	},

	methods: {
		getStats(period = null) {
			let uri = '/api/' + this.$route.params.slug + '/visits';
			uri += (period !== null) ? '?since=' + period : ''
			axios.get(uri).then(({data}) => this.visitsData = data)
		},
		newPeriod(period) {
			this.period = period;
			this.getStats(period)
		}
	}
}
</script>

<style>
	.graph-container {
		/*height: 200px;*/
		position: relative;
	}
</style>
