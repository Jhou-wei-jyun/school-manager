<template>
    <div class="container">
        <b-button @click="SelectModalChange('album')" type="is-text"
            >選擇</b-button
        >
        <input
            type="checkbox"
            ref="albumAllCheckbox"
            @change="selectAlbumAll()"
            v-show="false"
        />
        <b-button
            :disabled="!isAlbumSelectModal"
            @click="$refs.albumAllCheckbox.click()"
            type="is-text"
            >全選</b-button
        >
        <b-button
            :disabled="!isAlbumSelectModal"
            @click="deleteAlbumFromTrash()"
            type="is-text"
            >刪除</b-button
        >
        <b-button
            :disabled="!isAlbumSelectModal"
            @click="restoreAlbumFromTrash()"
            type="is-text"
            >復原</b-button
        >
        <hr class="divider my-0" />

        <div class="row d-flex flex-row scroll-album">
            <div
                class="col-lg-3 col-md-4 col-sm-6"
                style="padding: 15px 15px"
                v-for="(albumItem, index) in trashData.album"
                :key="index"
            >
                <div class="card" ref="card">
                    <div
                        class="cover"
                        :style="styleList"
                        @click="selectAlbum(index)"
                        v-show="isAlbumSelectModal"
                    ></div>
                    <div class="card-body ml-auto mr-auto">
                        <!-- <div class="topbar-divider d-none d-sm-block"></div> -->
                        <div>
                            <img
                                :src="
                                    'album/avatar/small/' + albumItem.albumImage
                                "
                                width="100"
                                style="height: 100px"
                            />
                            <input
                                ref="albumCheckbox"
                                class="checkbox"
                                v-show="isAlbumSelectModal"
                                type="checkbox"
                                v-model="albumSelectData"
                                :value="albumItem.album_id"
                                @change="cardborderChange(index)"
                            />
                        </div>
                    </div>

                    <div class="card-footer">
                        <span class="ml-auto mr-auto">{{
                            albumItem.albumTitle
                        }}</span>
                    </div>
                </div>
            </div>
        </div>
        <b-button @click="SelectModalChange('photo')" type="is-text"
            >選擇</b-button
        >
        <input
            type="checkbox"
            ref="photoAllCheckbox"
            @change="selectPhotoAll()"
            v-show="false"
        />
        <b-button
            :disabled="!isPhotoSelectModal"
            @click="$refs.photoAllCheckbox.click()"
            type="is-text"
            >全選</b-button
        >
        <b-button
            :disabled="!isPhotoSelectModal"
            @click="deletePhotoFromTrash()"
            type="is-text"
            >刪除</b-button
        >
        <b-button
            :disabled="!isPhotoSelectModal"
            @click="restorePhotoFromTrash()"
            type="is-text"
            >復原</b-button
        >
        <hr class="divider my-0" />
        <div class="row d-flex flex-row scroll-photo">
            <div
                class="col-lg-2 col-md-3 col-sm-4"
                v-for="(photoItem, index) in trashData.photo"
                :key="index"
            >
                <div class="index-box">
                    <img
                        ref="img"
                        class="index-img"
                        :src="
                            'album/' +
                            photoItem.album_id +
                            '/small/' +
                            photoItem.path
                        "
                        @click="isPhotoSelectModal ? selectIMG(index) : false"
                    />

                    <input
                        v-show="isPhotoSelectModal"
                        ref="photoCheckbox"
                        class="checkbox"
                        type="checkbox"
                        v-model="photoSelectData"
                        :value="photoItem.photo_id"
                        @change="borderChange(index)"
                    />
                </div>
            </div>
        </div>
        <b-loading
            :active.sync="isLoading"
            :is-full-page="true"
            v-model="isLoading"
            :can-cancel="false"
        ></b-loading>
    </div>
</template>

