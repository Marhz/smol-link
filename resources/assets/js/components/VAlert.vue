<template>
    <div class="alert alert-warning alert-dismissible " role="alert" v-if="show">
        <div>
            <div v-text="message"></div>
        </div>
        <button @click="close" type="button" class="close" data-dismiss="alert" aria-label="Close">
            <i class="fa fa-times"></i>
        </button>
    </div>
</template>

<script>
    export default {
        props: ['message'],
        data() {
            return {
                show: true
            }
        },
        methods: {
            close() {
                this.show = false;
            }
        }
    }
</script>

<style lang="scss">
.alert {
    position: absolute;
    z-index: 1000000;
    width: 100%;
    font-size: 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 1.25rem;
    .close {
        position: relative;
        font-size: 1rem;
        // padding: 0rem;
    }
}
</style>

