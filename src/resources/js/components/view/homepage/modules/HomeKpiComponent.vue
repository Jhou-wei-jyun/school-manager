<template>
    <div class="row ml-4 mr-4 mt-5">
        <!-- {{JSON.stringify(latestudentcount)}} -->
        <!-- <div class="col-12 mb-1">
            <div class="row">
                <div class="col-6">
                    Select Class :
                    <select v-model="department" @change="[getErrTemp(),getStudent(),getAttence()]">
                        <option :value="null">All</option>
                        <option v-for="depart in departmentsData" :value="depart.id">{{ depart.name }}</option>
                    </select>
                </div>
            </div>
        </div>-->
        <!-- Earnings (Monthly) Card Example -->
        <div
            class="col-md-4 col-sm-12"
            v-show="
                right['體溫異常人數'] == null
                    ? false
                    : right['體溫異常人數']['show']
            "
        >
            <a @click="showErTlist">
                <div class="card card-body">
                    <div
                        class="h6 font-weight-bold text-uppercase text-gray-600"
                    >
                        體溫異常人數
                    </div>

                    <div class="d-flex justify-content-between">
                        <div></div>
                        <div class="h1 text-gray-800 align-self-start">
                            {{ ErTcount }}
                        </div>

                        <!-- <i class="fas fa-thermometer-three-quarters fa-2x text-danger align-self-end"></i> -->
                        <img
                            src="images/dashboard_temperature.svg"
                            width="35"
                            class="align-self-end"
                        />
                    </div>
                </div>
            </a>
        </div>
        <!-- Earnings (Annual) Card Example -->
        <div
            class="col-md-4 col-sm-12"
            v-show="
                right['遲到人數'] == null ? false : right['遲到人數']['show']
            "
        >
            <a @click="showlatelist">
                <div class="card card-body">
                    <div
                        class="h6 font-weight-bold text-uppercase text-gray-600"
                    >
                        遲到人數
                    </div>

                    <div class="d-flex justify-content-between">
                        <div></div>
                        <div class="h1 text-gray-800 align-self-start">
                            {{ latestudentcount }}
                        </div>

                        <!-- <i class="fas fa-thermometer-three-quarters fa-2x text-danger align-self-end"></i> -->
                        <img
                            src="images/dashboard_late.svg"
                            width="35"
                            class="align-self-end"
                        />
                    </div>
                </div>
            </a>
        </div>
        <!-- Pending Requests Card Example -->
        <div
            class="col-md-4 col-sm-12"
            v-show="right['總人數'] == null ? false : right['總人數']['show']"
        >
            <a @click="gotoDepart_student">
                <div class="card card-body">
                    <div
                        class="h6 font-weight-bold text-uppercase text-gray-600"
                    >
                        總人數
                    </div>

                    <div class="d-flex justify-content-between">
                        <div></div>
                        <div class="h1 text-gray-800 align-self-start">
                            {{ studentcount }}
                        </div>

                        <!-- <i class="fas fa-thermometer-three-quarters fa-2x text-danger align-self-end"></i> -->
                        <img
                            src="images/dashboard_people.svg"
                            width="35"
                            class="align-self-end"
                        />
                    </div>
                </div>
            </a>
        </div>

        <b-modal :active.sync="isErrorTempList" :width="350" scroll="clip">
            <ErrorTempList
                :right="right"
                :temperatureDate="temperatureDate"
                @errTemperRefresh="errTemperRefresh"
            ></ErrorTempList>
        </b-modal>
        <b-modal :active.sync="isLateList" :width="350" scroll="clip">
            <LateList :latestudent="latestudent"></LateList>
        </b-modal>
    </div>
</template>

<script>
import moment from "moment";
import ErrorTempList from "./ErrorTempList";
import LateList from "./LateList";
export default {
    components: {
        ErrorTempList,
        LateList,
    },
    props: [
        "right",
        "temperatureDate",
        "ErTcount",
        "studentcount",
        "latestudentcount",
        "latestudent",
    ],
    data: function () {
        return {
            timestamp: moment().format("YYYY-MM-DD hh:mm:ss"),
            isLateList: false,
            isErrorTempList: false,
        };
    },
    mounted() {},
    methods: {
        errTemperRefresh() {
            this.$emit("errTemperRefresh");
        },
        showlatelist: function () {
            if (this.right["遲到列表"]["show"] == true) {
                this.isLateList = true;
            } else {
                this.$buefy.toast.open({
                    message: "使用者權限不足",
                    type: "is-danger",
                });
            }
        },
        showErTlist: function () {
            if (this.right["異常體溫列表"]["show"] == true) {
                this.isErrorTempList = true;
            } else {
                this.$buefy.toast.open({
                    message: "使用者權限不足",
                    type: "is-danger",
                });
            }
        },
        gotoDepart_student: function () {
            this.$store.dispatch("showchange");
            console.log(this.$store.getters.studentshow);
            window.open("/department");
        },
        getNow: function () {
            this.timestamp = moment().format("YYYY-MM-DD hh:mm:ss");
            // const today = new Date();
            // const date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
            // const time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
            // const dateTime = date +' '+ time;
            // this.timestamp = dateTime;
        },
    },
};
</script>
