<template>
    <div class="container" style="background-color: #fff">
        <div class="notification employee-name" style="background-color: #fff">
            <p
                v-show="step1"
                class="has-text-weight-semibold"
                style="color: #181f38; font-size: 20px"
            >
                Add a Item
            </p>
            <p
                v-show="step2"
                class="has-text-weight-semibold"
                style="color: #181f38; font-size: 20px"
            >
                Item description
            </p>
        </div>
        <div v-show="step1" id="main">
            <div class="red">
                <a
                    ><img
                        class="image"
                        :style="
                            selectedImageFile
                                ? 'padding-left:20px; border-radius: 50%;'
                                : 'padding-left:20px;'
                        "
                        width="125px;"
                        :src="
                            selectedImageFile ||
                            require('./image/img_property_default@2x.png')
                        "
                /></a>
                <b-upload v-model="editImage" type="file">
                    <a class="button table-btn">
                        <img
                            class="profile-camera"
                            width="30px;"
                            src="images/btn_camera@2x.png"
                            alt=""
                        />
                    </a>
                </b-upload>
            </div>
            <div class="blue">
                <b-field label="Name">
                    <b-input
                        size="is-default"
                        placeholder="Enter name"
                        v-model="name"
                        required
                    />
                </b-field>
                <b-field label="Area">
                    <b-select
                        placeholder="Select area"
                        v-model="selectArea"
                        expanded
                    >
                        <option
                            v-for="option in areaData"
                            :value="option"
                            :key="option.id"
                        >
                            {{ option.name }}
                        </option>
                    </b-select>
                </b-field>
                <b-field label="Type">
                    <b-select
                        placeholder="Select type"
                        v-model="selectCategory"
                        expanded
                    >
                        <option
                            v-for="option in categoryData"
                            :value="option.id"
                            :key="option.id"
                        >
                            {{ option.name }}
                        </option>
                    </b-select>
                </b-field>
                <b-field label="Mac">
                    <b-input
                        size="is-default"
                        placeholder="Enter mac"
                        v-model="mac"
                        required
                    />
                </b-field>
            </div>
            <div class="green"></div>
        </div>
        <div id="main" style="margin-top: 20px" v-show="step2">
            <div class="s2-red"></div>
            <div class="s2-blue" v-for="detail in details" :key="detail.index">
                <b-field label="Title">
                    <b-input v-model="detail.title" expanded />
                </b-field>
                <div
                    style="margin-top: 20px"
                    v-for="step in detail.list"
                    :key="step.index"
                >
                    <b-field label="Section Type">
                        <b-select
                            placeholder="Select type"
                            v-model="step.type"
                            expanded
                        >
                            <option value="video">Video</option>
                            <option value="text">Text</option>
                            <option value="image">Image</option>
                        </b-select>
                    </b-field>
                    <b-field label="Section Title">
                        <b-input
                            size="is-default"
                            placeholder="Enter title"
                            v-model="step.title"
                            required
                        />
                    </b-field>
                    <b-field>
                        <a
                            ><img
                                v-show="step.type == 'image'"
                                class="image"
                                width="250px;"
                                :src="step.presentFile || step.image"
                        /></a>
                        <video
                            v-show="step.type == 'video'"
                            :src="step.presentFile || step.video"
                            width="100%"
                            controls
                        ></video>
                        <b-upload
                            type="file"
                            @input="(file) => onSelectedFile(step, file)"
                        >
                            <a
                                v-show="!step.presentFile"
                                class="button"
                                type="is-info"
                                >upload video/photo</a
                            >
                        </b-upload>
                    </b-field>
                    <b-field label="Description">
                        <b-input
                            type="textarea"
                            size="is-default"
                            v-model="step.text"
                            required
                        />
                    </b-field>
                </div>
                <button
                    style="
                        float: right;
                        background-color: #e0e0e0;
                        margin-top: 20px;
                    "
                    class="card-bottom-button button is-primary"
                    @click="add_item_details()"
                >
                    Add new section
                </button>
                <b-button
                    style="float: right; margin-top: 20px"
                    class="card-bottom-button"
                    size="is-small"
                    type="is-text"
                    @click="$parent.close()"
                    >Delete section</b-button
                >
            </div>
            <div class="s2-green"></div>
        </div>
        <footer class="card-bottom">
            <b-button
                v-show="step1"
                class="card-bottom-button"
                size="is-small"
                type="is-primary"
                @click="showDetail"
                :disabled="!name || !mac || !selectArea || !selectCategory"
                >Next</b-button
            >
            <b-button
                v-show="step2 && itemInfo.modelType == 'new'"
                class="card-bottom-button"
                size="is-small"
                type="is-primary"
                @click="addNewItem()"
                >Add</b-button
            >
            <b-button
                v-show="itemInfo.modelType == 'detail'"
                class="card-bottom-button"
                size="is-small"
                type="is-primary"
                @click="updateDetails()"
                >Update</b-button
            >
            <b-button
                class="card-bottom-button"
                size="is-small"
                type="is-text"
                @click="$parent.close()"
                >Cancel</b-button
            >
        </footer>
    </div>
</template>

