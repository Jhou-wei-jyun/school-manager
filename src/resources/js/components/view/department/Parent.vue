<template>
    <div class="container mt-5">
        <!-- {{ JSON.stringify(employeeData) }} -->
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
                            >新增家長</a
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
                                <th>
                                    序號
                                    <img
                                        :src="sort_avart"
                                        alt
                                        width="15"
                                        @click="sortData('parent_id')"
                                    />
                                </th>
                                <th>
                                    家長姓名
                                    <img
                                        :src="sort_avart"
                                        alt
                                        width="15"
                                        @click="sortData('name')"
                                    />
                                </th>
                                <th>
                                    手機號碼
                                    <img
                                        :src="sort_avart"
                                        alt
                                        width="15"
                                        @click="sortData('phone')"
                                    />
                                </th>
                                <th>
                                    學生姓名
                                    <img
                                        :src="sort_avart"
                                        alt
                                        width="15"
                                        @click="sortData('student_name')"
                                    />
                                </th>
                                <th
                                    v-show="
                                        right['綁定'] == null
                                            ? false
                                            : right['綁定']['show']
                                    "
                                >
                                    綁定
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
                            <tr
                                v-for="item in pageOfItems"
                                :key="item.parent_id"
                            >
                                <!-- 一般 -->
                                <td>{{ item.parent_id }}</td>

                                <td>{{ item.name }}</td>
                                <td>{{ item.phone }}</td>

                                <td>
                                    <p
                                        v-for="n in item.student_name"
                                        :key="n.id"
                                    >
                                        {{ n }}
                                    </p>
                                </td>
                                <td
                                    v-show="
                                        right['綁定'] == null
                                            ? false
                                            : right['綁定']['show']
                                    "
                                >
                                    <b-button
                                        class="table-btn pl-0 pr-0"
                                        @click="showDetail(item)"
                                    >
                                        <img
                                            class="rounded-circle"
                                            width="50px"
                                            src="images/pair_icon.svg"
                                        />
                                    </b-button>
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
                                        @click="editParent(item)"
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
                                        @click="deleteParent(item)"
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
        <b-modal :active.sync="isPairDetail" :width="640" scroll="clip">
            <StudentsDetail
                :admin="admin_id"
                :emp-info="employee"
                @Parentrefresh="getEmployees"
            ></StudentsDetail>
        </b-modal>
        <b-modal :active.sync="isNewParents" :width="350" scroll="clip">
            <AddParents
                :admin="admin_id"
                @onDepartmentrefresh="getEmployees"
            ></AddParents>
        </b-modal>
        <b-modal :active.sync="isEditParents" :width="350" scroll="clip">
            <EidtParents
                :admin="admin_id"
                :parent-info="editparentdata"
                @Parentrefresh="getEmployees"
            ></EidtParents>
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
import AddParents from "./modules/AddParents";
import StudentsDetail from "./modules/StudentsDetail";
import EidtParents from "./modules/EditParents";
export default {
    components: {
        AddParents,
        StudentsDetail,
        EidtParents,
    },
    data: function () {
        return {
            employeeData: [],
            employeeDataSort: [],
            search_val: "",
            isLoading: false,
            isNewParents: false,
            isEditParents: false,
            editparentdata: null,

            isPaginated: true,
            department_id: null,
            title: null,
            avatar: "images/img_profile_default.png",
            sort_avart: "images/sequence_icon.svg",
            employee: null,
            isPairDetail: false,
            isReverse: false,

            school: null,
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
    computed: {},
    watch: {
        //
        employeeData() {
            this.employeeDataSort = this.employeeData;
            this.search(this.search_val);
        },
        search_val() {
            this.search(this.search_val);
        },
        //
    },
    created() {
        this.group_id = sessionStorage.group;
        this.admin_id = sessionStorage.id;
        this.getRight();
    },
    mounted() {
        !sessionStorage.token ? (window.location.pathname = "/") : "";
        this.department_id = window.location.href.split("?")[1];
        this.school = sessionStorage.school;
        this.getEmployees();
        //this.getTitle();
    },
    methods: {
        getRight() {
            axios
                .get("right/block", {
                    params: {
                        group_id: this.group_id,
                        tab_id: 3, //page_id :3 所有家長
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
        //
        xlsxExport() {
            // let newWindow = window.open();
            window.open("parent/export?school_id=" + this.school);
        },
        xlsxSample() {
            window.open("parentslist/sample?school_id=" + this.school);
        },
        xlsxImport(e) {
            let uploadFile = e.target.files[0];
            let formData = new FormData();
            if (uploadFile) {
                formData.append("file", uploadFile);
            }
            axios
                .post("parentslist/import", formData, {
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
        //
        sortData(type) {
            var vm = this;
            this.employeeDataSort = this.employeeDataSort.sort(function (a, b) {
                if (type === "parent_id") {
                    // console.log("tempers");
                    return vm.isReverse ? b[type] - a[type] : a[type] - b[type];
                }
                if (type === "name" || "phone" || "student_name") {
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
        search(n) {
            this.employeeDataSort = this.employeeData.filter(({ name }) =>
                name.includes(n)
            );
        },
        //
        deleteParent(department) {
            // console.log('delete id:' + id);
            this.$buefy.snackbar.open({
                message:
                    '要刪除這個<span style="color: red;">' +
                    department.name +
                    "</span>家長嗎？",
                type: "is-warning",
                position: "is-top",
                actionText: "好",
                queue: false,
                onAction: () => {
                    this.isLoading = true;
                    axios
                        .put("parent/delete", {
                            parent_id: department.parent_id,
                            admin_id: this.admin_id,
                        })
                        .then((response) => {
                            console.log(response.data);
                            if (response.data.result == true) {
                                var index = this.employeeData.findIndex(
                                    (d) => d.parent_id === department.parent_id
                                );
                                console.log("index:" + index);
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
        showDetail(item) {
            this.isPairDetail = true;
            this.employee = item;
        },
        addBtn() {
            this.isNewParents = true;
        },
        editParent(item) {
            this.editparentdata = item;
            this.isEditParents = true;
        },
        async getEmployees() {
            try {
                this.isLoading = true;
                const url = "parent/index";
                const response = await axios.get(url, {
                    params: {
                        school_id: this.school,
                    },
                });
                console.log(response.data);
                this.employeeData = response.data;
            } catch (e) {
            } finally {
                this.isLoading = false;
            }
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
    font-family: Archivo;
    font-size: 16px;
    font-weight: 500;
    font-stretch: normal;
    font-style: normal;
    line-height: normal;
    letter-spacing: normal;
    color: #6c7887;
}

.b-table.department-table .table th {
    padding-left: 24px;
}

.table tr.is-selected {
    background-color: transparent !important;
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
