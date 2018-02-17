<template>
	<select v-model="selectedPeriod" class="form-control" @change="$emit('newPeriod', selectedPeriod)">
		<option value="day">Last day</option>
		<option value="week">Last 7 days</option>
		<option value="month">Last 30 days</option>
		<option value="year">Last Year</option>
	</select>
</template>

<script>
	export default {
		props: ['period'],
		data() {
			return {
				selectedPeriod: this.period
			}
		},
		watch: {
			period: function() {
				this.selectedPeriod = this.period;
			}
		}
	}
</script>
