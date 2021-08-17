<template>
    <div>
        <div class="container">
            <ul class="nav nav-pills mt-5" id="pills-tab" role="tablist">
                <li
                    class="nav-item"
                    v-for="(item, index) in tabItem"
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
                    class="tab-pane fade show active"
                    id="pills-addRight"
                    role="tabpanel"
                >
                    <AddRight
                        :relation="relation"
                        @refresh="getGroup"
                    ></AddRight>
                </div>
                <div class="tab-pane fade" id="pills-editRight" role="tabpanel">
                    <EditRight
                        :relation="relation"
                        :group="group"
                        @refresh="getGroup"
                    ></EditRight>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import AddRight from "./modules/AddRight";
import EditRight from "./modules/EditRight";
export default {
    components: {
        AddRight,
        EditRight,
    },
    data: function () {
        let ption = null;
        ption = [
            {
                link: "#pills-addRight",
                id: "pills-addRight-tab",
                control: "pills-addRight",
                name: "新增群組",

                isActive: true,
                isShow: true,
            },
            {
                link: "#pills-editRight",
                id: "pills-editRight-tab",
                control: "pills-editRight",
                name: "編輯群組權限",
                isActive: false,
                isShow: true,
            },
        ];
        return {
            tabItem: ption,
            relation: [],
            group: [],
        };
    },

    created() {
        !sessionStorage.token ? (window.location.pathname = "/") : "";

        if (sessionStorage.permission === "special_admin") {
            this.getRelation();
            this.getGroup();
        } else {
            window.location.pathname = "/mainhome";
        }
    },
    computed: {},
    watch: {},
    mounted() {},
    methods: {
        getRelation() {
            axios
                .get("right/index", {
                    params: {},
                })
                .then((response) => {
                    this.relation = response.data;
                });
        },
        getGroup() {
            axios
                .get("group/index", {
                    params: {},
                })
                .then((response) => {
                    this.group = response.data;
                });
        },
    },
};
</script>

<style lang="scss" scoped>
</style>
