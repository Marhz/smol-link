<template>
	<div>
		<div class="pb-1 close-btn">
			<button type="button" @click="$emit('close')" aria-label="Close">
				<span aria-hidden="true"><i class="fa fa-times"></i></span>
			</button>
		</div>
		<div class="d-flex flex-column link-info">
			<h1>{{ url.label }}</h1>
			<div class="d-flex align-items-center">
				<span class="mr-3">{{ url.path }}</span> 
				<v-copy :data="url.path"/>
			</div>
			<div>{{ url.url }}</div>
			<!-- <button class="ml-1 btn btn-outline-warning btn-xs" @click="edit">Edit</button>
			<button class="ml-1 btn btn-outline btn-xs" @click="cancel" v-if="editing">Cancel</button> -->
		</div>
		<div v-if="editing">
			<div class="row no-gutters">
				<div class="form-group col-md-6 col-12 pr-md-3">
					<label for="slug" class="w-100">Slug :</label>
					<input type="text" id="slug" class="form-control" v-model="editUrl.slug"/>
				</div>
				<div class="form-group col-12 col-md-6 pl-md-3">
					<label for="label" class="w-100">Label :</label>
					<input type="text" id="label" class="form-control" v-model="editUrl.label"/>
				</div>
				<div class="col form-group">
					<button @click="submit" class="btn btn-primary float-right">Save</button>
				</div>
			</div>
		</div>
		<period-select :period="period" @newPeriod="newPeriod" />
		<div class="graph-container">
			<visits-graph-container
				:visits="visitsData"
				:options="{responsive: true, maintainAspectRatio: false}"
				:period="period"
			/>
		</div>
		<div class="graph-container">
			<countries-chart-container
				:countries="countriesData"
				:options="{responsive: true, maintainAspectRatio: false}"				
			/>
		</div>
 	</div>
</template>

<script>
import PeriodSelect from '../PeriodSelect.vue';
import CountriesChartContainer from '../CountriesChartContainer.vue';
import { mapGetters } from 'vuex';

export default {
	components: {
		PeriodSelect,
		CountriesChartContainer
	},
	props: ['url', 'smallScreen'],

	data() {
		return {
			period: 'day',
			editing: true,
			editUrl: {
				slug: '',
				label: ''
			}
		}
	},

	watch: {
		'$route.params.slug': function () {
			this.period = "day";
			this.getStats();
			this.editUrl.slug = this.url.slug;
			this.editUrl.label = this.url.label;
		},
	},
	mounted() {
		this.editUrl.slug = this.url.slug;
		this.editUrl.label = this.url.label;
		this.getStats();
	},

	methods: {
		getStats() {
			this.$store.dispatch('getVisits', { urlSlug: this.$route.params.slug, period: this.period });
		},
		newPeriod(period) {
			this.period = period;
			this.getStats();
		},
		edit() {
			this.editing = true
		},
		submit() {
			const slug = this.url.slug
			this.$store.dispatch('editUrl', { slug: this.url.slug, data: this.editUrl }).then(() => {
				console.log(this.editUrl.slug);
				this.$router.replace(this.editUrl.slug);
			});
		},
		cancel() {
			this.editing = false;
			this.editUrl.slug = this.url.slug;
			this.editUrl.label = this.url.label;
		}
	},
	computed: {
		visitsData() {
			return this.visits[this.$route.params.slug] || [];
			return this.$store.getters.visitsData(this.$route.params.slug)
		},
		countriesData() {
			return this.visitsData.map(visit => {
				if (visit.country === null)
					return 'Unknown';
				return visit.country;
			});
		},
		...mapGetters(['visits']),
	}
}
</script>

<style scoped>
	.graph-container {
		/*height: 200px;*/
		position: relative;
	}
</style>
