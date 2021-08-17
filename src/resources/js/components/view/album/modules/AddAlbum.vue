<template>
    <div class="card card-body">
        <header class="card-bottom d-flex align-items-center">
            <p class="h4 has-text-weight-semibold">新增相簿</p>
        </header>
        <div id="main">
            <b-field label="相簿標題">
                <b-input type="text" v-model="title" required></b-input>
            </b-field>
            <tag-change
                :tagData="tagData"
                @getFilterId="tagDataHasId = $event"
            ></tag-change>
            <b-field label="時間">
                <!-- <b-datepicker
                    v-model="date"
                    inline
                    :unselectable-days-of-week="[0, 6]"
                >
                </b-datepicker> -->
                <b-datepicker v-model="date"> </b-datepicker>
            </b-field>

            <b-field class="file is-primary" :class="{ 'has-name': !!file }">
                <b-upload v-model="file" class="file-label">
                    <span class="file-cta">
                        <span class="file-label">上傳</span>
                    </span>
                    <span class="file-name" v-if="file">
                        {{ file.name }}
                    </span>
                </b-upload>
            </b-field>
        </div>
        <footer class="card-bottom d-flex align-items-center">
            <b-button
                class="
                    notification_btn
                    notification_btn_gray
                    notification_btn_text_white
                    ml-auto
                    mr-2
                "
                size="is-medium"
                @click="$parent.close()"
                >取消</b-button
            >
            <b-button
                class="
                    notification_btn
                    notification_btn_sky
                    notification_btn_text_white
                "
                size="is-medium"
                @click="addAlbum()"
                >新增</b-button
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
import TagChange from "../components/TagChange";
export default {
    props: ["departmentId", "tagData"],
    components: {
        TagChange,
    },
    data: function () {
        return {
            isLoading: false,
            title: null,
            date: new Date(),
            file: null,
            tagDataHasId: [],
        };
    },
    computed: {},
    watch: {},
    mounted() {
        !sessionStorage.token ? (window.location.pathname = "/") : "";
    },
    methods: {
        refresh() {
            this.$emit("refresh");
        },
        addAlbum() {
            if (this.departmentId === null) {
                return;
            }
            if (this.title === null) {
                return;
            }
            if (this.date === null) {
                return;
            }
            if (this.file === null) {
                return;
            }
            this.isLoading = true;
            let formData = new FormData();
            formData.append("albumParent", 0);
            if (this.departmentId) {
                formData.append("department_id", this.departmentId);
            }
            if (this.title) {
                formData.append("albumTitle", this.title);
            }
            if (this.date) {
                formData.append(
                    "albumDate",
                    moment(this.date).format("YYYY-MM-DD")
                );
            }
            if (this.file) {
                formData.append("album_file", this.file);
            }

            axios

                .post("album/newAlbum", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                })

                .then((response) => {
                    if (response.data.result == true) {
                        this.addTag(response.data.data.album_id);
                        this.$buefy.toast.open({
                            message: "新增成功",
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
                    this.$parent.close();
                });
        },
        async addTag(id) {
            await axios
                .put("album/tag/AlbumSync", {
                    album_id: id,
                    tag_id: this.tagDataHasId,
                })

                .then((response) => {
                    if (response.data.result == true) {
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
                .finally(() => {});
        },
    },
};
</script>

<style lang="scss" scoped>
// #main {
//     height: 100%;
//     display: flex;

//     // background-color: turquoise;
//     .red {
//         width: 30%;
//     }

//     .blue {
//         width: 60%;
//     }

//     .green {
//         width: 10%;
//     }
// }

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
.text_center {
    text-align-last: center !important;
}
.edit_img {
    border-radius: 50%;
}
</style>
