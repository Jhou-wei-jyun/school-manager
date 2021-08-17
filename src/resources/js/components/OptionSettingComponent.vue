<template>
    <div class="container mt-5">
        <!-- {{ JSON.stringify(displayData) }}
        {{ JSON.stringify(employeeDataSort) }} -->
        <div class="card table shadow">
            <div class="card-header">
                <div class="d-flex flex-column">
                    <div>
                        <span v-for="(item, index) in typeData" :key="index">
                            <input
                                type="radio"
                                :value="item.type"
                                v-model="type"
                            />
                            {{ item.name }}
                        </span>
                    </div>
                    <b-field label="部門">
                        <div class="d-flex justify-content-arround flex-wrap">
                            <b-button
                                v-for="(item, index) in departmentIsFiltered"
                                :key="index"
                                @click="changeDepart(item[0].depart_id)"
                                v-text="index"
                            ></b-button>
                        </div>
                    </b-field>
                    <b-field label="班級">
                        <div class="d-flex justify-content-arround flex-wrap">
                            <b-button
                                v-for="(item, index) in departmentChange"
                                :key="index"
                                v-text="item.name"
                                @click="changeDepartment(item.id)"
                            ></b-button>
                        </div>
                    </b-field>
                </div>
            </div>
            <div class="card-body">
                <div v-for="(item, index) in optionData" :key="index">
                    <b-field :label="item.type"> </b-field>
                    <div class="d-flex justify-content-arround flex-wrap">
                        <div
                            v-for="(j, i) in item.options"
                            :key="i"
                            class="w20"
                        >
                            <input
                                type="checkbox"
                                v-model="selectData[item.type]"
                                :value="j"
                            />
                            <span>{{ j }}</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <input
                            type="text"
                            placeholder="按enter加入..."
                            v-model="inputData[item.type]"
                            @keyup.enter="pushSelectData(item.type)"
                        />
                        <button
                            size="is-small"
                            @click="editOption(item.option_id, item.type)"
                        >
                            上傳
                        </button>
                    </div>
                </div>
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
import moment from "moment";

export default {
    components: {},
    data: function () {
        return {
            isLoading: false,
            typeData: [
                {
                    type: "Contact",
                    name: "聯絡簿",
                },
                {
                    type: "Medicine",
                    name: "用藥簿",
                },
            ],
            type: null,
            school_id: null,
            teacher_id: null,
            depart_id: null,
            department_id: null,
            departmentData: [],
            optionData: [],
            selectData: {},
            inputData: {},
        };
    },

    created() {
        if (sessionStorage.teacher_id !== "null") {
            this.teacher_id = Number(sessionStorage.teacher_id);
        }
        !sessionStorage.token ? (window.location.pathname = "/") : "";
        this.school_id = sessionStorage.school;
    },
    mounted() {
        this.getIndexOptionType();
        this.getDepartment();
    },
    computed: {
        departmentIsFiltered: function () {
            if (this.teacher_id === null) {
                return this.departmentData;
            } else {
                let teacher = this.teacher_id;
                let filtered = {};
                for (let [key, value] of Object.entries(this.departmentData)) {
                    let result = value.filter(
                        (v) => v.supervisor_id === teacher
                    );
                    filtered[key] = result;
                }
                return filtered;
            }
        },
        departmentChange: function () {
            if (this.depart_id === null) {
                return [];
            } else {
                let depart_id = this.depart_id;
                let filtter = [];
                for (let [key, value] of Object.entries(
                    this.departmentIsFiltered
                )) {
                    let result = value.filter((v) => v.depart_id === depart_id);
                    if (result.length === 0) {
                        continue;
                    }
                    if (result[0].depart_id === depart_id) {
                        filtter = result;
                        break;
                    }
                }
                return filtter;
            }
        },
    },
    watch: {
        department_id(n, o) {
            this.getOption(n);
        },
        optionData(n, o) {
            for (const element of n) {
                this.selectData[element.type] = element.options;
            }
        },
    },
    methods: {
        pushSelectData(type) {
            this.selectData[type].push(this.inputData[type]);
            this.inputData[type] = null;
        },
        // pushSelectData(type) {
        //     let index = this.selectData[type].indexOf("其他");
        //     this.selectData[type].push(this.inputData[type]);
        //     if (index === -1) {
        //         this.selectData[type].push("其他");
        //     } else {
        //         this.selectData[type].splice(index, 1);
        //         this.selectData[type].push("其他");
        //     }

        //     this.inputData[type] = null;
        // },
        changeDepart(id) {
            this.depart_id = id;
        },
        changeDepartment(id) {
            this.department_id = id;
        },
        getIndexOptionType() {
            axios
                .get("option/indexOptionType")
                .then((response) => {
                    let dict = {};
                    let dict_input = {};
                    for (const element of response.data) {
                        dict[element.value] = [];
                        dict_input[element.value] = null;
                    }
                    console.log(dict);
                    this.selectData = dict;
                    this.inputData = dict_input;
                })
                .finally(() => {});
        },
        getDepartment() {
            this.isLoading = true;
            axios
                .get("department/index", {
                    params: {
                        school_id: this.school_id,
                    },
                })
                .then((response) => {
                    this.departmentData = response.data;
                })
                .finally(() => {
                    this.isLoading = false;
                });
        },
        getOption(department_id) {
            if (this.type === null) {
                return;
            }
            this.isLoading = true;
            axios
                .get("option/indexOption", {
                    params: {
                        type: this.type,
                        department_id: department_id,
                    },
                })
                .then((response) => {
                    this.optionData = response.data;
                })
                .finally(() => {
                    this.isLoading = false;
                });
        },
        editOption(option_id, type) {
            if (option_id === null) {
                return;
            }
            this.isLoading = true;
            axios
                .post("option/editOption", {
                    option_id: option_id,
                    options: this.selectData[type],
                })
                .then((response) => {
                    if (response.data.result == true) {
                        this.$buefy.toast.open({
                            message: "新增成功",
                            type: "is-success",
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
    },
};
</script>

<style lang="scss" scoped>
span {
    // font-family: Archivo;
    font-size: 16px;
    font-weight: 600;
    font-stretch: normal;
    font-style: normal;
    line-height: normal;
    letter-spacing: normal;
    color: #6c7887;
}
.w20 {
    width: 20%;
}
</style>
