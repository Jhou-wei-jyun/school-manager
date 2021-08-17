<template>
    <div class="card card-body overflow-auto">
        <header class="card-bottom d-flex align-items-center">
            <p class="h4 has-text-weight-semibold">編輯教師</p>
        </header>

        <div class="d-flex flex-column">
            <b-field horizontal label="姓名">
                <span>{{ optionContent.name }}</span>
            </b-field>
            <div v-for="(item, index) in optionData" :key="index">
                <b-field horizontal :label="item.type | nameChange">
                    <div class="d-flex justify-content-arround flex-wrap">
                        <div
                            v-for="(j, i) in item.options"
                            :key="i"
                            class="w20"
                        >
                            <input
                                type="checkbox"
                                v-model="optionContent[item.type]"
                                :value="j"
                            />
                            <span>{{ j }}</span>
                        </div>
                    </div>
                </b-field>
            </div>
            <b-field horizontal label="今日心情">
                <span
                    ><input
                        type="radio"
                        value="0"
                        v-model="optionContent.mood"
                    />普通</span
                >
                <span
                    ><input
                        type="radio"
                        value="1"
                        v-model="optionContent.mood"
                    />愉快</span
                >
                <span
                    ><input
                        type="radio"
                        value="2"
                        v-model="optionContent.mood"
                    />生氣</span
                >
            </b-field>
            <b-field horizontal label="學習日誌">
                <b-input
                    type="textarea"
                    maxlength="1000"
                    v-model="optionContent.daily"
                ></b-input>
            </b-field>
            <b-field horizontal label="給家長的話">
                <b-input
                    maxlength="200"
                    type="textarea"
                    v-model="optionContent.to_parent"
                />
            </b-field>
            <b-field horizontal label="給老師的話">
                {{ optionContent.to_teacher }}
            </b-field>
            <p>
                <b-button
                    class="
                        notification_btn
                        notification_btn_sky
                        notification_btn_text_white
                    "
                    type="button"
                    size="is-small"
                    data-toggle="collapse"
                    data-target="#fileUpload"
                    aria-expanded="false"
                    aria-controls="fileUpload"
                    @click="isFileUpload = !isFileUpload"
                >
                    檔案上傳
                </b-button>
            </p>
            <div class="collapse" id="fileUpload">
                <div class="card card-body">
                    <b-field
                        horizontal
                        label="檔案上傳"
                        class="file is-primary"
                        :class="{ 'has-name': !!optionFileContent.file }"
                    >
                        <div class="d-flex flex-column">
                            <div>
                                <b-upload
                                    v-model="optionFileContent.file"
                                    class="file-label"
                                    accept=".pdf,.docx,.doc,.pptx,.ppt,.xlsx,.xls"
                                    :disabled="
                                        optionFileContent.file === null &&
                                        isCountLimit === true
                                    "
                                >
                                    <span class="file-cta">
                                        <span class="file-label"
                                            >(上傳pdf,doc,ppt,xls)</span
                                        >
                                    </span>
                                    <span
                                        class="file-name"
                                        v-if="optionFileContent.file"
                                    >
                                        <a
                                            class="delete is-small"
                                            type="button"
                                            @click.prevent.self="deleteDropFile"
                                        ></a>
                                        {{ optionFileContent.file.name }}
                                    </span>
                                </b-upload>
                            </div>
                            <div>
                                <b-upload
                                    v-model="optionFileContent.file2"
                                    class="file-label"
                                    accept=".pdf,.docx,.doc,.pptx,.ppt,.xlsx,.xls"
                                    :disabled="
                                        optionFileContent.file === null ||
                                        (optionFileContent.file2 === null &&
                                            isCountLimit === true)
                                    "
                                >
                                    <span class="file-cta">
                                        <span class="file-label"
                                            >(上傳pdf,doc,ppt,xls)</span
                                        >
                                    </span>
                                    <span
                                        class="file-name"
                                        v-if="optionFileContent.file2"
                                    >
                                        <a
                                            class="delete is-small"
                                            type="button"
                                            @click.prevent.self="
                                                deleteDropFile2
                                            "
                                        ></a>
                                        {{ optionFileContent.file2.name }}
                                    </span>
                                </b-upload>
                            </div>
                        </div>
                    </b-field>
                    <b-field
                        horizontal
                        label="圖片上傳"
                        class="file is-primary"
                        :class="{ 'has-name': !!optionFileContent.photo }"
                    >
                        <div class="d-flex flex-column">
                            <div>
                                <b-upload
                                    v-model="optionFileContent.photo"
                                    class="file-label"
                                    accept="image/jpeg"
                                    :disabled="
                                        optionFileContent.photo === null &&
                                        isCountLimit === true
                                    "
                                >
                                    <span class="file-cta">
                                        <span class="file-label"
                                            >(上傳jpg)</span
                                        >
                                    </span>
                                    <span
                                        class="file-name"
                                        v-if="optionFileContent.photo"
                                    >
                                        <a
                                            class="delete is-small"
                                            type="button"
                                            @click.prevent.self="
                                                deleteDropPhoto
                                            "
                                        ></a>
                                        {{ optionFileContent.photo.name }}
                                    </span>
                                </b-upload>
                            </div>
                            <div>
                                <b-upload
                                    v-model="optionFileContent.photo2"
                                    class="file-label"
                                    :style="{
                                        'background-color': true,
                                    }"
                                    accept="image/jpeg"
                                    :disabled="
                                        optionFileContent.photo === null ||
                                        (optionFileContent.photo2 === null &&
                                            isCountLimit === true)
                                    "
                                >
                                    <span class="file-cta">
                                        <span class="file-label"
                                            >(上傳jpg)</span
                                        >
                                    </span>
                                    <span
                                        class="file-name"
                                        v-if="optionFileContent.photo2"
                                    >
                                        <a
                                            class="delete is-small"
                                            type="button"
                                            @click.prevent.self="
                                                deleteDropPhoto2
                                            "
                                        ></a>
                                        {{ optionFileContent.photo2.name }}
                                    </span>
                                </b-upload>
                            </div>
                        </div>
                    </b-field>
                    <b-field horizontal label="檔案上限">
                        <limit-count
                            :limit="2"
                            :count="this.fileCount"
                            @countLimit="isCountLimit = $event"
                        ></limit-count>
                    </b-field>
                    <div>
                        <b-button
                            class="
                                notification_btn
                                notification_btn_sky
                                notification_btn_text_white
                                ml-auto
                            "
                            type="button"
                            size="is-small"
                            @click="fileUpload"
                        >
                            上傳
                        </b-button>
                    </div>
                </div>
            </div>
        </div>

        <footer
            class="card-bottom d-flex align-items-center"
            v-if="!isFileUpload"
        >
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
                @click="updateContacts"
                >編輯</b-button
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
import LimitCount from "../components/LimitCount";
export default {
    components: {
        LimitCount,
    },
    props: {
        admin: {
            type: [String, Number, Object],
            default: null,
        },
        optionData: {
            type: Array,
            default: function () {
                return [];
            },
        },
        editData: {
            type: Object,
            default: function () {
                return {
                    condition: "",
                    Return: "",
                    bring: "",
                };
            },
        },
        // optionContentProps: {
        //     type: Object,
        //     default: function () {
        //         return {
        //             condition: [],
        //             Return: [],
        //             bring: [],
        //         };
        //     },
        // },
    },
    data: function () {
        return {
            isLoading: false,
            isFileUpload: false,
            optionContent: {
                condition: [],
                Return: [],
                bring: [],
                mood: null,
                daily: null,
            },
            optionFileContent: {
                file: null,
                file2: null,
                photo: null,
                photo2: null,
            },
            fileCount: 0,
            isCountLimit: false,
            // optionDataCopy: this.optionData,
            // editDataCopy: this.editData,
            // optionContentProps: {
            //     condition: [],
            //     Return: [],
            //     bring: [],
            // },
        };
    },
    computed: {
        // optionContentCopy: function () {
        //     return this.optionContentProps;
        // },
        // optionDataCopy: function () {
        //     return this.optionDataProps;
        // },
        // optionContentCopy: function () {
        //     return this.optionContent;
        // },
    },
    watch: {
        editData: {
            handler: function (n, o) {
                this.optionContent = Object.assign(this.optionContent, n);
            },
            deep: true,
        },
        "optionFileContent.file"(n, o) {
            if (n === null && o !== null) {
                //移除檔案
                this.fileCount = this.fileCount - 1;
            } else if (n !== null && o !== null) {
                this.checkFile(n, "file");
                //變更檔案
            } else if (n !== null && o === null) {
                //新增檔案
                this.fileCount = this.fileCount + 1;
                this.checkFile(n, "file");
            }
        },
        "optionFileContent.file2"(n, o) {
            if (n === null && o !== null) {
                //移除檔案
                this.fileCount = this.fileCount - 1;
            } else if (n !== null && o !== null) {
                this.checkFile(n, "file2");
                //變更檔案
            } else if (n !== null && o === null) {
                //新增檔案
                this.fileCount = this.fileCount + 1;
                this.checkFile(n, "file2");
            }
        },
        "optionFileContent.photo"(n, o) {
            if (n === null && o !== null) {
                //移除檔案
                this.fileCount = this.fileCount - 1;
            } else if (n !== null && o !== null) {
                this.checkFile(n, "photo");
                //變更檔案
            } else if (n !== null && o === null) {
                //新增檔案
                this.fileCount = this.fileCount + 1;
                this.checkFile(n, "photo");
            }
        },
        "optionFileContent.photo2"(n, o) {
            if (n === null && o !== null) {
                //移除檔案
                this.fileCount = this.fileCount - 1;
            } else if (n !== null && o !== null) {
                this.checkFile(n, "photo2");
                //變更檔案
            } else if (n !== null && o === null) {
                //新增檔案
                this.fileCount = this.fileCount + 1;
                this.checkFile(n, "photo2");
            }
        },
        // "optionContentCopy.condition"(n, o) {
        //     this.editDataCopy.condition = JSON.stringify(n);
        // },
        // "optionContentCopy.Return"(n, o) {
        //     this.editDataCopy.return = JSON.stringify(n);
        // },
        // "optionContentCopy.bring"(n, o) {
        //     this.editDataCopy.bring = JSON.stringify(n);
        // },
    },
    filters: {
        nameChange(type) {
            if (type === "condition") {
                return "身體狀況";
            } else if (type === "Return") {
                return "今天發回";
            } else if (type === "bring") {
                return "明日攜帶物品";
            } else {
                return null;
            }
        },
    },
    mounted() {},
    methods: {
        refresh() {
            this.$emit("refresh");
        },

        updateContacts() {
            this.isLoading = true;
            let formData = new FormData();
            if (this.admin) {
                formData.append("admin_id", JSON.stringify(this.admin));
            }
            if (this.editData.contact_id) {
                formData.append(
                    "contact_id",
                    JSON.stringify(this.editData.contact_id)
                );
            }
            if (this.optionContent) {
                for (const [key, value] of Object.entries(this.optionContent)) {
                    if (
                        key === "condition" ||
                        key === "Return" ||
                        key === "bring"
                    ) {
                        formData.append(key, JSON.stringify(value));
                    } else if (value !== null) {
                        formData.append(key, value);
                    }
                }
            }
            axios
                .post("contact/edit", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                })

                .then((response) => {
                    if (response.data.result == true) {
                        this.refresh();
                        this.$buefy.toast.open({
                            message: "更新成功",
                            type: "is-success",
                            queue: false,
                        });
                    }
                })
                .catch((error) => {
                    this.$buefy.toast.open({
                        message: error.response.data.error,
                        type: "is-danger",
                        queue: false,
                    });
                })
                .finally(() => {
                    this.isLoading = false;
                    this.$parent.close();
                });
        },
        fileUpload() {
            this.isLoading = true;
            let formData = new FormData();
            if (this.admin) {
                formData.append("admin_id", JSON.stringify(this.admin));
            }
            if (this.editData.contact_id) {
                formData.append(
                    "contact_id",
                    JSON.stringify(this.editData.contact_id)
                );
            }
            if (this.optionFileContent) {
                for (const [key, value] of Object.entries(
                    this.optionFileContent
                )) {
                    if (
                        key === "condition" ||
                        key === "Return" ||
                        key === "bring"
                    ) {
                        formData.append(key, JSON.stringify(value));
                    } else if (value !== null) {
                        formData.append(key, value);
                    }
                }
            }
            axios
                .post("contact/editFile", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                })
                .then((response) => {
                    if (response.data.result == true) {
                        this.refresh();
                        this.$buefy.toast.open({
                            message: "更新成功",
                            type: "is-success",
                            queue: false,
                        });
                    }
                })
                .catch((error) => {
                    this.$buefy.toast.open({
                        message: error.response.data.error,
                        type: "is-danger",
                        queue: false,
                    });
                })
                .finally(() => {
                    let newOptionFileContent = {
                        file: null,
                        file2: null,
                        photo: null,
                        photo2: null,
                    };
                    this.optionFileContent = Object.assign(
                        {},
                        newOptionFileContent
                    );
                    this.isLoading = false;
                });
        },
        checkFile(file, key) {
            const SIZE_LIMIT = 5242880; // 5MB
            if (file.size > SIZE_LIMIT) {
                this.$buefy.toast.open({
                    message: file.name + " 超過上限5MB",
                    type: "is-danger",
                    queue: false,
                });
                this.optionFileContent[key] = null;
            }
        },
        deleteDropFile() {
            this.optionFileContent.file = null;
        },
        deleteDropFile2() {
            this.optionFileContent.file2 = null;
        },
        deleteDropPhoto() {
            this.optionFileContent.photo = null;
        },
        deleteDropPhoto2() {
            this.optionFileContent.photo2 = null;
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
    position: relative;
    border-radius: 50%;
}
</style>
