<template>
<div class="graphc">
	<select v-model="period" class="form-control">
		<option value="day">Last day</option>
		<option value="week">Last 7 days</option>
		<option value="month">Last 30 days</option>
		<option value="year">Last Year</option>
	</select>
	<visits-graph
		:visits="visits"
		:options="options"
		:chart-data="chartData"
	></visits-graph>
</div>
</template>

<script>
	import VisitsGraph from './VisitsGraph.vue';
	import moment from 'moment';

	export default {
		components: { VisitsGraph },
		props: ['url', "options"],
		data() {
			return {
				period: "",
				chartData: null,
				visits: [],
				cachedVisits: {},
				periodData: {
					day: {
						visitsFormattingFunc: "hour",
						labelsCount: 24,
						labelsDisplayFunc: (labels) =>  labels.map(l => l + "h"),
						labelsPush: (i) => moment().subtract(i, 'hours').hour()
					},
					week: {
						visitsFormattingFunc: "date",
						labelsCount: 7,
						labelsDisplayFunc: (labels) => labels,
						labelsPush: (i) => moment().subtract(i, 'days').date()
					},
					month: {
						visitsFormattingFunc: "date",
						labelsCount: 30,
						labelsDisplayFunc: (labels) => labels,
						labelsPush: (i) => moment().subtract(i, 'days').date()
					},
					year: {
						visitsFormattingFunc: "month",
						labelsCount: 12,
						labelsDisplayFunc: (labels) => labels.map(l => l + 1),
						labelsPush: (i) => moment().subtract(i, 'months').month()
					}
				}
			}
		},
		mounted() {
			this.period = "day";
		},
		watch: {
			period: async function() {
				if (Object.keys(this.cachedVisits).includes(this.period)) {
					this.visits = this.cachedVisits[this.period];
				} else {
					const { data } = await axios.get('/api/' + this.url.slug + '/visits?since=' + this.period)
	  				const visits = data.map(visit => moment(visit.created_at))
					this.visits = visits;
					this.cachedVisits[this.period] = visits;
				}
				this.fillData();
			}
		},
		methods: {
			fillData() {
				this.chartData = {
			    	labels: this.durationConfig.labelsDisplayFunc(this.labels),
			      	datasets: [{
						label: this.visits.length + ' Visits',
			          	backgroundColor: '#f87979',
			          	data: this.visitsFormatted
			        }]
			    }
			},
		},
	  	computed: {
	  		visitsFormatted() {
	  			const visits = this.visits.reduce((acc, visit) => {
	  				let format = visit[this.durationConfig.visitsFormattingFunc]();
	  				if (Object.keys(acc).includes(format.toString())) {
	  					acc[format]++;
	  				} else {
	  					acc[format] = 1;
	  				}
	  				return acc;
	  			}, {});
	  			return this.labels.map(label => (Object.keys(visits).includes(label.toString())) ? visits[label] : 0)
	  		},
	  		labels() {
	  			return new Array(this.durationConfig.labelsCount).fill(null)
	  				.map((label, i) => this.durationConfig.labelsPush(i))
	  				.reverse();
	  			// const arr = [];
	  			// for (let i = 0; i < this.durationConfig.labelsCount; i++) {
	  			// 	arr.push(this.durationConfig.labelsPush(i));
	  			// }
	  			// return arr.reverse();
	  		},
	  		durationConfig() {
	  			return this.periodData[this.period];
	  		}
	  	}
	}
</script>

<style>
	.graphc {
	}
</style>
