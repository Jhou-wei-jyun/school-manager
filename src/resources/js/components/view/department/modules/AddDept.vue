<template>
    <div class="card card-body">
        <header class="card-bottom d-flex align-items-center">
            <p class="h4 has-text-weight-semibold">新增班級</p>
        </header>
        <div id="main">
            <!-- <div class="red d-flex justify-content-center">
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
                            @change="onImageChange"
                        />
                    </label>
                </div>
            </div> -->
            <b-field label="部門">
                <b-select
                    class="text_center"
                    size="is-middle"
                    placeholder="-選擇-"
                    v-model="selectedDepart"
                    expanded
                >
                    <option
                        v-for="option in departs"
                        :value="option.id"
                        :key="option.id"
                    >
                        {{ option.name }}
                    </option>
                    <option value="addDepart">新增部門...</option>
                </b-select>
            </b-field>
            <b-field label="班級名稱">
                <b-input type="text" v-model="name" required></b-input>
            </b-field>
            <!-- 老師 -->
            <b-field label="老師">
                <b-select
                    class="text_center"
                    size="is-middle"
                    placeholder="-選擇-"
                    v-model="selectedOption"
                    expanded
                >
                    <option
                        v-for="option in supervisors"
                        :value="option.id"
                        :key="option.id"
                    >
                        {{ option.name }}
                    </option>
                </b-select>
            </b-field>
            <b-field label="課堂時間">
                <div class="row">
                    <b-timepicker
                        class="col-5 text_center"
                        placeholder="起始"
                        :incrementMinutes="minutesGranularity"
                        :incrementHours="hoursGranularity"
                        v-model="startTime"
                        required
                    >
                    </b-timepicker>

                    <div class="col-2 align-self-center">–––</div>
                    <b-timepicker
                        v-if="startTime !== null"
                        class="col-5 text_center"
                        placeholder="結束"
                        :incrementMinutes="minutesGranularity"
                        :incrementHours="hoursGranularity"
                        v-model="finishTime"
                        :min-time="minTime"
                        required
                    >
                    </b-timepicker>
                    <b-select
                        v-else
                        expanded
                        class="col-5 text_center"
                        placeholder="結束"
                        required
                    >
                    </b-select>
                </div>
            </b-field>
        </div>
        <footer class="card-bottom d-flex align-items-center">
            <b-button
                class="
                    notification_btn
                    notification_btn_gray
                    notification_btn_text_white
                    ml-auto
                    mr-2
                "
                size="is-medium"
                @click="$parent.close()"
                >取消</b-button
            >
            <b-button
                class="
                    notification_btn
                    notification_btn_sky
                    notification_btn_text_white
                "
                size="is-medium"
                @click="addNewDepartment()"
                >新增</b-button
            >
        </footer>
        <b-loading
            :active.sync="isLoading"
            :is-full-page="true"
            v-model="isLoading"
            :can-cancel="false"
        ></b-loading>
        <b-modal :active.sync="addDepartModel" :width="350" scroll="clip">
            <add-depart
                :school="school"
                @refreshDepart="getDeparts"
            ></add-depart>
        </b-modal>
    </div>
</template>

