<script>
import { Doughnut, mixins } from 'vue-chartjs';
const { reactiveProp } = mixins

export default {
	props: ['visits', 'width', 'height', 'options'],
	mixins: [reactiveProp],
	extends: Doughnut,
	mounted () {
	    this.renderChart(this.chartData);
  	},
}
</script>
