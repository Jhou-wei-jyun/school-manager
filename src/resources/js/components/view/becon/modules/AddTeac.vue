<template>
    <div class="card card-body">
        <header class="card-bottom d-flex align-items-center">
            <p class="h4 has-text-weight-semibold">新增教師</p>
        </header>
        <div id="main">
            <div class="red d-flex justify-content-center">
                <div class="profile-img">
                    <img
                        class="edit_img"
                        width="125"
                        style="height: 125px"
                        :src="editImage || def_avatar"
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
                    <b-input size="is-default" v-model="name" required />
                </b-field>

                <b-field label="職稱">
                    <b-select v-model="selectedPosition" expanded>
                        <option
                            v-for="option in positionsData"
                            :value="option"
                            :key="option.id"
                        >
                            {{ option.name }}
                        </option>
                    </b-select>
                </b-field>
                <b-field label="性別">
                    <b-select v-model="selectedGender" expanded>
                        <option :value="1">男</option>
                        <option :value="2">女</option>
                        <option :value="3"></option>
                    </b-select>
                </b-field>
                <b-field label="電話">
                    <b-input
                        type="text"
                        v-model="phone"
                        required
                        size="10"
                        pattern="[0][9][0-9]{8}"
                        maxlength="10"
                    ></b-input>
                </b-field>
                <b-field label="Mac" v-show="have_Mac">
                    <b-input
                        size="is-default"
                        placeholder="輸入英文數字共8碼"
                        pattern="[A-Za-z0-9]{8}"
                        maxlength="8"
                        v-model="mac"
                    />
                </b-field>
                <b-field label="綁定日期">
                    <b-input
                        size="is-default"
                        type="text"
                        placeholder="yyyy-mm-dd"
                        v-model="onboard_date"
                        required
                    />
                </b-field>
            </div>
            <div class="green"></div>
        </div>
        <footer class="card-bottom d-flex align-items-center">
            <b-button
                class="notification_btn notification_btn_gray notification_btn_text_white ml-auto mr-2"
                size="is-medium"
                @click="$parent.close()"
                >取消</b-button
            >
            <b-button
                class="notification_btn notification_btn_sky notification_btn_text_white"
                size="is-medium"
                @click="addNewMember()"
                >新增</b-button
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
import moment from "moment";
export default {
    props: ["admin"],
    data: function () {
        return {
            def_avatar: "images/img_profile_default.png",
            def_camera: "images/btn_camera@2x.png",
            positionsData: [],
            ParentsData: [],
            name: null,
            avatar: null,
            // nickname: null,
            account: null,
            mac: null,
            onboard_date: moment().format("YYYY-MM-DD"),
            selectedGender: null,
            selectedPosition: null,
            selectedParents: null,
            positionsData: [],
            selectedImageFile: null,
            editImage: null,
            password: null,
            school: null,
            phone: null,
            teacher_type: null,
            have_Mac: false,
            file: null,
            isLoading: false,
        };
    },
    watch: {},
    mounted() {
        !sessionStorage.token ? (window.location.pathname = "/") : "";
        this.school = sessionStorage.school;
        this.teacher_type = sessionStorage.teacher_type;
        if (this.teacher_type == 1 || this.teacher_type == 3) {
            this.have_Mac = true;
        } else if (this.teacher_type == 2) {
            this.have_Mac = false;
        }
        this.getPosition();
    },
    methods: {
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
        //         this.editImage = image;
        //     }
        // },
        async handleFileUpload(e) {
            const files = e.target.files || e.dataTransfer.files;
            const file = files[0];
            if (this.checkFile(file)) {
                const image = await this.getBase64(file);
                this.editImage = image;
                this.file = file;
                console.log("get", this.file);
            }
        },
        Emprefresh() {
            this.$emit("emprefresh");
        },
        getPosition() {
            axios
                .get("teacher/SelectPosition")
                .then((response) => {
                    // console.log('depart:' + JSON.stringify(response.data));
                    this.positionsData = response.data;
                })
                .catch((error) => {});
        },
        addNewMember() {
            console.log("check");
            if (
                this.name === null ||
                this.selectedDepartment === null ||
                this.selectedGender === null ||
                this.onboard_date === null ||
                this.phone === null
            ) {
                return;
            }
            if (this.have_Mac) {
                if (this.mac === null || this.mac.length < 8) {
                    return;
                }
                if (this._valid(this.mac)) {
                    return;
                }
            }
            if (this.phone != null) {
                if (this.phone.length < 10) {
                    return;
                }
                if (!this._phone_valid(this.phone)) {
                    return;
                }
            }
            console.log("ok");
            this.isLoading = true;
            let formData = new FormData();
            if (this.admin) {
                formData.append("admin_id", this.admin);
            }
            if (this.name) {
                formData.append("name", this.name);
            }
            if (this.mac) {
                formData.append("mac", this.mac);
            }
            if (this.phone) {
                formData.append("phone", this.phone);
            }
            if (this.onboard_date) {
                formData.append("start_date", this.onboard_date);
            }
            if (this.selectedPosition) {
                formData.append("position_id", this.selectedPosition.id);
            }
            if (this.selectedGender) {
                formData.append("gender", this.selectedGender);
            }
            if (this.editImage) {
                formData.append("avatar", this.editImage);
            }
            if (this.file) {
                formData.append("avatar_file", this.file);
            }
            formData.append("school_id", this.school);
            axios
                .post("teacher/store", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                })
                .then((response) => {
                    if (response.data.result == true) {
                        this.$buefy.toast.open({
                            message: "新增成功",
                            type: "is-success",
                            queue: false,
                        });
                        this.Emprefresh();
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
                    this.$parent.close();
                });
        },
        //check input is alphabet or number
        _valid(value) {
            return /[^\a-\z\A-\Z0-9]/.test(value);
        },
        _phone_valid(value) {
            const Regex = /[0][9][0-9]{8}/;
            return Regex.test(value);
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
