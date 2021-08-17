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
                    class="tab-pane fade"
                    :class="{ active: departActive, show: departActive }"
                    id="pills-department"
                    role="tabpanel"
                >
                    <Department></Department>
                </div>
                <div
                    class="tab-pane fade"
                    :class="{ active: studentActive, show: studentActive }"
                    id="pills-student"
                    role="tabpanel"
                >
                    <Employee></Employee>
                </div>
                <div
                    class="tab-pane fade"
                    :class="{ active: parentActive, show: parentActive }"
                    id="pills-parent"
                    role="tabpanel"
                >
                    <Parent></Parent>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
// import Profile from "../../Profile";
import Department from "./Department";
import Employee from "./Employee";
import Parent from "./Parent";
export default {
    components: {
        Department,
        Employee,
        Parent,
    },
    data: function () {
        let ption = null;
        ption = [
            {
                link: "#pills-department",
                id: "pills-department-tab",
                control: "pills-department",
                name: "所有班級",

                isActive: false,
                isShow: false,
            },
            {
                link: "#pills-student",
                id: "pills-student-tab",
                control: "pills-student",
                name: "所有學生",
                isActive: false,
                isShow: false,
            },
            {
                link: "#pills-parent",
                id: "pills-parent-tab",
                control: "pills-parent",
                name: "所有家長",
                isActive: false,
                isShow: false,
            },
        ];
        return {
            tabItem: ption,
            group_id: null,
            right: null,
        };
    },

    created() {
        !sessionStorage.token ? (window.location.pathname = "/") : "";
        this.group_id = sessionStorage.group;
        this.getRight();
    },
    computed: {
        tabIsShow: function () {
            return this.tabItem.filter((item) => item.isShow == true);
        },
        departActive: function () {
            return this.tabItem[0]["isActive"];
        },
        studentActive: function () {
            return this.tabItem[1]["isActive"];
        },
        parentActive: function () {
            return this.tabItem[2]["isActive"];
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
    mounted() {},
    methods: {
        getRight() {
            axios
                .get("right/tab", {
                    params: {
                        group_id: this.group_id,
                        page_id: 2, //page_id :2 班級管理
                    },
                })
                .then((response) => {
                    this.right = response.data;
                });
        },
    },
};
</script>

<style lang="scss" scoped>
.notification {
    background-color: #eef1f5;
    margin-top: 30px;
}

span {
    font-family: Archivo;
    font-size: 16px;
    font-weight: 500;
    font-stretch: normal;
    font-style: normal;
    line-height: normal;
    letter-spacing: normal;
    color: #6c7887;
}

#footer {
    position: fixed;
    bottom: 0;
}

// .b-button {
//     &:focus,
//     &:hover {
//         background-color: transparent !important;
//     }
// }
.table thead td,
.table thead th {
    border-width: 19px 100px 100px 100px;
}

.content table td {
    vertical-align: inherit;
}

#main {
    display: flex;
    margin-bottom: -1em;
}

#main div {
    -ms-flex: 1;
    /* IE 10 */
    flex: 1;
}

.field {
    display: inline-block;
    justify-content: space-around;
}

.content {
    margin: 0 100px;
}

.card {
    margin-left: 12px;
    margin-right: 16px;
    margin-top: 10px;
    background-color: #ffffff;
    border-radius: 5px;
    width: 300px;
    height: 430px;
}

/* img {
    position: absolute;
    top: 20px;
    left: 20px;
    width: 70px;
    height: 70px;
} */

hr {
    /* position: relative;
    top: 10px;
    background-color: gray; */
    width: 70%;
    display: inline-block;
}

.time {
    color: black;
}

h3 {
    font-size: 25px;
    color: green;
}

.notification_btn {
    float: right;
    font-size: 14px;
}
#profileImage {
    font-family: Arial, Helvetica, sans-serif;
    width: 3rem;
    height: 3rem;
    border-radius: 50%;
    background: #4099ff;
    font-size: 1.5rem;
    color: #fff;
    text-align: center;
    line-height: 3rem;
    margin: 2rem 0;
}
</style>
