
<script>
import { Bar, mixins } from 'vue-chartjs';
const { reactiveProp } = mixins
import moment from 'moment';

export default {
	props: ['visits', 'width', 'height', 'duration'],
	mixins: [reactiveProp],
	extends: Bar,
	mounted () {
	    this.renderChart(this.chartData);
  	},
}
</script>
