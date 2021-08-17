<template>
    <div class="d-flex flex-column">
        <div class="d-flex">
            <span class="align-self-center mw65">標籤：</span>
            <div class="d-flex flex-wrap">
                <div
                    class="mr-1 mt-1 base"
                    v-for="(item, index) in tagDataAllFilter"
                    :key="'A' + index"
                >
                    <b-button
                        class="Tag"
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
                    :key="'B' + index"
                >
                    <b-button
                        class="userTag"
                        size="is-small"
                        rounded
                        v-text="item.user_tag_name"
                        @click="addUserTagDataFilter(item)"
                    ></b-button>
                </div>
            </div>
        </div>
        <div class="d-flex">
            <span class="align-self-center mw65">過濾器：</span>
            <div class="d-flex flex-wrap">
                <b-button
                    class="mr-1 mt-1 Tag"
                    size="is-small"
                    rounded
                    is-link
                    v-for="(item, index) in tagDataFilter"
                    :key="'AF' + index"
                    v-text="item.tag_name"
                    @click="removeTagDataFilter(item.tag_id)"
                ></b-button>
                <b-button
                    class="mr-1 mt-1 userTag"
                    size="is-small"
                    rounded
                    is-link
                    v-for="(item, index) in userTagDataFilter"
                    :key="'BF' + index"
                    v-text="item.user_tag_name"
                    @click="removeUserTagDataFilter(item.user_tag_id)"
                ></b-button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
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

        isAddTagModal: {
            type: Boolean,
            default: function () {
                return false;
            },
        },
        departmentId: {
            default: function () {
                return null;
            },
        },
    },
    data: function () {
        return {
            tagInputName: null,
            tagDataFilter: [],
            userTagDataFilter: [],
        };
    },
    computed: {
        tagDataAll: function () {
            return this.tagData;
        },
        userTagDataAll: function () {
            return this.userTagData;
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
        // tagDataFilterId: function () {
        //     return this.tagDataFilter.map(function (item) {
        //         return item.tag_id;
        //     });
        // },
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
    },
    watch: {
        // tagDataHasId: function (n, o) {
        //     this.$emit("getFilterId", this.tagDataHasId);
        // },
        tagDataFilterId: function (n, o) {
            this.$emit("setTagFilter", n);
        },

        userTagDataFilterId: function (n, o) {
            this.$emit("setUserTagFilter", n);
        },
    },
    mounted() {
        // this.$emit("getFilterId", this.tagDataHasId);
    },
    methods: {
        removeTagDataFilter(id) {
            this.tagDataFilter = this.tagDataFilter.filter(
                (item) => item.tag_id !== id
            );
        },
        removeUserTagDataFilter(id) {
            this.userTagDataFilter = this.userTagDataFilter.filter(
                (item) => item.user_tag_id !== id
            );
        },
        addModalChange() {
            this.$emit("addModalChange");
            this.$nextTick(() => {
                this.$refs.tagInput.focus();
            });
        },
        addTagDataFilter(item) {
            this.tagDataFilter = [...this.tagDataFilter, item];
        },
        addUserTagDataFilter(item) {
            this.userTagDataFilter = [...this.userTagDataFilter, item];
        },
        async deleteTag(item) {
            this.isLoading = true;
            await axios
                .post("album/tag/Delete", {
                    tag_id: item.tag_id,
                })
                .then((response) => {
                    if (response.data.result == true) {
                        this.$emit("deleteTag", item);
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
        addTag(InputName) {
            this.tagInputName = null;
            axios
                .post("album/tag/Add", {
                    tagName: InputName,
                    department_id: this.departmentId,
                })
                .then((response) => {
                    if (response.data.result == true) {
                        this.$emit("addTag", response.data.data);
                        // this.tagData = [...this.tagData, response.data.data];
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
    },
};
</script>

<style lang="scss" scoped>
.base {
    position: relative;
}
.Tag {
    background-color: rgba(255, 202, 211, 0.719);
}
.userTag {
    background-color: paleturquoise;
}
.mw65 {
    min-width: 65px;
}
.tag-delete {
    position: absolute;
    top: 7px;
    left: 5px;
    color: lighten(black, 90%);
}
.tag-delete:hover {
    color: lighten(black, 20%);
}
</style>
