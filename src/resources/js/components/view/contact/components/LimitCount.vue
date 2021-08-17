<template>
    <p class="h5">{{ count }}/{{ limit }}</p>
</template>

<script>
export default {
    props: {
        limit: {
            type: Number,
            default: function () {
                return 1;
            },
        },
        count: {
            type: Number,
            default: function () {
                return 0;
            },
        },
    },
    data: function () {
        return {};
    },

    watch: {
        count: function (n, o) {
            if (n >= this.limit) {
                this.$emit("countLimit", true);
            } else {
                this.$emit("countLimit", false);
            }
        },
    },
    mounted() {},
    methods: {},
};
</script>

<style lang="scss" scoped>
</style>
