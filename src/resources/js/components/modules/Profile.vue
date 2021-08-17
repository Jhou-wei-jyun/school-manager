<template>
    <div>
        <nav
            class="navbar navbar-expand navbar-light bg-transparent topbar mt-3"
        >
            <!-- Sidebar Toggle (Topbar) -->
            <!-- Nav Item - User Information -->

            <!-- Topbar Navbar -->
            <ul class="navbar-nav mr-5 ml-auto">
                <!-- <div class="topbar-divider d-none d-sm-block"></div> -->

                <!-- Nav Item - User Information -->
                <li
                    class="nav-item dropdown card bg-illoly-murasaki"
                    style="border-radius: 30rem"
                >
                    <a
                        class="nav-link dropdown-toggle"
                        href="#"
                        id="userDropdown"
                        role="button"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                    >
                        <img
                            class="img-profile rounded-circle"
                            :src="avatar || def_avatar"
                        />
                        <span
                            class="ml-2 d-none d-lg-inline text-gray-600 small"
                            >{{ name }}</span
                        >
                    </a>
                    <!-- Dropdown - User Information -->
                    <div
                        class="
                            dropdown-menu dropdown-menu-right
                            animated--grow-in
                        "
                        aria-labelledby="userDropdown"
                    >
                        <div v-for="(item, index) in pageIsShow" :key="index">
                            <a class="dropdown-item" :href="item.link">
                                <img :src="item.icon" width="30" />
                                {{ item.name }}
                            </a>

                            <div
                                class="dropdown-divider"
                                v-if="index !== pageIsShow.length - 1"
                            ></div>
                        </div>
                        <a class="dropdown-item" href="/" @click="logOut()">
                            <img src="images/signout_icon.svg" width="30" />
                            登出
                        </a>
                    </div>
                </li>
            </ul>
        </nav>
    </div>
</template>
<script>
import moment from "moment";
import AccInfo from "../view/teacher/Accountinformation";
export default {
    components: {
        AccInfo,
    },
    data: function () {
        let ption = null;
        ption = [
            {
                link: "account",
                name: "帳號設定",
                icon: "images/setting_icon.svg",
                isShow: false,
            },
            // {
            //     link: "/",
            //     name: "登出",
            //     icon: "images/signout_icon.svg",
            //     isShow: true,
            // },
        ];
        return {
            avatar: null,
            name: null,
            def_avatar: "images/no_photo.png",
            def_camera: "images/btn_camera@2x.png",
            pageItem: ption,
            group_id: null,
            right: null,
        };
    },
    created() {
        this.group_id = sessionStorage.group;
        this.getRight();
        this.avatar = sessionStorage.avatar;
        this.name = sessionStorage.name;
    },
    computed: {
        pageIsShow: function () {
            return this.pageItem.filter((item) => item.isShow == true);
        },
    },
    watch: {
        right: function (n, o) {
            this.pageItem.forEach(function (item, index) {
                n.forEach(function (r) {
                    if (r.page_name == item.name) {
                        if (r.show == 0) {
                            item["isShow"] = false;
                            return false;
                        } else {
                            item["isShow"] = true;
                            return false;
                        }
                    }
                    return true;
                });
            });
        },
    },
    methods: {
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
        logOut() {
            axios.post("logout");
        },
    },
};
</script>
<style lang="scss" scoped>
.avatar {
    position: relative;
    border-radius: 50%;
}
.profile {
    background-color: white;
    border-radius: 0.65rem;
    width: 20vw;
    height: 4vh;
    &_camera {
        position: absolute;
        top: -5px;
        right: 100px;
    }
    &_img {
        border-radius: 50%;
    }
}
.card-body {
    height: 40vh;
}
.marge {
    margin-top: auto;
    margin-bottom: auto;
}
.dropdown-menu {
    min-width: 0;
}
.dropdown-item {
    padding-left: 0 !important;
}
.profile-camera {
    position: absolute;
    right: 25%;
    bottom: 25%;
}
</style>