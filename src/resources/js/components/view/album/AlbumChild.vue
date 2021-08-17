
<template>
    <div class="container">
        <div class="d-flex flex-column pt-5">
            <div class="d-flex flex-row justify-content-start">
                <!-- <div class="dropdown no-arrow">
                    <a
                        class="nav-link dropdown-toggle"
                        role="button"
                        id="dropdownUploadMenuLink"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                    >
                        <i class="fas fa-ellipsis-v"></i>
                        上傳
                    </a>

                    <div
                        class="dropdown-menu"
                        aria-labelledby="dropdownUploadMenuLink"
                    >
                        <label>
                            <a class="dropdown-item">照片</a>
                            <input
                                v-show="false"
                                type="file"
                                accept="image/jpeg"
                                @change="handleFileUpload"
                            />
                        </label>
                    </div>
                </div> -->
                <div class="dropdown">
                    <b-button
                        @click="isUplodeModal = !isUplodeModal"
                        type="is-text"
                        >上傳</b-button
                    >
                </div>
                <div class="dropdown">
                    <b-button @click="SelectModalChange()" type="is-text"
                        >選擇</b-button
                    >
                </div>
                <input
                    type="checkbox"
                    ref="allCheckbox"
                    @change="selectPhotoAll()"
                    v-show="false"
                />
                <b-button
                    :disabled="!isSelectModal"
                    @click="$refs.allCheckbox.click()"
                    type="is-text"
                    >全選</b-button
                >
                <div class="dropdown">
                    <b-button
                        :disabled="!isSelectModal"
                        @click="deletePhoto()"
                        type="is-text"
                        >刪除</b-button
                    >
                </div>
            </div>
        </div>
        <tag-filter
            :tagData="tagData"
            :userTagData="userTagData"
            :isAddTagModal="isAddTagModal"
            :departmentId="department_id"
            @setTagFilter="tagDataFilterId = $event"
            @setUserTagFilter="tagUserDataFilterId = $event"
            @addModalChange="isAddTagModal = !isAddTagModal"
            @addTag="tagData = [...tagData, $event]"
            @deleteTag="deleteTag"
        ></tag-filter>
        <div id="trashCustom">
            <section v-show="isUplodeModal">
                <b-field>
                    <b-upload
                        v-model="dropFiles"
                        multiple
                        accept="image/jpeg"
                        drag-drop
                    >
                        <section class="section">
                            <div class="content has-text-centered">
                                <!-- <p>
                                    <b-icon icon="upload" size="is-large">
                                    </b-icon>
                                </p> -->
                                <p>拖曳檔案至此或者點擊</p>
                                <span>格式僅限.jpg</span>
                            </div>
                        </section>
                    </b-upload>
                </b-field>

                <!-- <div class="tags">
                    <span
                        v-for="(file, index) in dropFiles"
                        :key="index"
                        class="tag is-primary"
                    >
                        {{ file.name }}
                        <button
                            class="delete is-small"
                            type="button"
                            @click="deleteDropFile(index)"
                        ></button>
                    </span>
                </div> -->
            </section>
        </div>
        <div v-show="!isUplodeModal">
            <div class="row d-flex flex-row">
                <div
                    class="col-lg-2 col-md-3 col-sm-4"
                    v-for="(albumParent, index) in photoDataFilter"
                    :key="index"
                >
                    <div class="index-box">
                        <img
                            ref="img"
                            class="index-img"
                            :src="
                                'album/' +
                                albumParent.album_id +
                                '/small/' +
                                albumParent.path
                            "
                            @click="
                                isSelectModal
                                    ? selectIMG(index)
                                    : accessChild(albumParent.photo_id)
                            "
                            @contextmenu.prevent="
                                $refs.menu.open($event, albumParent.photo_id)
                            "
                        />
                        <input
                            ref="checkbox"
                            class="checkbox"
                            :class="classList.checkBoxDisplay"
                            type="checkbox"
                            v-model="selectData"
                            :value="albumParent.photo_id"
                            @change="borderChange(index)"
                        />
                    </div>
                </div>
            </div>

            <b-modal :active.sync="isSwiper" scroll="clip">
                <intro-swiper
                    :swiperInfo="photoDataFilter"
                    :photoId="selectedPhotoId"
                ></intro-swiper>
            </b-modal>
        </div>

        <context-menu
            ref="menu"
            @targetChange="targetPhotoId = $event"
            :navWidth="navWidth"
        >
            <template slot-scope="{ contextData }">
                <context-menu-item @click.native="tagEditToogle">
                    標籤編輯
                </context-menu-item>
            </template>
        </context-menu>

        <transition name="tagEditBackgroundShow">
            <div
                v-show="tagEditShow"
                class="tagEditBackground"
                @click="tagEditToogle()"
            ></div>
        </transition>
        <transition name="tagEditSlide">
            <div v-show="tagEditShow" class="tagEdit">
                <tag-edit
                    :id="targetPhotoId"
                    :tagData="tagData"
                    :userTagData="userTagData"
                    @refresh="getPhotos"
                ></tag-edit>
            </div>
        </transition>

        <b-loading
            :active.sync="isLoading"
            :is-full-page="true"
            v-model="isLoading"
            :can-cancel="false"
        ></b-loading>
    </div>
