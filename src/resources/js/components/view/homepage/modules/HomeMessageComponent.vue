<template>
    <div class="row mt-3 ml-2 mr-2">
        <div class="col-12">
            <div class="card card-body bg-illoly-illoly">
                <div class="row align-items-center">
                    <div class="col-9 ml-4 mt-4">
                        <span class="h4 text-gray-800">{{date_message}}</span>
                    </div>
                    <div class="col-9 ml-4 mt-1">
                        <span class="h5 text-gray-800">歡迎來到</span>

                        <span class="ml-3 mr-3 h3 font-weight-bold text-blue">
                                {{school_name}}
                        </span>
                        <span class="h5 text-gray-800">查看兒童的健康和安全狀況</span>
                    </div>
                    <!-- <div class="col-3">1</div> -->

                    <div class="col-9 ml-5 mt-3 mb-4">
                        <!-- <div class="col col-xl-9 col-lg-8 col-md-9"> -->
                        <!-- <div class="text-xs font-weight-bold text-uppercase mb-1">Daily Check</div> -->
                        <!-- <div class="h5 mb-0 font-weight-bold text-gray-800">Hi, every manager,</div> -->
                        <!-- <span
                            class="text-gray-500"
                        >checks up the data about daily children's health &amp; safety</span> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import moment from "moment";
export default {
    data: function () {
        return {
            school_name: null,
            school_url: null,
            school_logo: null,
            date: null,
            date_message: null,
        };
    },
    filters: {
        timeFormat(date) {
            if (date == null) {
                return date;
            } else {
                return moment(date).format("HH:mm");
            }
        },
    },
    watch: {
        date: function (n, o) {
            if (
                moment(n).format("HH:mm") < "12:00" &&
                moment(n).format("HH:mm") > "04:00"
            ) {
                this.date_message = "早安";
            } else if (moment(n).format("HH:mm") > "12:00" &&
                moment(n).format("HH:mm") < "18:00"){
                this.date_message = "午安";
            } else {
                this.date_message = "晚上好";
            }
        },
    },
    mounted() {
        let oo = JSON.parse(sessionStorage.school_info);
        this.school_name = oo.school_name;
        this.school_url = oo.school_url;
        this.school_logo = oo.school_log;
        this.date = new Date();
        //sessionStorage.clear();
    },
};
</script>
<style lang="scss" scoped>
</style>