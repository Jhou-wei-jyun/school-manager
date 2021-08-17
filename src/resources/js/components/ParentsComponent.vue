<template>
    <div class="container" style="background-color: #eef1f5">
        <div class="notification">
            <b-button
                size="is-medium"
                type="is-primary"
                class="notification_btn animate__animated animate__fadeIn"
                @click="addBtn"
                >Add a new Parents.</b-button
            >
            <span style="font-size: 24px; color: #181f38; font-weight: bold"
                >Parents</span
            >
            <br />
            <span style="font-size: 14px"
                >shows all the Parents information for overviewing.</span
            >
        </div>

        <b-table
            :data="employeeData"
            :loading="isLoading"
            mobile-cards
            class="department-table"
        >
            <template slot-scope="props">
                <b-table-column field label="ID" width="100">
                    <span>{{ props.row.parent_id }}</span>
                </b-table-column>
                <b-table-column field label="Name" width="180">
                    <div class="employee-name">
                        <span>{{ props.row.name }}</span>
                    </div>
                </b-table-column>
                <b-table-column field label="Phone" centered>
                    <span>{{ props.row.phone }}</span>
                </b-table-column>

                <b-table-column field label centered>
                    <b-dropdown aria-role="list">
                        <p
                            class="tag has-text-weight-semibold"
                            slot="trigger"
                            role="button"
                            style="
                                border-width: 0;
                                background-color: #fff;
                                font-size: 25px;
                                font-weight: 600;
                            "
                        >
                            ...
                        </p>

                        <b-dropdown-item
                            class="has-text-weight-semibold"
                            aria-role="listitem"
                            @click="editparent(props.row)"
                            >Edit</b-dropdown-item
                        >
                        <b-dropdown-item
                            class="has-text-weight-semibold"
                            aria-role="listitem"
                            @click="showDetail(props.row)"
                            >Pair Child</b-dropdown-item
                        >
                        <b-dropdown-item
                            class="has-text-weight-semibold"
                            style="color: red"
                            aria-role="listitem"
                            @click="deleteDepartment(props.row)"
                            >Delete</b-dropdown-item
                        >
                    </b-dropdown>
                </b-table-column>
            </template>

            <template slot="empty">
                <section class="section">
                    <div class="content has-text-grey has-text-centered">
                        <p>
                            <b-icon
                                icon="emoticon-sad"
                                size="is-large"
                            ></b-icon>
                        </p>
                        <p>Nothing here.</p>
                    </div>
                </section>
            </template>
        </b-table>
        <b-modal :active.sync="isEmpDetail" :width="640" scroll="clip">
            <StudentsDetail :emp-info="employee"></StudentsDetail>
            <!-- <EmpDetail :emp-info="employee"></EmpDetail> -->
        </b-modal>
        <b-modal :active.sync="isNewParents" :width="640" scroll="clip">
            <AddParents @onDepartmentrefresh="getEmployees"></AddParents>
        </b-modal>
        <b-modal :active.sync="isEditParents" :width="640" scroll="clip">
            <EidtParents
                :parent-info="editparentdata"
                @onDepartmentrefresh="getEmployees"
            ></EidtParents>
        </b-modal>
    </div>
</template>

<script>
import AddParents from "./AddParents";
import StudentsDetail from "./StudentsDetail";
import EidtParents from "./EditParents";
export default {
    components: {
        AddParents,
        StudentsDetail,
        EidtParents,
    },
    data: function () {
        return {
            employeeData: [],
            isLoading: false,
            isPaginated: true,
            department_id: null,
            title: null,
            avatar: "images/img_profile_default.png",
            isNewParents: false,
            isEditParents: false,
            employee: null,
            isEmpDetail: false,
            editparentdata: null,
            school: null,
        };
    },
    watch: {},
    mounted() {
        !sessionStorage.token ? (window.location.pathname = "/") : "";
        this.department_id = window.location.href.split("?")[1];
        this.school = sessionStorage.school;
        this.getEmployees();
        //this.getTitle();
    },
    methods: {
        deleteDepartment(department) {
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
                    console.log("onAction");
                    axios
                        .put("delparents?parent_id=" + department.parent_id)
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
                        });
                },
            });
        },
        showDetail(selected) {
            this.isEmpDetail = true;
            this.employee = selected;
        },
        addBtn() {
            this.isNewParents = true;
        },
        editparent(props) {
            console.log("props", props);
            this.isEditParents = true;
            this.editparentdata = props;
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
.notification_btn {
    float: right;
    font-size: 14px;
}
</style>
