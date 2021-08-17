
<template>
    <!-- <div> -->
    <!-- <button
            id="sidebarToggleTop"
            class="topbar btn btn-link fixed-top d-md-none rounded-circle mr-3 z-index-5"
        >
            <i class="fa fa-bars"></i>
    </button>-->

    <!-- new -->
    <div class="sidebar_base" ref="side">
        <ul
            v-show="isLoggedIn"
            class="navbar-nav sidebar sidebar-dark accordion base"
            :class="{ sidebarshow: currentSide, toggled: currentToggle }"
            ref="inf"
        >
            <!-- ,sidebarcustom_oggle_collapsed:check_sidebar_collapsed,sidebarcustom_toggle_base:check_sidebar_base -->
            <!-- Sidebar - Brand -->

            <!-- <a
            v-if="isLoggedIn"
            class="sidebar-brand d-flex align-items-center justify-content-center"
            href="/mainhome"
        >-->
            <div
                class="
                    sidebar-brand
                    d-flex
                    align-items-center
                    justify-content-center
                    sidebar-brand-icon
                "
            >
                <img src="images/homepage_icon_white.svg" />
            </div>
            <!-- </a> -->

            <!-- Nav Item - Dashboard -->
            <li class="nav-item" v-for="(item, idx) in navIsShow" :key="idx">
                <a
                    class="nav-link collapsed"
                    :href="item.link"
                    :data-toggle="item.collapse"
                    aria-expanded="false"
                    @mouseleave="item.hover = false"
                    @mouseover="item.hover = true"
                >
                    <!-- :data-toggle="{collapse:item.collapsework}" -->
                    <img
                        :src="item.icon_w"
                        width="40px"
                        v-if="item.hover == false"
                    />
                    <img :src="item.icon" width="40px" v-else />
                    <!-- <i class="fas fa-fw fa-tachometer-alt" :class="{ active: isCurrentPage(item.link)}"></i> -->
                    <span class="headerStyle linkname">{{ item.name }}</span>
                </a>
                <div
                    v-show="item.link == '#collapseSetting'"
                    id="collapseSetting"
                    class="collapse"
                >
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a
                            class="collapse-item"
                            v-for="(item, idx) in collapseIsShow"
                            :key="idx"
                            :href="item.link"
                            >{{ item.name }}</a
                        >
                    </div>
                </div>
                <hr class="sidebar-divider my-0" />
            </li>

            <div class="text-center d-none d-md-inline pt-2">
                <button
                    @click="toggle"
                    class="rounded-circle border-0"
                    id="sidebarToggle"
                ></button>
            </div>
            <transition name="navCoverShowAnimation">
                <div
                    v-show="navCoverShow"
                    class="navCover"
                    @click="trashClick()"
                ></div>
            </transition>
        </ul>
    </div>
</template>

