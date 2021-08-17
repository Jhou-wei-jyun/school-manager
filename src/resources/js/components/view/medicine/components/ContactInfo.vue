<template>
    <div class="card card-body d-flex flex-column">
        <div v-if="infoType === 'string'" class="h5">{{ contactInfo }}</div>
        <div v-else-if="infoType === 'image'">
            <div class="d-flex justify-content-around">
                <a
                    v-for="(item, index) in contactInfo"
                    :key="index"
                    :href="item.path"
                    target="_blank"
                >
                    <img :src="item.small_path" />
                </a>
            </div>
        </div>
        <div v-else-if="infoType === 'file'">
            <a
                v-for="(item, index) in contactInfo"
                :key="index"
                :href="item.path"
                target="_blank"
            >
                <p class="h5">
                    {{ item.name }}
                </p>
            </a>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        contactInfo: [String, Array],
        infoType: {
            type: String,
            required: true,
        },
    },
    data: function () {
        return {};
    },
    filters: {},
    mounted() {},
    methods: {},
};
</script>

<style lang="scss" scoped>
</style>
