<template>
    <div>
        <profile-component></profile-component>
        <div class="container mt-5">
            <!-- {{ JSON.stringify(checkData) }} -->
            <div class="card table shadow" id="white_square">
                <div class="d-flex flex-row justify-content-around mt-3">
                    <b-select
                        v-model="search_select"
                        placeholder="-對象-"
                        class="select ml-3"
                    >
                        <option value="teacher">教師</option>
                        <option value="student">學生</option>
                    </b-select>

                    <b-select
                        placeholder="-班級-"
                        :disabled="isDisabled"
                        v-model="selectedDepartment"
                        expanded
                    >
                        <option
                            v-for="option in departmentsData"
                            :value="option.id"
                            :key="option.id"
                        >
                            {{ option.name }}
                        </option>
                    </b-select>

                    <b-datepicker
                        placeholder="-選擇起訖時間-"
                        v-model="date_range"
                        range
                    >
                    </b-datepicker>
                    <b-button @click="getAttendance" class="mr-auto"
                        >搜尋</b-button
                    >
                    <b-button class="mr-3">
                        <export-excel
                            class="btn btn-default"
                            :data="checkDataRename"
                            worksheet="My Worksheet"
                            name="filename.xls"
                        >
                            匯出
                        </export-excel></b-button
                    >
                </div>

                <div
                    class="card-header d-flex flex-row justify-content-between"
                >
                    <b-input
                        v-model="search_val"
                        placeholder="Search"
                        type="search"
                        expanded
                    ></b-input>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table
                            class="table table-bordered"
                            id="dataTable"
                            width="100%"
                            cellspacing="0"
                        >
                            <thead>
                                <tr>
                                    <th>
                                        <input
                                            type="checkbox"
                                            ref="allCheckbox"
                                            @change="selectAll"
                                        />
                                    </th>

                                    <th>
                                        姓名
                                        <img
                                            :src="sort_avart"
                                            alt
                                            width="15"
                                            @click="sortData('name')"
                                        />
                                    </th>
                                    <th>體溫</th>
                                    <th>抵達時間</th>
                                    <th v-if="search_select === 'student'">
                                        離校時間
                                    </th>
                                    <th v-else>最後更新時間</th>
                                    <th>
                                        日期
                                        <img
                                            :src="sort_avart"
                                            alt
                                            width="15"
                                            @click="sortData('date')"
                                        />
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(item, index) in employeeDataShow"
                                    :key="index"
                                >
                                    <td>
                                        <input
                                            ref="checkbox"
                                            type="checkbox"
                                            v-model="checkData"
                                            :value="item"
                                        />
                                    </td>
                                    <td>
                                        {{ item.name }}
                                    </td>

                                    <td>
                                        {{ item.temperature_val }}
                                    </td>

                                    <td>
                                        {{ item.time_arrive }}
                                    </td>

                                    <td>
                                        {{ item.time_leaved }}
                                    </td>
                                    <td>{{ item.date }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <b-loading
                :active.sync="isLoading"
                :is-full-page="true"
                v-model="isLoading"
                :can-cancel="false"
            ></b-loading>
        </div>
    </div>
</template>

<script>
// import Profile from "../../Profile";
//
import { renameKey } from "../../../function/index";
import moment from "moment";
export default {
    components: {},
    data: function () {
        return {
            school: null,
            sort_avart: "images/sequence_icon.svg",
            search_select: null,
            departmentsData: [],
            selectedDepartment: null,
            date_range: [],
            //
            search_val: "",
            departmentData: [],
            employeeData: [],
            employeeDataShow: [],
            isLoading: false,
            def_avatar: "images/img_profile_default.png",

            isReverse: false,
            start: null,
            end: null,
            checkData: [],
            isDisabled: true,
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
    computed: {
        checkDataRename() {
            let contact = [];
            for (const element of this.checkData) {
                let data = renameKey(element, "name", "姓名");
                data = renameKey(data, "date", "日期");
                data = renameKey(data, "temperature_val", "體溫");
                data = renameKey(data, "time_arrive", "到校時間");
                data = renameKey(data, "time_leaved", "離校時間");
                delete data["user_id"];
                contact = [...contact, data];
            }
            return contact;
        },
    },
    watch: {
        search_select() {
            if (this.search_select == "student") {
                this.isDisabled = false;
            } else {
                this.isDisabled = true;
                this.selectedDepartment = null;
            }
        },
        date_range() {
            if (this.date_range == []) {
                this.start = null;
                this.end = null;
            } else {
                this.start = moment(this.date_range[0]).format("MM/DD/YYYY");
                this.end = moment(this.date_range[1]).format("MM/DD/YYYY");
            }
        },
        //
        employeeData() {
            this.employeeDataShow = this.employeeData;
            this.search(this.search_val);
        },
        search_val() {
            this.search(this.search_val);
        },
        //

        editID(n, o) {
            if (n === o) {
                return;
            }
        },
    },
    created() {
        !sessionStorage.token ? (window.location.pathname = "/") : "";
    },
    mounted() {
        this.school = sessionStorage.school;
        this.getDepartments();
    },
    methods: {
        selectAll() {
            console.log(this.$refs.allCheckbox.checked);
            if (this.$refs.checkbox) {
                if (this.$refs.allCheckbox.checked == true) {
                    this.$refs.checkbox.forEach((val, index) =>
                        val.checked == false ? val.click() : false
                    );
                } else {
                    this.$refs.checkbox.forEach((val, index) =>
                        val.checked == true ? val.click() : false
                    );
                }
            }
        },
        getAttendance() {
            console.log("click");
            if (
                this.school === null ||
                this.start === null ||
                this.end === null
            ) {
                return;
            }

            if (this.search_select == "student") {
                if (this.selectedDepartment === null) {
                    return;
                }
                this.isLoading = true;
                axios
                    .post("attendance/getAttendance_student", {
                        school_id: this.school,
                        department_id: this.selectedDepartment,
                        start: this.start,
                        end: this.end,
                    })
                    .then((response) => {
                        this.employeeData = response.data;
                    })
                    .catch((error) => {})
                    .finally(() => {
                        this.isLoading = false;
                        this.date_range = [];
                        this.checkData = [];
                        this.selectedDepartment = null;
                    });
            } else if (this.search_select == "teacher") {
                this.isLoading = true;
                axios
                    .post("attendance/getAttendance_teacher", {
                        school_id: this.school,
                        start: this.start,
                        end: this.end,
                    })
                    .then((response) => {
                        this.employeeData = response.data;
                    })
                    .catch((error) => {})
                    .finally(() => {
                        this.isLoading = false;
                        this.date_range = [];
                        this.checkData = [];
                        this.selectedDepartment = null;
                    });
            }
        },
        getDepartments() {
            axios
                .get("attendance/departmentsName", {
                    params: {
                        school_id: this.school,
                    },
                })
                .then((response) => {
                    this.departmentsData = response.data;
                })
                .catch((error) => {});
        },
        sortData(type) {
            var vm = this;
            this.employeeDataShow = this.employeeDataShow.sort(function (a, b) {
                if (type === "tempers") {
                    // console.log("tempers");
                    return vm.isReverse ? b[type] - a[type] : a[type] - b[type];
                }
                if (
                    type === "name" ||
                    "date_time" ||
                    "leave_at" ||
                    "department" ||
                    "date"
                ) {
                    // console.log("type");
                    if (vm.isReverse) {
                        return a[type] < b[type]
                            ? 1
                            : a[type] > b[type]
                            ? -1
                            : 0;
                    } else {
                        return a[type] < b[type]
                            ? -1
                            : a[type] > b[type]
                            ? 1
                            : 0;
                    }
                }
            });
            vm.isReverse = !vm.isReverse;
        },
        search(n) {
            this.checkData = [];
            this.employeeDataShow = this.employeeData.filter(
                ({ name }) => name.includes(n)
                // {
                //     return name.search(n) != -1;
                // }
            );
        },
    },
};
</script>

<style lang="scss" scoped>
.table .card-body {
    padding: 0;
}
</style>