<script>
export default {
    data: function () {
        let ption = null;
        ption = [
            {
                link: "mainhome",
                icon: "images/icon_home_b.svg",
                icon_w: "images/icon_home_w.svg",
                name: "首頁",
                hover: false,
                collapse: null,
                show: false,
            },
            {
                link: "department",
                icon: "images/icon_department_b.svg",
                icon_w: "images/icon_department_w.svg",
                name: "班級管理",
                hover: false,
                collapse: null,
                show: false,
            },
            {
                link: "teacher",
                icon: "images/icon_teacher_b.svg",
                icon_w: "images/icon_teacher_w.svg",
                name: "教師管理",
                hover: false,
                collapse: null,
                show: false,
            },
            {
                link: "area",
                icon: "images/icon_environment_b.svg",
                icon_w: "images/icon_environment_w.svg",
                name: "環境安全",
                hover: false,
                collapse: null,
                show: false,
            },
            {
                link: "device",
                icon: "images/icon_equipment_b.svg",
                icon_w: "images/icon_equipment_w.svg",
                name: "設備管理",
                hover: false,
                collapse: null,
                show: false,
            },
            {
                link: "contact",
                icon: "images/icon_contact_b.svg",
                icon_w: "images/icon_contact_w.svg",
                name: "聯絡簿管理",
                hover: false,
                collapse: null,
                show: false,
            },
            {
                link: "albumCloud",
                icon: "images/icon_album_b.svg",
                icon_w: "images/icon_album_w.svg",
                name: "相簿管理",
                hover: false,
                collapse: null,
                show: false,
            },
            {
                link: "notify",
                icon: "images/icon_notification_b.svg",
                icon_w: "images/icon_notification_w.svg",
                name: "推播通知",
                hover: false,
                collapse: null,
                show: false,
            },
            {
                link: "medicines",
                icon: "images/icon_medicine_b.svg",
                icon_w: "images/icon_medicine_w.svg",
                name: "用藥清單",
                hover: false,
                collapse: null,
                show: false,
            },
            {
                link: "leave",
                icon: "images/icon_leave_b.svg",
                icon_w: "images/icon_leave_w.svg",
                name: "接送清單",
                hover: false,
                collapse: null,
                show: false,
            },
            {
                link: "#collapseSetting",
                icon: "images/icon_setting_b.svg",
                icon_w: "images/icon_setting_w.svg",
                name: "設定",
                hover: false,
                collapse: "collapse",
                show: false,
            },
        ];
        let collapse = null;
        collapse = [
            {
                link: "announcement",
                name: "廣告輪播",
                show: false,
            },
            {
                link: "becon",
                name: "becon綁定",
                show: false,
            },
            {
                link: "attendance",
                name: "健康出勤報表",
                show: false,
            },
            {
                link: "option-setting",
                name: "選項設定",
                show: false,
            },
        ];

        return {
            account: sessionStorage.account,
            isLoggedIn: false,
            userPermit: null,
            navItem: ption,
            navCollapseItem: collapse,
            check_sidebar_collapsed: false,
            check_sidebar_base: false,
            currentToggle: false,
            group_id: null,
            right: null,
        };
    },

    created() {
        if (sessionStorage.token) {
            this.isLoggedIn = true;
        }
        this.group_id = sessionStorage.group;
        this.getRight();
        window.addEventListener("resize", this.handleWindowResize);
    },
    destroyed() {
        window.removeEventListener("resize", this.handleWindowResize);
    },
    mounted() {
        this.$nextTick(() => {
            this.$store.dispatch("updateNavWidth", this.$refs.side.clientWidth);
        });
    },
    computed: {
        navCoverShow() {
            return this.$store.getters.navCover;
        },
        currentSide() {
            console.log("success", this.$store.getters.sidebarshow);
            return this.$store.getters.sidebarshow;
        },
        navIsShow: function () {
            return this.navItem.filter((item) => item.show == true);
        },
        collapseIsShow: function () {
            return this.navCollapseItem.filter((item) => item.show == true);
        },
    },
    watch: {
        right: function (n, o) {
            this.navItem.forEach(function (item, index) {
                n.forEach(function (r) {
                    if (r.page_name == item.name) {
                        if (r.show == 0) {
                            item["show"] = false;
                            return false;
                        } else {
                            item["show"] = true;
                            return false;
                        }
                    }
                    return true;
                });
            });
            this.navCollapseItem.forEach(function (item, index) {
                n.forEach(function (r) {
                    if (r.page_name == item.name) {
                        if (r.show == 0) {
                            item["show"] = false;
                            return false;
                        } else {
                            item["show"] = true;
                            return false;
                        }
                    }
                    return true;
                });
            });
        },
    },
    methods: {
        handleWindowResize() {
            this.$store.dispatch("updateNavWidth", this.$refs.side.clientWidth);
        },
        trashClick() {
            this.$store.dispatch("trashClick");
        },
        getRight() {
            axios
                .get("right/page", {
                    params: {
                        group_id: this.group_id,
                    },
                })
                .then((response) => {
                    this.right = response.data;
                });
        },
        // show: function () {
        //     // 透過 $refs 取得 div 的寬、高
        //     console.log("test:", this.$refs.sidebartogglebtn);
        // },
        isCurrentPage(link) {
            const path = `/${link}`;
            return path === window.location.pathname;
        },
        // toggle() {
        //     if (this.$refs.inf.clientWidth == 224) {
        //         this.check_sidebar_base = false;
        //         this.check_sidebar_collapsed = true;
        //     }
        //     if (this.$refs.inf.clientWidth == 104) {
        //         this.check_sidebar_collapsed = false;
        //         this.check_sidebar_base = true;
        //     }
        // },
        toggle() {
            // console.log(this.$refs.inf.clientWidth);
            if (this.currentToggle == true) {
                this.currentToggle = false;
            } else {
                this.currentToggle = true;
            }
            // if (this.$refs.inf.clientWidth == 224) {
            //     this.currentToggle = true;
            // }
            // if (this.$refs.inf.clientWidth == 104) {
            //     this.currentToggle = false;
            // }
        },
    },
};
</script>

<style lang="scss" scoped>
.navbar {
    padding: 0;
}
#title {
    font-size: 2rem;
}
.base {
    position: relative;
}
.nav-item {
    a.nav-link {
        .headerStyle {
            font-size: 1rem;
            // font-weight: 500;
            // font-stretch: normal;
            // font-style: normal;
            // line-height: normal;
            // letter-spacing: normal;
            color: #fff;
        }
        &:hover .headerStyle {
            color: #707070;
        }
    }

    // &:focus,
    // &:hover {
    //     background-color: transparent !important;

    //     .headerStyle {
    //         color: #d4e2f4 !important;
    //     }
    // }
}
.navCover {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
}
.navCoverShowAnimation-enter-active,
.navCoverShowAnimation-leave-active {
    transition: 0.5s;
}
.navCoverShowAnimation-enter,
.navCoverShowAnimation-leave-to {
    opacity: 0;
}
.navCoverShowAnimation-leave,
.navCoverShowAnimation-enter-to {
    opacity: 10;
}
</style>
