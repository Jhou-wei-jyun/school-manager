<template>
<div class="container card" :loading="isLoading" style="border-radius: 5px;  margin:10px 140px;">
    <b-loading :is-full-page="isFullPage" :active.sync="isLoading" :can-cancel="true"></b-loading>
    <section>
        <b-steps  type="is-info" v-model="activeStep" :animated="isAnimated" :rounded="isRounded" :has-navigation="hasNavigation" :icon-prev="prevIcon" :icon-next="nextIcon" :label-position="labelPosition" style="padding: 50px;">
            <b-step-item step="1" label="新增" :clickable="isStepsClickable" disabled>
                <h1 class="title has-text-centered">{{newType == 'user' ? '建立帳戶' : '建立財產'}}</h1>
                <b-field v-show="newType == 'user'">
                    <b-select placeholder="請選擇部門" v-model="selectDepartment">
                        <option v-for="option in departmentsData" :value="option" :key="option.id">
                            {{ option.name }}
                        </option>
                    </b-select>
                    <b-select placeholder="請選擇職稱" v-model="selectPosition">
                        <option v-for="option in positionsData" :value="option.id" :key="option.id">
                            {{ option.name }}
                        </option>
                    </b-select>
                </b-field>
                <b-field v-show="newType == 'item'">
                    <b-select placeholder="請選擇區域" v-model="selectArea">
                        <option v-for="option in areaData" :value="option" :key="option.id">
                            {{ option.name }}
                        </option>
                    </b-select>
                    <b-select placeholder="請選擇類別" v-model="selectCategory">
                        <option v-for="option in categoryData" :value="option.id" :key="option.id">
                            {{ option.name }}
                        </option>
                    </b-select>
                </b-field>
                <b-field v-show="newType == 'user'">
                    <b-input type="text" placeholder="請輸入設定帳號" v-model="account" required></b-input>
                </b-field>
                <b-field>
                    <b-input type="text" placeholder="請輸入mac adress" v-model="mac" required></b-input>
                </b-field>
            </b-step-item>

            <b-step-item step="2" label="資訊" :clickable="isStepsClickable" align="center">
                <h1 class="title has-text-centered">{{ newType == 'user' ? '個人資訊' :'財產資訊' }}</h1>
                <div class="sss">
                    <a><img class="image is-128x128" :src="selectedImageFile || newType == 'user' ? '/images/no_photo.png?7a2abcedac26a797c465e2ba216b8780' : require('./image/no_item.png')" /></a>
                    <b-upload v-model="editImage" type="file">
                        <a class="button is-info">
                            <b-icon icon="upload"></b-icon>
                            <span>選擇照片</span>
                        </a>
                    </b-upload>
                </div>
                <b-field v-show="newType == 'user'">
                    <b-select placeholder="請選擇性別" v-model="selectedGender">
                        <option :value="1">男</option>
                        <option :value="2">女</option>
                        <option :value="3"></option>
                    </b-select>
                </b-field>
                <b-field>
                    <b-input type="text" :placeholder="newType == 'user' ? '請輸入員工姓名' : '請輸入財產名稱'" v-model="name" required></b-input>
                </b-field>
                <b-field>
                    <b-input type="text" :placeholder="newType == 'user' ? '請輸入員工到職日' : '請輸入物品設置日期'" v-model="start_date" required></b-input>
                </b-field>
                <h2 v-show="newType == 'item'" style="font-size:20px;">詳細資訊</h2>
                <b-button v-show="newType == 'item'" @click="add_item_details">+</b-button>
                <b-field v-show="newType == 'item'" v-for="option in details" :value="option.value" :key="option.index" grouped>
                    <b-input type="text" placeholder="Title" v-model="option.title" required></b-input>
                    <div v-show="newType == 'item'" v-for="list in option.list" :value="list.value" :key="list.index">
                        <b-input type="text" placeholder="title" v-model="list.title" required></b-input>
                        <b-input type="text" placeholder="Type" v-model="list.type" required></b-input>
                        <b-input type="text" placeholder="Image" v-model="list.image" required></b-input>
                        <b-input type="text" placeholder="Video" v-model="list.video" required></b-input>
                        <b-input type="text" placeholder="Text" v-model="list.text" required></b-input>
                    </div>
                </b-field>
            </b-step-item>

            <b-step-item step='3' label="確認" :clickable="isStepsClickable" disabled align="center">
                <form @submit.prevent="newEmployee" v-show="!confirmNew" enctype="multipart/form-data">
                    <h1 class="title has-text-centered" style="color:red;">請確認以下資訊是否正確</h1>
                    <a><img width="120px" :src="selectedImageFile || newType == 'user' ? '/images/no_photo.png?7a2abcedac26a797c465e2ba216b8780' : require('./image/no_item.png')" />
                    </a>
                    <p><strong>{{newType == 'user' ? '員工姓名:' : '物品名稱'}}</strong> {{ name }}</p>
                    <p><strong v-show="newType == 'user'">員工帳戶:</strong> {{ account }}</p>
                    <p><strong>MAC:</strong> {{ mac }}</p>
                    <p><strong v-show="newType == 'user'">員工性別:</strong> {{ selectedGender }}</p>
                    <p><strong>{{newType == 'user' ? '員工到職日:' : '物品設置日期'}}</strong> {{ start_date }}</p>
                    <p v-if="selectDepartment != null"><strong>部門:</strong> {{ newType == 'user' ? selectDepartment.name : selectArea.name }}</p>
                    <p><strong>{{newType=='user' ? '職位:' : '類別:'}}</strong> {{ newType == 'user' ? selectPosition : selectCategory}}</p>
                    <button type="submit" class="button is-info" :disabled="!canNewEmployee">確定新增</button>

                </form>
                <h1 style="color:#181f38;" v-show="confirmNew">{{ finished_context }}</h1>
            </b-step-item>

            <template v-if="customNavigation" slot="navigation" slot-scope="{previous, next}">
                <b-button outlined type="is-danger" icon-pack="fas" icon-left="backward" :disabled="previou.disabled" @click.prevent="previous.action">
                    Previous
                </b-button>
                <b-button outlined type="is-success" icon-pack="fas" icon-right="forward" :disabled="next.disabled" @click.prevent="next.action">
                    Next
                </b-button>
            </template>
        </b-steps>
    </section>
