<template>
    <div>
        <profile-component></profile-component>
        <div class="container-fluid">
            <HomeMessage></HomeMessage>
        </div>
        <!-- <div class="col-12 mb-1">
            <div class="row">
                <div class="col-6">
                    Select Class :
                    <select
                        v-model="department"
                        @change="[getErrTemp(),getStudent(),getAttence()]"
                    >
                        <option :value="null">All</option>
                        <option
                            v-for="depart in departmentsData"
                            :value="depart.id"
                        >{{ depart.name }}</option>
                    </select>
                </div>
            </div>
        </div>-->
        <div class="container-fluid">
            <HomeKpi
                :right="right"
                :ErTcount="ErTcount"
                :temperatureDate="temperatureDate"
                :studentcount="studentcount"
                :latestudent="latestudent"
                :latestudentcount="latestudentcount"
                @errTemperRefresh="getErrTemp"
            ></HomeKpi>
        </div>
        <div class="container-fluid">
            <div class="row ml-5 mr-5 mt-5">
                <div
                    class="col-md-6 col-sm-12"
                    v-show="
                        right['今日學生考勤'] == null
                            ? false
                            : right['今日學生考勤']['show']
                    "
                >
                    <div class="card card-body">
                        <div
                            class="
                                d-flex
                                flex-column
                                align-items-center
                                justify-content-around
                                chart-area
                            "
                        >
                            <div
                                class="
                                    h6
                                    font-weight-bold
                                    text-uppercase text-gray-600
                                "
                            >
                                今日學生考勤
                            </div>
                            <div>
                                <StudentHomeRing
                                    :item="Attendance_rate"
                                ></StudentHomeRing>
                            </div>

                            <div>
                                <span
                                    class="
                                        col-6
                                        h5
                                        font-weight-bold
                                        text-uppercase text-gray-500
                                    "
                                    >應到：{{ studentcount }}</span
                                >
                                <span
                                    class="
                                        col-6
                                        h5
                                        font-weight-bold
                                        text-uppercase text-gray-500
                                    "
                                    >實到：{{ realcome }}</span
                                >
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="col-md-6 col-sm-12"
                    v-show="
                        right['今日教師考勤'] == null
                            ? false
                            : right['今日教師考勤']['show']
                    "
                >
                    <div class="card card-body">
                        <div
                            class="
                                d-flex
                                flex-column
                                align-items-center
                                justify-content-around
                                chart-area
                            "
                        >
                            <div
                                class="
                                    h6
                                    font-weight-bold
                                    text-uppercase text-gray-600
                                "
                            >
                                今日教師考勤
                            </div>

                            <div>
                                <TeacherHomeRing
                                    :item="teacher_Attendance_rate"
                                ></TeacherHomeRing>
                            </div>

                            <div>
                                <span
                                    class="
                                        col-6
                                        h5
                                        font-weight-bold
                                        text-uppercase text-gray-500
                                    "
                                    >應到：{{ teachercount }}</span
                                >
                                <span
                                    class="
                                        col-6
                                        h5
                                        font-weight-bold
                                        text-uppercase text-gray-500
                                    "
                                    >實到：{{ teacher_realcome }}</span
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
// import Profile from "../../Profile";
import HomeMessage from "./modules/HomeMessageComponent";
import HomeKpi from "./modules/HomeKpiComponent";
// import ViewArriveChart from "./ViewArriveChartComponent";
// import HomeOverviewChart from "./HomeOverviewChartComponent";
import StudentHomeRing from "../../v-chart/StudentHomeRing";
import TeacherHomeRing from "../../v-chart/TeacherHomeRing";

import moment from "moment";

