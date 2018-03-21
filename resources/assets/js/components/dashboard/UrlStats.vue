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
		<div class="row no-gutters mt-3">
			<div class="col-md-6 col-12">
				<h3>Countries Chart</h3>
				<pie-chart-container
					:items="countriesData"
					:options="{responsive: true, maintainAspectRatio: false}"				
				/>
			</div>
			<div class="col-md-6 col-12">
				<h3>Referrers Chart</h3>
				<pie-chart-container
					:items="referrersData"
					:options="{responsive: true, maintainAspectRatio: false}"				
				/>
			</div>			
		</div>
 	</div>
</template>

<script>
import PeriodSelect from '../PeriodSelect.vue';
import PieChartContainer from '../PieChartContainer.vue';
import { mapGetters } from 'vuex';

export default {
	components: {
		PeriodSelect,
		PieChartContainer,
	},
	props: ['url'],

	data() {
		return {
			period: 'week',
			editing: true,
			editUrl: {
				slug: '',
				label: ''
			}
		}
	},

	watch: {
		url: function () {
			this.init();
		},
	},
	mounted() {
		this.init();
	},

	methods: {
		init() {
			this.period = "week";
			this.editUrl.slug = this.url.slug;
			this.editUrl.label = this.url.label;
			this.getStats();
		},
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
		},
		formatDataForPieChart: (key, nullReplacement) => (acc, item) => {
			item = item[key] || nullReplacement;
			(acc.hasOwnProperty(item)) ? acc[item]++ : acc[item] = 1;
			return acc
		},
	},
	computed: {
		visitsData() {
			return this.$store.getters.visitsData(this.$route.params.slug)
		},
		referrersData() {
			return this.visitsData.reduce(this.formatDataForPieChart('referrer', 'Direct link'), {});
		},
		countriesData() {
			return this.visitsData.reduce(this.formatDataForPieChart('country', 'Unknown'), {});
		},
	}
}
</script>
