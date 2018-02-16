<template>
    <div>
        <div class="row no-gutters">
            <div class="col-12 col-md">
                <input ref="test" @keypress.enter.prevent="submit" type="text" placeholder="Enter an url to shorten" class="" :class="error ? 'has-error' : ''" v-model="input">
            </div>
            <div class="col-12 col-md-auto">
                <button @click="submit" class="btn btn-primary" type="submit">Make smol</button>
            </div>
        </div>
        <div v-if="error" class="error" v-text="error.message"></div>
        <minifier-results v-if="hasResult" :results="results"></minifier-results>
    </div>
</template>

<script>
import minifierResults from "./MinifierResults.vue";
export default {
    components: { minifierResults },
    data() {
        return {
            input: '',
            hasResult: false,
            results: [],
            error: null
        }
    },
    methods: {
        submit() {
            axios.post('url/store', {url: this.input})
                .then(res => {
                    this.hasResult = true;
                    this.results.push(res.data);
                    this.error = null;
                    this.input = res.data.path
                    this.$nextTick(() => {
                        this.$refs.test.select();
                    })
                })
                .catch(({response}) => {
                    this.error = response.data
                    console.log(response.data.message);
                });
        }
    }
}
</script>

<style scoped>
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
    .flex div {
        padding: 0;
    }
    .error {
        color: red;
    }
    .has-error {
        border: 1px solid red;
    }
</style>
