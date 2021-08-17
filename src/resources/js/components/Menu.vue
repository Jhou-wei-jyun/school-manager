
<template>
    <!-- <div> -->
    <!-- <button
            id="sidebarToggleTop"
            class="topbar btn btn-link fixed-top d-md-none rounded-circle mr-3 z-index-5"
        >
            <i class="fa fa-bars"></i>
    </button>-->

    <!-- new -->
    <div class="container" style="background-color: #fff">
        <!-- Sidebar - Brand -->

        <!-- Nav Item - Dashboard -->

        <b-field class="nav-item" v-for="(item, idx) in navItem" :key="idx">
            <a class="nav-link collapsed" :href="item.link">
                <i
                    class="text-center fas fa-fw fa-tachometer-alt"
                    :class="{ active: isCurrentPage(item.link) }"
                ></i>
                <span class="headerStyle text-center">{{ item.name }}</span>
            </a>

            <hr class="sidebar-divider my-0" />
        </b-field>
    </div>
    <!-- </div> -->

    <!-- End of Sidebar -->

    <!-- old -->
    <!-- <b-navbar type="is-ims" :transparent="!isLoggedIn" wrapper-class="container" fixed-top>
        <template slot="brand">
            <template v-if="isLoggedIn">
                <b-navbar-item href="mainhome">
                    <img src="images/ic_logo@2x.png" />
                </b-navbar-item>
            </template>
            <template v-if="!isLoggedIn">
                <b-navbar-item href="/">
                    <img src="images/ic_logo@2x.png" />
                </b-navbar-item>
            </template>
        </template>
        <template slot="start">
            <template v-if="isLoggedIn">
                <b-navbar-item v-for="(item, idx) in navItem" :key="idx" :href="item.link">
                    <span
                        class="headerStyle"
                        :class="{ active: isCurrentPage(item.link)}"
                    >{{ item.name }}</span>
                </b-navbar-item>
            </template>
        </template>

        <template slot="end">
            <template v-if="isLoggedIn">
                <b-navbar-item>
                    <a class="is-hidden-touch avatar">
                        <img style="border-radius: 50%;" :src="avatar" />
                    </a>
                </b-navbar-item>
                <b-navbar-dropdown :label="account">
                    <b-navbar-item href="about">about</b-navbar-item>
                    <b-navbar-item href="/">Sign out</b-navbar-item>
                </b-navbar-dropdown>
            </template>
        </template>
    </b-navbar>-->
</template>

<script>
export default {
    data: function () {
        let ption = null;
        if (
            sessionStorage.account == "gcreate" ||
            sessionStorage.account == "admin"
        ) {
            ption = [
                {
                    link: "department",
                    name: "Class",
                    permit: 0,
                },
                {
                    link: "employee",
                    name: "Students",
                    permit: 10,
                },
                {
                    link: "teacher",
                    name: "Teachers",
                    permit: 10,
                },
                {
                    link: "parents",
                    name: "Parents",
                    permit: 10,
                },
                {
                    link: "updateadv",
                    name: "Update",
                    permit: 0,
                },
                {
                    link: "notify",
                    name: "Notification.",
                    permit: 0,
                },
                {
                    link: "chart",
                    name: "Chartroom",
                    permit: 0,
                },
                {
                    link: "device",
                    name: "AP Management",
                    permit: 0,
                },
                {
                    link: "area",
                    name: "Area Management",
                    permit: 10,
                },
            ];
        } else {
            ption = [
                {
                    link: "department",
                    name: "Class",
                    permit: 0,
                },
                {
                    link: "employee",
                    name: "Students",
                    permit: 10,
                },
                {
                    link: "teacher",
                    name: "Teachers",
                    permit: 10,
                },
                {
                    link: "parents",
                    name: "Parents",
                    permit: 10,
                },
                {
                    link: "updateadv",
                    name: "Update",
                    permit: 0,
                },
                {
                    link: "notify",
                    name: "Notification.",
                    permit: 0,
                },
                {
                    link: "chart",
                    name: "Chartroom",
                    permit: 0,
                },
            ];
        }
        return {
            account: sessionStorage.account,
            avatar: "images/img_profile_default.png",
            isLoggedIn: false,
            userPermit: null,
            navItem: ption,
        };
    },
    mounted() {
        if (sessionStorage.token) {
            this.isLoggedIn = true;
            this.userPermit = sessionStorage.permit;

            if (this.userPermit == 11) {
                this.navItem = this.navItem.filter((r) => {
                    return r.permit == 0;
                });
            }

            if (this.userPermit == 12) {
                this.navItem = this.navItem.filter((r) => {
                    return r.permit == 0 || r.permit == 12;
                });
            }

            if (sessionStorage.avatar) {
                this.avatar = sessionStorage.avatar;
            }
        }
        this.show();
    },
    computed: {
        currentSide() {
            console.log("success", this.$store.getters.sidebarshow);
            return this.$store.getters.sidebarshow;
        },
    },
    methods: {
        show: function () {
            // 透過 $refs 取得 div 的寬、高
            console.log("test:", this.$refs.sidebartogglebtn);
        },
        isCurrentPage(link) {
            const path = `/${link}`;
            return path === window.location.pathname;
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

a.navbar-item {
    .headerStyle {
        font-family: Archivo;
        font-size: 14px;
        font-weight: 500;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        color: #5c657c;

        &.active {
            color: #5fcab4;
        }
    }

    &:focus,
    &:hover {
        background-color: transparent !important;

        .headerStyle {
            color: #d4e2f4 !important;
        }
    }
}

.avatar {
    padding-top: 6px;
}
</style>
