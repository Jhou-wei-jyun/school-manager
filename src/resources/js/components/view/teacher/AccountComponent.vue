<template>
    <div>
        <profile-component></profile-component>
        <div
            class="container mt-5 ml-5 mr-5"
            v-show="
                right['帳號資訊'] == null ? false : right['帳號資訊']['show']
            "
        >
            <div class="row">
                <div class="col-12">
                    <div class="card card-body bg-illoly-murasaki">
                        <div class="row card-body justify-content-center">
                            <!-- <div
                                class="col-4 d-flex justify-content-center align-items-center"
                            >
                                <div class="profile-img">
                                    <img
                                        class="edit_img"
                                        width="125"
                                        style="height: 125px"
                                        :src="avatar || def_avatar"
                                    />
                                    <label style="height: 30px">
                                        <img
                                            class="profile-camera"
                                            width="30"
                                            :src="def_camera"
                                            alt
                                        />

                                    </label>
                                </div>
                            </div> -->

                            <div
                                class="
                                    col-5
                                    d-flex
                                    flex-wrap
                                    justify-content-around
                                "
                            >
                                <div class="row">
                                    <div>
                                        <span class="h4 col-6">姓名</span>
                                    </div>
                                    <div class="profile pl-2 pt-1 col-6">
                                        <span class="h5">{{
                                            infoData.name
                                        }}</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div>
                                        <span class="h4 col-6">帳號</span>
                                    </div>
                                    <div class="profile pl-2 pt-1 col-6">
                                        <span class="h5">{{
                                            infoData.account
                                        }}</span>
                                    </div>
                                </div>
                                <!-- <div class="row">
                                    <div>
                                        <span class="h4 col-6">班級</span>
                                    </div>
                                    <div class="profile pl-2 pt-1 col-6"></div>
                                </div> -->
                                <div class="row">
                                    <div>
                                        <span class="h4 col-6">權限</span>
                                    </div>
                                    <div class="profile pl-2 pt-1 col-6">
                                        <span class="h5">{{
                                            infoData.group_name
                                        }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div
            class="container mt-5 ml-5 mr-5"
            v-show="
                right['教師資訊'] == null ? false : right['教師資訊']['show']
            "
        >
            <AccInfo :right="right" :admin="admin_id"></AccInfo>
        </div>
    </div>
</template>
<script>
import moment from "moment";
// import Profile from "../../Profile";
import AccInfo from "./Accountinformation";
export default {
    components: {
        AccInfo,
    },

    data: function () {
        return {
            account: null,
            avatar: null,
            name: null,
            def_avatar: "images/no_photo.png",
            def_camera: "images/btn_camera@2x.png",
            school: null,
            id: null,
            infoData: [],
            group_id: null,
            right: [],
            admin_id: null,
        };
    },
    created() {
        !sessionStorage.token ? (window.location.pathname = "/") : "";
        this.group_id = sessionStorage.group;
        this.admin_id = sessionStorage.id;
        this.getRight();
    },
    mounted() {
        this.avatar = sessionStorage.avatar;
        this.name = sessionStorage.name;
        this.account = sessionStorage.account;
        this.school = sessionStorage.school;
        this.id = sessionStorage.id;
        this.getInfo();
    },
    methods: {
        getRight() {
            axios
                .get("right/block", {
                    params: {
                        group_id: this.group_id,
                        tab_id: 13, //page_id :13 帳號設定
                    },
                })
                .then((response) => {
                    this.right = response.data;
                });
        },
        getInfo() {
            axios
                .get("account/info", {
                    params: {
                        account: this.account,
                    },
                })
                .then((response) => {
                    this.infoData = response.data;
                })
                .catch({});
        },
        checkFile(file) {
            let result = true;
            const SIZE_LIMIT = 5242880; // 5MB
            if (!file) {
                result = false;
            }
            if (file.type !== "image/jpeg" && file.type !== "image/png") {
                result = false;
            }
            if (file.size > SIZE_LIMIT) {
                this.$buefy.toast.open({
                    message: "檔案上限5MB",
                    type: "is-danger",
                    queue: false,
                });
                result = false;
            }
            return result;
        },
        getBase64(file) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = () => resolve(reader.result);
                reader.onerror = (error) => reject(error);
            });
        },
        async onImageChange(e) {
            const files = e.target.files || e.dataTransfer.files;
            const file = files[0];
            if (this.checkFile(file)) {
                const image = await this.getBase64(file);
                this.avatar = image;
                sessionStorage.avatar = this.avatar;

                await axios
                    .post("account/updateInfo", {
                        school_id: this.school,
                        id: this.id,
                        avatar: this.avatar,
                    })
                    .then((response) => {
                        this.$buefy.toast.open({
                            message: "更新成功",
                            type: "is-success",
                            queue: false,
                        });
                    })
                    .catch((error) => {
                        this.$buefy.toast.open({
                            message: "更新失敗",
                            type: "is-danger",
                            queue: false,
                        });
                    });
            }
        },
    },
};
</script>
<style lang="scss" scoped>
.edit_img {
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
</style>