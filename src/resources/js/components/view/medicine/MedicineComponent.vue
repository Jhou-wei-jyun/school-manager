<template>
    <div class="container mt-5">
        <!-- {{ JSON.stringify(editData) }} -->
        <div class="card card-body table-depart">
            <table
                class="table table-bordered"
                id="dataTable"
                width="100%"
                cellspacing="0"
            >
                <thead>
                    <tr>
                        <th>校區</th>
                        <th>
                            <input
                                type="checkbox"
                                ref="selectAllDepartmentBox"
                                @change="selectAllDepartment"
                            />
                            <span> 班別 </span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(depart, key, index) in departmentIsFiltered"
                        :key="index"
                    >
                        <td>{{ key }}</td>
                        <td>
                            <div class="d-flex justify-content-start flex-wrap">
                                <span
                                    v-for="(item, idx) in depart"
                                    :key="idx"
                                    class="col-2"
                                >
                                    <input
                                        ref="selectDepartmentBox"
                                        type="checkbox"
                                        v-model="currentDepartment"
                                        :value="item.id"
                                    />
                                    {{ item.name }}
                                </span>
                                <!-- <b-button
                                    @click="department_change(item.id)"
                                    class="notification_btn notification_btn_sky notification_btn_text_white"
                                    size="is-small"
                                    v-for="(item, idx) in depart"
                                    :key="idx"
                                    >{{ item.name }}</b-button
                                > -->
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div>
                <div v-if="currentDepartment !== null">
                    <b-field horizontal label="選擇日期" class="w-50">
                        <b-datepicker
                            :date-formatter="
                                (date) =>
                                    new Intl.DateTimeFormat('en-CA').format(
                                        date
                                    )
                            "
                            v-model="date"
                            :first-day-of-week="1"
                            placeholder="點擊"
                        >
                            <button
                                class="button is-primary"
                                @click="date = new Date()"
                            >
                                <span>Today</span>
                            </button>
                        </b-datepicker>
                    </b-field>
                </div>

                <div class="scroll-contact-table mt-3">
                    <table
                        class="table table-bordered"
                        id="dataTable"
                        cellspacing="0"
                    >
                        <thead>
                            <tr>
                                <th>
                                    <input
                                        type="checkbox"
                                        ref="allCheckbox"
                                        @change="selectAll"
                                    />
                                </th>

                                <th>姓名</th>
                                <th>身體狀況</th>
                                <th>服藥時段</th>
                                <th>服藥方式</th>
                                <th>備註</th>
                                <th>附圖</th>
                                <th>用藥狀況</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(item, index) in medicinesData"
                                :key="index"
                            >
                                <td>
                                    <input
                                        ref="checkbox"
                                        type="checkbox"
                                        v-model="checkData"
                                        :value="item"
                                    />
                                </td>
                                <td>
                                    <p class="ellipsis">{{ item.name }}</p>
                                </td>

                                <td>
                                    <p class="ellipsis">
                                        {{ item.reason | jsonToString }}
                                    </p>
                                </td>

                                <td>
                                    <p class="ellipsis">
                                        {{ item.time | jsonToString }}
                                    </p>
                                </td>
                                <td>
                                    <p class="ellipsis">
                                        {{ item.taken | jsonToString }}
                                        {{ item.pack || item.cc }}
                                    </p>
                                </td>

                                <td>
                                    <p class="ellipsis">{{ item.notation }}</p>
                                </td>

                                <td>
                                    <div
                                        class="
                                            d-flex
                                            justify-content-around
                                            align-items-center
                                            flex-wrap
                                        "
                                    >
                                        <a
                                            @click="
                                                showInfo(item.photos, 'image')
                                            "
                                        >
                                            <svg
                                                version="1.1"
                                                id="圖層_1"
                                                xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink"
                                                width="40"
                                                height="40"
                                                viewBox="0 0 44.3 41.8"
                                                style="
                                                    enable-background: new 0 0
                                                        44.3 41.8;
                                                "
                                                xml:space="preserve"
                                                :style="
                                                    getStyle(item.photos.length)
                                                "
                                            >
                                                <g>
                                                    <circle
                                                        class="st0"
                                                        cx="24.2"
                                                        cy="20"
                                                        r="1.5"
                                                    />
                                                    <path
                                                        class="st0"
                                                        d="M25.2,27.9c-2.3,1.3-3.5,1.2-5.4-0.5c-1-0.9-2-1.9-3.2-2.7c-0.5-0.3-1.5-0.5-2-0.2c-1.7,1.1-3.4,2.2-4.8,3.6
		c-0.6,0.6-0.7,2.1-0.5,3.1c0.1,0.4,1.3,0.9,2.1,0.9c1.7,0.1,3.3,0,5,0c4.1,0,8.3,0,12.4,0c1.1,0,2.2,0,2.3-1.5c0.1-1.9,0-3.8,0-6.2
		C28.8,25.8,27,26.8,25.2,27.9z"
                                                    />
                                                    <path
                                                        class="st0"
                                                        d="M29.5,15c-6.3,0-12.6,0-18.8,0.1c-0.5,0-1.5,0.8-1.5,1.3c-0.1,3.1-0.1,6.2-0.1,9.4c0.5-0.1,0.6-0.1,0.7-0.2
		c1.1-0.8,2.2-1.5,3.2-2.3c2-1.4,3.2-1.3,5,0.2c1.1,0.9,2.1,1.8,3.1,2.7c0.9,0.8,1.7,1,2.8,0.3c2-1.2,4.1-2.3,6.1-3.6
		c0.5-0.3,1-1.1,1.1-1.8c0.2-1.4,0.1-2.9,0-4.3C31.1,15.7,30.7,15,29.5,15z M24.2,23.2c-1.8,0-3.2-1.5-3.2-3.2
		c0-1.8,1.5-3.2,3.2-3.2c1.8,0,3.2,1.5,3.2,3.2C27.4,21.8,26,23.2,24.2,23.2z"
                                                    />
                                                    <path
                                                        class="st0"
                                                        d="M33.3,11.6c-6-0.7-12-1.3-18-2c-1.6-0.2-2,0.6-2.2,2c-0.1,1.5,0.7,1.6,1.8,1.6c4.4,0,8.8,0,13.1,0
		c4.1,0,4.9,0.8,4.9,4.8c0,3.3,0,6.6,0,10c0.1,0,0.3,0,0.4,0c0.1-0.2,0.2-0.4,0.2-0.6c0.5-4.6,1.1-9.3,1.6-13.9
		C35.3,12.1,34.4,11.8,33.3,11.6z"
                                                    />
                                                </g>
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                                <td>
                                    <div
                                        class="
                                            d-flex
                                            justify-content-around
                                            align-items-center
                                            flex-wrap
                                        "
                                    >
                                        <a
                                            :disabled="
                                                date === null || isDisabled
                                            "
                                            @click="
                                                showCheck(item.id, item.checked)
                                            "
                                        >
                                            <span
                                                :style="
                                                    getCheckStyle(item.checked)
                                                "
                                            >
                                                用藥完成
                                                <i
                                                    class="fas fa-check-circle"
                                                ></i>
                                            </span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="float-button">
                        <b-button v-show="checkData.length !== 0" class="ml-1">
                            <export-excel
                                class="btn btn-default"
                                :data="checkDataRename"
                                worksheet="My Worksheet"
                                name="filename.xls"
                            >
                                匯出
                            </export-excel></b-button
                        >
                    </div>
                </div>
            </div>
        </div>
        <b-modal :active.sync="isInfo" :width="640" scroll="clip">
            <contact-info
                :contactInfo="Info"
                :infoType="infoType"
            ></contact-info>
        </b-modal>

        <b-modal :active.sync="isCheck" :width="260" scroll="clip">
            <div class="d-flex flex-column align-items-center">
                <div class="d-flex justify-content-center p-4">
                    <h1>已經完成餵藥了嗎？</h1>
                </div>

                <div class="d-flex justify-content-around pb-1">
                    <b-button
                        @click="
                            isCheck = false;
                            medicine_id = null;
                        "
                    >
                        取消</b-button
                    >
                    <b-button
                        class="ml-1"
                        @click="
                            check(medicine_id);
                            isCheck = false;
                        "
                    >
                        確認</b-button
                    >
                </div>
            </div>
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
import { renameKey } from "../../../function/index";
import moment from "moment";
import ContactInfo from "./components/ContactInfo";
export default {
    components: {
        ContactInfo,
    },
    data: function () {
        return {
            selected: new Date(),
            showWeekNumber: false,
            locale: undefined, // Browser locale
            isDisabled: false,
            isInfo: false,
            isCheck: false,
            isLoading: false,
            school: null,
            optionData: [],
            optionContent: {
                condition: [],
                Return: [],
                bring: [],
                mood: null,
                daily: null,
                file: null,
                file2: null,
                photo: null,
                photo2: null,
            },
            departmentsData: [],
            currentDepartment: [],
            date: new Date(),
            medicinesData: [],
            Info: null,
            editData: {},
            checkData: [],
            group_id: null,
            admin_id: null,
            teacher_id: null,
            infoType: "string",
            fileCount: 0,
            isCountLimit: false,
            medicine_id: null,
        };
    },
    computed: {
        checkDataRename() {
            let contact = [];
            for (const element of this.checkData) {
                let taken = "";
                if (element.pack) {
                    taken = element.taken
                        ? JSON.parse(element.taken).join(",")
                        : "" + element.pack + "包";
                } else if (element.cc) {
                    taken = element.taken
                        ? JSON.parse(element.taken).join(",")
                        : "" + element.cc + "c.c";
                }

                let data = renameKey(element, "name", "姓名");
                data = renameKey(data, "date", "日期");
                data["reason"] = element.reason
                    ? JSON.parse(element.reason).join(",")
                    : null;
                data = renameKey(data, "reason", "服藥理由");
                data["time"] = element.time
                    ? JSON.parse(element.time).join(",")
                    : null;
                data = renameKey(data, "time", "服藥時段");
                data["taken"] = taken;
                data = renameKey(data, "taken", "服藥方式");
                data = renameKey(data, "notation", "備註");
                data = renameKey(data, "checker", "服藥完成");
                delete data["id"];
                delete data["other"];
                delete data["pack"];
                delete data["cc"];
                delete data["photo"];
                delete data["checked"];
                contact = [...contact, data];
            }
            return contact;
        },
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
        // contact_id: function () {
        //     let contact_id = [];
        //     for (const element of this.medicinesData) {
        //         contact_id = [...contact_id, element.id];
        //     }
        //     return contact_id;
        // },
    },
    watch: {
        currentDepartment: {
            handler(n, o) {
                console.log(n);
                if (this.date === null) {
                    return;
                } else if (n.length === 0) {
                    this.medicinesData = [];
                } else {
                    let newOptionContent = {
                        condition: [],
                        Return: [],
                        bring: [],
                        mood: null,
                        daily: null,
                        file: null,
                        file2: null,
                        photo: null,
                        photo2: null,
                    };

                    this.optionContent = Object.assign({}, newOptionContent);

                    if (
                        !(
                            moment(this.date).isAfter(
                                moment().add(-2, "days").format("YYYY-MM-DD")
                            ) && //前天、今天之後
                            moment(this.date).isBefore(
                                moment().add(1, "days").format("YYYY-MM-DD")
                            )
                        ) //前天、今天之前
                    ) {
                        this.isDisabled = true;
                    } else {
                        this.isDisabled = false;
                    }
                    this.getMedicines(this.date);
                }
            },
            deep: true,
        },
        // currentDepartment(n, o) {
        //     if (this.date === null) {
        //         return;
        //     } else {
        //         let newOptionContent = {
        //             condition: [],
        //             Return: [],
        //             bring: [],
        //             mood: null,
        //             daily: null,
        //             file: null,
        //             file2: null,
        //             photo: null,
        //             photo2: null,
        //         };

        //         this.optionContent = Object.assign({}, newOptionContent);

        //         if (
        //             !(
        //                 moment(this.date).isAfter(
        //                     moment().add(-2, "days").format("YYYY-MM-DD")
        //                 ) && //前天、今天之後
        //                 moment(this.date).isBefore(
        //                     moment().add(1, "days").format("YYYY-MM-DD")
        //                 )
        //             ) //前天、今天之前
        //         ) {
        //             this.isDisabled = true;
        //         } else {
        //             this.isDisabled = false;
        //         }
        //         this.getMedicines(this.date);
        //     }
        // },
        date(n, o) {
            let newOptionContent = {
                condition: [],
                Return: [],
                bring: [],
                mood: null,
                daily: null,
                file: null,
                file2: null,
                photo: null,
                photo2: null,
            };

            this.optionContent = Object.assign({}, newOptionContent);

            if (
                !(
                    moment(n).isAfter(
                        moment().add(-2, "days").format("YYYY-MM-DD")
                    ) && //前天、今天之後
                    moment(n).isBefore(
                        moment().add(1, "days").format("YYYY-MM-DD")
                    )
                ) //前天、今天之前
            ) {
                this.isDisabled = true;
            } else {
                this.isDisabled = false;
            }
            if (this.currentDepartment.length === 0) {
                this.medicinesData = [];
            } else {
                this.getMedicines(n);
            }
        },
    },
    filters: {
        jsonToString(json) {
            if (json === null) {
                return null;
            } else if (typeof json === "string") {
                return JSON.parse(json).toString();
            } else {
                return json.toString();
            }
        },
    },
    created() {
        !sessionStorage.token ? (window.location.pathname = "/") : "";
        //sessionStorage　出來是string
        if (sessionStorage.teacher_id !== "null") {
            this.teacher_id = Number(sessionStorage.teacher_id);
        }
        this.school = sessionStorage.school;
        this.group_id = sessionStorage.group;
        this.admin_id = sessionStorage.id;
    },
    mounted() {
        this.getDepartments();
    },
    methods: {
        selectAll() {
            // console.log(this.$refs.allCheckbox);
            // console.log(this.$refs.allCheckbox.checked);
            if (this.$refs.checkbox) {
                if (this.$refs.allCheckbox.checked == true) {
                    this.$refs.checkbox.forEach((val, index) =>
                        val.checked == false ? val.click() : false
                    );
                } else {
                    this.$refs.checkbox.forEach((val, index) =>
                        val.checked == true ? val.click() : false
                    );
                }
            }
        },
        selectAllDepartment() {
            // console.log(index);
            // console.log(this.$refs.selectAllDepartmentBox);
            // console.log(this.$refs.selectAllDepartmentBox.checked);
            if (this.$refs.selectDepartmentBox) {
                if (this.$refs.selectAllDepartmentBox.checked == true) {
                    this.$refs.selectDepartmentBox.forEach((val, index) =>
                        val.checked == false ? val.click() : false
                    );
                } else {
                    this.$refs.selectDepartmentBox.forEach((val, index) =>
                        val.checked == true ? val.click() : false
                    );
                }
            }
        },
        selectAll() {
            console.log(this.$refs.allCheckbox.checked);
            if (this.$refs.checkbox) {
                if (this.$refs.allCheckbox.checked == true) {
                    this.$refs.checkbox.forEach((val, index) =>
                        val.checked == false ? val.click() : false
                    );
                } else {
                    this.$refs.checkbox.forEach((val, index) =>
                        val.checked == true ? val.click() : false
                    );
                }
            }
        },
        showCheck(id, check) {
            if (check === 1) {
                return;
            } else {
                this.medicine_id = id;
                this.isCheck = true;
            }
        },
        showInfo(item, type) {
            this.infoType = type;
            this.Info = item;
            this.isInfo = true;
        },
        department_change(id) {
            this.currentDepartment = id;
        },
        getDepartments() {
            this.isLoading = true;
            axios
                .get("department/index", { params: { school_id: this.school } })
                .then((response) => {
                    this.departmentsData = response.data;
                })
                .catch({})
                .finally(() => {
                    this.isLoading = false;
                });
        },
        getMedicines(date) {
            this.isLoading = true;
            axios
                .get("medicines/index", {
                    params: {
                        department_id: this.currentDepartment,
                        date: date,
                    },
                })
                .then((response) => {
                    if (response.data.result == true) {
                        this.medicinesData = response.data.data;
                    }
                })
                .catch((error) => {
                    this.$buefy.toast.open({
                        message: error.response.data.error,
                        type: "is-danger",
                        queue: false,
                    });
                })
                .finally(() => {
                    this.checkData = [];
                    this.isLoading = false;
                });
        },

        getStyle(length) {
            switch (length) {
                case 0:
                    return { fill: "#cccccc" };
                default:
                    return { fill: "#008778" };
            }
        },
        getCheckStyle(check) {
            switch (check) {
                case 1:
                    return { color: "coral" };
                default:
                    return { color: "#cccccc" };
            }
        },
        check() {
            if (this.medicine_id === null) {
                return;
            }
            this.isLoading = true;
            axios
                .get("medicines/check", {
                    params: {
                        medicine_id: this.medicine_id,
                        teacher_id: this.teacher_id,
                    },
                })
                .then((response) => {
                    if (response.data.result == true) {
                        this.medicinesData.map((item) => {
                            if (item.id === this.medicine_id) {
                                item.checked = 1;
                            }
                        });
                    }
                })
                .catch((error) => {
                    this.$buefy.toast.open({
                        message: error.response.data.error,
                        type: "is-danger",
                        queue: false,
                    });
                })
                .finally(() => {
                    this.medicine_id = null;
                    this.isLoading = false;
                });
        },
    },
};
</script>

<style lang="scss" scoped>
// .scroll-table {
//     overflow-y: scroll;
//     height: 15vh;
//     .table th {
//         position: sticky;
//         top: -1px;
//         z-index: 1;
//     }
// }
.scroll-contact-table {
    overflow-y: scroll;
    height: 60vh;
    table {
        border: 1px solid #dbdbdb;
        min-width: 100%;

        vertical-align: top;
    }
    table.table th {
        position: sticky;
        top: -1px;
        z-index: 1;
        white-space: nowrap;
        border-width: 1px;
    }
    table.table td {
        // white-space: normal !important;
        word-wrap: break-word;
        border-width: 1px;
        max-width: 100px;
    }
}
.ellipsis {
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    white-space: normal;
}
.float-button {
    position: fixed;
    bottom: 3vh;
    right: 3vw;
}
.w20 {
    width: 20%;
}
</style>
