<template>
    <pie-chart
        :options="options"
        :chart-data="why"
        :height="400"
    />
</template>

<script>
import PieChart from './PieChart.vue';
import moment from 'moment';

export default {
    components: { PieChart },
    props: {
        options: Object,
        items: {
            type: Object,
            default: () => {}
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
            ],
        }
    },
    mounted() {
        this.why = this.chartData;
    },
    watch: {
        items: function() {
            this.why = this.chartData;
        },
    },
    computed: {
        chartData() {
            return {
                labels: this.labels,
                datasets: [{
                    backgroundColor: this.backgroundColors,
                    data: this.values
                }]
            }
        },
        values() {
            return Object.values(this.items).sort((a, b) => a < b ? 1 : -1);
        },
        labels() {
            return Object.keys(this.items).sort((a, b) => this.items[a] < this.items[b] ? 1 : -1);
        },
        backgroundColors() {
            return this.labels.map((label, i) => this.colors[i % this.colors.length])
        }
    }
}
</script>
