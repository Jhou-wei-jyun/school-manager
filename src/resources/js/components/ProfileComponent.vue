
<template>
    <!-- <div> -->
    <!-- new -->
    <!-- Content Wrapper -->
    <!-- Topbar -->
    <nav class="navbar navbar-expand navbar-light bg-transparent topbar">
        <!-- Sidebar Toggle (Topbar) -->
        <!-- Nav Item - User Information -->
        <button
            id="sidebarToggleTop"
            class="btn btn-link d-md-none rounded-circle"
            @click="sidebarcontroll"
        >
            <!-- @click="sidebarcontroll" -->
            <!-- @click="showbtn" -->
            <i class="fa fa-bars"></i>
        </button>

        <!-- Topbar Navbar -->
        <ul class="navbar-nav mr-5 ml-auto">
            <!-- <div class="topbar-divider d-none d-sm-block"></div> -->

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
                <a
                    class="nav-link dropdown-toggle"
                    href="#"
                    id="userDropdown"
                    role="button"
                    data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                >
                    <span class="mr-2 d-none d-lg-inline text-gray-600 small"
                        >Valerie Luna</span
                    >
                    <img
                        class="img-profile rounded-circle"
                        src="https://source.unsplash.com/QAB-WJcbgJk/60x60"
                    />
                </a>
                <!-- Dropdown - User Information -->
                <div
                    class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                    aria-labelledby="userDropdown"
                >
                    <a class="dropdown-item" href="about">
                        <i
                            class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"
                        ></i>
                        about
                    </a>
                    <div class="dropdown-divider"></div>
                    <a
                        class="dropdown-item"
                        href="/"
                        data-toggle="modal"
                        data-target="#logoutModal"
                    >
                        <i
                            class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"
                        ></i>
                        Sign out
                    </a>
                </div>
            </li>
        </ul>
    </nav>
    <!-- End of Topbar -->
    <!-- <b-modal :active.sync="isShow" :width="640" scroll="clip">
            <Menu></Menu>
    </b-modal>-->
    <!-- </div> -->
</template>

<script>
import Menu from "./Menu";
export default {
    components: {
        Menu,
    },
    data: function () {
        return {
            isShow: false,
        };
    },
    methods: {
        sidebarcontroll() {
            // console.log("publish sucess");
            this.$store.dispatch("sideshow");
        },
        showbtn() {
            this.isShow = true;
        },
    },
};
</script>

<style lang="scss" scoped>
</style>
