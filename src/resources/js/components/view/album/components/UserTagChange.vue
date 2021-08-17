<template>
    <div>
        <b-field label="標籤一覽">
            <div class="d-flex flex-wrap" id="tag">
                <b-button
                    size="is-small"
                    v-for="(item, index) in userTagDataAllFilter"
                    :key="index"
                    v-text="item.user_tag_name"
                    @click="addDataHas(item)"
                ></b-button>
            </div>
        </b-field>
        <b-field label="關聯標籤">
            <div class="d-flex flex-wrap" id="tag">
                <b-button
                    size="is-small"
                    v-for="(item, index) in userTagDataHas"
                    :key="index"
                    v-text="item.user_tag_name"
                    @click="removeDataHas(item.user_tag_id)"
                ></b-button>
            </div>
        </b-field>
    </div>
</template>

<script>
export default {
    // props: ["editData", "userTagData"],
    props: {
        editData: {
            type: Object,
            default: function () {
                return { tags: [] };
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
            editDataTarget: this.editData,
            userTagDataAll: this.userTagData,
            userTagDataHas: this.editData.tags,
        };
    },
    computed: {
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
        userTagDataHasId: function (n, o) {
            this.$emit("getFilterId", this.userTagDataHasId);
        },
    },
    mounted() {
        this.$emit("getFilterId", this.userTagDataHasId);
    },
    methods: {
        addDataHas(item) {
            this.userTagDataHas = [...this.userTagDataHas, item];
        },
        removeDataHas(id) {
            this.userTagDataHas = this.userTagDataHas.filter(
                (item) => item.user_tag_id !== id
            );
        },
    },
};
</script>

<style lang="scss" scoped>
#tag {
    height: 36px;
    overflow-x: hidden;
    overflow-y: auto;
}
</style>
