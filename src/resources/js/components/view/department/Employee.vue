<template>
    <div class="container mt-5">
        <!-- {{ JSON.stringify(displayData) }}
        {{ JSON.stringify(employeeDataSort) }} -->
        <div class="card table shadow">
            <div class="d-flex flex-row justify-content-end mt-3">
                <div class="dropdown no-arrow">
                    <a
                        class="nav-link dropdown-toggle"
                        href="#"
                        role="button"
                        id="dropdownMenuLink"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                    >
                        <i class="fas fa-ellipsis-v fa-2x"></i>
                    </a>

                    <div
                        class="dropdown-menu"
                        aria-labelledby="dropdownMenuLink"
                    >
                        <a
                            class="dropdown-item"
                            @click="addBtn"
                            v-show="
                                right['新增'] == null
                                    ? false
                                    : right['新增']['show']
                            "
                            >新增學生</a
                        >
                        <a class="dropdown-item" @click="xlsxExport"
                            >匯出Excel</a
                        >

                        <!-- <a class="dropdown-item" @click="$refs.excel.click()"
                            >匯入Excel</a
                        >
                        <input
                            v-show="false"
                            ref="excel"
                            type="file"
                            accept=".xlsx"
                            @change="xlsxImport"
                        />
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" @click="xlsxSample"
                            >格式下載</a
                        > -->
                    </div>
                </div>
            </div>
            <div class="card-header d-flex flex-row justify-content-between">
                <b-input
                    v-model="search_val"
                    placeholder="Search"
                    type="search"
                    icon="magnify"
                    icon-clickable
                    expanded
                ></b-input>

                <!-- <b-button
                        size="is-medium"
                        class="notification_btn notification_btn_green shadow animate__animated animate__fadeIn ml-auto"
                >匯出Excel</b-button>-->

                <!-- <b-button
                    size="is-medium"
                    class="notification_btn notification_btn_yellow shadow animate__animated animate__fadeIn ml-1"
                    @click="addBtn"
                >新增</b-button>-->
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
                                <th></th>
                                <th>
                                    姓名
                                    <img
                                        :src="sort_avart"
                                        alt
                                        width="15"
                                        @click="sortData('name')"
                                    />
                                </th>
                                <th>
                                    班級
                                    <img
                                        :src="sort_avart"
                                        alt
                                        width="15"
                                        @click="sortData('department')"
                                    />
                                </th>
                                <th>
                                    體溫
                                    <img
                                        :src="sort_avart"
                                        alt
                                        width="15"
                                        @click="sortData('tempers')"
                                    />
                                </th>
                                <th>
                                    抵達時間
                                    <img
                                        :src="sort_avart"
                                        alt
                                        width="15"
                                        @click="sortData('date_time')"
                                    />
                                </th>
                                <th>
                                    離校時間
                                    <img
                                        :src="sort_avart"
                                        alt
                                        width="15"
                                        @click="sortData('leave_at')"
                                    />
                                </th>
                                <th
                                    v-show="
                                        right['編輯'] == null
                                            ? false
                                            : right['編輯']['show']
                                    "
                                >
                                    編輯
                                </th>
                                <th
                                    v-show="
                                        right['刪除'] == null
                                            ? false
                                            : right['刪除']['show']
                                    "
                                >
                                    刪除
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in pageOfItems" :key="item.id">
                                <!-- 一般 -->
                                <td>
                                    <img
                                        class="profile_img"
                                        width="40"
                                        style="height: 40px"
                                        :src="item.avatar || def_avatar"
                                    />
                                </td>
                                <td>{{ item.name }}</td>

                                <td>{{ item.department }}</td>

                                <td
                                    class="text_color_green"
                                    v-if="
                                        item.tempers <= 37.5 &&
                                        item.tempers > 35.0
                                    "
                                >
                                    {{ item.tempers }}
                                </td>
                                <td class="text_color_red" v-else>
                                    {{ item.tempers }}
                                </td>

                                <td
                                    class="text_color_gray200"
                                    v-if="
                                        checkTime(
                                            item.date_time,
                                            item.start
                                        ) === 'no_time'
                                    "
                                >
                                    {{ item.date_time | timeFormat }}
                                </td>
                                <td
                                    v-else-if="
                                        checkTime(
                                            item.date_time,
                                            item.start
                                        ) === true
                                    "
                                >
                                    {{ item.date_time | timeFormat }}
                                </td>
                                <td
                                    class="text_color_red"
                                    v-else-if="
                                        checkTime(
                                            item.date_time,
                                            item.start
                                        ) === false
                                    "
                                >
                                    {{ item.date_time | timeFormat }}
                                </td>

                                <td
                                    class="text_color_gray200"
                                    v-if="
                                        checkLeaveTime(item.leave_at, item.end)
                                    "
                                >
                                    {{ item.leave_at | timeFormat }}
                                </td>
                                <td v-else>
                                    {{ item.leave_at | timeFormat }}
                                </td>
                                <td
                                    v-show="
                                        right['編輯'] == null
                                            ? false
                                            : right['編輯']['show']
                                    "
                                >
                                    <b-button
                                        class="table-btn pl-0 pr-0"
                                        @click="editEmp(item)"
                                    >
                                        <img
                                            class="rounded-circle"
                                            width="40px"
                                            src="images/edit_icon.svg"
                                        />
                                    </b-button>
                                </td>
                                <td
                                    v-show="
                                        right['刪除'] == null
                                            ? false
                                            : right['刪除']['show']
                                    "
                                >
                                    <b-button
                                        class="table-btn pl-0 pr-0"
                                        @click="deleteEmp(item)"
                                    >
                                        <img
                                            class="rounded-circle"
                                            width="40px"
                                            src="images/delete_icon.svg"
                                        />
                                    </b-button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-center">
                <jw-pagination
                    :items="employeeDataSort"
                    :pageSize="12"
                    :labels="customLabels"
                    @changePage="onChangePage"
                ></jw-pagination>
            </div>
        </div>
        <b-modal :active.sync="isAddEmp" :width="640" scroll="clip">
            <AddEmp :admin="admin_id" @emprefresh="getallEmployees"></AddEmp>
        </b-modal>
        <b-modal :active.sync="isEditEmp" :width="640" scroll="clip">
            <EditEmp
                :student-info="editdata"
                :admin="admin_id"
                @emprefresh="getallEmployees"
            ></EditEmp>
        </b-modal>
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
import AddEmp from "./modules/AddEmp";
import EditEmp from "./modules/EditEmp";
// import Pagination from "./Pagination";
export default {
    components: {
        AddEmp,
        EditEmp,
        // Pagination,
    },
    data: function () {
        return {
            isAddEmp: false,
            isAddFaceEmp: false,
            search_val: "",
            departmentData: [],
            employeeData: [],
            employeeDataSort: [],
            isLoading: false,
            isEditEmp: false,
            isEditFaceEmp: false,

            tabBtnStyle: 0,
            selectPosition: null,
            current_department_id: null,

            canEdit: false,
            isNewPosition: false,
            position: null,
            editdata: null,

            account: null,
            password: null,
            position_level: null,
            mac: null,
            macsplit: [],
            department: null,
            activeTab: 0,
            multiline: true,
            editID: null,
            name: null,
            school: null,
            student_type: null,
            isAddPosition: false,

            editImage: null,
            selectedImageFile: null,
            positionsData: [],
            canEditProfile: false,

            canNewPosition: false,
            deleteshow: true,

            def_avatar: "images/img_profile_default.png",
            sort_avart: "images/sequence_icon.svg",
            isReverse: false,
            //分頁
            pageOfItems: [], //分割後Data
            customLabels: {
                //樣式
                first: "<<",
                last: ">>",
                previous: "<",
                next: ">",
            },
            group_id: null,
            right: [],
            admin_id: null,
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
    computed: {},
    watch: {
        //底層資料更新
        employeeData() {
            this.employeeDataSort = this.employeeData;
            this.search(this.search_val);
        },
        //搜尋關鍵字更新
        search_val() {
            this.search(this.search_val);
        },

        editID(n, o) {
            if (n === o) {
                return;
            }
        },
    },
    created() {
        !sessionStorage.token ? (window.location.pathname = "/") : "";
        this.group_id = sessionStorage.group;
        this.admin_id = sessionStorage.id;
        this.getRight();
    },
    mounted() {
        // this.animate();
        this.school = sessionStorage.school;
        this.student_type = sessionStorage.student_type;
        // this.getDepartments();
        this.getallEmployees();
        this.current_department_id = 0;
    },
    methods: {
        getRight() {
            axios
                .get("right/block", {
                    params: {
                        group_id: this.group_id,
                        tab_id: 2, //page_id :2 所有學生
                    },
                })
                .then((response) => {
                    this.right = response.data;
                });
        },
        //資料切割分頁
        onChangePage(pageOfItems) {
            // update page of items
            this.pageOfItems = pageOfItems;
        },
        xlsxExport() {
            if (this.student_type == 1) {
                console.log("becon");
                var url = "student/becon/export";
            } else if (this.student_type == 2) {
                console.log("face");
                var url = "student/face/export";
            } else if (this.student_type == 3) {
                console.log("becon_face");
                var url = "student/becon_face/export";
            }
            window.open(url + "?school_id=" + this.school);
        },
        xlsxSample() {
            window.open("allEmployees/sample?school_id=" + this.school);
        },
        xlsxImport(e) {
            let uploadFile = e.target.files[0];
            let formData = new FormData();
            if (uploadFile) {
                formData.append("file", uploadFile);
            }
            axios
                .post("student/import", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                })
                .then((response) => {
                    console.log(response.data);
                    if (response.data.result == true) {
                        this.$buefy.toast.open({
                            message: "更新成功",
                            type: "is-success",
                            queue: false,
                        });
                        this.getallEmployees();
                    }
                })
                .catch((error) => {
                    this.$buefy.toast.open({
                        message: error.response.data.error,
                        type: "is-danger",
                        queue: false,
                    });
                });
        },
        sortData(type) {
            var vm = this;
            this.employeeDataSort = this.employeeDataSort.sort(function (a, b) {
                if (type === "tempers") {
                    // console.log("tempers");
                    return vm.isReverse ? b[type] - a[type] : a[type] - b[type];
                }
                if (
                    type === "name" ||
                    "date_time" ||
                    "leave_at" ||
                    "department"
                ) {
                    // console.log("time");
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
        checkTime(item, time) {
            if (typeof item == "object") {
                // console.log("1");
                return true;
            } else {
                var currentTime = moment(moment(item).format("HH:mm"), "HH:mm");
                var startTime = moment(time, "HH:mm");

                if (startTime.isValid()) {
                    // console.log("2");
                    return currentTime.isBefore(startTime);
                } else {
                    // console.log("3");
                    return "no_time";
                }
                // var endTime = moment("08:07", "HH:mm");
            }
        },
        checkLeaveTime(item, time) {
            if (typeof item == "object") {
                return true;
            } else {
                var currentTime = moment(moment(item).format("HH:mm"), "HH:mm");
                var endTime = moment(time, "HH:mm");
                if (endTime.isValid()) {
                    return currentTime.isBefore(endTime);
                } else {
                    return true;
                }
            }
        },
        search(n) {
            this.employeeDataSort = this.employeeData.filter(
                ({ name }) => name.includes(n)
                // {
                //     return name.search(n) != -1;
                // }
            );
        },
        editEmp(item) {
            this.isEditEmp = true;
            this.editdata = item;
        },
        deleteEmp(index) {
            const user_id = index.id;
            const user_name = index.name;
            this.$buefy.snackbar.open({
                message:
                    '要刪除這個<span style="color: red;">' +
                    user_name +
                    "</span>學生嗎？",
                type: "is-info",
                position: "is-top",
                actionText: "好",
                queue: false,
                onAction: () => {
                    this.isLoading = true;
                    axios
                        .post("student/delete", {
                            user_id: user_id,
                            admin_id: this.admin_id,
                        })
                        .then((response) => {
                            if (response.data.result == true) {
                                var index = this.employeeData.findIndex(
                                    (d) => d.id === user_id
                                );
                                this.employeeData.splice(index, 1);
                                this.$buefy.toast.open({
                                    message: "已刪除",
                                    queue: false,
                                });
                            }
                        })
                        .catch((error) => {
                            if (error) {
                                this.$buefy.toast.open({
                                    message: error.response.data.error,
                                    type: "is-danger",
                                    queue: false,
                                });
                            }
                        })
                        .finally(() => {
                            this.isLoading = false;
                        });
                },
            });
        },

        addBtn() {
            this.isAddEmp = true;
        },
        async getallEmployees() {
            try {
                if (this.student_type == 1) {
                    console.log("becon");
                    var url = "student/becon/index";
                } else if (this.student_type == 2) {
                    console.log("face");
                    var url = "student/face/index";
                } else if (this.student_type == 3) {
                    console.log("becon_face");
                    var url = "student/becon_face/index";
                }

                this.isLoading = true;
                const response = await axios.get(url, {
                    params: {
                        school_id: this.school,
                    },
                });
                this.employeeData = response.data;
            } catch (e) {
            } finally {
                this.isLoading = false;
            }
        },
        //check input is alphabet or number
        _valid(value) {
            return /[^\a-\z\A-\Z0-9]/.test(value);
        },
    },
};
</script>

<style lang="scss" scoped>
.notification {
    background-color: #eef1f5;
    margin-top: 30px;
}
span {
    // font-family: Archivo;
    font-size: 16px;
    font-weight: 600;
    font-stretch: normal;
    font-style: normal;
    line-height: normal;
    letter-spacing: normal;
    color: #6c7887;
}
// input {
//     border: 1px solid rgba(206, 206, 208, 0.947);
//     border-radius: 5px;
//     padding: 9px 2vw;
//     // margin: 0 auto;
//     // box-sizing: border-box;
//     background-color: rgb(225, 225, 235);
// }
// input:hover {
//     border: 1px solid rgba(174, 174, 176, 0.947);
// }
// input:invalid {
//     border: 2px solid red;
// }

.department-group {
    background-color: white;
    margin: auto 10px;
    border-radius: 1rem;

    .b-tabs {
        margin-bottom: 4px;

        .tab-content {
            padding: 0;
        }
    }
}

.profile_img {
    border-radius: 50%;
}

.upload_btn {
    border-width: 0;
}

.employee-name {
    display: flex;
    align-items: center;

    img {
        border-radius: 50%;
    }

    span {
        margin-left: 0.5rem;
    }
}
.table .card-body {
    padding: 0;
}
</style>
