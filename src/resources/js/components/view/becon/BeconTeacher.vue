<template>
    <div class="container mt-5">
        <!-- {{ JSON.stringify(teacherData) }} -->
        <!-- {{JSON_Data}} -->
        <div class="card table shadow">
            <div
                class="
                    card-header
                    d-flex
                    flex-column
                    justify-content-between
                    mt-3
                "
            >
                <div class="d-flex flex-row justify-content-between">
                    <b-input
                        v-model="search_val"
                        placeholder="Search"
                        type="search"
                        icon="magnify"
                        icon-clickable
                        expanded
                    ></b-input>
                    <b-select v-model="search_select" class="mr-auto">
                        <option value="name">姓名</option>
                        <option value="phone">電話</option>
                        <option value="mac">MAC</option>
                    </b-select>
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
                                <th>姓名</th>
                                <th>電話</th>
                                <th>MAC</th>
                                <th>電量</th>
                                <th>綁定時間</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in pageOfItems" :key="item.id">
                                <!-- 一般 -->
                                <td>{{ item.name }}</td>
                                <td>{{ item.phone }}</td>
                                <td>{{ item.mac }}</td>
                                <td>{{ item.battery }}</td>
                                <td>{{ item.onboard_date }}</td>
                                <td>
                                    <b-button
                                        class="table-btn pl-0 pr-0"
                                        @click="EditTec(item)"
                                    >
                                        <img
                                            class="rounded-circle"
                                            width="40px"
                                            src="images/edit_icon.svg"
                                        />
                                    </b-button>
                                    <b-button
                                        class="table-btn pl-0 pr-0"
                                        @click="deleteTec(item)"
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
                    :items="teacherDataSort"
                    :pageSize="12"
                    :labels="customLabels"
                    @changePage="onChangePage"
                ></jw-pagination>
            </div>
        </div>

        <b-modal :active.sync="isAddTeac" :width="640" scroll="clip">
            <AddTeac @emprefresh="getallTeacher"></AddTeac>
        </b-modal>
        <b-modal :active.sync="isEditTec" :width="640" scroll="clip">
            <EditTec
                :admin="admin_id"
                :teacher-info="editdata"
                @emprefresh="getallTeacher"
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
import XLSX from "xlsx";
import AddTeac from "./modules/AddTeac";
import EditTec from "./modules/EditTec";
export default {
    components: {
        AddTeac,
        EditTec,
    },
    data: function () {
        return {
            isAddTeac: false,
            search_val: "",
            teacherData: [],
            teacherDataSort: [],
            isLoading: false,
            isEditTec: false,
            editdata: null,
            mac: null,
            department: null,
            name: null,
            school: null,
            search_select: "name",
            teacher_type: null,
            //分頁
            pageOfItems: [], //分割後Data
            customLabels: {
                //樣式
                first: "<<",
                last: ">>",
                previous: "<",
                next: ">",
            },
            admin_id: null,
        };
    },
    watch: {
        teacherData() {
            this.teacherDataSort = this.teacherData;
            this.search(this.search_val);
        },
        search_val() {
            this.search(this.search_val);
        },
        //
    },
    mounted() {
        !sessionStorage.token ? (window.location.pathname = "/") : "";
        // this.animate();
        this.school = sessionStorage.school;
        this.admin_id = sessionStorage.id;
        this.teacher_type = sessionStorage.teacher_type;
        // this.getDepartments();
        this.getallTeacher();
    },
    methods: {
        //資料切割分頁
        onChangePage(pageOfItems) {
            // update page of items
            this.pageOfItems = pageOfItems;
        },
        search(n) {
            if (this.search_select === "name") {
                this.teacherDataSort = this.teacherData.filter(({ name }) =>
                    name.includes(n)
                );
            }
            if (this.search_select === "phone") {
                this.teacherDataSort = this.teacherData.filter(({ phone }) => {
                    if (phone !== null) {
                        return phone.includes(n);
                    } else {
                        if (n === "") {
                            return true;
                        } else {
                            return false;
                        }
                    }
                });
            }
            if (this.search_select === "mac") {
                this.teacherDataSort = this.teacherData.filter(({ mac }) => {
                    if (mac !== null) {
                        return mac.includes(n);
                    } else {
                        if (n === "") {
                            return true;
                        } else {
                            return false;
                        }
                    }
                });
            }
        },
        EditTec(item) {
            // console.log("props", item);
            this.isEditTec = true;
            this.editdata = item;
        },
        deleteTec(index) {
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
                        .post("teacher/delete", {
                            teacher_id: user_id,
                            admin_id: this.admin_id,
                        })
                        .then((response) => {
                            if (response.data.result == true) {
                                var index = this.teacherData.findIndex(
                                    (d) => d.id === user_id
                                );
                                this.teacherData.splice(index, 1);
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
            this.isAddTeac = true;
        },
        async getallTeacher() {
            try {
                this.isLoading = true;
                const response = await axios.get("becon/teacher", {
                    params: {
                        school_id: this.school,
                    },
                });
                this.teacherData = response.data;
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
</style>