<script>
export default {
    components: {},
    props: ["trigger"],
    data: function () {
        return {
            isAlbumSelectModal: false,
            isPhotoSelectModal: false,
            isLoading: false,
            department_id: null,
            trashData: [],
            albumSelectData: [],
            photoSelectData: [],
            styleList: {},
        };
    },
    watch: {},
    created() {
        !sessionStorage.token ? (window.location.pathname = "/") : "";
        this.department_id = window.location.href.split("?")[1];
    },
    mounted() {
        this.getTrash();
    },
    filters: {
        // urlSplitAlbum: function (url) {
        //     return url.replace("album/", "");
        // },
    },
    watch: {
        trigger: function (n, o) {
            this.getTrash();
        },
    },
    computed: {
        // departmentIsFiltered: function () {
        //     if (this.teacher_id === null) {
        //         return this.departmentsData;
        //     } else {
        //         let teacher = this.teacher_id;
        //         let filtered = {};
        //         for (let [key, value] of Object.entries(this.departmentsData)) {
        //             let result = value.filter(
        //                 (v) => v.supervisor_id === teacher
        //             );
        //             filtered[key] = result;
        //         }
        //         return filtered;
        //     }
        // },
    },
    methods: {
        refresh() {
            this.$emit("refresh");
        },
        async restoreAlbumFromTrash() {
            if (this.albumSelectData.length === 0) {
                return;
            }
            this.isLoading = true;
            await axios
                .post("album/restoreAlbumFromTrash", {
                    album_id: this.albumSelectData,
                })
                .then((response) => {
                    if (response.data.result == true) {
                        this.$refs.card.forEach((val, index) =>
                            val.classList.remove("selectBord")
                        );
                        this.getTrash().then(
                            this.$buefy.toast.open({
                                message: "已復原",
                                queue: false,
                            })
                        );
                        this.refresh();
                        this.albumSelectData = [];
                    }
                })
                .catch((error) => {
                    if (error) {
                        console.log(error);
                        this.$buefy.toast.open({
                            message: error.response.data.error,
                            type: "is-danger",
                            queue: false,
                        });
                    }
                    this.isLoading = false;
                })
                .finally(() => {});
        },
        async restorePhotoFromTrash() {
            if (this.photoSelectData.length === 0) {
                return;
            }
            this.isLoading = true;
            await axios
                .post("album/restorePhotoFromTrash", {
                    photo_id: this.photoSelectData,
                })
                .then((response) => {
                    if (response.data.result == true) {
                        this.$refs.img.forEach((val, index) =>
                            val.classList.remove("selectBord")
                        );
                        this.getTrash().then(
                            this.$buefy.toast.open({
                                message: "已復原",
                                queue: false,
                            })
                        );
                        this.refresh();
                        this.photoSelectData = [];
                    }
                })
                .catch((error) => {
                    if (error) {
                        console.log(error);
                        this.$buefy.toast.open({
                            message: error.response.data.error,
                            type: "is-danger",
                            queue: false,
                        });
                    }
                    this.isLoading = false;
                })
                .finally(() => {});
        },
        async deleteAlbumFromTrash() {
            if (this.albumSelectData.length === 0) {
                return;
            }
            this.isLoading = true;
            await axios
                .post("album/deleteAlbumFromTrash", {
                    album_id: this.albumSelectData,
                })
                .then((response) => {
                    if (response.data.result == true) {
                        this.$refs.card.forEach((val, index) =>
                            val.classList.remove("selectBord")
                        );
                        this.getTrash().then(
                            this.$buefy.toast.open({
                                message: "已刪除",
                                queue: false,
                            })
                        );
                        this.refresh();
                        this.albumSelectData = [];
                    }
                })
                .catch((error) => {
                    if (error) {
                        console.log(error);
                        this.$buefy.toast.open({
                            message: error.response.data.error,
                            type: "is-danger",
                            queue: false,
                        });
                    }
                    this.isLoading = false;
                })
                .finally(() => {});
        },
        async deletePhotoFromTrash() {
            if (this.photoSelectData.length === 0) {
                return;
            }
            this.isLoading = true;
            await axios
                .post("album/deletePhotoFromTrash", {
                    photo_id: this.photoSelectData,
                })
                .then((response) => {
                    if (response.data.result == true) {
                        this.$refs.img.forEach((val, index) =>
                            val.classList.remove("selectBord")
                        );
                        this.getTrash().then(
                            this.$buefy.toast.open({
                                message: "已刪除",
                                queue: false,
                            })
                        );
                        this.refresh();
                        this.photoSelectData = [];
                    }
                })
                .catch((error) => {
                    if (error) {
                        console.log(error);
                        this.$buefy.toast.open({
                            message: error.response.data.error,
                            type: "is-danger",
                            queue: false,
                        });
                    }
                    this.isLoading = false;
                })
                .finally(() => {});
        },
        selectAlbumAll() {
            if (this.$refs.albumCheckbox) {
                if (this.$refs.albumAllCheckbox.checked == true) {
                    this.$refs.albumCheckbox.forEach((val, index) =>
                        val.checked == false ? val.click() : false
                    );
                } else {
                    this.$refs.albumCheckbox.forEach((val, index) =>
                        val.checked == true ? val.click() : false
                    );
                }
            }
        },
        selectPhotoAll() {
            if (this.$refs.photoCheckbox) {
                if (this.$refs.photoAllCheckbox.checked == true) {
                    this.$refs.photoCheckbox.forEach((val, index) =>
                        val.checked == false ? val.click() : false
                    );
                } else {
                    this.$refs.photoCheckbox.forEach((val, index) =>
                        val.checked == true ? val.click() : false
                    );
                }
            }
        },
        SelectModalChange(type) {
            if (type === "album") {
                this.isAlbumSelectModal = !this.isAlbumSelectModal;
                this.$refs.albumCheckbox.forEach((val, index) =>
                    val.checked == true ? val.click() : false
                );
                this.$refs.albumAllCheckbox.checked == true
                    ? this.$refs.albumAllCheckbox.click()
                    : false;
                this.styleList = {
                    width: this.$refs.card[0].clientWidth + "px",
                    height: this.$refs.card[0].clientHeight + "px",
                };
            }
            if (type === "photo") {
                this.isPhotoSelectModal = !this.isPhotoSelectModal;
                this.$refs.photoCheckbox.forEach((val, index) =>
                    val.checked == true ? val.click() : false
                );
                this.$refs.photoAllCheckbox.checked == true
                    ? this.$refs.photoAllCheckbox.click()
                    : false;
            }
        },
        photoAllCheck() {
            this.$refs.photoCheckbox.forEach((val, index) =>
                val.checked == true ? val.click() : false
            );
        },
        selectAlbum(index) {
            if (this.$refs.albumCheckbox && this.$refs.card) {
                this.$refs.albumCheckbox[index].click();
            }
        },
        selectIMG(index) {
            if (this.$refs.photoCheckbox && this.$refs.img) {
                this.$refs.photoCheckbox[index].click();
            }
        },
        cardborderChange(index) {
            this.$refs.card[index].classList.toggle("selectBord");
        },
        borderChange(index) {
            this.$refs.img[index].classList.toggle("selectBord");
        },
        async getTrash() {
            this.isLoading = true;
            await axios
                .get("album/trashIndex", {
                    params: { department_id: this.department_id },
                })
                .then((response) => {
                    this.trashData = response.data;
                })
                .catch({})
                .finally(() => {
                    this.isLoading = false;
                });
        },
    },
};
</script>

