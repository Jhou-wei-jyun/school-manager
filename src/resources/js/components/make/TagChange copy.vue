<template>
    <div>
        <b-field label="標籤一覽">
            <div class="d-flex flex-wrap" id="tag">
                <b-button
                    size="is-small"
                    v-for="(item, index) in tagDataAllFilter"
                    :key="index"
                    v-text="item.tag_name"
                    @click="addDataHas(item)"
                ></b-button>
            </div>
        </b-field>
        <b-field label="關聯標籤">
            <div class="d-flex flex-wrap" id="tag">
                <b-button
                    size="is-small"
                    v-for="(item, index) in tagDataHas"
                    :key="index"
                    v-text="item.tag_name"
                    @click="removeDataHas(item.tag_id)"
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
                return { tags: [] };
            },
        },
        tagData: {
            type: Array,
            default: function () {
                return [];
            },
        },
    },
    data: function () {
        return {
            editDataTarget: this.editData,
            tagDataAll: this.tagData,
            tagDataHas: this.editData.tags,
        };
    },
    computed: {
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
    },
    watch: {
        tagDataHasId: function (n, o) {
            this.$emit("getFilterId", this.tagDataHasId);
        },
    },
    mounted() {
        this.$emit("getFilterId", this.tagDataHasId);
    },
    methods: {
        addDataHas(item) {
            this.tagDataHas = [...this.tagDataHas, item];
        },
        removeDataHas(id) {
            this.tagDataHas = this.tagDataHas.filter(
                (item) => item.tag_id !== id
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
