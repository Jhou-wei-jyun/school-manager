<template>
    <div class="card card-body">
        <header class="card-bottom d-flex align-items-center">
            <p class="h4 has-text-weight-semibold">新增家長</p>
        </header>

        <div class="blue">
            <b-field label="姓名">
                <b-input type="text" v-model="name" required></b-input>
            </b-field>
            <b-field label="電話">
                <b-input
                    type="text"
                    v-model="phone"
                    required
                    size="10"
                    pattern="[0][9][0-9]{8}"
                    maxlength="10"
                ></b-input>
            </b-field>
            <b-field label="關係人">
                <b-select size="is-middle" v-model="selectedOption" expanded>
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
                @click="addNewParents()"
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
export default {
    props: ["admin"],
    data: function () {
        return {
            def_avatar: "images/img_profile_default.png",
            supervisors: [],
            name: null,
            // ename: null,
            phone: null,
            selectedImageFile: null,
            selectedOption: null,
            editImage: null,
            school: null,
            isLoading: false,
        };
    },
    watch: {},
    mounted() {
        this.school = sessionStorage.school;
        this.getSupervisors();
    },
    methods: {
        Departmentrefresh() {
            this.$emit("onDepartmentrefresh");
        },
        getSupervisors() {
            this.supervisors = [
                { id: "mother", name: "mother" },
                { id: "father", name: "father" },
                { id: "grandmother", name: "grandmother" },
                { id: "grandfather", name: "grandfather" },
            ];
        },
        addNewParents() {
            if (
                this.name === null ||
                this.selectedOption === null ||
                this.phone === null
            ) {
                return;
            }
            if (this.phone != null) {
                if (this.phone.length < 10) {
                    return;
                }
            }
            if (!this._phone_valid(this.phone)) {
                return;
            }
            this.isLoading = true;
            let formData = new FormData();
            if (this.admin) {
                formData.append("admin_id", this.admin);
            }
            if (this.name) {
                formData.append("name", this.name);
            }
            if (this.selectedOption) {
                formData.append("title", this.selectedOption.id);
            }
            if (this.phone) {
                formData.append("phone", this.phone);
            }
            if (this.school) {
                formData.append("school_id", this.school);
            }
            // 新增
            axios
                .post("parent/store", formData)
                .then((response) => {
                    if (response.data.result == true) {
                        this.$buefy.toast.open({
                            message: "新增成功",
                            type: "is-success",
                            queue: false,
                        });
                    }
                    this.isNewDepartment = false;
                    this.Departmentrefresh();
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
        _phone_valid(value) {
            const Regex = /[0][9][0-9]{8}/;
            return Regex.test(value);
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
    position: absoulate;
    margin-bottom: 90px;
    margin-left: 80px;
    padding-bottom: 15px;
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
