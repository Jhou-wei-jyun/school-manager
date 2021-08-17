<template>
    <div class="container mt-5">
        <!-- {{teacherData}} -->
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
                            >新增教師</a
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
                                    最後更新時間
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

                                <td>{{ item.leave_at | timeFormat }}</td>
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
                                            @click="editTec(item)"
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
                                            @click="deleteTeac(item)"
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
                    :items="teacherDataSort"
                    :pageSize="12"
                    :labels="customLabels"
                    @changePage="onChangePage"
                ></jw-pagination>
            </div>
        </div>

        <b-modal :active.sync="isAddTeac" :width="640" scroll="clip">
            <AddTeac :admin="admin_id" @emprefresh="emprefresh"></AddTeac>
        </b-modal>
        <b-modal :active.sync="isEditTec" :width="640" scroll="clip">
            <EditTec
                :admin="admin_id"
                :teacher-info="editdata"
                @emprefresh="emprefresh"
            ></EditTec>
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
import AddTeac from "./modules/AddTeac";
import EditTec from "./modules/EditTec";
export default {
    components: {
        AddTeac,
        EditTec,
    },
    props: ["teacherData"],
    data: function () {
        return {
            isAddTeac: false,
            isEditTec: false,
            isLoading: false,
            def_avatar: "images/img_profile_default.png",
            sort_avart: "images/sequence_icon.svg",
            search_val: "",
            def_data: [],
            teacherDataSort: [],
            editdata: null,

            //
            canEditProfile: false,

            tabBtnStyle: 0,
            selectPosition: null,

            canEdit: false,
            isNewPosition: false,
            position: null,
            isEditProfile: false,
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

            isAddPosition: false,

            // editImage: null,
            selectedImageFile: null,

            positionsData: [],
            canNewPosition: false,
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
    created() {
        this.group_id = sessionStorage.group;
        this.admin_id = sessionStorage.id;
        this.getRight();
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
        teacherData() {
            this.def_data = this.teacherData;
            this.search(this.search_val);
        },
        search_val() {
            this.search(this.search_val);
        },
        editID(n, o) {
            if (n === o) {
                return;
            }
        },
        position(n, o) {
            if (n === o) {
                return;
            }
        },
    },
    mounted() {
        !sessionStorage.token ? (window.location.pathname = "/") : "";
        // this.animate();
        this.school = sessionStorage.school;
        this.teacher_type = sessionStorage.teacher_type;
    },
    methods: {
        getRight() {
            axios
                .get("right/block", {
                    params: {
                        group_id: this.group_id,
                        tab_id: 5, //page_id :5 教師考勤
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
            if (this.teacher_type == 1) {
                console.log("becon");
                var url = "teacher/becon/export/time";
            } else if (this.teacher_type == 2) {
                console.log("face");
                var url = "teacher/face/export/time";
            } else if (this.teacher_type == 3) {
                console.log("becon_face");
                var url = "teacher/becon_face/export/time";
            }
            window.open(url + "?school_id=" + this.school);
        },
        xlsxSample() {
            window.open("allTeachers/sample?school_id=" + this.school);
        },
        xlsxImport(e) {
            let uploadFile = e.target.files[0];
            let formData = new FormData();
            if (uploadFile) {
                formData.append("file", uploadFile);
            }
            axios
                .post("allTeachers/import", formData, {
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
                        this.emprefresh();
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
            this.teacherDataSort = this.teacherDataSort.sort(function (a, b) {
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
        checkTime(item) {
            if (typeof item == "object") {
                return true;
            } else {
                var currentTime = moment(moment(item).format("HH:mm"), "HH:mm");
                var startTime = moment("09:07", "HH:mm");
                // var endTime = moment("08:07", "HH:mm");
                return currentTime.isBefore(startTime);
            }
        },
        emprefresh() {
            this.$emit("emprefresh");
        },
        search(n) {
            this.teacherDataSort = this.def_data.filter(({ name }) =>
                name.includes(n)
            );
        },
        editTec(item) {
            // console.log("props", item);
            this.isEditTec = true;
            this.editdata = item;
        },
        deleteTeac(index) {
            const user_id = index.id;
            const user_name = index.name;
            this.$buefy.snackbar.open({
                message:
                    '要刪除這個<span style="color: red;">' +
                    user_name +
                    "</span>老師嗎？",
                type: "is-info",
                position: "is-top",
                actionText: "好",
                queue: false,
                onAction: () => {
                    this.isLoading = true;
                    axios
                        .post("teacher/delete", {
                            teacher_id: user_id,
                            admin_id: this.admin_id,
                        })
                        .then((response) => {
                            if (response.data.result == true) {
                                var index = this.def_data.findIndex(
                                    (d) => d.id === user_id
                                );
                                this.def_data.splice(index, 1);
                                this.$buefy.toast.open({
                                    message: "已刪除",
                                    queue: false,
                                });
                            }
                        })
                        .catch((error) => {
                            if (error) {
                                this.$buefy.toast.open({
                                    duration: 5000,
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
            // window.location.href = 'newEmployee?user';
            this.isAddTeac = true;
        },
        //check input is alphabet or number
        _valid(value) {
            return /[^\a-\z\A-\Z0-9]/.test(value);
        },
    },
};
</script>

<style lang="scss" scoped>
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
input {
    border: 1px solid rgba(206, 206, 208, 0.947);
    border-radius: 5px;
    padding: 9px 2vw;
    // margin: 0 auto;
    // box-sizing: border-box;
    background-color: rgb(225, 225, 235);
}
input:hover {
    border: 1px solid rgba(174, 174, 176, 0.947);
}

.department-group {
    background-color: white;
    margin: 5px 5px;
    padding: 10px;
    border-radius: 10px;

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
