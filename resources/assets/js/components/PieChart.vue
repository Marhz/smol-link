<script>
import { Pie, mixins } from 'vue-chartjs';
const { reactiveProp } = mixins

export default {
	props: ['visits', 'width', 'height', 'options'],
	mixins: [reactiveProp],
	extends: Pie,
	mounted () {
	    this.renderChart(this.chartData);
  	},
}
</script>
