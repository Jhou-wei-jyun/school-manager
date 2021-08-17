<template>
<div class="container" style="background-color:#FFF;">
    <b-loading :is-full-page="isFullPage" :active.sync="isLoading" :can-cancel="true"></b-loading>
    <div class="notification employee-name" style="background-color:#fff">
        <p class="has-text-weight-semibold" style="color:#181f38; font-size:18px;">Add an area</p>
    </div>
    <div id="main">
        <div class="red">
        </div>
        <div class="blue">
            <b-field label="Area Name">
                <b-input size="is-default" placeholder="Enter Area Name" v-model="name" required expanded />
            </b-field>
            <b-field label="Maximum Capacity">
                <b-input size="is-default" placeholder="Enter Maximum Capacity" v-model="max_num_peoples" required expanded />
            </b-field>
            <span class="imageTitle">lottie</span>
            <b-field class="file" style="margin-top:5px;">
                <b-upload v-model="lottieFile" v-if="lottieFile == null">
                    <a class="button is-gray" style="width:12rem;">
                        <p style="font-size:14px;">Upload file</p>
                    </a>
                </b-upload>
            </b-field>
            <div class="tags">
                <span class="tag is-info" v-if="lottieFile">
                    {{lottieFile.name || lottieFile }}
                    <button class="delete is-small" type="button" @click="deleteDropFile(lottieFile.name)">
                    </button>
                </span>
            </div>

            <span class="imageTitle">location_photo_social_0</span>
            <b-field class="file" style="margin-top:5px;">
                <b-upload v-model="socialFile0" v-if="socialFile0 == null">
                    <a class="button is-gray" style="width:12rem;">
                        <p style="font-size:14px;">Upload file</p>
                    </a>
                </b-upload>
            </b-field>
            <div class="tags">
                <span class="tag is-info" v-if="socialFile0">
                    {{ socialFile0.name  || socialFile0 }}
                    <button class="delete is-small" type="button" @click="deleteDropFile(socialFile0.name)">
                    </button>
                </span>
            </div>

            <span class="imageTitle">location_photo_social_1</span>
            <b-field class="file" style="margin-top:5px;">
                <b-upload v-model="socialFile1" v-if="socialFile1 == null">
                    <a class="button is-gray" style="width:12rem;">
                        <p style="font-size:14px;">Upload file</p>
                    </a>
                </b-upload>
            </b-field>
            <div class="tags">
                <span class="tag is-info" v-if="socialFile1">
                    {{ socialFile1.name || socialFile1 }}
                    <button class="delete is-small" type="button" @click="deleteDropFile(socialFile1.name)">
                    </button>
                </span>
            </div>

            <span class="imageTitle">location_photo_social_2</span>
            <b-field class="file" style="margin-top:5px;">
                <b-upload v-model="socialFile2" v-if="socialFile2 == null">
                    <a class="button is-gray" style="width:12rem;">
                        <p style="font-size:14px;">Upload file</p>
                    </a>
                </b-upload>
            </b-field>
            <div class="tags">
                <span class="tag is-info" v-if="socialFile2">
                    {{ socialFile2.name || socialFile2 }}
                    <button class="delete is-small" type="button" @click="deleteDropFile(socialFile2.name)">
                    </button>
                </span>
            </div>

            <span class="imageTitle">location_emergency_exit</span>
            <b-field class="file" style="margin-top:5px;">
                <b-upload v-model="exitFile" v-if="exitFile == null">
                    <a class="button is-gray" style="width:12rem;">
                        <p style="font-size:14px;">Upload file</p>
                    </a>
                </b-upload>
            </b-field>
            <div class="tags">
                <span class="tag is-info" v-if="exitFile">
                    {{ exitFile.name || exitFile }}
                    <button class="delete is-small" type="button" @click="deleteDropFile(exitFile.name)">
                    </button>
                </span>
            </div>
        </div>
        <div class="green">
        </div>
    </div>
    <footer class="card-bottom">
        <b-button v-show="modelType == 'new'" class="card-bottom-button" size="is-small" style="width:8rem;" type="is-primary" :disabled="!name || !max_num_peoples" @click="addNewArea()">Add</b-button>
        <b-button v-show="modelType == 'detail'" class="card-bottom-button" size="is-small" style="width:8rem;" type="is-primary" @click="addNewArea()">Update</b-button>
        <b-button class="card-bottom-button" size="is-small" type="is-text" @click="$parent.close()">Cancel</b-button>
    </footer>
