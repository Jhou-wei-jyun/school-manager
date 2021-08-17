<template>
    <div class="card card-body mt-5">
        <header class="card-bottom d-flex align-items-center">
            <p class="h4 has-text-weight-semibold">編輯群組</p>
        </header>
        <div>
            <b-button
                class="notification_btn notification_btn_sky notification_btn_text_white"
                size="is-small"
                v-for="(item, idx) in group"
                :key="idx"
                @click="getGroupRight(item.group_id)"
                >{{ item.group_name }}</b-button
            >
        </div>
        <!-- <p>page:{{ pageData }}</p>
        <p>tab:{{ tabData }}</p>
        <p>block:{{ blockData }}</p> -->

        <div id="main mt-2">
            <div class="row">
                <div
                    class="nav-item col-lg-3 col-md-4 col-sm-6 col-xs-12"
                    v-for="(page, p_idx) in relation"
                    :key="p_idx"
                >
                    {{ p_idx }}
                    <input
                        type="checkbox"
                        :ref="'all_page_Checkbox'"
                        :value="page.page_id"
                        v-model="pageData"
                        @change="selectPage(p_idx, page.page_id)"
                    />
                    <a
                        class="collapsed text-nowrap"
                        :href="'#page_' + page.page_id"
                        data-toggle="collapse"
                        aria-expanded="false"
                        :aria-controls="'page_' + page.page_id"
                    >
                        <span class="headerStyle linkname">{{
                            page.page_name
                        }}</span>
                    </a>

                    <div :id="'page_' + page.page_id" class="collapse">
                        <div class="bg-gray-300 py-2 collapse-inner rounded">
                            <div
                                class="nav-item col-12 text-nowrap"
                                v-for="(tab, t_idx) in page.tabs"
                                :key="t_idx"
                            >
                                {{ t_idx }}
                                <input
                                    type="checkbox"
                                    :ref="'page_' + page.page_id + '_Checkbox'"
                                    :value="tab.tab_id"
                                    v-model="tabData"
                                    @change="
                                        selectTab(
                                            page.page_id,
                                            t_idx,
                                            tab.tab_id
                                        )
                                    "
                                />
                                <a
                                    class="collapse-item"
                                    :href="'#tab_' + tab.tab_id"
                                    data-toggle="collapse"
                                    ><span class="headerStyle linkname">{{
                                        tab.tab_name
                                    }}</span>
                                </a>
                                <div :id="'tab_' + tab.tab_id" class="collapse">
                                    <div
                                        class="bg-gray-400 py-2 collapse-inner rounded"
                                    >
                                        <div
                                            class="collapse-item col-12 text-nowrap"
                                            v-for="(block, b_idx) in tab.blocks"
                                            :key="b_idx"
                                        >
                                            {{ b_idx }}
                                            <input
                                                type="checkbox"
                                                :ref="
                                                    'tab_' +
                                                    tab.tab_id +
                                                    '_Checkbox'
                                                "
                                                :value="block.block_id"
                                                v-model="blockData"
                                            />
                                            <span
                                                class="headerStyle linkname"
                                                >{{ block.block_name }}</span
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="card-bottom d-flex align-items-center">
            <b-button
                class="notification_btn notification_btn_sky notification_btn_text_white mr-auto"
                size="is-medium"
                @click="editGroup"
                >編輯</b-button
            >
            <b-button
                class="notification_btn notification_btn_sky notification_btn_text_white"
                size="is-medium"
                @click="deleteGroup"
                >刪除</b-button
            >
        </footer>
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
export default {
    props: ["group", "relation"],
    data: function () {
        return {
            isLoading: false,
            group_id: null,
            pageData: [],
            tabData: [],
            blockData: [],
        };
    },
    watch: {},
    mounted() {},
    methods: {
        selectPage(i, page) {
            // console.log(this.$refs["all_page_Checkbox"][i].checked);
            // console.log(this.$refs["page_" + page + "_Checkbox"]);
            if (this.$refs["page_" + page + "_Checkbox"]) {
                if (this.$refs["all_page_Checkbox"][i].checked === true) {
                    this.$refs[
                        "page_" + page + "_Checkbox"
                    ].forEach((val, index) =>
                        val.checked == false ? val.click() : false
                    );
                } else {
                    this.$refs[
                        "page_" + page + "_Checkbox"
                    ].forEach((val, index) =>
                        val.checked == true ? val.click() : false
                    );
                }
            }
        },
        selectTab(page, i, tab) {
            // console.log(this.$refs["page_" + page + "_Checkbox"][i].checked);
            if (this.$refs["tab_" + tab + "_Checkbox"]) {
                if (
                    this.$refs["page_" + page + "_Checkbox"][i].checked === true
                ) {
                    this.$refs[
                        "tab_" + tab + "_Checkbox"
                    ].forEach((val, index) =>
                        val.checked == false ? val.click() : false
                    );
                } else {
                    this.$refs[
                        "tab_" + tab + "_Checkbox"
                    ].forEach((val, index) =>
                        val.checked == true ? val.click() : false
                    );
                }
            }
        },
        refresh() {
            this.$emit("refresh");
        },

        getGroupRight(id) {
            this.isLoading = true;
            axios
                .get("group/right-index", {
                    params: {
                        group_id: id,
                    },
                })
                .then((response) => {
                    this.pageData = response.data.pageData;
                    this.tabData = response.data.tabData;
                    this.blockData = response.data.blockData;
                })
                .finally(() => {
                    this.group_id = id;
                    this.isLoading = false;
                });
        },
        editGroup() {
            if (this.pageData === []) {
                return;
            }
            this.isLoading = true;
            axios
                .post("group/edit", {
                    group_id: this.group_id,
                    arr_page_id: this.pageData,
                    arr_tab_id: this.tabData,
                    arr_block_id: this.blockData,
                })
                .then((response) => {
                    if (response.data.result == true) {
                        this.$buefy.toast.open({
                            message: "succes",
                            type: "is-success",
                            queue: false,
                        });
                        this.refresh();
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
                    this.isLoading = false;
                    this.group_id = null;
                    this.pageData = [];
                    this.tabData = [];
                    this.blockData = [];
                });
        },
        deleteGroup() {
            this.isLoading = true;
            axios
                .put("group/delete", {
                    group_id: this.group_id,
                })
                .then((response) => {
                    if (response.data.result == true) {
                        this.$buefy.toast.open({
                            message: "succes",
                            type: "is-success",
                            queue: false,
                        });
                        this.refresh();
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
                    this.isLoading = false;
                    this.group_id = null;
                    this.pageData = [];
                    this.tabData = [];
                    this.blockData = [];
                });
        },
    },
};
</script>

<style lang="scss" scoped>
#main {
    height: 100%;
    display: flex;

    // background-color: turquoise;
    .red {
        width: 30%;
    }

    .blue {
        width: 60%;
    }

    .green {
        width: 10%;
    }
}

.card-bottom {
    width: 100%;
    height: 100px;

    .card-bottom-button {
        float: right;
        right: 1rem;
        margin-top: 40px;
    }
}

.card-bottom {
    width: 100%;
    height: 100px;

    .card-bottom-button {
        float: right;
        right: 1rem;
        margin-top: 40px;
        text-decoration: none;
    }
}

.edit_img {
    border-radius: 50%;
}
</style>
