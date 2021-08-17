<template>
    <div class="card card-body">
        <div
            v-for="item in announceData"
            :key="item.id"
            class="d-flex flex-column"
        >
            <div>
                <div class="h3">{{ item.title }}</div>
                <img :src="item.avatar" />
            </div>
            <div class="ml-auto">
                <div class="h5">{{ item.onboard_date }}</div>
            </div>
            <!-- <div class="d-flex flex-row justify-content-around">
            <div v-for="item in AnnounceInfo.image" :key='item'>
                <img :src="item" width="125" />
            </div>
        </div>-->
        </div>
        <b-loading
            :active.sync="isLoading"
            :is-full-page="true"
            v-model="isLoading"
            :can-cancel="false"
        ></b-loading>
    </div>
</template>

<script>
import moment from "moment";
export default {
    props: ["AnnounceInfo"],
    data: function () {
        return {
            isLoading: false,
            announceData: [],
        };
    },
    mounted() {
        this.getinfo();
    },
    methods: {
        async getinfo() {
            this.isLoading = true;
            await axios
                .post("announcement/info", {
                    id: this.AnnounceInfo.id,
                })
                .then((response) => {
                    console.log(response.data.result);
                    this.announceData = response.data;
                })
                .finally(() => {
                    this.isLoading = false;
                });
        },
    },
};
</script>

<style lang="scss" scoped>
</style>
