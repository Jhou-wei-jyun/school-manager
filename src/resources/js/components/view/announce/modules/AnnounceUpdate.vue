<template>
    <div class="card card-body">
        <div class="d-flex flex-column justify-content-between">
            <div class="row mt-5">
                <div class="col-3 d-flex flex-column text-right">
                    <span class="h4 mb-5">標題</span>
                </div>

                <div class="col-5 d-flex flex-column">
                    <div>
                        <input
                            v-model="title"
                            type="text"
                            class="field mb-5 h5"
                            required
                        />
                    </div>
                    <div>
                        <div class="field filename mb-3">
                            <span class="h5">{{ A }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-4 d-flex flex-column justify-content-end pb-1">
                    <label class="d-flex flex-row justify-content-start ml-2">
                        <b-button
                            @click="$refs.file.click()"
                            size="is-small"
                            class="notification_btn notification_btn_sky notification_btn_text_white animate__animated animate__fadeIn"
                            >選擇檔案</b-button
                        >
                        <input
                            ref="file"
                            v-show="false"
                            type="file"
                            accept="image/jpeg"
                            @change="handleFileUpload"
                        />
                    </label>
                </div>
            </div>

            <footer class="card-bottom">
                <b-button
                    size="is-small"
                    class="notification_btn notification_btn_green shadow animate__animated animate__fadeIn mt-5"
                    @click="upload()"
                    >上傳</b-button
                >
            </footer>
        </div>
        <b-loading
            :active.sync="isLoading"
            :is-full-page="true"
            v-model="isLoading"
            :can-cancel="false"
        ></b-loading>

        <!-- <b-button @click="upload()">upload</b-button> -->
    </div>
</template>
<script>
export default {
    props: ["admin"],
    data: function () {
        return {
            isLoading: false,
            // error: null,
            school: null,
            title: null,
            A: null,
            // A_file: "",
            file: null,
        };
    },
    mounted() {
        this.school = sessionStorage.school;
    },
    methods: {
        refresh() {
            this.$emit("refresh");
        },
        checkFile(file) {
            let result = true;
            const SIZE_LIMIT = 5242880; // 5MB
            if (!file) {
                result = false;
            }
            if (file.type !== "image/jpeg") {
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
        // getBase64(file) {
        //     return new Promise((resolve, reject) => {
        //         const reader = new FileReader();
        //         reader.readAsDataURL(file);
        //         reader.onload = () => resolve(reader.result);
        //         reader.onerror = (error) => reject(error);
        //     });
        // },
        // async AonImageChange(e) {
        //     const files = e.target.files || e.dataTransfer.files;
        //     const file = files[0];
        //     this.A = file.name;
        //     if (this.checkFile(file)) {
        //         const image = await this.getBase64(file);
        //         this.A_file = image;
        //     }
        // },
        async handleFileUpload(e) {
            const files = e.target.files || e.dataTransfer.files;
            const file = files[0];
            this.A = file.name;
            if (this.checkFile(file)) {
                this.file = file;
                console.log("get", this.file);
            }
        },

        upload() {
            if (this.title == null) {
                return;
            }
            // if (this.A_file == "") {
            //     return;
            // }
            let formData = new FormData();
            if (this.admin) {
                formData.append("admin_id", this.admin);
            }
            if (this.school) {
                formData.append("school_id", this.school);
            }
            if (this.title) {
                formData.append("title", this.title);
            }
            if (this.A) {
                formData.append("A_name", this.A);
            }
            // if (this.A_file) {
            //     formData.append("A", this.A_file);
            // }
            if (this.file) {
                formData.append("avatar_file", this.file);
            }
            this.isLoading = true;
            axios
                .post("announcement/store", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                })
                .then((response) => {
                    this.avatar = response.data;
                    this.$buefy.toast.open({
                        message: "上傳成功",
                        type: "is-success",
                        queue: false,
                    });
                    this.refresh();
                })
                .catch((error) => {
                    this.$buefy.toast.open({
                        message: "上傳失敗",
                        type: "is-danger",
                        queue: false,
                    });
                })
                .finally(() => {
                    this.title = null;
                    this.A = null;
                    // this.A_file = "";
                    this.isLoading = false;
                    this.$parent.close();
                    // axios
                    //     .post("refresh_info", { school_id: this.school })
                    //     .then((response) => {
                    //         sessionStorage.school_info = JSON.stringify(
                    //             response.data.data.school_info
                    //         );
                    //         this.fresh();
                    //     })
                    //     .finally(() => {
                    //         this.$parent.close();
                    //     });
                });
        },
    },
};
</script>

<style lang="scss" scoped>
.card {
    box-shadow: none;
    width: 55vw;
}
.field {
    background-color: white;
    border-radius: 0.65rem;
    border: 1px solid black;
    width: 20vw;
    height: 4vh;
}
.filename {
    white-space: nowrap;
    overflow: hidden;
}
</style>
