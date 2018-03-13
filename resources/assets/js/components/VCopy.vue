<template>
	<button class="btn btn-outline-success" @click="copy">Copy</button>
</template>

<script>
export default {
	props: ['data'],
	methods: {
		copy() {
			const tmp = document.createElement('input');
			document.body.appendChild(tmp);
			tmp.value = this.data;
			tmp.select();
			document.execCommand('copy');
			tmp.remove()
		}
	}
}
</script>
