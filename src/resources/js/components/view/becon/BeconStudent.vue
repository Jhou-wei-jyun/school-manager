<template>
    <div class="container mt-5">
        <!-- {{ JSON.stringify(employeeData) }} -->
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
                                <th>家長電話</th>
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
                                <td>{{ item.parent_phone }}</td>
                                <td>{{ item.mac }}</td>
                                <td>{{ item.battery }}</td>
                                <td>{{ item.onboard_date }}</td>
                                <td>
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
            <AddEmp @emprefresh="getallEmployees"></AddEmp>
        </b-modal>
        <b-modal :active.sync="isEditEmp" :width="640" scroll="clip">
            <EditEmp
                :admin="admin_id"
                :student-info="editdata"
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
import XLSX from "xlsx";
import AddEmp from "./modules/AddEmp";
import EditEmp from "./modules/EditEmp";
export default {
    components: {
        AddEmp,
        EditEmp,
    },
    data: function () {
        return {
            isAddEmp: false,
            search_val: "",
            employeeData: [],
            employeeDataSort: [],
            isLoading: false,
            isEditEmp: false,
            editdata: null,
            mac: null,
            department: null,
            name: null,
            school: null,
            search_select: "name",
            student_type: null,
            //分頁
            pageOfItems: [], //分割後Data
            customLabels: {
                //樣式
                first: "<<",
                last: ">>",
                previous: "<",
                next: ">",
            },
            // file_path: "",
            admin_id: null,
        };
    },
    watch: {
        employeeData() {
            this.employeeDataSort = this.employeeData;
            this.search(this.search_val);
        },
        search_val() {
            this.search(this.search_val);
        },
        //
    },
    mounted() {
        !sessionStorage.token ? (window.location.pathname = "/") : "";
        this.school = sessionStorage.school;
        this.admin_id = sessionStorage.id;
        this.student_type = sessionStorage.student_type;
        this.getallEmployees();
    },
    methods: {
        //資料切割分頁
        onChangePage(pageOfItems) {
            // update page of items
            this.pageOfItems = pageOfItems;
        },
        //
        search(n) {
            if (this.search_select === "name") {
                this.employeeDataSort = this.employeeData.filter(({ name }) =>
                    name.includes(n)
                );
            }
            if (this.search_select === "phone") {
                this.employeeDataSort = this.employeeData.filter(
                    ({ phone }) => {
                        if (phone !== null) {
                            return phone.includes(n);
                        } else {
                            if (n === "") {
                                return true;
                            } else {
                                return false;
                            }
                        }
                    }
                );
            }
            if (this.search_select === "mac") {
                this.employeeDataSort = this.employeeData.filter(({ mac }) => {
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
        editEmp(item) {
            // console.log("props", item);
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
                this.isLoading = true;
                const response = await axios.get("becon/student", {
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
</style>
