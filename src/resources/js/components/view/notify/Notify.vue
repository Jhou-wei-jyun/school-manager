<template>
    <div class="container mt-5">
        <div class="card card-body">
            <div class="d-flex flex-column justify-content-between">
                <div class="row mt-5">
                    <div class="col-3 d-flex flex-column text-right">
                        <span class="h4 mb-4">對象</span>
                        <!-- <span
                            v-if="picked == 'teacher' || picked == 'parent'"
                            class="pt-4 pb-1 mb-4"
                        ></span>-->
                        <span class="h4 mb-4">緊急</span>
                        <span class="h4 mb-4">類別</span>
                        <span class="h4 mb-4">標題</span>
                        <span class="h4" style="margin-bottom: 125px"
                            >內容</span
                        >
                        <!-- <span class="h4">附件</span> -->
                    </div>
                    <div class="col-9 d-flex flex-column pr-5">
                        <div class="mb-2">
                            <p class="pt-1">
                                <span
                                    v-show="
                                        right['所有人'] == null
                                            ? false
                                            : right['所有人']['show']
                                    "
                                    ><input
                                        type="radio"
                                        value="all"
                                        v-model="picked"
                                    />所有人</span
                                >
                                <span
                                    v-show="
                                        right['教師'] == null
                                            ? false
                                            : right['教師']['show']
                                    "
                                    ><input
                                        type="radio"
                                        value="teacher"
                                        v-model="picked"
                                    />教師</span
                                >
                                <span
                                    v-show="
                                        right['家長'] == null
                                            ? false
                                            : right['家長']['show']
                                    "
                                    ><input
                                        type="radio"
                                        value="parent"
                                        v-model="picked"
                                    />家長</span
                                >
                            </p>

                            <!-- <b-select icon="account" v-model="selectUser" expanded>
                            <option value="0">All</option>-->
                            <!-- <option
                                    v-for="option in users"
                                    :value="option.user_id"
                                    :key="option.user_id"
                            >{{ option.user_name }}</option>-->
                            <!-- </b-select> -->
                        </div>
                        <!-- <div v-if="picked == 'teacher' || picked == 'parent'" class="mb-3">
                            <b-select
                                v-if="picked == 'teacher'"
                                icon="account"
                                v-model="selectTeacher"
                                expanded
                            >
                                <option value="0">All</option>
                                <option
                                    v-for="option in teachers"
                                    :value="option.user_id"
                                    :key="option.user_id"
                                >{{ option.user_name }}</option>
                            </b-select>
                            <b-select
                                v-if="picked == 'parent'"
                                icon="account"
                                v-model="selectParent"
                                expanded
                            >
                                <option value="0">All</option>
                                <option
                                    v-for="option in parents"
                                    :value="option.user_id"
                                    :key="option.user_id"
                                >{{ option.user_name }}</option>
                            </b-select>
                        </div>-->
                        <div class="mb-2">
                            <p class="pt-2">
                                <span
                                    ><input
                                        type="radio"
                                        value="default"
                                        v-model="selectType"
                                    />普通</span
                                >
                                <span
                                    ><input
                                        type="radio"
                                        value="emergency"
                                        v-model="selectType"
                                    />重要</span
                                >
                            </p>
                        </div>
                        <div class="mb-3">
                            <b-select v-model="type" expanded required>
                                <option value="[校園通知]">校園通知</option>
                                <option value="[重要通知]">重要通知</option>
                                <option value="[繳費通知]">繳費通知</option>
                                <option value="[系統通知]">系統通知</option>
                            </b-select>
                        </div>
                        <div class="mb-3">
                            <b-input
                                v-model="title"
                                expanded
                                required
                            ></b-input>
                        </div>
                        <div class="mb-2">
                            <b-input
                                type="textarea"
                                v-model="message"
                                maxlength="1000"
                                expanded
                                required
                            ></b-input>
                        </div>
                        <!-- 附件 -->
                        <!-- <div class="d-flex flex-row">
                            <label>
                                <b-button
                                    @click="$refs.fileA.click()"
                                    size="is-small"
                                    class="notification_btn notification_btn_text_white animate__animated animate__fadeIn"
                                    :class="colorcheckA"
                                >檔案1</b-button>
                                <input
                                    ref="fileA"
                                    v-show="false"
                                    type="file"
                                    accept="image/jpeg, image/png"
                                    @change="AonImageChange"
                                />
                            </label>
                            <label class="ml-2">
                                <b-button
                                    @click="$refs.fileB.click()"
                                    size="is-small"
                                    class="notification_btn notification_btn_text_white animate__animated animate__fadeIn"
                                    :class="colorcheckB"
                                >檔案2</b-button>
                                <input
                                    ref="fileB"
                                    v-show="false"
                                    type="file"
                                    accept="image/jpeg, image/png"
                                    @change="BonImageChange"
                                />
                            </label>
                            <label class="ml-2">
                                <b-button
                                    @click="$refs.fileC.click()"
                                    size="is-small"
                                    class="notification_btn notification_btn_text_white animate__animated animate__fadeIn"
                                    :class="colorcheckC"
                                >檔案3</b-button>
                                <input
                                    ref="fileC"
                                    v-show="false"
                                    type="file"
                                    accept="image/jpeg, image/png"
                                    @change="ConImageChange"
                                />
                            </label>
                        </div>-->
                    </div>

                    <footer class="card-bottom">
                        <b-button
                            v-if="picked == 'all'"
                            size="is-medium"
                            class="notification_btn notification_btn_blue shadow animate__animated animate__fadeIn mt-5"
                            @click="sendAllNotify"
                            >發送</b-button
                        >
                        <b-button
                            v-if="picked == 'teacher'"
                            size="is-medium"
                            class="notification_btn notification_btn_blue shadow animate__animated animate__fadeIn mt-5"
                            @click="sendTeacherNotify"
                            >發送</b-button
                        >
                        <b-button
                            v-if="picked == 'parent'"
                            size="is-medium"
                            class="notification_btn notification_btn_blue shadow animate__animated animate__fadeIn mt-5"
                            @click="sendParentNotify"
                            >發送</b-button
                        >
                    </footer>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["admin"],
    data: function () {
        return {
            teachers: [],
            parents: [],
            type: null,
            title: null,
            message: null,
            confirmToSend: false,
            // selectAll: 0,
            selectType: null,
            user: null,
            school: null,
            picked: null,
            // token: [],
            // A_file: "",
            // B_file: "",
            // C_file: "",
            group_id: null,
            right: [],
        };
    },
    computed: {
        type_title: function () {
            return this.type + this.title;
        },
        // colorcheckA: function () {
        //     if (this.A_file === "") {
        //         return {
        //             notification_btn_sky: false,
        //             notification_btn_gray: true,
        //         };
        //     } else {
        //         return {
        //             notification_btn_sky: true,
        //             notification_btn_gray: false,
        //         };
        //     }
        // },
        // colorcheckB: function () {
        //     if (this.B_file === "") {
        //         return {
        //             notification_btn_sky: false,
        //             notification_btn_gray: true,
        //         };
        //     } else {
        //         return {
        //             notification_btn_sky: true,
        //             notification_btn_gray: false,
        //         };
        //     }
        // },
        // colorcheckC: function () {
        //     if (this.C_file === "") {
        //         return {
        //             notification_btn_sky: false,
        //             notification_btn_gray: true,
        //         };
        //     } else {
        //         return {
        //             notification_btn_sky: true,
        //             notification_btn_gray: false,
        //         };
        //     }
        // },
    },
    created() {
        this.group_id = sessionStorage.group;
        this.getRight();
    },
    watch: {},
    mounted() {
        !sessionStorage.token ? (window.location.pathname = "/") : "";
        this.user = sessionStorage.id;
        this.school = sessionStorage.school;
        // this.getTeachers();
        // this.getParents();
    },
    methods: {
        getRight() {
            axios
                .get("right/block", {
                    params: {
                        group_id: this.group_id,
                        tab_id: 6, //page_id :6 訊息推送
                    },
                })
                .then((response) => {
                    this.right = response.data;
                });
        },
        getTeachers() {
            axios
                .get("notify/teacher?id=" + this.school)
                .then((response) => {
                    // console.log('users:' + JSON.stringify(response.data));
                    this.teachers = response.data;
                })
                .catch((error) => {});
        },
        getParents() {
            axios
                .get("notify/parent?id=" + this.school)
                .then((response) => {
                    // console.log('users:' + JSON.stringify(response.data));
                    this.parents = response.data;
                })
                .catch((error) => {});
        },
        sendTeacherNotify() {
            if (
                this.type === null ||
                this.title === null ||
                this.message === null
            ) {
                return;
            }
            let formData = new FormData();
            if (this.admin) {
                formData.append("admin_id", this.admin);
            }
            if (this.user) {
                formData.append("sent_id", this.user);
            }
            if (this.type_title) {
                formData.append("title", this.type_title);
            }
            if (this.message) {
                formData.append("message", this.message);
            }
            if (this.selectType) {
                formData.append("type", this.selectType);
            }
            if (this.school) {
                formData.append("school_id", this.school);
            }
            // if (this.A_file) {
            //     formData.append("A_file", this.A_file);
            // }
            // if (this.B_file) {
            //     formData.append("B_file", this.B_file);
            // }
            // if (this.C_file) {
            //     formData.append("C_file", this.C_file);
            // }
            if (this.picked) {
                formData.append("target", this.picked);
            }

            axios
                .post("notify/teacher", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                })
                .then((response) => {
                    console.log("succeed:" + JSON.stringify(response.data));
                    this.$buefy.toast.open({
                        message: "推播成功",
                        type: "is-success",
                        queue: false,
                    });
                    this.type = null;
                    this.title = null;
                    this.message = null;
                    this.selectUser = null;
                    this.selectType = null;
                })
                .catch((error) => {
                    this.$buefy.toast.open({
                        message: "推播失敗，請聯絡專業人員處理",
                        type: "is-danger",
                        queue: false,
                    });
                })
                .finally(() => {
                    this.NotifyRefresh();
                    // this.A_file = "";
                    // this.B_file = "";
                    // this.C_file = "";
                });
        },
        sendParentNotify() {
            if (
                this.type === null ||
                this.title === null ||
                this.message === null
            ) {
                return;
            }
            let formData = new FormData();
            if (this.admin) {
                formData.append("admin_id", this.admin);
            }
            if (this.user) {
                formData.append("sent_id", this.user);
            }
            if (this.type_title) {
                formData.append("title", this.type_title);
            }
            if (this.message) {
                formData.append("message", this.message);
            }
            if (this.selectType) {
                formData.append("type", this.selectType);
            }
            if (this.school) {
                formData.append("school_id", this.school);
            }
            // if (this.A_file) {
            //     formData.append("A_file", this.A_file);
            // }
            // if (this.B_file) {
            //     formData.append("B_file", this.B_file);
            // }
            // if (this.C_file) {
            //     formData.append("C_file", this.C_file);
            // }
            if (this.picked) {
                formData.append("target", this.picked);
            }

            axios
                .post("notify/parent", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                })
                .then((response) => {
                    console.log("succeed:" + JSON.stringify(response.data));
                    this.$buefy.toast.open({
                        message: "推播成功",
                        type: "is-success",
                        queue: false,
                    });
                    this.type = null;
                    this.title = null;
                    this.message = null;
                    this.selectUser = null;
                    this.selectType = null;
                })
                .catch((error) => {
                    this.$buefy.toast.open({
                        message: "推播失敗，請聯絡專業人員處理",
                        type: "is-danger",
                        queue: false,
                    });
                })
                .finally(() => {
                    this.NotifyRefresh();
                    // this.A_file = "";
                    // this.B_file = "";
                    // this.C_file = "";
                });
        },
        sendAllNotify() {
            if (
                this.type === null ||
                this.title === null ||
                this.message === null
            ) {
                return;
            }
            let formData = new FormData();
            if (this.admin) {
                formData.append("admin_id", this.admin);
            }
            if (this.user) {
                formData.append("sent_id", this.user);
            }
            if (this.type_title) {
                formData.append("title", this.type_title);
            }
            if (this.message) {
                formData.append("message", this.message);
            }
            // if (this.selectAll) {
            //     formData.append("user", this.selectAll);
            // }
            if (this.selectType) {
                formData.append("type", this.selectType);
            }
            if (this.school) {
                formData.append("school_id", this.school);
            }
            // if (this.A_file) {
            //     formData.append("A_file", this.A_file);
            // }
            // if (this.B_file) {
            //     formData.append("B_file", this.B_file);
            // }
            // if (this.C_file) {
            //     formData.append("C_file", this.C_file);
            // }
            if (this.picked) {
                formData.append("target", this.picked);
            }

            axios
                .post("notify/all", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                })
                .then((response) => {
                    console.log("succeed:" + JSON.stringify(response.data));
                    this.$buefy.toast.open({
                        message: "推播成功",
                        type: "is-success",
                        queue: false,
                    });
                    // this.token = response.data;
                    this.type = null;
                    this.title = null;
                    this.message = null;
                    this.selectType = null;
                })
                .catch((error) => {
                    this.$buefy.toast.open({
                        message: "推播失敗，請聯絡專業人員處理",
                        type: "is-danger",
                        queue: false,
                    });
                })
                .finally(() => {
                    this.NotifyRefresh();
                    // this.A_file = "";
                    // this.B_file = "";
                    // this.C_file = "";
                });
        },
        NotifyRefresh() {
            this.$emit("NotifyRefresh");
        },
        checkFile(file) {
            let result = true;
            const SIZE_LIMIT = 5242880; // 5MB
            if (!file) {
                result = false;
            }
            if (file.type !== "image/jpeg" && file.type !== "image/png") {
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
        // async AonImageChange(e) {
        //     const files = e.target.files || e.dataTransfer.files;
        //     const file = files[0];
        //     // this.A = file.name;
        //     if (this.checkFile(file)) {
        //         const image = await this.getBase64(file);
        //         this.A_file = image;
        //     }
        // },
        // async BonImageChange(e) {
        //     const files = e.target.files || e.dataTransfer.files;
        //     const file = files[0];
        //     // this.B = file.name;
        //     if (this.checkFile(file)) {
        //         const image = await this.getBase64(file);
        //         this.B_file = image;
        //     }
        // },
        // async ConImageChange(e) {
        //     const files = e.target.files || e.dataTransfer.files;
        //     const file = files[0];
        //     // this.C = file.name;
        //     if (this.checkFile(file)) {
        //         const image = await this.getBase64(file);
        //         this.C_file = image;
        //     }
        // },
    },
};
</script>

<style lang="scss" scoped>
.card {
    box-shadow: none;
    width: 60vw;
}
// .select select,
// .textarea,
// .input,
// .taginput .taginput-container.is-focusable {
//     background-color: #ffffff !important;
// }
</style>
