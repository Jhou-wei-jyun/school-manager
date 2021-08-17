<template>
    <div class="card card-body">
        <header class="card-bottom d-flex align-items-center">
            <p class="h4 has-text-weight-semibold">遲到列表</p>
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
                        <th>抵達時間</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in latestudent" :key="item.id">
                        <td>{{ item.name }}</td>
                        <td>
                            {{ item.date_time | timeFormat }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import moment from "moment";
export default {
    props: ["latestudent"],
    data: function () {
        return {};
    },
    filters: {
        timeFormat(date) {
            if (date == null) {
                return date;
            } else {
                return moment(date).format("HH:mm");
            }
        },
    },
    mounted() {},
    methods: {},
};
</script>

<style lang="scss" scoped>
</style>
