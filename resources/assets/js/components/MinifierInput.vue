<template>
    <div>
        <div class="flex">
            <div class="col">
                <input @keypress.enter.prevent="submit" type="text" placeholder="Enter an url to shorten" class="" :class="error ? 'has-error' : ''" v-model="input">
            </div>
            <div class="col-auto">
                <button @click="submit" class="btn btn-primary" type="submit">Make smol</button>
            </div>
        </div>
        <minifier-result v-if="hasResult" :result="result"></minifier-result>
        <div v-if="error" class="error" v-text="error.message"></div>
    </div>
</template>

<script>
import minifierResult from "./MinifierResult.vue";
export default {
    components: { minifierResult },
    data() {
        return {
            input: '',
            hasResult: false,
            result: null,
            error: null
        }
    },
    methods: {
        submit() {
            this.hasResult = false;
            axios.post('url/store', {url: this.input})
                .then(res => {
                    this.hasResult = true;
                    this.result = res.data;
                    this.error = null;
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
        font-size: 25px;
    }
    input {
        border-radius: 0;
        /*line-height: 40px;*/
        width: 100%;
        padding: 10px;
        font-size: 35px;
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
