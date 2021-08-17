<template>
    <div class="container base">
        <div class="d-flex flex-column pt-5">
            <div class="d-flex flex-row justify-content-start">
                <div class="dropdown no-arrow">
                    <a
                        class="nav-link dropdown-toggle"
                        href="#"
                        role="button"
                        id="dropdownAddMenuLink"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                    >
                        <i class="fas fa-ellipsis-v"></i>
                        新增
                    </a>

                    <div
                        class="dropdown-menu"
                        aria-labelledby="dropdownAddMenuLink"
                    >
                        <a
                            class="dropdown-item"
                            @click="isAddAlbum = !isAddAlbum"
                            >相簿</a
                        >
                    </div>
                    <div class="dropdown">
                        <b-button @click="SelectModalChange()">選擇</b-button>
                    </div>
                    <div class="dropdown">
                        <b-button
                            :disabled="!isSelectModal"
                            @click="deleteAlbum()"
                            >刪除</b-button
                        >
                    </div>
                    <div class="dropdown">
                        <b-button @click="trashToogle()">垃圾桶</b-button>
                    </div>
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
        <div>
            <!-- {{ JSON.stringify(albumData) }} -->
            <!-- {{ selectData }} -->
            <div class="row d-flex flex-row">
                <div
                    class="col-lg-3 col-md-4 col-sm-6"
                    style="padding: 15px 15px"
                    v-for="(albumParent, index) in albumDataFilter"
                    :key="index"
                >
                    <div class="card" ref="card">
                        <div
                            class="cover"
                            @click="selectAlbum(index)"
                            v-show="isSelectModal"
                            :style="styleList"
                        ></div>

                        <div class="card-body ml-auto mr-auto">
                            <!-- <div class="topbar-divider d-none d-sm-block"></div> -->
                            <a :href="'albumChild?' + albumParent.album_id">
                                <div>
                                    <img
                                        v-if="
                                            albumParent.photos.length === 0
                                                ? true
                                                : false
                                        "
                                        :src="
                                            'album/avatar/small/' +
                                            albumParent.albumImage
                                        "
                                        width="150"
                                        style="height: 150px"
                                    />

                                    <div
                                        v-else-if="
                                            albumParent.photos.length === 1
                                        "
                                        class="childImage"
                                    >
                                        <img
                                            v-for="(
                                                child, cidx
                                            ) in albumParent.photos"
                                            :key="cidx"
                                            :src="
                                                'album/' +
                                                child.album_id +
                                                '/small/' +
                                                child.path
                                            "
                                            width="150"
                                            style="height: 150px"
                                        />
                                    </div>
                                    <div v-else class="childImage">
                                        <img
                                            v-for="(
                                                child, cidx
                                            ) in albumParent.photos.slice(0, 4)"
                                            :key="cidx"
                                            :src="
                                                'album/' +
                                                child.album_id +
                                                '/small/' +
                                                child.path
                                            "
                                            width="75"
                                            style="height: 75px"
                                        />
                                    </div>
                                </div>
                            </a>
                            <input
                                ref="checkbox"
                                class="checkbox"
                                :class="classList.checkBoxDisplay"
                                type="checkbox"
                                v-model="selectData"
                                :value="albumParent.album_id"
                                @change="borderChange(index)"
                            />
                        </div>

                        <a class="card-footer" @click="editAlbum(albumParent)">
                            <span class="ml-auto mr-auto">{{
                                albumParent.albumTitle
                            }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <transition name="trashBackgroundShow">
            <div
                v-show="trashShow"
                class="trashBackground"
                @click="trashToogle()"
            ></div>
        </transition>
        <transition name="trashSlide">
            <div v-show="trashShow" class="trash">
                <Trash @refresh="getAlbums" :trigger="trashTrigger"></Trash>
            </div>
        </transition>

        <!-- <b-modal :active.sync="isTrash" :width="600" scroll="clip">
            <Trash></Trash>
        </b-modal> -->

        <b-modal :active.sync="isAddAlbum" :width="350" scroll="clip">
            <AddAlbum
                :departmentId="department_id"
                :tagData="tagData"
                @refresh="getAlbums"
            ></AddAlbum>
        </b-modal>
        <b-modal :active.sync="isEditAlbum" :width="350" scroll="clip">
            <EditAlbum
                :departmentId="department_id"
                :editData="editData"
                :tagData="tagData"
                @refresh="getAlbums"
            ></EditAlbum>
        </b-modal>

        <b-loading
            :active.sync="isLoading"
            :is-full-page="true"
            v-model="isLoading"
            :can-cancel="false"
        ></b-loading>
    </div>
</template>

<script>
import moment from "moment";
import AddAlbum from "./modules/AddAlbum";
import EditAlbum from "./modules/EditAlbum";
import Trash from "./modules/Trash";
import TagFilter from "./components/TagFilter";
export default {
    components: {
        AddAlbum,
        Trash,
        EditAlbum,
        TagFilter,
    },
    data: function () {
        return {
            isAddTagModal: false,
            trashShow: false,
            isSelectModal: false,
            isAddAlbum: false,
            isEditAlbum: false,
            isLoading: false,
            classList: {
                checkBoxDisplay: { checkBoxDisplay: true },
            },
            styleList: {},
            department_id: null,
            selectData: [],
            albumData: [],
            tagData: [],
            userTagData: [],

            editData: [],

            trashTrigger: false,

            tagDataFilterId: [],
            tagUserDataFilterId: [],
        };
    },
    filters: {
        // urlSplitAlbum: function (url) {
        //     return url.replace("album/", "");
        // },
    },
    computed: {
        trashClick: function () {
            return this.$store.getters.trashClick;
        },
        // tagDataAllId: function () {
        //     return this.tagDataAll.map(function (item) {
        //         return item.tag_id;
        //     });
        // },
        // userTagDataAllId: function () {
        //     return this.userTagDataAll.map(function (item) {
        //         return item.user_tag_id;
        //     });
        // },

        // albumDataFilter: function () {
        //     if (this.tagDataFilterId.length === 0) {
        //         return this.albumData;
        //     } else {
        //         return this.albumData.filter((item) => {
        //             let hasTag = item.tags.map((element) => element.tag_id);
        //             let result = this.tagDataFilterId.filter(
        //                 (e) => hasTag.indexOf(e) !== -1
        //             );
        //             return result.length === 0 ? false : true;
        //         });
        //     }
        // },
        albumDataFilter: function () {
            if (
                this.tagDataFilterId.length === 0 &&
                this.tagUserDataFilterId.length === 0
            ) {
                return this.albumData;
            } else {
                let tag = this.albumData.filter((item) => {
                    let hasTag = item.photoTags;
                    let result = this.tagDataFilterId.filter(
                        (e) => hasTag.indexOf(e) !== -1
                    );
                    return result.length === 0 ? false : true;
                });
                let userTag = this.albumData.filter((item) => {
                    let hasUserTag = item.photoUserTags;
                    let userTagresult = this.tagUserDataFilterId.filter(
                        (e) => hasUserTag.indexOf(e) !== -1
                    );
                    return userTagresult.length === 0 ? false : true;
                });
                console.log("tag", tag);
                console.log("userTag", userTag);
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
        isSelectModal: function (n, o) {
            this.classList.checkBoxDisplay.checkBoxDisplay = !n;
        },
        trashClick: function (n, o) {
            this.trashToogle();
        },
    },
    created() {
        !sessionStorage.token ? (window.location.pathname = "/") : "";
        // this.school = sessionStorage.school;
        this.department_id = window.location.href.split("?")[1];
    },
    mounted() {
        // 監聽全域性點選事件
        document.addEventListener("click", () => {
            this.isAddTagModal = false;
        });
        this.getAlbums();
        this.getTags();
        this.getUserTags();
    },
    methods: {
        deleteTag(item) {
            this.getAlbums();
            this.tagData = this.tagData.filter(
                (element) => element.tag_id !== item.tag_id
            );
        },

        editAlbum(item) {
            this.isEditAlbum = true;
            this.editData = item;
        },
        trashToogle() {
            this.trashShow = !this.trashShow;
            this.$store.dispatch("navCoverToggle");
        },
        selectAlbum(index) {
            if (this.$refs.checkbox && this.$refs.card) {
                this.$refs.checkbox[index].click();
            }
        },
        borderChange(index) {
            // console.log(this.$refs.card);
            this.$refs.card[index].classList.toggle("selectBord");
        },
        async deleteAlbum() {
            if (this.selectData.length === 0) {
                return;
            }
            this.isLoading = true;
            await axios
                .post("album/deleteAlbum", {
                    album_id: this.selectData,
                })
                .then((response) => {
                    if (response.data.result == true) {
                        this.$refs.card.forEach((val, index) =>
                            val.classList.remove("selectBord")
                        );
                        this.getAlbums().then(
                            this.$buefy.toast.open({
                                message: "移至垃圾桶",
                                queue: false,
                            })
                        );
                        this.trashTrigger = !this.trashTrigger;
                        this.selectData = [];
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
        SelectModalChange() {
            this.isSelectModal = !this.isSelectModal;
            this.$refs.checkbox.forEach((val, index) =>
                val.checked == true ? val.click() : false
            );
            this.styleList = {
                width: this.$refs.card[0].clientWidth + "px",
                height: this.$refs.card[0].clientHeight + "px",
            };
        },
        async getAlbums() {
            this.isLoading = true;
            await axios
                .get("album/indexAlbum", {
                    params: { department_id: this.department_id },
                })
                .then((response) => {
                    if (response.data.result == true) {
                        this.albumData = response.data.data;
                    }
                })
                .catch({})
                .finally(() => {
                    this.isLoading = false;
                });
        },
        async getTags() {
            this.isLoading = true;
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
.base {
    position: relative;
}
.notification {
    background-color: #eef1f5;
    margin-top: 30px;
}
span {
    // font-family: Archivo;
    font-size: 16px;
    font-weight: 600;
    font-stretch: normal;
    font-style: normal;
    line-height: normal;
    letter-spacing: normal;
    color: #6c7887;
}
// input {
//     border: 1px solid rgba(206, 206, 208, 0.947);
//     border-radius: 5px;
//     padding: 9px 2vw;
//     // margin: 0 auto;
//     // box-sizing: border-box;
//     background-color: rgb(225, 225, 235);
// }
// input:hover {
//     border: 1px solid rgba(174, 174, 176, 0.947);
// }
// input:invalid {
//     border: 2px solid red;
// }

.department-group {
    background-color: white;
    margin: auto 10px;
    border-radius: 1rem;

    .b-tabs {
        margin-bottom: 4px;

        .tab-content {
            padding: 0;
        }
    }
}

.profile_img {
    border-radius: 50%;
}

.upload_btn {
    border-width: 0;
}

.employee-name {
    display: flex;
    align-items: center;

    img {
        border-radius: 50%;
    }

    span {
        margin-left: 0.5rem;
    }
}
.table .card-body {
    padding: 0;
}
.childImage {
    display: flex;
    flex-wrap: wrap;
    width: 150px;
}
.divider {
    filter: alpha(opacity=100, finishopacity=0, style=2);
    height: 6px;
}
.card {
    .cover {
        position: absolute;
        background: transparent;
        border-radius: 1rem;
        // width: 240px;
        // height: 240px;
    }
    .checkbox {
        position: absolute;
        top: 5px;
        left: 5px;
    }
    .checkBoxDisplay {
        display: none;
    }
    &.selectBord {
        color: rgba(37, 37, 252, 0.838);
        border: solid;
    }
}
.card-footer:hover {
    border-radius: 0rem 0rem 1rem 1rem;
    background: rgba(107, 107, 114, 0.55);
}

.trash {
    background: white;
    position: absolute;
    width: 60vw;
    height: 100vh;
    top: 0;
    right: 0;
}
.trashSlide-enter-active,
.trashSlide-leave-active {
    transition: 0.5s;
}

.trashSlide-enter,
.trashSlide-leave-to {
    opacity: 0;
    right: -60vw;
}
.trashSlide-leave,
.trashSlide-enter-to {
    opacity: 10;
    right: 0;
}
.trashBackground {
    position: absolute;
    top: 0;
    left: -50vw;
    width: 150vw;
    height: 100vh;
    background-color: rgba(0, 0, 0, 0.5);
}
.trashBackgroundShow-enter-active,
.trashBackgroundShow-leave-active {
    transition: 0.5s;
}
.trashBackgroundShow-enter,
.trashBackgroundShow-leave-to {
    opacity: 0;
}
.trashBackgroundShow-leave,
.trashBackgroundShow-enter-to {
    opacity: 10;
}
</style>