<script>
import moment from "moment";
import AddDepart from "./AddDepart.vue";
export default {
    components: {
        AddDepart,
    },
    data: function () {
        return {
            def_avatar: "images/img_department_default@2x.png",
            def_camera: "images/btn_camera@2x.png",
            supervisors: [],
            departs: [],
            name: null,
            startTime: null,
            finishTime: null,
            editImage: null,
            selectedDepart: null,
            selectedOption: null,
            school: null,
            minutesGranularity: 15,
            hoursGranularity: 1,
            minHour: null,
            minMin: null,
            minTime: null,
            file: null,
            isLoading: false,
            admin_id: null,
            addDepartModel: false,
        };
    },
    computed: {
        startTimeFormat() {
            if (this.startTime === null) {
                return null;
            } else {
                let myDate = new Date(Date.parse(this.startTime));
                let H = myDate.getHours();
                let M = myDate.getMinutes();
                H = H.toString().padStart(2, "0");
                M = M.toString().padEnd(2, "0");
                let realDate = H + ":" + M;
                this.minHour = myDate.getHours();
                this.minMin = myDate.getMinutes();
                return realDate;
            }
        },
        finishTimeFormat() {
            if (this.finishTime === null) {
                return null;
            } else {
                let myDate = new Date(Date.parse(this.finishTime));
                let H = myDate.getHours();
                let M = myDate.getMinutes();
                H = H.toString().padStart(2, "0");
                M = M.toString().padEnd(2, "0");
                let realDate = H + ":" + M;
                return realDate;
            }
        },
    },
    watch: {
        startTimeFormat() {
            const min = new Date();
            min.setHours(this.minHour);
            min.setMinutes(this.minMin);
            this.minTime = min;
        },
        selectedDepart(n, o) {
            if (n == "addDepart") {
                this.addDepartModel = !this.addDepartModel;
                this.selectedDepart = null;
            } else {
            }
        },
    },
    mounted() {
        !sessionStorage.token ? (window.location.pathname = "/") : "";
        this.school = sessionStorage.school;
        this.admin_id = sessionStorage.id;
        this.getDeparts();
        this.getSupervisors();
    },
    methods: {
        // checkFile(file) {
        //     let result = true;
        //     const SIZE_LIMIT = 2097152; // 2B
        //     if (!file) {
        //         result = false;
        //     }
        //     if (file.type !== "image/jpeg") {
        //         result = false;
        //     }
        //     if (file.size > SIZE_LIMIT) {
        //         this.$buefy.toast.open({
        //             message: "檔案上限5MB",
        //             type: "is-danger",
        //             queue: false,
        //         });
        //         result = false;
        //     }
        //     return result;
        // },
        // getBase64(file) {
        //     return new Promise((resolve, reject) => {
        //         const reader = new FileReader();
        //         reader.readAsDataURL(file);
        //         reader.onload = () => resolve(reader.result);
        //         reader.onerror = (error) => reject(error);
        //     });
        // },
        // async onImageChange(e) {
        //     const files = e.target.files || e.dataTransfer.files;
        //     const file = files[0];
        //     if (this.checkFile(file)) {
        //         const image = await this.getBase64(file);
        //         this.editImage = image;
        //     }
        // },
        // async handleFileUpload(e) {
        //     const files = e.target.files || e.dataTransfer.files;
        //     const file = files[0];
        //     if (this.checkFile(file)) {
        //         const image = await this.getBase64(file);
        //         this.editImage = image;
        //         this.file = file;
        //         console.log("get", this.file);
        //     }
        // },
        Departmentrefresh() {
            this.$emit("onDepartmentrefresh");
        },
        getDeparts() {
            axios
                .get("department/SelectDepart", {
                    params: { school_id: this.school },
                })
                .then((response) => {
                    this.departs = response.data;
                })
                .catch((error) => {})
                .finally(() => {
                    this.selectedDepart = null;
                });
        },
        getSupervisors() {
            axios
                .get("department/SelectTeacher", {
                    params: { school_id: this.school },
                })
                .then((response) => {
                    this.supervisors = response.data;
                })
                .catch((error) => {});
        },
        addNewDepartment() {
            if (this.name === null) {
                return;
            }
            if (this.startTimeFormat === null) {
                return;
            }
            if (this.selectedOption === null) {
                return;
            }
            if (this.finishTimeFormat === null) {
                return;
            }
            this.isLoading = true;
            let formData = new FormData();
            if (this.admin_id) {
                formData.append("admin_id", this.admin_id);
            }
            if (this.name) {
                formData.append("name", this.name);
            }
            if (this.startTime) {
                formData.append("startTime", this.startTimeFormat);
            }
            if (this.selectedDepart) {
                formData.append("selectDepart", this.selectedDepart);
            }
            if (this.selectedOption) {
                formData.append("selectteacher", this.selectedOption);
            }
            if (this.finishTime) {
                formData.append("finishTime", this.finishTimeFormat);
            }
            // if (this.editImage) {
            //     formData.append("editImage", this.editImage);
            // }
            // if (this.file) {
            //     formData.append("avatar_file", this.file);
            // }
            if (this.school) {
                formData.append("school_id", this.school);
            }
            axios
                .post("department/store", formData, {
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
                        this.isNewDepartment = false;
                        this.Departmentrefresh();
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
    },
};
</script>

<style lang="scss" scoped>
// #main {
//     height: 100%;
//     display: flex;

//     // background-color: turquoise;
//     .red {
//         width: 30%;
//     }

//     .blue {
//         width: 60%;
//     }

//     .green {
//         width: 10%;
//     }
// }

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
.text_center {
    text-align-last: center !important;
}
.edit_img {
    border-radius: 50%;
}
</style>
