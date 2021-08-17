<template>
    <div class="card card-body d-flex flex-column">
        <div class="d-flex flex-row justify-content-between align-items-end">
            <div class="h3">{{notifyInfo.title}}</div>
            <div class="h6">{{notifyInfo.time.date | timeFormat}}</div>
        </div>
        <div class>
            <div class="h5">{{notifyInfo.message}}</div>
        </div>
        <!-- <div class="d-flex flex-row justify-content-around">
            <div v-for="item in notifyInfo.image" :key='item'>
                <img :src="item" width="125" />
            </div>
        </div>-->
    </div>
</template>

<script>
import moment from "moment";
export default {
    props: ["notifyInfo"],
    data: function () {
        return {};
    },
    filters: {
        timeFormat(date) {
            if (date == null) {
                return date;
            } else {
                return moment(date).format("YYYY-MM-DD");
            }
        },
    },
    mounted() {},
    methods: {},
};
</script>

<style lang="scss" scoped>
</style>
