<template>
    <div class="card card-body">
        <header class="card-bottom d-flex align-items-center">
            <p class="h4 has-text-weight-semibold">異常體溫列表</p>
        </header>

        <div>
            <table
                class="table table-bordered"
                id="dataTable"
                width="100%"
                cellspacing="0"
            >
                <thead>
                    <tr>
                        <th>姓名</th>
                        <th>體溫</th>
                        <th
                            v-show="
                                right['體溫確認'] == null
                                    ? false
                                    : right['體溫確認']['show']
                            "
                        >
                            未確認
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in temperatureDate" :key="item.id">
                        <td>{{ item.name }}</td>
                        <td>
                            {{ item.temperature_val }}
                        </td>
                        <td
                            v-show="
                                right['體溫確認'] == null
                                    ? false
                                    : right['體溫確認']['show']
                            "
                        >
                            <b-button
                                v-if="item.check == 0"
                                class="table-btn pl-0 pr-0"
                                @click="check_temper(item.id)"
                            >
                                <img
                                    class="rounded-circle"
                                    width="40px"
                                    src="images/edit_icon.svg"
                                />
                            </b-button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
export default {
    props: ["temperatureDate", "right"],
    data: function () {
        return {
            admin_id: null,
        };
    },
    created() {
        this.admin_id = sessionStorage.id;
    },
    methods: {
        errTemperRefresh() {
            this.$emit("errTemperRefresh");
        },
        check_temper(temperature_id) {
            axios
                .post("temperature/check", {
                    admin_id: this.admin_id,
                    temperature_id: temperature_id,
                })
                .then((response) => {
                    if (response.data.result == true) {
                        this.$buefy.toast.open({
                            message: "Check!!",
                            type: "is-success",
                            queue: false,
                        });
                        this.errTemperRefresh();
                    }
                })
                .catch((error) => {
                    this.$buefy.toast.open({
                        message: error.response.data.error,
                        type: "is-danger",
                        queue: false,
                    });
                });
        },
    },
};
</script>

<style lang="scss" scoped>
</style>
