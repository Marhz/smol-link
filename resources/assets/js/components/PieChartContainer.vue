<template>
<div class="graphc">
 	<pie-chart
		:options="options"
		:chart-data="why"
		:height="400"
	/>
</div>
</template>

<script>
import PieChart from './PieChart.vue';
import moment from 'moment';

export default {
    components: { PieChart },
    props: {
        options: Object,
        countries: {
            type: Array,
            default: () => []
        }
    },
    data() {
        return {
            why: [],
            colors: [
                '#3C1642',
                '#086375',
                '#90BE6D',
                '#EA9010',
                '#37371F',
                '#40F99B',
                '#61707D',
                '#9D69A3',
                '#E85D75',
                '#FF9B71',
            ]
        }
    },
    watch: {
        countries: function() {
            // this.fillData();
            this.why = this.chartData
        },
    },
    mounted() {
        this.why = this.chartData
    },
    computed: {
        chartData() {
            return {
                labels: this.labels,
                datasets: [{
                    backgroundColor: this.backgroundColors,
                    data: this.countriesData
                }]
            }
        },
        countriesData() {
            return this.labels.map(label => {
                return this.countries.filter(c => c === label).length
            })
        },
        labels() {
            const obj = this.countries.reduce((acc, country) => {
                if (Object.keys(acc).includes(country))
                    acc[country]++
                else
                    acc[country] = 1;
                return acc
            }, {})
            return Object.keys(obj).sort((a, b) => obj[a] < obj[b] ? 1 : -1);
        },
        backgroundColors() {
            return this.labels.map((label, i) => this.colors[i % this.colors.length])
        }
    }
}
</script>

<style>
	.graphc {
		position: relative;
		/*max-height: 200px;*/
	}
</style>
