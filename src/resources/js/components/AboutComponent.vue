<template>
    <div class="row align-items-center">
        <div class="col-xl-3 col-lg-4 col-md-5">
            <div>
                <label>
                    <img
                        :src="logo || def_avatar"
                        alt="logo"
                        width="150px;"
                        height="150px;"
                        class="image"
                    />
                    <!-- <img v-if="isPNG" :src="'data:image/png;base64,'+logo" alt="logo" class="image" /> -->
                    <!-- <img v-else :src="'data:image/jpeg;base64,'+logo" alt="logo" class="image" /> -->
                    <div v-show="false">
                        <input
                            type="file"
                            id="logo_name"
                            accept="image/jpeg, image/png"
                            @change="onImageChange"
                        />
                    </div>
                </label>
                <b-button @click="uploadimg()">upload</b-button>
            </div>
            <div>
                <b-field label="info">
                    <b-input
                        maxlength="200"
                        type="textarea"
                        v-model="info"
                    ></b-input>
                </b-field>
                <b-button @click="uploadinfo()">upload</b-button>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data: function () {
        return {
            logo: "",
            info: null,
            message: null,
            error: null,
            school: null,
            isLoading: false,
            isPNG: true,
            def_avatar: "images/img_profile_default.png",
        };
    },
    mounted() {
        !sessionStorage.token ? (window.location.pathname = "/") : "";
        this.school = sessionStorage.school;
        this.getinfo();
        this.checkPNG();
    },
    methods: {
        async getinfo() {
            try {
                this.isLoading = true;
                const response = await axios
                    .post("getinfo", {
                        school_id: this.school,
                    })
                    .then((response) => {
                        this.logo = response.data.school_log;
                        this.info = response.data.school_info;
                    });
            } catch (e) {
            } finally {
                this.isLoading = false;
            }
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
                this.logo = image;
                // console.log("123", image);
            }
        },
        uploadimg() {
            if (this.logo) {
                axios
                    .post("updateimg", {
                        school_id: this.school,
                        school_logo: this.logo,
                    })
                    .then((response) => {
                        this.logo = response.data;
                        this.$buefy.toast.open({
                            message: "上傳成功",
                            type: "is-success",
                            queue: false,
                        });
                    })
                    .catch((error) => {
                        this.$buefy.toast.open({
                            message: "上傳失敗",
                            type: "is-danger",
                            queue: false,
                        });
                    })
                    .finally(() => {
                        axios
                            .post("refresh_info", { school_id: this.school })
                            .then((response) => {
                                sessionStorage.school_info = JSON.stringify(
                                    response.data.data.school_info
                                );
                            });
                    });
            } else {
                return;
            }
        },
        uploadinfo() {
            if (this.info) {
                axios
                    .post("updateinfo", {
                        school_id: this.school,
                        school_info: this.info,
                    })
                    .then((response) => {
                        this.info = response.data;
                        this.$buefy.toast.open({
                            message: "上傳成功",
                            type: "is-success",
                            queue: false,
                        });
                    })
                    .catch((error) => {
                        this.$buefy.toast.open({
                            message: "上傳失敗",
                            type: "is-danger",
                            queue: false,
                        });
                    })
                    .finally(() => {
                        axios
                            .post("refresh_info", { school_id: this.school })
                            .then((response) => {
                                sessionStorage.school_info = JSON.stringify(
                                    response.data.data.school_info
                                );
                            });
                    });
            } else {
                return;
            }
        },
        checkPNG() {
            if (this.logo.indexOf("/9j/", 0) != -1) {
                return (this.logo = true);
            } else {
                return (this.logo = false);
            }
        },
    },
};
</script>