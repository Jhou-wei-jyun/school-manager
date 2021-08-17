<template>
    <div class="container tag-edit-scroll">
        <div>
            <span>詳細</span>
            <hr />
            <img :src="photoData.path" width="300px" />
            <hr />
            <span
                >高度 × 寬度 {{ photoData.height }} ×
                {{ photoData.width }} px</span
            >
            <br />
            <span>檔案大小 {{ photoData.file_size | sizeFormat }}</span>
            <br />
            <span>檔案類型 {{ photoData.file_type }}</span>
            <br />
            <span>上傳時間 {{ photoData.update_date_time }}</span>

            <tag-change
                :tagData="tagData"
                :userTagData="userTagData"
                :editData="editData"
                @getFilterId="tagDataHasId = $event"
                @getFilterUserId="userTagDataHasId = $event"
            ></tag-change>

            <footer class="card-bottom d-flex align-items-center">
                <b-button
                    class="
                        notification_btn
                        notification_btn_sky
                        notification_btn_text_white
                        ml-auto
                    "
                    size="is-small"
                    @click="editTag()"
                    >編輯標籤</b-button
                >
            </footer>
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
import TagChange from "../components/TagChange";
export default {
    components: {
        TagChange,
    },
    props: {
        id: {
            type: Number,
            default: function () {
                return null;
            },
        },
        tagData: {
            type: Array,
            default: function () {
                return [];
            },
        },
        userTagData: {
            type: Array,
            default: function () {
                return [];
            },
        },
    },
    data: function () {
        return {
            photo_id: null,
            isLoading: false,
            photoData: {},
            editData: {
                tags: [],
                userTags: [],
            },
            tagDataHasId: [],
            userTagDataHasId: [],
        };
    },

    created() {
        !sessionStorage.token ? (window.location.pathname = "/") : "";
        // this.department_id = window.location.href.split("?")[1];
    },
    mounted() {},
    filters: {
        sizeFormat: function (value) {
            const digits = String(value).length;

            switch (true) {
                case digits <= 3:
                    return value + "B";
                case 3 < digits && digits <= 6:
                    return (value / 1000).toPrecision(digits - 3) + "KB";
                case 6 < digits && digits <= 9:
                    return (value / 1000000).toPrecision(digits - 6) + "MB";
                default:
                    return value + "B";
            }
        },
    },
    watch: {
        id: function (n, o) {
            this.photo_id = n;
            this.getPhotoInfo(n);
            this.getTags(n);
            this.getUserTags(n);
        },
    },
    computed: {},
    methods: {
        refresh() {
            this.$emit("refresh");
        },
        editTag() {
            this.isLoading = true;
            axios
                .all([
                    axios.put("album/tag/tagPhotoSync", {
                        photo_id: this.photo_id,
                        tag_id: this.tagDataHasId,
                    }),
                    axios.put("album/tag/userTagPhotoSync", {
                        photo_id: this.photo_id,
                        user_tag_id: this.userTagDataHasId,
                    }),
                ])
                .then(
                    axios.spread((response1, response2) => {
                        if (
                            response1.data.result == true &&
                            response2.data.result == true
                        ) {
                            this.$buefy.toast.open({
                                message: "編輯成功",
                                type: "is-success",
                                queue: false,
                            });
                            this.refresh();
                        }
                    })
                )
                .finally(() => {
                    this.isLoading = false;
                    this.$parent.close();
                });
        },
        async getTags(id) {
            this.isLoading = true;
            await axios
                .get("album/tag/getPhotoTag", {
                    params: {
                        photo_id: id,
                    },
                })
                .then((response) => {
                    if (response.data.result == true) {
                        this.editData = Object.assign(this.editData, {
                            tags: response.data.data,
                        });
                    }
                })
                .catch({})
                .finally(() => {
                    this.isLoading = false;
                });
        },
        async getUserTags(id) {
            this.isLoading = true;
            await axios
                .get("album/tag/getPhotoUserTag", {
                    params: {
                        photo_id: id,
                    },
                })
                .then((response) => {
                    if (response.data.result == true) {
                        this.editData = Object.assign(this.editData, {
                            userTags: response.data.data,
                        });
                    }
                })
                .catch({})
                .finally(() => {
                    this.isLoading = false;
                });
        },
        async getPhotoInfo(id) {
            this.isLoading = true;
            await axios
                .get("album/child/getPhotoInfo", {
                    params: { photo_id: id },
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
    },
};
</script>

<style lang="scss" scoped>
.tag-edit-scroll {
    overflow-y: scroll;
    height: 100vh;
}
</style>