<script>
import moment from "moment";
export default {
    props: ["itemInfo"],
    data: function () {
        let step1 = true;
        let step2 = false;
        let details = [
            {
                title: null,
                list: [
                    {
                        title: null,
                        type: null,
                        image: null,
                        video: null,
                        text: null,
                        file: null,
                    },
                ],
            },
        ];

        if (this.itemInfo.modelType == "detail") {
            step1 = false;
            step2 = true;
            details = this.itemInfo.showDetails.details.map((d) => {
                return {
                    title: d.title,
                    list: d.list.map((step) => {
                        return {
                            ...step,
                            file: null,
                            presentFile: null,
                        };
                    }),
                };
            });
        }

        return {
            name: null,
            areaData: [],
            selectArea: null,
            categoryData: [],
            selectCategory: null,
            mac: null,
            selectedImageFile: null,

            selectedFile: null,
            editImage: null,
            detailFile: null,

            step1: step1,
            step2: step2,

            details: details,

            uploading: false,
            sending: false,
        };
    },
    watch: {
        editImage(image) {
            if (image == null) {
                return;
            }
            console.log("image:" + image);
            var reader = new FileReader();

            reader.onload = (e) => {
                this.selectedImageFile = e.target.result;
            };

            reader.readAsDataURL(image);
        },
    },
    mounted() {
        if (!sessionStorage.token) {
            window.location.pathname = "/";
            return;
        }

        this.getAreas();
        this.getCategories();
    },
    methods: {
        async updateDetails() {
            // let formData = new FormData();

            const steps = this.details.reduce((all_steps, detail) => {
                const steps = detail.list.filter((step) => step.file);
                return [...all_steps, ...steps];
            }, []);

            let failed = false;
            if (steps.length > 0) {
                this.uploading = true;

                for (let s = 0; s < steps.length; s++) {
                    const result = await this.uploadFile(steps[s]);
                    failed = !result;
                    if (failed) {
                        break;
                    }
                }

                this.uploading = false;
            }

            if (failed) {
                return;
            }

            const details = this.details.map((detail) => {
                return {
                    ...detail,
                    list: detail.list.map((data) => {
                        const step = {
                            ...data,
                        };
                        delete step.file;
                        delete step.presentFile;
                        return step;
                    }),
                };
            });
            try {
                this.sending = true;
                await axios.post(
                    `updateDetail/${this.itemInfo.showDetails.id}`,
                    details
                );
                this.$parent.close();
            } catch (e) {
                // console.log(e)
            } finally {
                this.sending = false;
            }
        },
        onSelectedFile(step, file) {
            if (file == null) {
                return;
            }
            var reader = new FileReader();

            reader.onload = (e) => {
                step.presentFile = e.target.result;
            };

            reader.readAsDataURL(file);
            step.file = file;
        },
        async uploadFile(step) {
            if (!step.file) {
                return;
            }

            let formData = new FormData();
            let file_url;
            formData.append("file", step.file);
            try {
                await axios
                    .post("uploadDetailFile", formData)
                    .then((response) => {
                        console.log("upload res:" + response.data);
                        this.file_url = response.data;
                    });
            } catch (e) {
            } finally {
                if (step.type == "image") {
                    step.image = this.file_url;
                } else if (step.type == "video") {
                    step.video = this.file_url;
                }
                return true;
            }
        },
        add_item_details() {
            // console.log("add new detail");
            this.details.map((v) => {
                v.list.push({
                    title: null,
                    type: null,
                    image: null,
                    video: null,
                    text: null,
                });
            });

            // console.log("Detail:" + JSON.stringify(this.details));
        },
        showDetail() {
            axios
                .get("checkMac?mac=" + this.mac)
                .then((response) => {
                    this.step1 = false;
                    this.step2 = true;
                })
                .catch((error) => {
                    this.$buefy.toast.open({
                        message: "This mac adress already exist!",
                        type: "is-danger",
                        queue: false,
                    });
                    return;
                });
        },
        getCategories() {
            axios
                .get("itemCategories")
                .then((response) => {
                    let categoryData = response.data;
                    this.categoryData = categoryData;
                })
                .catch((error) => {});
        },
        getAreas() {
            axios
                .get("itemAreas")
                .then((response) => {
                    // console.log('areaData:' + JSON.stringify(response.data));
                    let areaData = response.data;
                    this.areaData = areaData;
                })
                .catch((error) => {});
        },

        addNewItem() {
            // this.isLoading = true;
            let formData = new FormData();
            if (this.name) {
                formData.append("name", this.name);
            }

            if (this.mac) {
                formData.append("mac", this.mac);
            }

            if (this.editImage) {
                formData.append("imageFile", this.editImage);
            }

            if (this.selectCategory) {
                formData.append("category_id", this.selectCategory);
            }

            if (this.selectArea) {
                formData.append("area_id", this.selectArea.id);
            }

            if (this.details) {
                formData.append("details", JSON.stringify(this.details));
            }

            axios
                .post("item", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                })
                .then((response) => {
                    console.log("succeed:" + JSON.stringify(response.data));
                    this.$parent.close();
                })
                .catch((error) => {
                    console.log("error:" + JSON.stringify(error));
                })
                .finally(() => {});
        },
    },
};
</script>

<style lang="scss" scoped>
.notification {
    margin-bottom: 0;
}

#main {
    width: 100%;
    height: 100%;
    display: flex;

    // background-color: turquoise;
    .red {
        width: 166px;
    }

    .s2-red {
        width: 15%;
    }

    .blue {
        padding-top: 20px;
        width: 360px;
    }

    .s2-blue {
        width: 70%;
    }

    .green {
        width: 162px;
    }

    .s2-green {
        width: 15%;
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

.profile-camera {
    position: absoulate;
    margin-bottom: 90px;
    margin-left: 80px;
    padding-bottom: 15px;
}
</style>
<style lang="scss">
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
</style>
