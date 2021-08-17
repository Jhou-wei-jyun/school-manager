<template>
    <div class="card card-body">
        <header class="card-bottom d-flex align-items-center">
            <p class="h4 has-text-weight-semibold">班級設定</p>
        </header>

        <div>
            <b-field label="班級名稱">
                <b-input
                    type="text"
                    v-model="editData.depart_name"
                    required
                ></b-input>
            </b-field>
            <!-- 老師 -->
            <b-field label="老師">
                <b-select
                    size="is-middle"
                    v-model="editData.teacher_id"
                    expanded
                >
                    <option
                        v-for="option in supervisorsIsFiltered"
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
                @click="updateDepartment()"
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
import moment from "moment";
export default {
    props: ["editDeptData", "admin"],
    data: function () {
        return {
            supervisors: [],
            editData: this.editDeptData,
            startTime: null,
            finishTime: null,
            minutesGranularity: 15,
            hoursGranularity: 1,
            minHour: null,
            minMin: null,
            minTime: null,
            isLoading: false,
            teacher_id: null,
        };
    },
    computed: {
        supervisorsIsFiltered() {
            if (this.teacher_id === null) {
                return this.supervisors;
            } else {
                return this.supervisors.filter((v) => v.id === this.teacher_id);
            }
        },
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
    },
    mounted() {
        //sessionStorage　出來是string
        if (sessionStorage.teacher_id !== "null") {
            this.teacher_id = Number(sessionStorage.teacher_id);
        }
        !sessionStorage.token ? (window.location.pathname = "/") : "";
        this.school = sessionStorage.school;
        this.getSupervisors();
    },
    methods: {
        Departmentrefresh() {
            this.$emit("onDepartmentrefresh");
        },
        getSupervisors() {
            axios
                .get("department/SelectTeacher", {
                    params: { school_id: this.school },
                })
                .then((response) => {
                    this.supervisors = response.data;
                    console.log("supervisors:" + response.data);
                })
                .catch((error) => {});
        },
        updateDepartment() {
            if (this.editData.depart_name === null) {
                return;
            }
            if (this.startTimeFormat === null) {
                return;
            }
            if (this.editData.teacher_id === null) {
                return;
            }
            if (this.finishTimeFormat === null) {
                return;
            }
            this.isLoading = true;
            let formData = new FormData();
            if (this.editData.depart_id) {
                formData.append("id", this.editData.depart_id);
            }
            if (this.admin) {
                formData.append("admin_id", this.admin);
            }
            if (this.editData.depart_name) {
                formData.append("name", this.editData.depart_name);
            }
            if (this.startTime) {
                formData.append("startTime", this.startTimeFormat);
            }
            if (this.editData.teacher_id) {
                formData.append("selectteacher", this.editData.teacher_id);
            }
            if (this.finishTime) {
                formData.append("finishTime", this.finishTimeFormat);
            }
            axios
                .post("department/update", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                })
                .then((response) => {
                    this.Departmentrefresh();
                })
                .catch((error) => {})
                .finally(() => {
                    this.isLoading = false;
                    this.$parent.close();
                });
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
.text_center {
    text-align-last: center !important;
}
.edit_img {
    border-radius: 50%;
}
</style>