</div>
</template>

<script>
export default {
    data: function () {
        return {
            isLoading: false,
            isFullPage: true,

            newType: null,

            activeStep: 0,
            showSocial: true,
            isAnimated: true,
            isRounded: true,
            isStepsClickable: false,
            selectPosition: null,

            hasNavigation: true,
            customNavigation: false,
            isProfileSuccess: true,

            prevIcon: 'chevron-left',
            nextIcon: 'chevron-right',
            labelPosition: 'bottom',
            confirmNew: false,

            finished_context: null,

            selectedImageFile: null,
            editImage: null,

            departmentsData: [],
            positionsData: [],

            selectDepartment: null,
            name: null,
            selectedGender: null,
            account: null,
            mac: null,
            start_date: null,
            showFinish: true,

            //ITEM
            selectArea: null,
            areaData: [],
            selectCategory: null,
            categoryData: [],

            details: [{
                title: null,
                list: [{
                    title:null,
                    type: null,
                    image: null,
                    video: null,
                    text: null
                }]
            }],
        }
    },
    watch: {
        editImage(image) {
            if (image == null) {
                return;
            }

            var reader = new FileReader();

            reader.onload = e => {
                this.selectedImageFile = e.target.result;
            };

            reader.readAsDataURL(image);
        },
    },
    computed: {
        canNewEmployee() {
            if (this.newType == 'user') {
                return this.name && this.account && this.mac && this.selectDepartment && this.selectedGender && this.selectPosition && this.start_date;
            } else {
                return this.name && this.mac && this.selectArea && this.selectCategory && this.start_date;
            }
        }
    },
    mounted() {
        !sessionStorage.token ? window.location.pathname = "/" : '';
        this.newType = window.location.search.slice(1);
        if (this.newType == 'user') {
            this.getDepartments();
            this.getPositions();
        } else {
            this.getAreas();
            this.getCategories();
        }
    },
    methods: {
        add_item_details() {
            this.details.map(v => {
                v.list.push({
                    title:null,
                    type: null,
                    image: null,
                    video: null,
                    text: null
                })
            });
            // this.details.list.push({
            //     type: null,
            //     image: null,
            //     video: null,
            //     text: null
            // });
        },
        getCategories() {
            axios.get('itemCategories').then(response => {
                // console.log('category:' + response.data);
                let categoryData = response.data;
                this.categoryData = categoryData;
            }).catch(error => {

            });
        },
        getAreas() {
            axios.get('itemAreas').then(response => {
                // console.log('areaData:' + response.data);
                let areaData = response.data;
                this.areaData = areaData;
            }).catch(error => {

            });
        },
        newEmployee() {
            this.isLoading = true;
            let formData = new FormData();
            if (this.name) {
                formData.append("name", this.name);
            }
            if (this.account) {
                formData.append("account", this.account);
            }
            if (this.mac) {
                formData.append("mac", this.mac);
            }
            if (this.start_date) {
                formData.append("start_date", this.start_date);
            }
            if (this.selectPosition) {
                formData.append("position_id", this.selectPosition);
            }
            if (this.selectDepartment) {
                formData.append("department_id", this.selectDepartment.id);
            }
            if (this.selectedGender) {
                formData.append("gender", this.selectedGender);
            }
            if (this.editImage) {
                formData.append("imageFile", this.editImage);
            }

            if (this.selectCategory) {
                formData.append('category_id', this.selectCategory);
            }

            if (this.selectArea) {
                formData.append('area_id', this.selectArea.id);
            }

            if (this.details) {
                formData.append('details', JSON.stringify(this.details));
            }

            axios.post(this.newType == 'user' ? 'employee' : 'item', formData, {
                headers: {
                    "Content-Type": "multipart/form-data"
                }
            }).then(response => {
                console.log('succeed:' + JSON.stringify(response.data));
                this.finished_context = 'Register succeed'
                setTimeout(() => window.location.href = this.newType == 'user' ? 'employee' : 'property', 1000);
            }).catch(error => {
                this.finished_context = 'Register failed'
            }).finally(() => {
                this.isLoading = false;
            });
            this.confirmNew = true;
        },
        getDepartments() {
            axios.get('departmentsName').then(response => {
                console.log('depart:' + JSON.stringify(response.data));
                this.departmentsData = response.data;
            }).catch(error => {

            });
        },
        getPositions() {
            axios.get('positions').then(response => {
                console.log('depart:' + JSON.stringify(response.data));
                this.positionsData = response.data;
            }).catch(error => {

            });
        }
    },
}
</script>

<style scoped>
h1 {
    font-size: 50px;
}

.sss {
    display: inline-block;
    justify-content: space-around;
}

img {
    width: 100px;
    height: 100px;
    border-radius: 50%;
}
</style>
