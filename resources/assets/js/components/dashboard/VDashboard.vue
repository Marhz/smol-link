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
		<div class="row no-gutters" ref="urlsContainer">
			<url-overview
				:urls="urls" 
				:selectedUrl="selectedUrl" 
			/>
			<div class="col-md-8 col-12 link-view" ref="info" v-if='selectedUrl !== undefined'>
				<router-view
					:url="selectedUrl"
					@close="closeStats"
					@updateUrl="updateUrl"
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
			if (this.smallScreen) {
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
				this.$router.push(this.urls[0].slug);
			});
		},
		error() {

		},
		closeStats() {
			this.$router.push('/');
		}
	},
	computed:{
		...mapGetters(['urls', 'visits']),
		selectedUrl() {
			return this.urls.find(url => url.slug === this.$route.params.slug) || { slug: '' };
		},
		showStats() {
			return !!this.$route.params.slug;
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
