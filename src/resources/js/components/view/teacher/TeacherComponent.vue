<template>
    <div>
        <profile-component></profile-component>
        <!-- {{ teacherData }} -->
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
                    :class="{ active: allActive, show: allActive }"
                    id="pills-allteacher"
                    role="tabpanel"
                    aria-labelledby="pills-allteacher-tab"
                >
                    <AllTeacher
                        :teacherData="teacherData"
                        @emprefresh="getTeachers"
                    ></AllTeacher>
                </div>
                <div
                    class="tab-pane fade"
                    :class="{ active: timeActive, show: timeActive }"
                    id="pills-teachertime"
                    role="tabpanel"
                    aria-labelledby="pills-teachertime-tab"
                >
                    <TeacherTime
                        :teacherData="teacherData"
                        @emprefresh="getTeachers"
                    ></TeacherTime>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
// import Profile from "../../Profile";
import AllTeacher from "./AllTeacher";
import TeacherTime from "./TeacherTime";
export default {
    components: {
        AllTeacher,
        TeacherTime,
    },
    data: function () {
        let ption = null;
        ption = [
            {
                link: "#pills-allteacher",
                id: "pills-allteacher-tab",
                control: "pills-allteacher",
                name: "所有教師",

                isActive: false,
                isShow: false,
            },
            {
                link: "#pills-teachertime",
                id: "pills-teachertime-tab",
                control: "pills-teachertime",
                name: "教師考勤",
                isActive: false,
                isShow: false,
            },
        ];
        return {
            isLoading: false,
            teacherData: [],
            school: null,
            teacher_type: null,
            tabItem: ption,
            group_id: null,
            right: null,
        };
    },
    created() {
        !sessionStorage.token ? (window.location.pathname = "/") : "";
        this.group_id = sessionStorage.group;
        this.getRight();
        this.school = sessionStorage.school;
        this.teacher_type = sessionStorage.teacher_type;
        this.getTeachers();
    },
    computed: {
        tabIsShow: function () {
            return this.tabItem.filter((item) => item.isShow == true);
        },
        allActive: function () {
            return this.tabItem[0]["isActive"];
        },
        timeActive: function () {
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
                        page_id: 3, //page_id :3 教師管理
                    },
                })
                .then((response) => {
                    this.right = response.data;
                });
        },
        async getTeachers() {
            try {
                if (this.teacher_type == 1) {
                    console.log("becon");
                    var url = "teacher/becon/index";
                } else if (this.teacher_type == 2) {
                    console.log("face");
                    var url = "teacher/face/index";
                } else if (this.teacher_type == 3) {
                    console.log("becon_face");
                    var url = "teacher/becon_face/index";
                }
                this.isLoading = true;
                const response = await axios.get(url, {
                    params: {
                        school_id: this.school,
                    },
                });
                this.teacherData = response.data;
                console.log("更新一次:", this.teacherData);
            } catch (e) {
            } finally {
                this.isLoading = false;
            }
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
input {
    border: 1px solid rgba(206, 206, 208, 0.947);
    border-radius: 5px;
    padding: 9px 2vw;
    // margin: 0 auto;
    // box-sizing: border-box;
    background-color: rgb(225, 225, 235);
}
input:hover {
    border: 1px solid rgba(174, 174, 176, 0.947);
}

.department-group {
    background-color: white;
    margin: 5px 5px;
    padding: 10px;
    border-radius: 10px;

    .b-tabs {
        margin-bottom: 4px;

        .tab-content {
            padding: 0;
        }
    }
}

.profile_img {
    border-radius: 50%;
}

.upload_btn {
    border-width: 0;
}

.employee-name {
    display: flex;
    align-items: center;

    img {
        border-radius: 50%;
    }

    span {
        margin-left: 0.5rem;
    }
}
</style>
