<template>
    <div>
        <profile-component></profile-component>
        <div class="container">
            <ul class="nav nav-pills mt-5" id="pills-tab" role="tablist">
                <li
                    class="nav-item"
                    v-for="(item, index) in tabIsShow"
                    :key="index"
                >
                    <a
                        class="nav-link"
                        :class="{ active: item.isActive }"
                        :id="item.id"
                        :aria-controls="item.control"
                        data-toggle="pill"
                        :href="item.link"
                        role="tab"
                        >{{ item.name }}</a
                    >
                </li>
            </ul>

            <div class="tab-content" id="pills-tabContent">
                <div
                    class="tab-pane fadeve"
                    :class="{ active: notifyActive, show: notifyActive }"
                    id="pills-notify"
                    role="tabpanel"
                    aria-labelledby="pills-notify-tab"
                >
                    <Notify
                        :admin="admin_id"
                        @NotifyRefresh="getNotifies"
                    ></Notify>
                </div>
                <div
                    class="tab-pane fade"
                    :class="{ active: indexActive, show: indexActive }"
                    id="pills-index"
                    role="tabpanel"
                    aria-labelledby="pills-index-tab"
                >
                    <NotifyIndex :indexData="notifyData"></NotifyIndex>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
// import Profile from "../../Profile";
import Notify from "./Notify";
import NotifyIndex from "./NotifyIndex";
// import EditDept from "./EditDept";
export default {
    components: {
        Notify,
        NotifyIndex,
    },
    data: function () {
        let ption = null;
        ption = [
            {
                link: "#pills-notify",
                id: "pills-notify-tab",
                control: "pills-notify",
                name: "訊息推送",
                isActive: false,
                isShow: false,
            },
            {
                link: "#pills-index",
                id: "pills-index-tab",
                control: "pills-index",
                name: "訊息管理",
                isActive: false,
                isShow: false,
            },
        ];
        return {
            school: null,
            notifyData: null,
            tabItem: ption,
            group_id: null,
            right: null,
            admin_id: null,
        };
    },
    created() {
        !sessionStorage.token ? (window.location.pathname = "/") : "";
        this.group_id = sessionStorage.group;
        this.getRight();
        this.admin_id = sessionStorage.id;
        this.school = sessionStorage.school;
        this.getNotifies();
    },
    mounted() {},
    computed: {
        tabIsShow: function () {
            return this.tabItem.filter((item) => item.isShow == true);
        },
        notifyActive: function () {
            return this.tabItem[0]["isActive"];
        },
        indexActive: function () {
            return this.tabItem[1]["isActive"];
        },
    },
    watch: {
        right: function (n, o) {
            let count = 0;
            this.tabItem.forEach(function (item, index) {
                for (let [key, value] of Object.entries(n)) {
                    if (value.tab_name == item.name) {
                        if (value.show == 0) {
                            item["isShow"] = false;
                            break;
                        } else {
                            count = count + 1;
                            item["isShow"] = true;
                            if (count == 1) {
                                //第一個show 給予class:active, show
                                item["isActive"] = true;
                            }
                            break;
                        }
                    }
                    continue;
                }
            });
        },
    },
    methods: {
        getRight() {
            axios
                .get("right/tab", {
                    params: {
                        group_id: this.group_id,
                        page_id: 6, //page_id :6 推播通知
                    },
                })
                .then((response) => {
                    this.right = response.data;
                });
        },
        async getNotifies() {
            await axios
                .get("notify/index", {
                    params: {
                        admin_id: this.admin_id,
                        school_id: this.school,
                    },
                })
                .then((response) => {
                    this.notifyData = response.data;
                });
        },
    },
};
</script>

<style lang="scss" scoped>
</style>
