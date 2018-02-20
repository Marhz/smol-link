<template>
<div class="graphc">
 	<line-graph
		:options="options"
		:chart-data="why"
		:height="400"
	/>
</div>
</template>

<script>
import LineGraph from './LineGraph.vue';
import moment from 'moment';

export default {
	components: { LineGraph },
	props: {
		options: Object,
		period: String,
		visits: {
			type: Array,
			default: () => []
		}
	},
	data() {
		return {
			why: [],
			periodData: {
				day: {
					visitsFormattingFunc: "hour",
					labelsCount: 24,
					labelsDisplayFunc: (labels) =>  labels.map(l => (l.toString().length > 1) ? `${l}h` : `0${l}h`),
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
					labelsDisplayFunc: (labels) => labels.map((l, i) => moment(i + 1, 'MM').format('MMMM')),
					labelsPush: (i) => moment().subtract(i, 'months').month()
				}

			}
		}
	},
	watch: {
		graphData: function() {
			this.why = this.graphData
		},
	},
	computed: {
		graphData() {
			return {
				labels: this.durationConfig.labelsDisplayFunc(this.labels),
				datasets: [{
					label: 'Visits',
					backgroundColor: '#f87979',
					data: this.visitsFormatted
				}]
			}
		},
		visitsFormatted() {
			const visits = this.visits.map(visit => moment(visit.created_at))
			.filter(visit => {
				return (visit > moment().subtract(1, this.period).add(1, 'hour'))
			}).reduce((acc, visit) => {
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
		},
		durationConfig() {
			return this.periodData[this.period];
		}
	}
}
</script>

<style>
	.graphc {
		position: relative;
	}
	.yolo {
		height: 400px;
	}
</style>
