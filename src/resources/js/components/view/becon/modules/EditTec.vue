<template>
    <div class="card card-body">
        <header class="card-bottom d-flex align-items-center">
            <p class="h4 has-text-weight-semibold">編輯</p>
        </header>
        <div id="main">
            <div class="red d-flex justify-content-center">
                <div class="profile-img">
                    <img
                        class="edit_img"
                        width="125"
                        style="height: 125px"
                        :src="editData.avatar || def_avatar"
                    />
                    <label style="height: 30px">
                        <img
                            class="profile-camera"
                            width="30"
                            :src="def_camera"
                            alt
                        />
                        <input
                            v-show="false"
                            type="file"
                            accept="image/jpeg"
                            @change="handleFileUpload"
                        />
                    </label>
                </div>
            </div>
            <div class="blue">
                <b-field label="姓名">
                    <b-input
                        type="text"
                        v-model="editData.name"
                        required
                    ></b-input>
                </b-field>
                <b-field label="電話">
                    <b-input
                        size="10"
                        placeholder="輸入電話號碼共10碼"
                        pattern="[0][9][0-9]{8}"
                        maxlength="10"
                        v-model="editData.phone"
                        required
                    />
                </b-field>
                <b-field label="Mac" v-show="have_Mac">
                    <b-input
                        size="8"
                        placeholder="輸入英文數字共8碼"
                        pattern="[A-Za-z0-9]{8}"
                        maxlength="8"
                        v-model="editMac"
                        required
                    />
                </b-field>
                <b-field label="性別">
                    <b-select
                        placeholder="Select a gender"
                        v-model="editData.gender"
                        expanded
                    >
                        <option :value="1">男</option>
                        <option :value="2">女</option>
                        <option :value="3"></option>
                    </b-select>
                </b-field>
            </div>
        </div>
        <footer class="card-bottom d-flex align-items-center">
            <b-button
                class="notification_btn notification_btn_sky notification_btn_text_white mr-auto"
                size="is-medium"
                @click="updateAvatar()"
                >編輯圖片</b-button
            >
            <b-button
                class="notification_btn notification_btn_gray notification_btn_text_white ml-auto mr-2"
                size="is-medium"
                @click="$parent.close()"
                >取消</b-button
            >

            <b-button
                class="notification_btn notification_btn_sky notification_btn_text_white"
                size="is-medium"
                @click="updateTeacher()"
                >編輯</b-button
            >
        </footer>
        <b-loading
            :active.sync="isLoading"
            :is-full-page="true"
            v-model="isLoading"
            :can-cancel="false"
        ></b-loading>
    </div>
</template>

<script>
export default {
    props: ["teacherInfo", "admin"],
    data: function () {
        return {
            def_avatar: "images/img_department_default@2x.png",
            def_camera: "images/btn_camera@2x.png",
            editData: this.teacherInfo,
            editMac: null,
            school: null,
            have_Mac: false,
            file: null,
            isLoading: false,
        };
    },
    mounted() {
        this.school = sessionStorage.school;
        this.teacher_type = sessionStorage.teacher_type;
        if (this.editData.mac) {
            this.have_Mac = true;
            this.editMac = this.editData.mac.split(":").join("");
        }
    },
    methods: {
        Emprefresh() {
            this.$emit("emprefresh");
        },
        updateTeacher() {
            if (this.editData.name === null || this.editData.gender === null) {
                return;
            }
            if (this.have_Mac) {
                if (this.editMac === null || this.editMac.length < 8) {
                    return;
                }
                if (this._valid(this.editMac)) {
                    return;
                }
            }

            if (!this._phone_valid(this.editData.phone)) {
                console.log(!this._phone_valid(this.editData.phone));
                return;
            }
            this.isLoading = true;
            let formData = new FormData();
            if (this.admin) {
                formData.append("admin_id", this.admin);
            }
            if (this.editData.id) {
                formData.append("teacher_id", this.editData.id);
            }
            if (this.editData.name) {
                formData.append("name", this.editData.name);
            }
            if (this.editData.gender) {
                formData.append("gender", this.editData.gender);
            }
            if (this.editMac) {
                formData.append("mac", this.editMac);
            }
            if (this.editData.phone) {
                formData.append("phone", this.editData.phone);
            }
            formData.append("school_id", this.school);
            // if (this.teacher_type == 1) {
            //     console.log("becon");
            //     var url = "teacher/becon/update";
            // } else if (this.teacher_type == 2) {
            //     console.log("face");
            //     var url = "teacher/face/update";
            // } else if (this.teacher_type == 3) {
            //     console.log("becon_face");
            //     var url = "teacher/becon_face/update";
            // }
            // 新增
            axios
                .post("teacher/update/info", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                })
                .then((response) => {
                    this.$buefy.toast.open({
                        message: "更新成功",
                        type: "is-success",
                        queue: false,
                    });
                    this.Emprefresh();
                })
                .catch((error) => {
                    this.$buefy.toast.open({
                        message: error.response.data.error,
                        type: "is-danger",
                        queue: false,
                    });
                })
                .finally(() => {
                    this.isLoading = false;
                    this.$parent.close();
                });
        },
        updateAvatar() {
            this.isLoading = true;
            let formData = new FormData();
            if (this.admin) {
                formData.append("admin_id", this.admin);
            }
            if (this.editData.id) {
                formData.append("teacher_id", this.editData.id);
            }
            if (this.editData.avatar) {
                formData.append("avatar", this.editData.avatar);
            }
            if (this.file) {
                formData.append("avatar_file", this.file);
            }
            formData.append("school_id", this.school);
            // 新增
            axios
                .post("teacher/update/avatar", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                })
                .then((response) => {
                    this.$buefy.toast.open({
                        message: "更新成功",
                        type: "is-success",
                        queue: false,
                    });
                    this.Emprefresh();
                })
                .catch((error) => {
                    this.$buefy.toast.open({
                        message: error.response.data.error,
                        type: "is-danger",
                        queue: false,
                    });
                })
                .finally(() => {
                    this.isLoading = false;
                    this.$parent.close();
                });
        },
        //check input is alphabet or number
        _valid(value) {
            const Regex = /[^\a-\z\A-\Z0-9]/;
            return Regex.test(value);
        },
        _phone_valid(value) {
            const Regex = /[0][9][0-9]{8}/;
            return Regex.test(value);
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
        //         this.editData.avatar = image;
        //         // console.log("123", image);
        //     }
        // },
        async handleFileUpload(e) {
            const files = e.target.files || e.dataTransfer.files;
            const file = files[0];
            if (this.checkFile(file)) {
                const image = await this.getBase64(file);
                this.editData.avatar = image;
                this.file = file;
                console.log("get", this.file);
            }
        },
    },
};
</script>

<style lang="scss" scoped>
#main {
    height: 100%;
    display: flex;

    // background-color: turquoise;
    .red {
        width: 30%;
    }

    .blue {
        width: 60%;
    }

    .green {
        width: 10%;
    }
}

.card-bottom {
    width: 100%;
    height: 100px;

    .card-bottom-button {
        float: right;
        right: 1rem;
        margin-top: 40px;
    }
}

.card-bottom {
    width: 100%;
    height: 100px;

    .card-bottom-button {
        float: right;
        right: 1rem;
        margin-top: 40px;
        text-decoration: none;
    }
}

.edit_img {
    position: relative;
    border-radius: 50%;
}
</style>
