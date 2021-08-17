<template>
    <div class="card card-body">
        <header class="card-bottom d-flex align-items-center">
            <p class="h4 has-text-weight-semibold">編輯家長</p>
        </header>

        <div>
            <b-field label="姓名">
                <b-input
                    type="text"
                    placeholder="Enter Name"
                    v-model="name"
                    required
                ></b-input>
            </b-field>
            <!-- <b-field label="English Name">
                    <b-input type="text" placeholder="Enter English Name" v-model="ename" required></b-input>
                </b-field>-->
            <b-field label="電話">
                <b-input
                    size="10"
                    placeholder="輸入電話號碼共10碼"
                    pattern="[0][9][0-9]{8}"
                    maxlength="10"
                    v-model="phone"
                    required
                />
            </b-field>
            <b-field label="關係人">
                <b-select
                    size="is-middle"
                    placeholder="Select title"
                    v-model="selectedOption"
                    expanded
                >
                    <option
                        v-for="option in supervisors"
                        :value="option"
                        :key="option.id"
                    >
                        {{ option.name }}
                    </option>
                </b-select>
            </b-field>
        </div>

        <footer class="card-bottom d-flex align-items-center">
            <b-button
                class="notification_btn notification_btn_gray notification_btn_text_white ml-auto mr-2"
                size="is-medium"
                @click="$parent.close()"
                >取消</b-button
            >
            <b-button
                class="notification_btn notification_btn_sky notification_btn_text_white"
                size="is-medium"
                @click="updateParents()"
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
export default {
    props: ["parentInfo", "admin"],
    data: function () {
        console.log("this.parentInfo", this.parentInfo);
        return {
            def_avatar: "images/img_department_default@2x.png",
            def_camera: "images/btn_camera@2x.png",
            supervisors: [],
            name: this.parentInfo.name,
            ename: this.parentInfo.ename,
            phone: this.parentInfo.phone,
            selectedImageFile: null,
            selectedOption: {
                id: this.parentInfo.title,
                name: this.parentInfo.title,
            },
            editImage: null,
            parent_id: this.parentInfo.parent_id,
            isLoading: false,
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
        !sessionStorage.token ? (window.location.pathname = "/") : "";
        this.getSupervisors();
    },
    methods: {
        _phone_valid(value) {
            const Regex = /[0][9][0-9]{8}/;
            return Regex.test(value);
        },
        Parentrefresh() {
            this.$emit("Parentrefresh");
        },
        getSupervisors() {
            this.supervisors = [
                { id: "mother", name: "mother" },
                { id: "father", name: "father" },
                { id: "grandmother", name: "grandmother" },
                { id: "grandfather", name: "grandfather" },
            ];
        },
        updateParents() {
            if (!this._phone_valid(this.phone)) {
                return;
            }
            this.isLoading = true;
            let formData = {};
            if (this.admin) {
                formData["admin_id"] = this.admin;
            }
            if (this.parent_id) {
                formData["parent_id"] = this.parent_id;
            }
            if (this.name) {
                formData["name"] = this.name;
            }
            if (this.name) {
                formData["ename"] = this.name;
            }
            if (this.selectedOption) {
                formData["title"] = this.selectedOption.id;
            }
            if (this.phone) {
                formData["phone"] = this.phone;
            }
            // if (this.editImage) {
            //     formData.append("editImage", this.editImage);
            // }
            // 新增
            axios
                .post("parent/update", formData, {})
                .then((response) => {
                    this.$buefy.toast.open({
                        message: "更新成功",
                        type: "is-success",
                        queue: false,
                    });
                    this.ClearData();
                    this.Parentrefresh();
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
        ClearData() {
            this.supervisors = [];
            this.name = null;
            this.ename = null;
            this.phone = null;
            this.selectedImageFile = null;
            this.selectedOption = null;
            this.editImage = null;
            this.parent_id = null;
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

.profile-camera {
    position: absolute;
    left: 11vw;
    top: 23vh;
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
