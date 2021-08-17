<template>
    <div class="container base" @click="tagInputClose()">
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
        <div class="d-flex flex-column">
            <div class="d-flex">
                <span class="align-self-center mw65">標籤：</span>
                <div class="d-flex flex-wrap">
                    <div
                        class="mr-1 mt-1 base"
                        v-for="(item, index) in tagDataAllFilter"
                        :key="index"
                    >
                        <b-button
                            size="is-small"
                            rounded
                            v-text="item.tag_name"
                            @click="addTagDataFilter(item)"
                        ></b-button>
                        <a @click.stop="deleteTag(item)" class="tag-delete">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>

                    <div class="align-self-center">
                        <!-- <button @click.stop="clickInner()">1111</button> -->
                        <a v-if="!isAddTagModal" @click.stop="addModalChange()">
                            <i class="fas fa-plus fa-sm text_color_gray500"></i>
                        </a>
                        <input
                            placeholder="按enter加入新標籤..."
                            ref="tagInput"
                            type="text"
                            v-else
                            v-model="tagInputName"
                            @keyup.enter="
                                (tagInputName === '') | (tagInputName === null)
                                    ? false
                                    : addTag(tagInputName)
                            "
                        />
                    </div>
                </div>
            </div>
            <div class="d-flex">
                <span class="align-self-center mw65">人物標籤：</span>
                <div class="d-flex flex-wrap">
                    <div
                        class="mr-1 mt-1 base"
                        v-for="(item, index) in userTagDataAllFilter"
                        :key="index"
                    >
                        <b-button
                            size="is-small"
                            rounded
                            v-text="item.tag_name"
                        ></b-button>
                    </div>

                    <div class="align-self-center">
                        <!-- <button @click.stop="clickInner()">1111</button> -->
                        <a v-if="!isAddTagModal" @click.stop="addModalChange()">
                            <i class="fas fa-plus fa-sm text_color_gray500"></i>
                        </a>
                        <input
                            placeholder="按enter加入新標籤..."
                            ref="tagInput"
                            type="text"
                            v-else
                            v-model="tagInputName"
                            @keyup.enter="
                                (tagInputName === '') | (tagInputName === null)
                                    ? false
                                    : addTag(tagInputName)
                            "
                        />
                    </div>
                </div>
            </div>
            <div class="d-flex">
                <span class="align-self-center mw65">過濾器：</span>
                <div class="d-flex flex-wrap">
                    <b-button
                        class="mr-1 mt-1"
                        size="is-small"
                        rounded
                        is-link
                        v-for="(item, index) in tagDataFilter"
                        :key="index"
                        v-text="item.tag_name"
                        @click="removeTagDataFilter(item.tag_id)"
                    ></b-button>
                </div>
            </div>
        </div>
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
export default {
    components: {
        AddAlbum,
        Trash,
        EditAlbum,
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
            tagDataFilter: [],
            userTagDataFilter: [],
            editData: [],
            tagInputName: null,
            trashTrigger: false,
        };
    },
    filters: {
        // urlSplitAlbum: function (url) {
        //     return url.replace("album/", "");
        // },
    },
    computed: {
        tagDataAll: function () {
            return this.tagData;
        },
        userTagDataAll: function () {
            return this.userTagData;
        },
        trashClick: function () {
            return this.$store.getters.trashClick;
        },
        tagDataAllId: function () {
            return this.tagDataAll.map(function (item) {
                return item.tag_id;
            });
        },
        userTagDataAllId: function () {
            return this.userTagDataAll.map(function (item) {
                return item.user_tag_id;
            });
        },
        tagDataFilterId: function () {
            return this.tagDataFilter.map(function (item) {
                return item.tag_id;
            });
        },
        userTagDataFilterId: function () {
            return this.userTagDataFilter.map(function (item) {
                return item.user_tag_id;
            });
        },
        tagDataAllFilter: function () {
            return this.tagDataAll.filter(
                (item) => !this.tagDataFilterId.includes(item.tag_id)
            );
        },
        userTagDataAllFilter: function () {
            return this.userTagDataAll.filter(
                (item) => !this.userTagDataFilterId.includes(item.user_tag_id)
            );
        },
        albumDataFilter: function () {
            if (this.tagDataFilterId.length === 0) {
                return this.albumData;
            } else {
                return this.albumData.filter((item) => {
                    let hasTag = item.tags.map((element) => element.tag_id);
                    let result = this.tagDataFilterId.filter(
                        (e) => hasTag.indexOf(e) !== -1
                    );
                    return result.length === 0 ? false : true;
                });
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
        this.getAlbums();
        this.getTags();
        this.getUserTags();
    },
    methods: {
        async deleteTag(item) {
            this.isLoading = true;
            await axios
                .post("album/tag/Delete", {
                    tag_id: item.tag_id,
                })
                .then((response) => {
                    if (response.data.result == true) {
                        // this.tagData = [...this.tagData, response.data.data];
                        this.getAlbums();
                        this.tagData = this.tagData.filter(
                            (element) => element.tag_id !== item.tag_id
                        );
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
                })
                .finally(() => {
                    this.isLoading = false;
                });
        },
        addModalChange() {
            this.isAddTagModal = !this.isAddTagModal;
            this.$nextTick(() => {
                this.$refs.tagInput.focus();
            });
        },
        addTag(InputName) {
            this.tagInputName = null;
            axios
                .post("album/tag/Add", {
                    tagName: InputName,
                    department_id: this.department_id,
                })
                .then((response) => {
                    if (response.data.result == true) {
                        this.tagData = [...this.tagData, response.data.data];
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
                })
                .finally(() => {});
        },
        tagInputClose() {
            this.tagInputName = null;
            this.isAddTagModal = false;
        },
        addTagDataFilter(item) {
            this.tagDataFilter = [...this.tagDataFilter, item];
        },
        removeTagDataFilter(id) {
            this.tagDataFilter = this.tagDataFilter.filter(
                (item) => item.tag_id !== id
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
                    this.albumData = response.data;
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
.tag-delete {
    position: absolute;
    top: 7px;
    left: 5px;
    color: lighten(black, 90%);
}
.tag-delete:hover {
    color: lighten(black, 20%);
}
.mw65 {
    min-width: 65px;
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
.base {
    position: relative;
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
