<script>
import { Bar, mixins } from 'vue-chartjs';
const { reactiveProp } = mixins

export default {
	props: ['visits', 'width', 'height', 'options'],
	mixins: [reactiveProp],
	extends: Bar,
	mounted () {
	    this.renderChart(this.chartData);
  	},
}
</script>