<style lang="scss" scoped>
//
.scroll-album {
    overflow-y: scroll;
    height: 25vh;
}
.scroll-photo {
    overflow-y: scroll;
    height: 60vh;
}
.span {
    font-size: 20px;
    font-weight: bold;
}
.divider {
    filter: alpha(opacity=100, finishopacity=0, style=2);
    height: 6px;
}
.dropdown-menu {
    width: clamp(200px, 50vw, 502px);

    &.show {
        display: grid;
        // grid-auto-rows: 100px;
        // grid-auto-columns: 100px;
        // grid-template-columns: repeat(auto-fill, 100px);
        grid-template-columns: repeat(auto-fill, 100px) [last-col] 100px;
        grid-template-rows: auto [last-line];
    }
}
a.dropdown-item {
    // grid-area: auto;
    padding-right: 16px;
}
.item-plus {
    grid-column: last-col / span 1;
    grid-row: 1 / last-line;
}
.img-close {
    position: absolute;
    top: 0;
}
.img-edit {
    position: absolute;
    bottom: 50px;
    right: 0;
}
.card-delete {
    position: absolute;
    top: 0;
    right: 0;
}
.fix {
    padding: 8px 16px;
}
.albumCheckBoxDisplay {
    display: none;
}
.card {
    .cover {
        position: absolute;
        background: transparent;
        border-radius: 1rem;
        // width: 200px;
        // height: 200px;
    }
    .checkbox {
        position: absolute;
        top: 5px;
        left: 5px;
    }
    .albumCheckBoxDisplay {
        display: none;
    }
    &.selectBord {
        color: rgba(37, 37, 252, 0.838);
        border: solid;
    }
}
.index-box {
    position: relative;
    margin: 10px;
    display: flex;
    flex-direction: column;
    justify-content: center; /* Centering y-axis */
    align-items: center; /* Centering x-axis */
    .index-img {
        width: 100px;
        height: 100px;
        overflow: hidden;
    }
    .checkbox {
        position: absolute;
        top: 0px;
        left: 0px;
    }
    .photoCheckBoxDisplay {
        display: none;
    }
    .selectBord {
        color: rgba(37, 37, 252, 0.838);
        border: solid;
    }
}
</style>
