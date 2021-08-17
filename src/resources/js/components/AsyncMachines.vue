<template>
    <div class="container mt-5">
        {{ test }}
        <div class="d-flex justify-content-around">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>
                            <input
                                type="checkbox"
                                ref="allCheckbox"
                                @change="selectAll"
                            />
                        </th>
                        <th>尚未同步</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, key) in notSyncMachines" :key="key">
                        <td>
                            <input
                                ref="checkbox"
                                type="checkbox"
                                v-model="selectData"
                                :value="item.serial_number"
                            />
                        </td>
                        <td v-text="item.serial_number"></td>
                    </tr>
                </tbody>
            </table>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th></th>
                        <th>已同步</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, key) in syncMachines" :key="key">
                        <td>
                            <input
                                type="radio"
                                v-model="targetPicked"
                                :value="item.serial_number"
                            />
                        </td>
                        <td v-text="item.serial_number"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            <b-button
                class="notification_btn notification_btn_sky notification_btn_text_white"
                size="is-medium"
                @click="syncMachine()"
                :disabled="isDisabled"
                >同步</b-button
            >
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
export default {
    components: {},
    data: function () {
        return {
            school: null,
            isLoading: false,
            machineList: [],
            selectData: [],
            targetPicked: null,
            test: null,
        };
    },
    filters: {},
    watch: {},
    created() {
        !sessionStorage.token ? (window.location.pathname = "/") : "";
        this.school = sessionStorage.school;
    },
    mounted() {
        this.getMachine();
    },
    computed: {
        syncMachines: function () {
            return this.machineList.filter((item) => item.sync == true);
        },
        notSyncMachines: function () {
            return this.machineList.filter((item) => item.sync == false);
        },
        isDisabled: function () {
            return this.selectData.length !== 0 && this.targetPicked !== null
                ? false
                : true;
        },
    },
    methods: {
        selectAll() {
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
        async getMachine() {
            await axios
                .get("machine/index", {
                    params: { school_id: this.school },
                })
                .then((response) => {
                    this.machineList = response.data;
                });
        },
        async syncMachine() {
            await axios
                .post("machine/sync", {
                    copy_machine_array: this.selectData,
                    origin_machine: this.targetPicked,
                })
                .then((response) => {
                    console.log("response", response.data);
                    this.test = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
    },
};
</script>

<style lang="scss" scoped>
</style>
