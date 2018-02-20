import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
	strict: true,
	state: {
		urls: [],
		visits: {}
	},
	getters: {
		urls: (state) => state.urls,
		visits: (state) => state.visits,
		visitsData: (state, getters) => (slug) => {
			return getters.visits[slug]
		},
	},
	actions: {
		async getUrls(context) {
			const { data } = await axios.get('/api/urls');
			context.commit('addUrls', data)
		},
		async getVisits(context, { urlSlug, period }) {
			if (Object.keys(context.state.visits).includes(urlSlug))
				return;

			let uri = '/api/' + urlSlug + '/visits';
			uri += (period !== null) ? '?since=' + period : '';
			const visits = context.state.visits[urlSlug]; 
			const { data } = await axios.get(uri);
			context.commit('addVisits', {data, urlSlug});
		},
		async editUrl(context, { slug, data }) {
			const uri = '/api/' + slug + '/update';
			const res = await axios.put(uri, data);
			context.commit('updateUrl', { slug, data: res.data });
		},
		async pushUrl(context, url) {
			const { data } = await axios.post('/url/store', { url });
			// console.log(data);
			context.commit('addUrl', data);
		}
	},
	mutations: {
		addUrls(state, urls) {
			state. urls = urls;
		},

		addVisits(state, { data, urlSlug }) {
			state.visits = { ...state.visits, [urlSlug]: data};
		},

		updateUrl(state, { slug, data }) {
			state.urls = state.urls.map(url => (url.slug === slug) ? data : url);
		},

		addUrl(state, url) {
			state.urls = [url, ...state.urls]
		}
	}
});