</template>

<script>
import ContextMenu from "../../ContextMenu/ContextMenu";
import ContextMenuItem from "../../ContextMenu/ContextMenuItem";
import TagEdit from "./modules/TagEdit";
import TagFilter from "./components/TagFilter";
export default {
    components: {
        TagFilter,
        TagEdit,
        ContextMenu,
        ContextMenuItem,
    },
    data() {
        return {
            tagDataFilterId: [],
            tagUserDataFilterId: [],
            //
            targetPhotoId: null,
            tagEditShow: false,
            //
            //tag-filter
            isAddTagModal: false,
            tagData: [],
            userTagData: [],
            department_id: null,
            //
            isUplodeModal: false,
            isSelectModal: false,
            isSwiper: false,
            isLoading: false,
            album_id: null,
            selectedPhotoId: null,
            photoData: [],
            selectData: [],
            file: null,
            dropFiles: [],
            classList: {
                checkBoxDisplay: { checkBoxDisplay: true },
            },
            rightMenuObj: {
                text: ["詳細", "標籤"],
                handler: {
                    content() {
                        console.log("檢視資料詳細");
                    },
                    tag() {
                        this.$emit("clicked", this.rightMenuList);
                        // emit("tagTrigger");
                        console.log("複製使用者id點選事件");
                    },
                },
            },
        };
    },
    created() {
        !sessionStorage.token ? (window.location.pathname = "/") : "";
        this.album_id = window.location.href.split("?")[1];
        this.getAlbumInfo().then(() => {
            this.getTags();
            this.getUserTags();
        });
    },
    mounted() {
        // 監聽全域性點選事件
        document.addEventListener("click", () => {
            this.isAddTagModal = false;
        });
        this.getPhotos();
    },
    computed: {
        navWidth: function () {
            return this.$store.getters.navWidth;
        },
        trashClick: function () {
            return this.$store.getters.trashClick;
        },
        photoDataFilter: function () {
            if (
                this.tagDataFilterId.length === 0 &&
                this.tagUserDataFilterId.length === 0
            ) {
                return this.photoData;
            } else {
                let tag = this.photoData.filter((item) => {
                    let hasTag = item.tags.map((element) => element.tag_id);
                    let result = this.tagDataFilterId.filter(
                        (e) => hasTag.indexOf(e) !== -1
                    );
                    return result.length === 0 ? false : true;
                });
                let userTag = this.photoData.filter((item) => {
                    let hasUserTag = item.user_tags.map(
                        (element) => element.profile.user_tag_id
                    );
                    let userTagresult = this.tagUserDataFilterId.filter(
                        (e) => hasUserTag.indexOf(e) !== -1
                    );
                    return userTagresult.length === 0 ? false : true;
                });

                let result = new Set(tag);
                let repeat = new Set();
                userTag.forEach((item) => {
                    console.log(result.has(item));
                    result.has(item) ? repeat.add(item) : result.add(item);
                });
                return result;
            }
        },
    },
    watch: {
        trashClick: function (n, o) {
            this.tagEditToogle();
        },
        isSelectModal: function (n, o) {
            this.classList.checkBoxDisplay.checkBoxDisplay = !n;
        },
        dropFiles: function (n, o) {
            if (n.length !== 0) {
                this.handleFileUpload();
            }
        },
    },
    filters: {
        // small: function (url) {
        //     return url.replace(".jpg", "_small.jpg");
        // },
        // urlSplitAlbum: function (url) {
        //     return url.replace("album/", "");
        // },
    },
    methods: {
        tagEditToogle() {
            this.$refs.menu.close();
            this.tagEditShow = !this.tagEditShow;
            this.$store.dispatch("navCoverToggle");
        },
        deleteDropFile(index) {
            this.dropFiles.splice(index, 1);
        },
        selectPhotoAll() {
            if (this.$refs.checkbox) {
                if (this.$refs.allCheckbox.checked == true) {
                    this.$refs.checkbox.forEach((val, index) =>
                        val.checked == false ? val.click() : false
                    );
                } else {
                    this.$refs.checkbox.forEach((val, index) =>
                        val.checked == true ? val.click() : false
                    );
                }
            }
        },
        async newPhoto() {
            this.isLoading = true;
            let formData = new FormData();
            if (this.album_id) {
                formData.append("album_id", this.album_id);
            }
            if (this.dropFiles) {
                for (var index in this.dropFiles) {
                    formData.append("album_files[]", this.dropFiles[index]);
                }
            }
            console.log(this.dropFiles);
            // if (this.file) {
            //     formData.append("album_file", this.file);
            // }
            await axios
                .post("album/child/newPhoto", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                })
                .then((response) => {
                    if (response.data.result == true) {
                        this.getPhotos().then(
                            this.$buefy.toast.open({
                                message: "新增成功",
                                type: "is-success",
                                queue: false,
                            })
                        );
                    }
                })
                .catch((error) => {
                    if (error) {
                        this.$buefy.toast.open({
                            message: error.response.data.error,
                            type: "is-danger",
                            queue: false,
                        });
                    }
                })
                .finally(() => {
                    this.dropFiles = [];
                    this.isLoading = false;
                });
        },
        async deletePhoto() {
            if (this.selectData.length === 0) {
                return;
            }
            this.isLoading = true;
            await axios
                .post("album/child/deletePhoto", {
                    photo_id: this.selectData,
                })
                .then((response) => {
                    if (response.data.result == true) {
                        this.$refs.img.forEach((val, index) =>
                            val.classList.remove("selectBord")
                        );
                        this.getPhotos().then(
                            this.$buefy.toast.open({
                                message: "移至垃圾桶",
                                queue: false,
                            })
                        );
                        this.selectData = [];
                    }
                })
                .catch((error) => {
                    if (error) {
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
        selectIMG(index) {
            if (this.$refs.checkbox && this.$refs.img) {
                this.$refs.checkbox[index].click();
            }
        },
        borderChange(index) {
            this.$refs.img[index].classList.toggle("selectBord");
        },
        SelectModalChange() {
            this.isSelectModal = !this.isSelectModal;
            this.$refs.checkbox.forEach((val, index) =>
                val.checked == true ? val.click() : false
            );
        },
        accessChild(photo_id) {
            this.selectedPhotoId = photo_id;
            this.isSwiper = true;
        },
        async getPhotos() {
            this.isLoading = true;
            await axios
                .get("album/child/index", {
                    params: { album_id: this.album_id },
                })
                .then((response) => {
                    if (response.data.result == true) {
                        this.photoData = response.data.data;
                    }
                })
                .catch({})
                .finally(() => {
                    this.isLoading = false;
                });
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
                    message: file.name + " 超過上限5MB",
                    type: "is-danger",
                    queue: false,
                });
                result = false;
            }
            return result;
        },
        handleFileUpload(e) {
            const check = [];
            this.dropFiles.forEach((element) => {
                check.push(this.checkFile(element));
            });

            if (check.find((element) => element === false) === undefined) {
                this.newPhoto();
            } else {
                this.dropFiles = [];
            }

            // const files = e.target.files || e.dataTransfer.files;
            // const file = files[0];
            // if (this.checkFile(file)) {
            //     console.log(file);
            //     this.file = file;
            //     this.newPhoto();
            // }
        },
        async getAlbumInfo() {
            await axios
                .get("album/child/getAlbumInfo", {
                    params: { album_id: this.album_id },
                })
                .then((response) => {
                    if (response.data.result == true) {
                        console.log(response.data.data);
                        this.department_id = response.data.data.department_id;
                    }
                })
                .catch({})
                .finally(() => {});
        },
        deleteTag(item) {
            this.tagData = this.tagData.filter(
                (element) => element.tag_id !== item.tag_id
            );
        },
        async getTags() {
            this.isLoading = true;
            console.log(this.department_id);
            await axios
                .get("album/tag/Index", {
                    params: { department_id: this.department_id },
                })
                .then((response) => {
                    if (response.data.result == true) {
                        this.tagData = response.data.data;
                    }
                })
                .catch({})
                .finally(() => {
                    this.isLoading = false;
                });
        },
        async getUserTags() {
            this.isLoading = true;
            console.log(this.department_id);
            await axios

                .get("album/tag/userTagIndex", {
                    params: { department_id: this.department_id },
                })
                .then((response) => {
                    if (response.data.result == true) {
                        this.userTagData = response.data.data;
                    }
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
#trashCustom {
    .section {
        padding: 35vh 30vw;
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
    .checkBoxDisplay {
        display: none;
    }
    .selectBord {
        color: rgba(37, 37, 252, 0.838);
        border: solid;
    }
}
.tagEdit {
    background: white;
    position: absolute;
    width: 30vw;
    height: 100vh;
    top: 0;
    right: 0;
}
.tagEditSlide-enter-active,
.tagEditSlide-leave-active {
    transition: 0.5s;
}

.tagEditSlide-enter,
.tagEditSlide-leave-to {
    opacity: 0;
    right: -60vw;
}
.tagEditSlide-leave,
.tagEditSlide-enter-to {
    opacity: 10;
    right: 0;
}
.tagEditBackground {
    position: absolute;
    top: 0;
    left: -50vw;
    width: 150vw;
    height: 100vh;
    background-color: rgba(0, 0, 0, 0.5);
}
.tagEditBackgroundShow-enter-active,
.tagEditBackgroundShow-leave-active {
    transition: 0.5s;
}
.tagEditBackgroundShow-enter,
.tagEditBackgroundShow-leave-to {
    opacity: 0;
}
.tagEditBackgroundShow-leave,
.tagEditBackgroundShow-enter-to {
    opacity: 10;
}
</style>