<template>
    <div>
		<period-select :period="period" @newPeriod="newPeriod" />
		<div class="graph-container">
			<visits-graph-container
				:visits="visitsData"
				:options="{responsive: true, maintainAspectRatio: false}"
				:period="period"
			/>
		</div>
    </div>
</template>

<script>
import PeriodSelect from './PeriodSelect.vue';
import { mapGetters } from 'vuex';

export default {
    components: {
        PeriodSelect
    },
    
    props: ['url'],

    data() {
        return {
            period: 'day'
        }
    },

    mounted() {
        this.getStats();
    },

    methods: {
        getStats() {
            this.$store.dispatch('getVisits', { urlSlug: this.url.slug, period: this.period });
        },
        newPeriod(period) {
            this.period = period;
            this.getStats();
        },
    },

    computed: {
        visitsData() {
			return this.visits[this.url.slug+ ':' + this.period];
        },
        ...mapGetters(['visits'])
    }

}
</script>