</div>
</template>

<script>
import moment from 'moment';
export default {
    props: ["areaInfo"],
    data: function () {
        return {
            name: null,
            max_num_peoples: null,
            lottieFile: null,
            socialFile0: null,
            socialFile1: null,
            socialFile2: null,
            exitFile: null,

            areaId: null,

            lottie: null,
            file0: null,
            file1: null,
            file2: null,
            exit: null,

            modelType: this.areaInfo.modelType,
            isFullPage: true,
            isLoading: false,
        }
    },
    watch: {
    },
    mounted() {
        !sessionStorage.token ? window.location.pathname = "/" : '';
        console.log('view details:'+JSON.stringify(this.areaInfo.showDetails));
         if (this.modelType == 'detail') {
             this.areaId = this.areaInfo.showDetails.id;
             this.name = this.areaInfo.showDetails.name;
             this.max_num_peoples = this.areaInfo.showDetails.max_num_peoples;
             this.lottieFile = this.areaInfo.showDetails.lottie;
             this.socialFile0 = this.areaInfo.showDetails.location_photo_social_0;
             this.socialFile1 = this.areaInfo.showDetails.location_photo_social_1;
             this.socialFile2 = this.areaInfo.showDetails.location_photo_social_2;
             this.exitFile = this.areaInfo.showDetails.location_emergency_exit;
        }
    },
    methods: {
        deleteDropFile(filename) {
            if (this.lottieFile.name == filename) {
                this.lottieFile = null;
            }else
            if (this.socialFile0.name == filename) {
                this.socialFile0 = null;
            }else
            if (this.socialFile1.name == filename) {
                this.socialFile1 = null;
            }else
            if (this.socialFile2.name == filename) {
                this.socialFile2 = null;
            }else
            if (this.exitFile.name == filename) {
                this.exitFile = null;
            }
        },
        addNewArea() {
            this.isLoading = true;
            let formData = new FormData();
            if (this.areaId){
                formData.append("id", this.areaId);
            }
            if (this.name) {
                formData.append("name", this.name);
            }
            if (this.max_num_peoples) {
                formData.append("max_num_peoples", this.max_num_peoples);
            }
            if (this.lottieFile) {
                formData.append("lottie", this.lottieFile);
            }
            if (this.socialFile0) {
                formData.append("location_photo_social_0", this.socialFile0);
            }
            if (this.socialFile1) {
                formData.append("location_photo_social_1", this.socialFile1);
            }
            if (this.socialFile2) {
                formData.append("location_photo_social_2", this.socialFile2);
            }
            if (this.exitFile) {
                formData.append("location_emergency_exit", this.exitFile);
            }

            axios.post('area', formData, {
                headers: {
                    "Content-Type": "multipart/form-data"
                }
            }).then(response => {
                console.log('succeed:' + JSON.stringify(response.data));
            }).catch(error => {}).finally(() => {
                this.$parent.close();
                this.isLoading = false;
            });
        }
    },
}
</script>

<style lang="scss" scoped>
.imageTitle {
    width: 240px;
    height: 14px;
    font-family: Archivo;
    font-size: 12px;
    font-weight: 600;
    font-stretch: normal;
    font-style: normal;
    line-height: normal;
    letter-spacing: normal;
    color: #181f38;
}

.notification {
    margin-bottom: 0;
}

#main {
    width: 100%;
    height: 100%;
    display: flex;

    // background-color: turquoise;
    .red {
        width: 15%;
    }

    .blue {
        padding-top: 20px;
        width: 70%;
    }

    .green {
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
