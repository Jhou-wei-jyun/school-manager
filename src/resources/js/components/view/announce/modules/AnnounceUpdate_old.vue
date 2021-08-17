<template>
    <div class="container mt-5">
        <div class="card card-body">
            <div class="d-flex flex-column justify-content-between">
                <div class="row mt-5">
                    <div class="col-4 d-flex flex-column text-right">
                        <span class="h4 mb-5">標題</span>
                        <span class="h4">選擇檔案</span>
                    </div>

                    <div class="col-8 d-flex flex-column">
                        <div>
                            <input
                                v-model="title"
                                type="text"
                                class="field mb-5 h5"
                                required
                            />
                        </div>
                        <div>
                            <label>
                                <div class="field filename mb-3">
                                    <span class="h5">{{ A }}</span>
                                </div>
                                <input
                                    v-show="false"
                                    type="file"
                                    accept="image/jpeg, image/png"
                                    @change="AonImageChange"
                                />
                            </label>
                        </div>
                        <div>
                            <label>
                                <div class="field filename mb-3">
                                    <span class="h5">{{ B }}</span>
                                </div>
                                <input
                                    v-show="false"
                                    type="file"
                                    accept="image/jpeg, image/png"
                                    @change="BonImageChange"
                                />
                            </label>
                        </div>
                        <div>
                            <label>
                                <div class="field filename">
                                    <span class="h5">{{ C }}</span>
                                </div>
                                <input
                                    v-show="false"
                                    type="file"
                                    accept="image/jpeg, image/png"
                                    @change="ConImageChange"
                                />
                            </label>
                        </div>
                    </div>
                </div>

                <footer class="card-bottom">
                    <b-button
                        size="is-medium"
                        class="notification_btn notification_btn_green shadow animate__animated animate__fadeIn mt-5"
                        @click="upload()"
                        >上傳</b-button
                    >
                </footer>
            </div>

            <!-- <b-button @click="upload()">upload</b-button> -->
        </div>
    </div>
</template>
<script>
export default {
    data: function () {
        return {
            // error: null,
            id: null,
            school: null,
            title: null,
            A: null,
            B: null,
            C: null,
            A_file: "",
            B_file: "",
            C_file: "",
        };
    },
    mounted() {
        this.school = sessionStorage.school;
        this.id = sessionStorage.id;
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
        async AonImageChange(e) {
            const files = e.target.files || e.dataTransfer.files;
            const file = files[0];
            this.A = file.name;
            if (this.checkFile(file)) {
                const image = await this.getBase64(file);
                this.A_file = image;
            }
        },
        async BonImageChange(e) {
            const files = e.target.files || e.dataTransfer.files;
            const file = files[0];
            this.B = file.name;
            if (this.checkFile(file)) {
                const image = await this.getBase64(file);
                this.B_file = image;
            }
        },
        async ConImageChange(e) {
            const files = e.target.files || e.dataTransfer.files;
            const file = files[0];
            this.C = file.name;
            if (this.checkFile(file)) {
                const image = await this.getBase64(file);
                this.C_file = image;
            }
        },
        upload() {
            if (this.title == null) {
                return;
            }
            if (this.A_file == "") {
                return;
            }
            axios
                .post("announcement/update", {
                    user_id: this.id,
                    school_id: this.school,
                    title: this.title,
                    A_name: this.A,
                    B_name: this.B,
                    C_name: this.C,
                    A: this.A_file,
                    B: this.B_file,
                    C: this.C_file,
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
                    this.A_file = null;
                    this.B = null;
                    this.B_file = null;
                    this.C = null;
                    this.C_file = null;
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
