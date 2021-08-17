<template>
    <div>
        <profile-component></profile-component>
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
                    class="tab-pane fade active show"
                    id="pills-album"
                    role="tabpanel"
                >
                    <Album></Album>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
// import Profile from "../../Profile";
import Album from "./Album.vue";
import moment from "moment";
export default {
    components: {
        Album,
    },
    data: function () {
        let ption = null;
        ption = [
            {
                link: "#pills-album",
                id: "pills-album-tab",
                control: "pills-album",
                name: "相簿",

                isActive: true,
                isShow: false,
            },
            // {
            //     link: "#pills-trash",
            //     id: "pills-trash-tab",
            //     control: "pills-trash",
            //     name: "垃圾桶",
            //     isActive: false,
            //     isShow: false,
            // },
        ];
        return {
            tabItem: ption,
            isLoading: false,
            school: null,
            departmentsData: [],
            currentDepartment: null,
            date: null,
            teacher_id: null,
        };
    },
    computed: {},
    watch: {},
    created() {
        !sessionStorage.token ? (window.location.pathname = "/") : "";
    },
    mounted() {},
    methods: {},
};
</script>

<style lang="scss" scoped>
.table .card-body {
    padding: 0;
}
.table td,
.table th {
    border: 1px solid #dbdbdb;
    border-width: 1px;
    padding: 0.75rem;
    vertical-align: top;
}
th,
td {
    text-align: left !important;
}
td {
    white-space: normal !important;
    word-wrap: break-word;
}
table {
    table-layout: fixed;
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
</style>
