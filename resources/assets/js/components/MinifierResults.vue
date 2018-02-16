<template>
	<div>
		<minifier-result :result="result" v-for="(result, i) in results" :key="i" />
	</div>
</template>

<script>
	import MinifierResult from './MinifierResult.vue';
	export default {
		components: { MinifierResult },
		props: ['results']
	}
</script>
