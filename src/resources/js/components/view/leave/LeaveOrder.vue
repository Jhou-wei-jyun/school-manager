<template>
    <div class="mt-5">
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
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div>
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
                                <th>接送人</th>
                                <th>電話</th>
                                <th>關係</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(item, index) in orderLeavesData"
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
                                    <p
                                        class="ellipsis"
                                        v-for="(item_name, idx) in item.name"
                                        :key="idx"
                                    >
                                        {{ item_name.profile.name }}
                                    </p>
                                </td>
                                <td>
                                    <p class="ellipsis">
                                        {{ item.sent_people }}
                                    </p>
                                </td>
                                <td>
                                    <p class="ellipsis">{{ item.phone }}</p>
                                </td>

                                <td>
                                    <p class="ellipsis">{{ item.title }}</p>
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
            showWeekNumber: false,
            locale: undefined, // Browser locale
            isDisabled: false,
            isInfo: false,
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
            orderLeavesData: [],
            Info: null,
            editData: {},
            checkData: [],
            group_id: null,
            admin_id: null,
            teacher_id: null,
            infoType: "string",
            fileCount: 0,
            isCountLimit: false,
        };
    },
    computed: {
        checkDataRename() {
            let contact = [];
            for (const element of this.checkData) {
                let nameArray = [];
                for (const item of element.name) {
                    nameArray = [...nameArray, item.profile.name];
                }
                let data = renameKey(element, "name", "姓名");
                data["姓名"] = nameArray.join(",");
                data = renameKey(data, "sent_people", "接送人");
                data = renameKey(data, "phone", "電話");
                data = renameKey(data, "title", "關係");
                delete data["qrcode_id"];

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
        //     for (const element of this.orderLeavesData) {
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
                    this.orderLeavesData = [];
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
                    this.getOrderLeave(this.date);
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
        //         this.getOrderLeave(this.date);
        //     }
        // },
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
        getOrderLeave(date) {
            this.isLoading = true;
            axios
                .get("leave/orderindex", {
                    params: {
                        department_id: this.currentDepartment,
                        date: date,
                    },
                })
                .then((response) => {
                    if (response.data.result == true) {
                        this.orderLeavesData = response.data.data;
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
                    return { fill: "lightgreen" };
            }
        },
        getCheckStyle(check) {
            switch (check) {
                case 1:
                    return { fill: "lightgreen" };
                default:
                    return { fill: "#cccccc" };
            }
        },
        check(id) {
            this.isLoading = true;
            axios
                .get("medicines/check", {
                    params: {
                        medicine_id: id,
                        teacher_id: this.teacher_id,
                    },
                })
                .then((response) => {
                    if (response.data.result == true) {
                        this.orderLeavesData.map((item) => {
                            if (item.id === id) {
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