export default {
    components: {
        HomeMessage,
        HomeKpi,
        StudentHomeRing,
        TeacherHomeRing,
    },
    data: function () {
        return {
            def_avatar: "images/no_photo.png",
            school: null,
            avatar: null,
            name: null,
            department: null,
            departmentsData: [],
            temperatureDate: [],
            ErTcount: null,
            studentcount: null,
            absentcount: null,
            latestudent: null,
            latestudentcount: null,
            // getData: null
            Attence: null,
            // attenceData: null,
            teachercount: null,
            absentteachercount: null,
            group_id: null,
            right: [],
        };
    },
    computed: {
        resultdata: function () {
            return this.ErTcount / this.studentcount;
        },
        realcome: function () {
            return this.studentcount - this.absentcount;
        },
        Attendance_rate: function () {
            return Math.round((this.realcome / this.studentcount) * 100);
        },
        teacher_realcome: function () {
            return this.teachercount - this.absentteachercount;
        },
        teacher_Attendance_rate: function () {
            return Math.round(
                (this.teacher_realcome / this.teachercount) * 100
            );
        },
    },
    created() {
        !sessionStorage.token ? (window.location.pathname = "/") : "";
        this.group_id = sessionStorage.group;
        this.getRight();
    },
    mounted() {
        this.school = sessionStorage.school;
        this.avatar = sessionStorage.avatar;
        this.name = sessionStorage.name;
        this.student_type = sessionStorage.student_type;
        this.teacher_type = sessionStorage.teacher_type;
        // this.getDepartments();
        // this.getAttence();
        this.getErrTemp();
        this.getStudent();
        this.getabsentStudent();
        this.getlateStudent();
        this.getTeacher();
        this.getabsentTeacher();
    },
    methods: {
        getRight() {
            axios
                .get("right/block", {
                    params: {
                        group_id: this.group_id,
                        tab_id: 10, //page_id :10 首頁
                    },
                })
                .then((response) => {
                    this.right = response.data;
                });
        },
        sidebarcontroll() {
            // console.log("publish sucess");
            this.$store.dispatch("sideshow");
        },
        getDepartments() {
            axios
                .post("departments", {
                    school_id: this.school,
                })
                .then((response) => {
                    this.departmentsData = response.data;
                })
                .catch({});
        },
        async getErrTemp() {
            try {
                const response = await axios.get("data/getErrTemp", {
                    params: {
                        school_id: this.school,
                        department_id: this.department,
                    },
                });
                console.log(response.data);
                this.temperatureDate = response.data.data;
                this.ErTcount = response.data.count;
                console.log("temperature: ", this.temperatureDate);
            } catch (error) {}
        },
        async getStudent() {
            try {
                const response = await axios.get("data/getStudent", {
                    params: {
                        school_id: this.school,
                        department_id: this.department,
                    },
                });
                this.studentcount = response.data;
                console.log("studentcount: ", this.studentcount);
            } catch (error) {
            } finally {
                // this.sentData();
            }
        },
        async getabsentStudent() {
            try {
                const response = await axios.get("data/getabsentStudent", {
                    params: {
                        school_id: this.school,
                        // department_id: this.department,
                    },
                });
                this.absentcount = response.data;
                console.log("absentcount: ", this.absentcount);
            } catch (error) {
            } finally {
                // this.sentData();
            }
        },
        async getlateStudent() {
            try {
                const response = await axios.get("data/getlateStudent", {
                    params: {
                        school_id: this.school,
                        // department_id: this.department,
                    },
                });
                this.latestudent = response.data.data;
                this.latestudentcount = response.data.count;
                console.log("latestudentcount: ", this.latestudent);
            } catch (error) {
            } finally {
                // this.sentData();
            }
        },
        // async getAttence() {
        //     let weekOfday = moment().format("E");
        //     let last_monday = moment()
        //         .subtract(weekOfday - 1, "days")
        //         .format("YYYY-MM-DD");
        //     let last_sunday = moment()
        //         .subtract(7 - weekOfday, "days")
        //         .format("YYYY-MM-DD");
        //     console.log(weekOfday, last_monday, last_sunday);
        //     try {
        //         const response = await axios.post("getAttence", {
        //             school_id: this.school,
        //             department_id: this.department,
        //             last_monday: last_monday,
        //             last_sunday: last_sunday,
        //         });
        //         this.Attence = JSON.stringify(response.data);

        //         console.log("Attence: ", JSON.stringify(this.Attence));
        //     } catch (error) {}
        // },
        async getTeacher() {
            try {
                const response = await axios.get("data/getTeacher", {
                    params: {
                        school_id: this.school,
                    },
                });
                this.teachercount = response.data;
                console.log("teachercount: ", this.teachercount);
            } catch (error) {
            } finally {
                // this.sentData();
            }
        },
        async getabsentTeacher() {
            try {
                const response = await axios.get("data/getabsentTeacher", {
                    params: {
                        school_id: this.school,
                        // department_id: this.department,
                    },
                });
                this.absentteachercount = response.data;
                console.log("absentteachercount: ", this.absentteachercount);
            } catch (error) {
            } finally {
                // this.sentData();
            }
        },
        // getData(ErTcount, studentcount, Attence) {
        //     this.resultdata = ErTcount / studentcount;
        //     this.attenceData = Attence;
        //     console.log("getsuccess: ", ErTcount);
        //     console.log("getsuccess: ", studentcount);
        //     console.log("getsuccess: ", this.resultdata);
        //     console.log("getsuccess: ", this.attenceData);
        // },
    },
};
</script>
<style lang="scss" scoped>
.dropdown-menu {
    min-width: 0;
}
.dropdown-item {
    padding-left: 0 !important;
}
</style>