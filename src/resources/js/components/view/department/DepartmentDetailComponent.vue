<template>
    <div class="container mt-5">
        <!-- {{ employeeData }} -->
        <!-- {{employeeDataSort}} -->
        <!-- {{title}} -->
        <!-- {{department_id}} -->
        <div class="card table shadow">
            <div class="card-header d-flex flex-column">
                <div class="d-flex flex-column mt-3">
                    <div class="d-flex flex-row justify-content-between">
                        <p class="h3">{{ title.depart_name }}</p>
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
                                <a
                                    class="dropdown-item"
                                    @click="editBtn"
                                    v-show="
                                        right['配對'] == null
                                            ? false
                                            : right['配對']['show']
                                    "
                                    >配對學生</a
                                >
                                <a
                                    class="dropdown-item"
                                    @click="editDepart"
                                    v-show="
                                        right['設定'] == null
                                            ? false
                                            : right['設定']['show']
                                    "
                                    >班級設定</a
                                >
                                <a class="dropdown-item" @click="xlsxExport"
                                    >匯出Excel</a
                                >
                                <!-- <a
                                    class="dropdown-item"
                                    @click="$refs.excel.click()"
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

                    <span>班級人數：{{ title.count }}</span>
                    <span>班級導師：{{ title.teacher_name }}</span>

                    <span>時段：{{ title.start }} ~ {{ title.end }}</span>
                </div>
                <div class="d-flex flex-row justify-content-between mt-3">
                    <b-input
                        class="mr-auto"
                        v-model="search_val"
                        placeholder="Search"
                        type="search"
                        icon="magnify"
                        icon-clickable
                        expanded
                    ></b-input>

                    <!-- <b-button
                        size="is-medium"
                        class="notification_btn notification_btn_yellow shadow animate__animated animate__fadeIn ml-auto"
                        @click="editBtn"
                    >分配學生</b-button>-->
                </div>
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
                            <tr v-for="item in pageOfItems" :key="item.name">
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

                                <td v-if="checkTime(item.date_time)">
                                    {{ item.date_time | timeFormat }}
                                </td>
                                <td class="text_color_red" v-else>
                                    {{ item.date_time | timeFormat }}
                                </td>
                                <td
                                    class="text_color_gray200"
                                    v-if="checkLeaveTime(item.leave_at)"
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
                                    <b-button class="table-btn pl-0 pr-0">
                                        <img
                                            class="rounded-circle"
                                            width="40px"
                                            src="images/edit_icon.svg"
                                            @click="editEmp(item)"
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
                                    <b-button class="table-btn pl-0 pr-0">
                                        <img
                                            class="rounded-circle"
                                            width="40px"
                                            src="images/delete_icon.svg"
                                            @click="deleteEmp(item)"
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
        <b-modal :active.sync="isEditDept" :width="350" scroll="clip">
            <EditDept
                :editDeptData="editDeptData"
                :admin="admin_id"
                @onDepartmentrefresh="getTitle"
            ></EditDept>
        </b-modal>
        <b-modal :active.sync="isEditStudents" :width="640" scroll="clip">
            <EditStudents
                :dep-id="department_id"
                :admin="admin_id"
                @onclassrefresh="getEmployees"
            ></EditStudents>
        </b-modal>
        <b-modal :active.sync="isEditEmp" :width="640" scroll="clip">
            <EditEmp
                :student-info="editdata"
                :admin="admin_id"
                @emprefresh="getEmployees"
            ></EditEmp>
        </b-modal>
        <b-modal :active.sync="isAddEmp" :width="640" scroll="clip">
            <AddEmp
                :depart-id="department_id"
                :admin="admin_id"
                @emprefresh="getEmployees"
            ></AddEmp>
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
// import EmpDetail from "./EmpDetail";
import EditStudents from "./modules/EditStudents";
import EditEmp from "./modules/EditEmp";
import EditDept from "./modules/EditDept";
import AddEmp from "./modules/AddEmp";
export default {
    components: {
        // EmpDetail,
        EditStudents,
        EditEmp,
        AddEmp,
        EditDept,
    },
    data: function () {
        return {
            search_val: "",
            employeeData: [],
            employeeDataSort: [],
            editDeptData: [],
            isLoading: false,
            isPaginated: true,
            department_id: null,
            title: [],
            school: null,
            def_avatar: "images/img_profile_default.png",
            sort_avart: "images/sequence_icon.svg",

            employee: null,
            isEmpDetail: false,
            isEditStudents: false,
            isEditDept: false,
            isEditEmp: false,
            isAddEmp: false,
            editdata: null,
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
        employeeData() {
            this.employeeDataSort = this.employeeData;
            console.log("sucess");
            this.search(this.search_val);
        },
        search_val() {
            this.search(this.search_val);
        },
    },
    created() {
        !sessionStorage.token ? (window.location.pathname = "/") : "";
        this.school = sessionStorage.school;
        this.group_id = sessionStorage.group;
        this.admin_id = sessionStorage.id;
        this.student_type = sessionStorage.student_type;
        this.department_id = window.location.href.split("?")[1];
        this.getTitle();
        this.getRight();
    },
    mounted() {
        this.getEmployees();
        // this.getTitle();
    },
    methods: {
        getRight() {
            axios
                .get("right/block", {
                    params: {
                        group_id: this.group_id,
                        tab_id: 14, //tab_id :14 班級詳細
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
        sortData(type) {
            var vm = this;
            this.employeeDataSort = this.employeeDataSort.sort(function (a, b) {
                if (type === "tempers") {
                    // console.log("tempers");
                    return vm.isReverse ? b[type] - a[type] : a[type] - b[type];
                }
                if (type === "name" || "date_time" || "leave_at") {
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
        addBtn() {
            this.isAddEmp = true;
        },
        checkTime(item) {
            if (typeof item == "object") {
                return true;
            } else {
                var currentTime = moment(moment(item).format("HH:mm"), "HH:mm");
                var startTime = moment(this.title.start, "HH:mm");
                // var endTime = moment("08:07", "HH:mm");
                return currentTime.isBefore(startTime);
            }
        },
        checkLeaveTime(item) {
            if (typeof item == "object") {
                return true;
            } else {
                var currentTime = moment(moment(item).format("HH:mm"), "HH:mm");
                var endTime = moment(this.title.end, "HH:mm");
                return currentTime.isBefore(endTime);
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
        showDetail(selected) {
            this.isEmpDetail = true;
            this.employee = selected;
        },
        async getTitle() {
            await axios
                .get("department/detail", {
                    params: {
                        department_id: this.department_id,
                    },
                })
                .then((response) => {
                    this.title = response.data;
                })
                .catch((error) => {})
                .finally(() => {});
        },
        editBtn(selected) {
            this.isEditStudents = true;
            this.employee = selected;
        },
        editDepart() {
            this.isEditDept = true;
            this.editDeptData = this.title;
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
                        .post("department/student/delete", {
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
                                this.getTitle();
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
        async getEmployees() {
            try {
                if (this.student_type == 1) {
                    console.log("becon");
                    var url = "department/student/becon/index";
                } else if (this.student_type == 2) {
                    console.log("face");
                    var url = "department/student/face/index";
                } else if (this.student_type == 3) {
                    console.log("becon_face");
                    var url = "department/student/becon_face/index";
                }
                this.isLoading = true;
                await axios
                    .get(url, {
                        params: {
                            department_id: this.department_id,
                            school_id: this.school,
                        },
                    })
                    .then((response) => {
                        this.employeeData = response.data;
                    })
                    .catch((error) => {});
            } catch (e) {
            } finally {
                this.getTitle();
                this.isLoading = false;
            }
        },
        xlsxSample() {
            window.open(
                "department/detail/sample?department_id=" +
                    this.department_id +
                    "&school_id=" +
                    this.school
            );
        },
        xlsxExport() {
            if (this.student_type == 1) {
                console.log("becon");
                var url = "student/becon/department/export";
            } else if (this.student_type == 2) {
                console.log("face");
                var url = "student/face/department/export";
            } else if (this.student_type == 3) {
                console.log("becon_face");
                var url = "student/becon_face/department/export";
            }
            window.open(
                url +
                    "?department_id=" +
                    this.department_id +
                    "&school_id=" +
                    this.school
            );
            // axios
            //     .get("department/detail/export", {
            //         params: {
            //             department_id: this.department_id,
            //             school_id: this.school,
            //         },
            //     })
            //     .then((response) => {
            //         // console.log(response);
            //         window.open(
            //             "department/detail/export?department_id=" +
            //                 this.department_id +
            //                 "&school_id=" +
            //                 this.school
            //         );
            //     })
            //     .catch((error) => {
            //         this.$buefy.toast.open({
            //             message: "下載失敗請聯繫相關人員協助處理",
            //             type: "is-danger",
            //             queue: false,
            //         });
            //     });
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
                        this.getEmployees();
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
