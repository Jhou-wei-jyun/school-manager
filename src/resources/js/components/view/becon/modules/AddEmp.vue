<template>
    <div class="card card-body">
        <header class="card-bottom d-flex align-items-center">
            <p class="h4 has-text-weight-semibold">新增學生</p>
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
                    <b-input type="text" v-model="name" required></b-input>
                </b-field>
                <b-field label="家長">
                    <b-select v-model="selectedParents" required expanded>
                        <option
                            v-for="option in ParentsData"
                            :value="option.parent_id"
                            :key="option.parent_id"
                        >
                            {{ option.name }}
                        </option>
                    </b-select>
                </b-field>
                <b-field label="班級">
                    <b-select v-model="selectedDepartment" expanded>
                        <option
                            v-for="option in departmentsData"
                            :value="option.id"
                            :key="option.id"
                        >
                            {{ option.name }}
                        </option>
                    </b-select>
                </b-field>
                <b-field label="性別">
                    <b-select v-model="selectedGender" expanded required>
                        <option :value="1">男</option>
                        <option :value="2">女</option>
                        <option :value="3"></option>
                    </b-select>
                </b-field>
                <b-field label="Mac" v-show="have_Mac" required>
                    <b-input
                        size="8"
                        placeholder="輸入英文數字共8碼"
                        pattern="[A-Za-z0-9]{8}"
                        maxlength="8"
                        v-model="mac"
                        required
                    />
                </b-field>
                <b-field label="綁定日期" required>
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
    props: ["departId", "admin"],
    data: function () {
        return {
            def_avatar: "images/img_profile_default.png",
            def_camera: "images/btn_camera@2x.png",
            positionsData: [],
            ParentsData: [],
            selectedPosition: null,
            name: null,
            avatar: null,
            nickname: null,
            account: null,
            mac: null,
            onboard_date: moment().format("YYYY-MM-DD"),
            newType: "user",
            selectedGender: null,
            selectedDepartment: null,
            selectedParents: null,
            departmentsData: [],
            selectedImageFile: null,
            editImage: null,
            password: null,
            school: null,
            student_type: null,
            have_Mac: false,
            file: null,
            isLoading: false,
        };
    },
    watch: {},
    mounted() {
        this.school = sessionStorage.school;
        this.student_type = sessionStorage.student_type;
        if (this.student_type == 1 || this.student_type == 3) {
            this.have_Mac = true;
        } else if (this.student_type == 2) {
            this.have_Mac = false;
        }
        this.getParents();
        this.getDepartments();
    },
    methods: {
        Emprefresh() {
            this.$emit("emprefresh");
        },
        getParents() {
            axios
                .get("parent/index", { params: { school_id: this.school } })
                .then((response) => {
                    // console.log('position:' + JSON.stringify(response.data));
                    this.ParentsData = response.data;
                    console.log("this.ParentsData", this.ParentsData);
                })
                .catch((error) => {});
        },
        getDepartments() {
            axios
                .get("student/SelectDepartment", {
                    params: {
                        school_id: this.school,
                        department_id: this.departId,
                    },
                })
                .then((response) => {
                    // console.log('depart:' + JSON.stringify(response.data));
                    this.departmentsData = response.data;
                })
                .catch((error) => {});
        },
        addNewMember() {
            // if (this.student_type == 1) {
            //     console.log("becon");
            //     var url = "student/becon/store";
            // } else if (this.student_type == 2) {
            //     console.log("face");
            //     var url = "student/face/store";
            // } else if (this.student_type == 3) {
            //     console.log("becon_face");
            //     var url = "student/becon_face/store";
            // }
            // console.log(url);
            // console.log("check");
            if (
                this.name === null ||
                this.selectedParents === null ||
                this.selectedGender === null ||
                this.onboard_date === null
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
            if (this.onboard_date) {
                formData.append("start_date", this.onboard_date);
            }
            formData.append("school_id", this.school);
            formData.append("position_id", 10);

            if (this.selectedGender) {
                formData.append("gender", this.selectedGender);
            }
            if (this.editImage) {
                formData.append("avatar", this.editImage);
            }
            if (this.file) {
                formData.append("avatar_file", this.file);
            }

            if (this.selectedParents) {
                formData.append("parent_id", this.selectedParents);
            }
            if (this.selectedDepartment) {
                formData.append("department_id", this.selectedDepartment);
            }

            axios
                .post("student/store", formData, {
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
    border-radius: 50%;
}
</style>
