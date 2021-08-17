<template>
    <div class="card table shadow">
        <b-modal :active.sync="isAddTeac" :width="640" scroll="clip">
            <AddTeac :admin="admin_id" @emprefresh="getUser"></AddTeac>
        </b-modal>
        <b-modal :active.sync="isEditTec" :width="640" scroll="clip">
            <EditTec
                :admin="admin_id"
                :teacher-info="editdata"
                @emprefresh="getUser"
            ></EditTec>
        </b-modal>
        <!-- {{sortOrders}} -->
        <div class="card-header d-flex flex-row justify-content-between mt-5">
            <b-input
                class="mr-auto"
                v-model="search_val"
                placeholder="Search"
                type="search"
                icon="magnify"
                icon-clickable
                expanded
            ></b-input>
            <b-button
                size="is-medium"
                class="
                    notification_btn notification_btn_yellow
                    shadow
                    animate__animated animate__fadeIn
                "
                @click="addBtn()"
                v-show="right['新增'] == null ? false : right['新增']['show']"
                >新增</b-button
            >
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
                            <th>姓名</th>
                            <th>職稱</th>
                            <th>帳號</th>
                            <th>電話</th>
                            <th>權限</th>
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
                        <tr v-for="item in userDataShow" :key="item.id">
                            <td>
                                <img
                                    class="avatar"
                                    width="40px;"
                                    height="40px;"
                                    :src="item.avatar || def_avatar"
                                />
                            </td>
                            <td>{{ item.name }}</td>
                            <td>{{ item.position }}</td>
                            <td>{{ item.account }}</td>
                            <td>{{ item.phone }}</td>
                            <td>{{ item.group_name }}</td>
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
                                        @click="editUser(item)"
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
        <b-loading
            :active.sync="isLoading"
            :is-full-page="true"
            v-model="isLoading"
            :can-cancel="false"
        ></b-loading>
    </div>
</template>

<script>
import AddTeac from "./modules/AddTeac";
import EditTec from "./modules/EditTec";
export default {
    components: {
        AddTeac,
        EditTec,
    },
    props: ["right", "admin"],
    data: function () {
        return {
            isLoading: false,
            def_avatar: "images/img_profile_default.png",
            search_val: "",
            userData: [],
            userDataShow: [],
            isAddTeac: false,
            isEditTec: false,
            editdata: [],
            school: null,
            admin_id: this.admin,
        };
    },
    computed: {
        sortOrders: function () {
            return Object.keys(this.userData).forEach((key) => {
                this.userData[key] = 1;
            });
        },
    },
    watch: {
        userData() {
            this.search(this.search_val);
        },
        search_val() {
            this.search(this.search_val);
        },
    },
    mounted() {
        this.school = sessionStorage.school;
        this.teacher_type = sessionStorage.teacher_type;
        this.getUser();
    },
    methods: {
        search(n) {
            this.userDataShow = this.userData.filter(({ name }) =>
                name.includes(n)
            );
        },
        // sort(key):  {

        //         this.userDataShow = this.userDataShow.slice().sort(function (a, b) {
        //             a = a[key];
        //             b = b[key];
        //             return (a === b ? 0 : a > b ? 1 : -1) * this.sortOrders[key];
        //         });

        // },
        editUser(item) {
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
                                var index = this.userDataShow.findIndex(
                                    (d) => d.id === user_id
                                );
                                this.userDataShow.splice(index, 1);
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
        async getUser() {
            await axios
                .get("account/user", {
                    params: {
                        school_id: this.school,
                    },
                })
                .then((response) => {
                    this.userData = response.data;
                });
        },
        addBtn() {
            this.isAddTeac = true;
        },
    },
};
</script>

<style lang="scss" scoped>
.avatar {
    border-radius: 50%;
}
</style>
