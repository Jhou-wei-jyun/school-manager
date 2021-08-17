<template>
    <div class="container">
        <div class="d-flex mt-4 mb-4">
            <b-button
                size="is-medium"
                class="
                    notification_btn notification_btn_yellow
                    shadow
                    animate__animated animate__fadeIn
                    ml-auto
                "
                v-show="right['新增'] == null ? false : right['新增']['show']"
                @click="addBtn()"
                >新增</b-button
            >
        </div>
        <div v-for="(depart_item, index) in departmentIsFiltered" :key="index">
            <span>{{ index }}</span>

            <hr class="divider my-0" />

            <div class="row d-flex flex-row">
                <div
                    class="col-lg-3 col-md-4 col-sm-6"
                    style="padding: 15px 15px"
                    v-for="depart in depart_item"
                    :key="depart.id"
                >
                    <div class="card">
                        <div class="card-body ml-auto mr-auto">
                            <!-- <div class="topbar-divider d-none d-sm-block"></div> -->
                            <div>
                                <img
                                    :src="depart.avatar || def_card"
                                    width="150"
                                    style="height: 150px"
                                    class="image"
                                    @click="assignTag(depart.id)"
                                />
                                <a
                                    class="card-delete fix"
                                    v-show="
                                        right['刪除'] == null
                                            ? false
                                            : right['刪除']['show']
                                    "
                                    @click="delBtn(depart)"
                                >
                                    <i class="far fa-times-circle"></i>
                                </a>
                                <!-- Nav Item - User Information -->
                                <div
                                    class="nav-item dropdown no-arrow img-edit"
                                >
                                    <a
                                        class="nav-link dropdown-toggle"
                                        href="#"
                                        id="userDropdown"
                                        role="button"
                                        data-toggle="dropdown"
                                        aria-haspopup="true"
                                        aria-expanded="false"
                                        v-show="
                                            right['編輯'] == null
                                                ? false
                                                : right['編輯']['show']
                                        "
                                    >
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <!-- Dropdown - User Information -->
                                    <div
                                        class="
                                            dropdown-menu dropdown-menu-right
                                            shadow
                                            animated--grow-in
                                        "
                                        aria-labelledby="userDropdown"
                                    >
                                        <a class="dropdown-item item-plus">
                                            <label>
                                                <i
                                                    class="fas fa-plus fa-5x"
                                                ></i>
                                                <input
                                                    v-show="false"
                                                    type="file"
                                                    accept="image/jpeg"
                                                    @change="handleFileUpload"
                                                />
                                            </label>
                                        </a>
                                        <a
                                            class="dropdown-item"
                                            v-for="item in def_ilst"
                                            :key="item.id"
                                        >
                                            <img
                                                :src="item"
                                                @click="
                                                    img_change(depart.id, item)
                                                "
                                                width="60"
                                                style="height: 60px"
                                                class="image"
                                            />
                                        </a>
                                        <a
                                            class="dropdown-item"
                                            v-for="item in img_ilst"
                                            :key="item.id"
                                        >
                                            <img
                                                v-if="item != null"
                                                :src="item.avatar"
                                                @click="
                                                    img_change(
                                                        depart.id,
                                                        item.avatar
                                                    )
                                                "
                                                width="60"
                                                style="height: 60px"
                                                class="image"
                                            />
                                            <a
                                                @click="delete_img(item.id)"
                                                class="img-close"
                                            >
                                                <i
                                                    class="far fa-times-circle"
                                                ></i>
                                            </a>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <span class="ml-auto mr-auto">{{
                                depart.name
                            }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <b-modal :active.sync="isNewDepartment" :width="350" scroll="clip">
            <AddDept @onDepartmentrefresh="getDepartments"></AddDept>
        </b-modal>
        <b-modal :active.sync="isDelDepartment" :width="300" scroll="clip">
            <DelDept
                :depart-info="delData"
                :admin="admin_id"
                @onDepartmentrefresh="getDepartments"
            ></DelDept>
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
import AddDept from "./modules/AddDept";
import EditDept from "./modules/EditDept";
import DelDept from "./modules/DelDept";
export default {
    components: {
        AddDept,
        EditDept,
        DelDept,
    },
    data: function () {
        return {
            //
            // current_img
            def_card: "/images/card_class_penguin.png",
            def_ilst: [
                "/images/card_class_penguin.png",
                "/images/card_class_cat.png",
                "/images/card_class_bear.png",
                "/images/card_class_bee.png",
            ],
            img_ilst: [],
            save_img: null,
            //
            departmentsData: [],
            isNewDepartment: false,
            isDelDepartment: false,
            name: null,
            startTime: null,
            finishTime: null,
            selectedOption: null,
            isLoading: false,

            defaultAvatar: "/images/Dept_photo.png",
            NewsupervisorAvatar:
                "/images/no_photo.png?7a2abcedac26a797c465e2ba216b8780",

            selected: null,
            school: null,
            editdeptdata: null,
            isEditDepartment: false,
            delData: null,
            file: null,
            teacher_id: null,
            group_id: null,
            right: [],
            admin_id: null,
        };
    },
    watch: {
        selectedOption(a, b) {
            if (a == null) {
                return;
            }
            if (a === b) {
                return;
            }
            this.NewsupervisorAvatar = a.avatar ? a.avatar : defaultAvatar;
        },
    },
    created() {
        //sessionStorage　出來是string
        if (sessionStorage.teacher_id !== "null") {
            this.teacher_id = Number(sessionStorage.teacher_id);
        }
        this.group_id = sessionStorage.group;
        this.getRight();
        this.admin_id = sessionStorage.id;
    },
    mounted() {
        !sessionStorage.token ? (window.location.pathname = "/") : "";
        window.addEventListener("scroll", this.handleScroll);
        this.school = sessionStorage.school;
        this.getDepartments();
        this.get_img();
    },
    computed: {
        departmentIsFiltered: function () {
            if (this.teacher_id === null) {
                return this.departmentsData;
            } else {
                let teacher = this.teacher_id;
                let filtered = {};
                for (let [key, value] of Object.entries(this.departmentsData)) {
                    let result = value.filter(
                        (v) => v.supervisor_id === teacher
                    );
                    filtered[key] = result;
                }
                return filtered;
            }
        },
    },
    methods: {
        teacherFiltered(obj) {},
        getRight() {
            axios
                .get("right/block", {
                    params: {
                        group_id: this.group_id,
                        tab_id: 1, //page_id :1 所有班級
                    },
                })
                .then((response) => {
                    this.right = response.data;
                });
        },
        refresh() {
            this.$emit("refresh");
        },
        delete_img(id) {
            this.isLoading = true;
            axios
                .put("department/avatar/delete", {
                    avatar_id: id,
                    admin_id: this.admin_id,
                })
                .then((response) => {
                    if (response.data.result == true) {
                        this.$buefy.toast.open({
                            message: "已刪除",
                            queue: false,
                        });
                        this.get_img();
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
        img_change(id, item) {
            this.isLoading = true;
            axios
                .post("department/avatar/change", {
                    department_id: id,
                    admin_id: this.admin_id,
                    avatar: item,
                })
                .then((response) => {
                    if (response.data.result == true) {
                        this.getDepartments();
                        this.refresh();
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
        async get_img() {
            await axios
                .get("department/avatar/index", {
                    params: { school_id: this.school },
                })
                .then((response) => {
                    this.img_ilst = response.data;
                });
        },
        updateIMG() {
            let formData = new FormData();
            if (this.school) {
                formData.append("school_id", this.school);
            }
            if (this.admin_id) {
                formData.append("admin_id", this.admin_id);
            }
            if (this.file) {
                formData.append("avatar_file", this.file);
            }
            this.isLoading = true;
            axios
                .post("department/avatar/store", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                })
                .then((response) => {
                    if (response.data.result == true) {
                        this.$buefy.toast.open({
                            message: "上傳成功",
                            type: "is-success",
                            queue: false,
                        });
                        this.get_img();
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
        checkFile(file) {
            let result = true;
            const SIZE_LIMIT = 5242880; // 5MB
            if (!file) {
                result = false;
            }
            if (file.type !== "image/jpeg") {
                result = false;
            }
            if (file.size > SIZE_LIMIT) {
                this.$buefy.toast.open({
                    message: "檔案上限5MB",
                    type: "is-danger",
                    queue: false,
                });
                result = false;
            }
            return result;
        },
        getBase64(file) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = () => resolve(reader.result);
                reader.onerror = (error) => reject(error);
            });
        },
        // async onImageChange(e) {
        //     const files = e.target.files || e.dataTransfer.files;
        //     const file = files[0];
        //     if (this.checkFile(file)) {
        //         const image = await this.getBase64(file);
        //         this.save_img = image;
        //         this.updateIMG();
        //     }
        // },
        async handleFileUpload(e) {
            const files = e.target.files || e.dataTransfer.files;
            const file = files[0];
            if (this.checkFile(file)) {
                const image = await this.getBase64(file);
                this.save_img = image;
                this.file = file;
                this.updateIMG();
                console.log("get", this.file);
            }
        },
        assignTag(id) {
            window.open("departmentDetail?" + id);
        },

        addBtn() {
            this.isNewDepartment = true;
        },
        delBtn(depart) {
            this.isDelDepartment = true;
            this.delData = depart;
        },
        getDepartments() {
            axios
                .get("department/index", { params: { school_id: this.school } })
                .then((response) => {
                    this.departmentsData = response.data;
                })
                .catch({});
        },
        editdept(props) {
            this.isEditDepartment = true;
            this.editdeptdata = props;
        },
    },
};
</script>

<style lang="scss" scoped>
//
.span {
    font-size: 20px;
    font-weight: bold;
}
.divider {
    filter: alpha(opacity=100, finishopacity=0, style=2);
    height: 6px;
}
.dropdown-menu {
    width: clamp(200px, 50vw, 502px);

    &.show {
        display: grid;
        // grid-auto-rows: 100px;
        // grid-auto-columns: 100px;
        // grid-template-columns: repeat(auto-fill, 100px);
        grid-template-columns: repeat(auto-fill, 100px) [last-col] 100px;
        grid-template-rows: auto [last-line];
    }
}
a.dropdown-item {
    // grid-area: auto;
    padding-right: 16px;
}
.item-plus {
    grid-column: last-col / span 1;
    grid-row: 1 / last-line;
}
.img-close {
    position: absolute;
    top: 0;
}
.img-edit {
    position: absolute;
    bottom: 50px;
    right: 0;
}
.card-delete {
    position: absolute;
    top: 0;
    right: 0;
}
.fix {
    padding: 8px 16px;
}
</style>
