<template>
    <div>
        <b-field label="標籤一覽">
            <div class="d-flex flex-wrap" id="tag">
                <b-button
                    class="Tag"
                    size="is-small"
                    v-for="(item, index) in tagDataAllFilter"
                    :key="'A' + index"
                    v-text="item.tag_name"
                    @click="addDataHas(item)"
                ></b-button>
                <b-button
                    class="userTag"
                    size="is-small"
                    v-for="(item, index) in userTagDataAllFilter"
                    :key="'B' + index"
                    v-text="item.user_tag_name"
                    @click="addUserDataHas(item)"
                ></b-button>
            </div>
        </b-field>
        <b-field label="關聯標籤">
            <div class="d-flex flex-wrap" id="tag">
                <b-button
                    class="Tag"
                    size="is-small"
                    v-for="(item, index) in tagDataHas"
                    :key="'AS' + index"
                    v-text="item.tag_name"
                    @click="removeDataHas(item.tag_id)"
                ></b-button>
                <b-button
                    class="userTag"
                    size="is-small"
                    v-for="(item, index) in userTagDataHas"
                    :key="'BS' + index"
                    v-text="item.user_tag_name"
                    @click="removeUserDataHas(item.user_tag_id)"
                ></b-button>
            </div>
        </b-field>
    </div>
</template>

<script>
export default {
    // props: ["editData", "tagData"],
    props: {
        editData: {
            type: Object,
            default: function () {
                return {
                    tags: [],
                    userTags: [],
                };
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
            // editDataTarget: this.editData,
            // tagDataAll: this.tagData,
            // userTagDataAll: this.userTagData,
        };
    },
    computed: {
        tagDataAll: function () {
            return this.tagData;
        },
        userTagDataAll: function () {
            return this.userTagData;
        },
        tagDataHas: {
            get() {
                if (this.editData.tags == null) {
                    return [];
                }
                return this.editData.tags;
            },
            set(value) {
                // console.log(value);
                this.editData.tags = value;
            },
        },
        userTagDataHas: {
            get() {
                if (this.editData.userTags == null) {
                    return [];
                }
                return this.editData.userTags;
            },
            set(value) {
                // console.log(value);
                this.editData.userTags = value;
            },
        },
        tagDataAllId: function () {
            return this.tagDataAll.map(function (item) {
                return item.tag_id;
            });
        },
        tagDataHasId: function () {
            return this.tagDataHas.map(function (item) {
                return item.tag_id;
            });
        },
        tagDataAllFilter: function () {
            return this.tagDataAll.filter(
                (item) => !this.tagDataHasId.includes(item.tag_id)
            );
        },

        userTagDataAllId: function () {
            return this.userTagDataAll.map(function (item) {
                return item.user_tag_id;
            });
        },
        userTagDataHasId: function () {
            return this.userTagDataHas.map(function (item) {
                return item.user_tag_id;
            });
        },
        userTagDataAllFilter: function () {
            return this.userTagDataAll.filter(
                (item) => !this.userTagDataHasId.includes(item.user_tag_id)
            );
        },
    },
    watch: {
        tagDataHasId: function (n, o) {
            this.$emit("getFilterId", this.tagDataHasId);
        },
        userTagDataHasId: function (n, o) {
            this.$emit("getFilterUserId", this.userTagDataHasId);
        },
    },
    mounted() {},
    methods: {
        addDataHas(item) {
            this.tagDataHas = [...this.tagDataHas, item];
        },
        removeDataHas(id) {
            this.tagDataHas = this.tagDataHas.filter(
                (item) => item.tag_id !== id
            );
        },
        addUserDataHas(item) {
            this.userTagDataHas = [...this.userTagDataHas, item];
        },
        removeUserDataHas(id) {
            this.userTagDataHas = this.userTagDataHas.filter(
                (item) => item.user_tag_id !== id
            );
        },
    },
};
</script>

<style lang="scss" scoped>
// #tag {
//     height: 36px;
//     overflow-x: hidden;
//     overflow-y: auto;
// }
.Tag {
    background-color: rgba(255, 202, 211, 0.719);
}
.userTag {
    background-color: paleturquoise;
}
</style>
