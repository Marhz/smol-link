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
		}
	},
	actions: {
		async getUrls(context) {
			const { data } = await axios.get('/api/urls');
			context.commit('addUrls', data)
		},
		async getVisits(context, { urlSlug, period }) {
			const namespace = urlSlug + ':' + period;

			if (Object.keys(context.state.visits).includes(namespace))
				return;

			let uri = '/api/' + urlSlug + '/visits';
			uri += (period !== null) ? '?since=' + period : '';
			const { data } = await axios.get(uri);
			context.commit('addVisits', {data, urlSlug, namespace});
		},
		async editUrl(context, { slug, data }) {
			const uri = '/api/' + slug + '/update';
			const res = await axios.put(uri, data)
			context.commit('updateUrl', { slug, data: res.data })
		}
	},
	mutations: {
		addUrls(state, urls) {
			state. urls = urls;
			// state.urls = urls.reduce((acc, url) => {
			// 	acc[url.slug] = url;
			// 	return acc;
			// }, {});
		},

		addVisits(state, { data, urlSlug, namespace }) {
			state.visits = { ...state.visits, [namespace]: data};
		},

		updateUrl(state, { slug, data }) {
			// state.urls = Object.assign(state.urls, { [slug]: data })
			state.urls = state.urls.map(url => (url.slug === slug) ? data : url);
		}
	}
});
