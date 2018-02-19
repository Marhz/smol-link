<template>
	<div class="container-fluid dashboard">
        <div class="row no-gutters">
            <div class="col-12 col-md">
                <input ref="test" @keypress.enter.prevent="submit" type="text" placeholder="Enter an url to shorten" class="" :class="error ? 'has-error' : ''" v-model="input">
            </div>
            <div class="d-none d-md-flex col-md-auto">
                <button @click="submit" class="btn btn-primary" type="submit">Make smol</button>
            </div>
        </div>
		<div class="row no-gutters" :style="urlContainerHeight" ref="urlsContainer">
			<url-overview 
				:urls="urls" 
				:selectedUrl="selectedUrl" 
				@open="showStats = true"
			/>
			<div class="col-md-8 col-12 link-view" :style="urlContainerHeight" ref="info" v-show='showStats'>
				<router-view
					:url="selectedUrl"
					:small-screen="smallScreen"
					@close="showStats = false"
					@updateUrl="updateUrl"
					v-if="selectedUrl !== undefined"
				></router-view>
			</div>
		</div>
	</div>
</template>

<script>
import UrlOverview from './UrlOverview.vue';
import { mapGetters } from 'vuex';
import debounce from 'lodash/debounce';

export default {
	components: {
		UrlOverview,
	},
	props: ['user'],
	data() {
		return {
			showStats: true,
			input: '',
			smallScreen: (window.innerWidth <= 768)
		}
	},
	mounted() {
		window.addEventListener('resize', debounce(this.setElemsHeight, 100))
		this.setElemsHeight();
		this.$store.dispatch('getUrls');
	},
	beforeDestroy() {
		window.removeEventListener('resize', this.setElemsHeight)
	},
	methods: {
		setElemsHeight() {
			this.smallScreen = (window.innerWidth <= 768);
			this.$refs.urlsContainer.style.height = (window.innerHeight - this.$refs.urlsContainer.offsetTop - this.$refs.urlsContainer.firstChild.firstChild.clientHeight) + "px"
			this.showStats = this.smallScreen || this.$route.params.slug.length > 0;
			if (this.smallScreen && this.$refs.info.style.height !== "100%") {
				this.$refs.info.style.height = "100%"
			} else {
				this.$refs.info.style.height = (window.innerHeight - this.$refs.urlsContainer.offsetTop) + "px";
			}
		},
		selectUrl(url) {
			// this.selectedUrl = url
		},
		updateUrl(slug, newUrl) {
			const url = this.urls.find(url => url.slug === slug)
			url.label = newUrl.label;
			url.slug = newUrl.slug;
		},
		submit() {
			this.$store.dispatch('pushUrl', this.input).then(() => {
				console.log(this.urls[0].slug);
				this.$router.push(this.urls[0].slug);
			});
		},
		error() {

		}
	},
	computed:{
		...mapGetters(['urls', 'visits']),
		selectedUrl() {
			return this.urls.find(url => url.slug === this.$route.params.slug);
			return this.urls[this.$route.params.slug];
			if (url !== undefined )
				url.selected = (url.slug) === this.$route.params.slug;
			console.log(url);
			return url;
		},
		urlContainerHeight() {
			// return {color: 'red'};
			// console.log(this.$refs);
			// return { height: (window.innerHeight - this.$refs.urlsContainer.offsetTop) + "px" };
				
		}
	}
}
</script>

<style scoped>
	.container-fluid {
		padding: 0;
		height: 100%;
	}
	    button {
        height: 100%;
        border-radius: 0;
        width: 100%;
        font-size: 2rem;
    }
    input {
        border-radius: 0;
        /*line-height: 40px;*/
        width: 100%;
        padding: 10px;
        font-size: 2.3rem;
    }
</style>
