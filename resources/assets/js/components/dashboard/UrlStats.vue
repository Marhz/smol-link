<template>
	<div style="position: relative;">
		<div class="d-flex justify-content-end pb-1" v-if="smallscreen">
			<button type="button" @click="$emit('close')" class="close-btn" aria-label="Close">
				<span aria-hidden="true"><i class="fa fa-times"></i></span>
			</button>
		</div>
		<div class="d-flex align-items-center">
			<h1>{{ url.path }}</h1>
			<button class="ml-1 btn btn-outline-warning btn-xs" @click="edit">Edit</button>
			<button class="ml-1 btn btn-outline btn-xs" @click="cancel" v-if="editing">Cancel</button>
		</div>
		<div v-if="editing">
			<div class="form-group">
				<label for="label">Label :</label>
				<input type="text" id="label" v-model="editUrl.label"/>
			</div>
			<div class="form-group">
				<label for="slug">Slug :</label>
				<input type="text" id="slug" v-model="editUrl.slug"/>
			</div>
			<button @click="submit" class="btn btn-primary">Save</button>
		</div>
		<period-select :period="period" @newPeriod="newPeriod" />
		<div class="graph-container">
			<visits-graph-container
				:visits="visitsData"
				:options="{responsive: true, maintainAspectRatio: false}"
				:period="period"
			/>
		</div>
		<div style="background: red; height: 500px; width: 100%;"></div>
 	</div>
</template>

<script>
import PeriodSelect from '../PeriodSelect.vue';
import { mapGetters } from 'vuex';

export default {
	components: {
		PeriodSelect
	},
	props: ['url', 'smallscreen'],

	data() {
		return {
			period: 'day',
			editing: false,
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
			return this.visits[this.$route.params.slug+ ':' + this.period];
			return this.$store.getters.visitsData(this.$route.params.slug)
		},
		...mapGetters(['visits']),
	}
}
</script>

<style>
	.graph-container {
		/*height: 200px;*/
		position: relative;
	}
</style>
