<template>
    <div class="container mt-5">
        <!-- {{ JSON.stringify(indexData) }} -->
        <!-- {{teacherDataShow}} -->
        <!-- {{JSON.stringify(notifyData)}} -->
        <div class="card table shadow">
            <!-- <div class="card-header d-flex flex-row justify-content-between">
                <b-input
                    v-model="search_val"
                    placeholder="Search"
                    type="search"
                    icon="magnify"
                    icon-clickable
                    expanded
                ></b-input>
            </div>-->
            <div class="card-body">
                <div class="table-responsive">
                    <table
                        class="table table-bordered"
                        id="tblReport"
                        width="100%"
                        cellspacing="0"
                    >
                        <thead>
                            <tr>
                                <th>序號</th>
                                <th>訊息標題</th>
                                <th>傳送時間</th>
                                <th>指定對象</th>
                                <th>建立者</th>
                                <th>傳送人數</th>
                                <th>查看</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in indexData" :key="item.id">
                                <td>{{ item.id }}</td>
                                <td>{{ item.title }}</td>
                                <td>{{ item.time | timeFormat }}</td>
                                <td>{{ item.target }}</td>
                                <td>{{ item.sent }}</td>
                                <td>{{ item.count }}</td>
                                <td>
                                    <a @click="showinfo(item)">
                                        <i class="fas fa-search"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <b-modal :active.sync="isInfo" :width="640" scroll="clip">
            <NotifyInfo :notify-info="notifydata"></NotifyInfo>
        </b-modal>
    </div>
</template>

<script>
import moment from "moment";
import NotifyInfo from "./components/NotifyInfo";
export default {
    props: ["indexData"],
    components: {
        NotifyInfo,
    },
    data: function () {
        return {
            isInfo: false,
            notifydata: null,
        };
    },
    filters: {
        timeFormat(date) {
            if (date == null) {
                return date;
            } else {
                return moment(date).format("YYYY-MM-DD");
            }
        },
    },
    watch: {},
    mounted() {},
    methods: {
        showinfo(item) {
            console.log("12");
            this.isInfo = true;
            this.notifydata = item;
        },
    },
};
</script>

<style lang="scss" scoped>
.table .card-body {
    padding: 0;
}
</style>
