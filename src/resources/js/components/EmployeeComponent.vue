<template>
    <div class="container" style="background-color: #eef1f5">
        <div
            class="notification"
            style="backgroundcolor: #eef1f5; margin-top: 30px"
        >
            <b-button
                style="float: right; font-size: 14px; margin-left: 10px"
                size="is-large"
                type="is-primary"
                class="animate__animated animate__fadeIn"
                @click="addBtn"
                >Add a Student.</b-button
            >
            <!-- <b-button style="float:right; font-size:14px; background-color:#E0E0E0;" size="is-large" type="is-normal" v-if="!canNewPosition" class="animate__animated animate__fadeIn" @click="newPosition">Add a position</b-button> -->
        </div>
        <div></div>

        <div class="department-group" style="margin-top: 60px">
            <form @submit.prevent="searchEmp">
                <div class="container" style="background-color: #fff">
                    <b-field grouped style="padding: 40px; margin-right: 336px">
                        <b-input
                            v-model="search_val"
                            placeholder="Search..."
                            type="search"
                            icon="magnify"
                            icon-clickable
                            expanded
                        ></b-input>
                        <p class="control">
                            <button type="submit" class="button is-primary">
                                Search
                            </button>
                        </p>
                    </b-field>
                </div>
            </form>
            <!-- <b-tabs class="device-tabs">
            <b-tab-item v-for="department in departmentData" :key="department.id">
                <template slot="header">
                    <span class="device-type-name" @click="tabBtnOnclick(department.id)">{{department.name}}</span>
                </template>
            </b-tab-item>
            </b-tabs>-->
            <b-table
                :data="employeeData"
                :loading="isLoading"
                mobile-cards
                class="department-table"
            >
                <!-- @page-change="onPageChange"
                    :total="recordData.total"
                    :paginated="isPaginated"
                    :current-page.sync="recordData.current_page"
                :per-page="recordData.per_page"-->
                <template slot-scope="props">
                    <!-- <b-table-column field="user_id" label="ID" width="40">
                    <span>{{props.row.id}}</span>
                    </b-table-column>-->
                    <b-table-column
                        field="user_avatar"
                        label="Name"
                        width="100px"
                    >
                        <div class="employee-name">
                            <img
                                class="profile_img"
                                v-show="editID != props.row.id"
                                width="40px;"
                                height="40px;"
                                :src="props.row.avatar || def_avatar"
                            />
                            <span v-show="editID != props.row.id">{{
                                props.row.name
                            }}</span>
                            <b-upload
                                v-show="editID === props.row.id"
                                v-model="editImage"
                                type="file"
                            >
                                <a class="button upload_btn">
                                    <b-icon icon="upload"></b-icon>
                                    <img
                                        class="profile_img"
                                        :src="
                                            selectedImageFile
                                                ? selectedImageFile
                                                : props.row.avatar || def_avatar
                                        "
                                        width="40px;"
                                        height="40px;"
                                    />
                                </a>
                            </b-upload>
                            <b-input
                                v-show="editID === props.row.id"
                                v-model="name"
                            ></b-input>
                        </div>
                    </b-table-column>
                    <!-- <b-table-column v-show="editID != props.row.id" field="user_position" label="Position" width="40">
                    <span>{{props.row.position.name}}</span>
                </b-table-column>
                <b-table-column v-show="editID === props.row.id" field="user_position" label="Position" width="40">
                    <b-select v-model="selectPosition">
                        <option :placeholder="option.name" v-for="option in positionsData" :value="option.id" :key="option.name">
                            {{ option.name }}
                        </option>
                    </b-select>
                    </b-table-column>-->
                    <!-- <b-table-column field="user_position_level" label="Level" width="40"><span>{{props.row.position.level}}</span></b-table-column> -->

                    <!-- <b-table-column v-show="editID != props.row.id" field="user_account" label="Account" width="40"><span>{{props.row.account}}</span></b-table-column>
                <b-table-column v-show="editID === props.row.id" field="user_account" label="Account" width="40">
                    <b-input v-model="account"></b-input>
                </b-table-column>
                  <b-table-column v-show="editID != props.row.id" field="user_password" label="Password" width="40"><span>{{props.row.password}}</span></b-table-column>
                <b-table-column v-show="editID === props.row.id" field="user_password" label="Password" width="40">
                    <b-input v-model="password"></b-input>
                    </b-table-column>-->

                    <b-table-column
                        v-show="editID != props.row.id"
                        label="Mac"
                        width="40"
                    >
                        <span>{{ props.row.mac }}</span>
                    </b-table-column>
                    <b-table-column
                        v-show="editID === props.row.id"
                        label="Mac"
                        width="40"
                    >
                        <b-field>
                            <b-input
                                size="8"
                                placeholder="輸入英文數字共8碼"
                                pattern="[A-Za-z0-9]{8}"
                                maxlength="8"
                                v-model="mac"
                                required
                            />
                        </b-field>

                        <!-- <input
                            v-for="i in [0,1,2,3]"
                            v-model="macsplit[i]"
                            type="text"
                            size="2"
                            maxlength="2"
                            pattern="[A-Za-z0-9]{2}"
                            @keyup="setBlur(i)"
                            required
                        />-->
                        <!-- <input v-model="macsplit[3]" type="text" size="2" maxlength="2" /> -->
                    </b-table-column>

                    <b-table-column
                        field="cube_battery"
                        label="Batter"
                        width="40"
                    >
                        <span
                            :style="
                                props.row.battery != null &&
                                props.row.battery != ''
                                    ? props.row.battery > 80
                                        ? 'color:#00A600;'
                                        : 'color:#CE0000;'
                                    : ''
                            "
                            >{{ props.row.battery }}</span
                        >
                    </b-table-column>

                    <b-table-column
                        field="offer"
                        label="Onboard Date"
                        width="40"
                    >
                        <span>{{ props.row.onboard_date }}</span>
                    </b-table-column>
                    <b-table-column field="edit" label=" " width="40">
                        <b-button
                            class="table-btn"
                            @click="editProfile(props.row)"
                        >
                            <img width="20px;" src="images/editBtn.png" />
                        </b-button>
                    </b-table-column>
                    <b-table-column field="x" label="   " width="20">
                        <b-button
                            v-if="deleteshow"
                            class="table-btn"
                            @click="deleteEmp(props.row)"
                        >
                            <span>X</span>
                        </b-button>
                    </b-table-column>
                </template>
                <template slot="empty">
                    <section class="section">
                        <div class="content has-text-grey has-text-centered">
                            <p>
                                <b-icon
                                    icon="emoticon-sad"
                                    size="is-large"
                                ></b-icon>
                            </p>
                            <p>Nothing here.</p>
                        </div>
                    </section>
                </template>
            </b-table>
        </div>
        <b-modal :active.sync="isAddEmp" :width="640" scroll="clip">
            <AddEmp @emprefresh="getDepartmentEmployees"></AddEmp>
        </b-modal>
        <b-modal :active.sync="isAddPosition" :width="440" scroll="clip">
            <div class="container" style="background-color: #fff">
                <div
                    class="notification employee-name"
                    style="background-color: #fff"
                >
                    <p
                        class="has-text-weight-semibold"
                        style="color: #181f38; font-size: 18px"
                    >
                        Add a position
                    </p>
                </div>
                <div style="width: 360px">
                    <b-field style="margin-left: 80px">
                        <b-input
                            v-model="position"
                            placeholder="Enter a position name"
                        />
                    </b-field>
                    <b-field style="margin-left: 80px">
                        <b-input
                            v-model="position_level"
                            type="number"
                            placeholder="Enter a position level"
                        />
                    </b-field>
                </div>
                <footer class="card-bottom">
                    <b-button
                        class="card-bottom-button"
                        size="is-small"
                        type="is-primary"
                        @click="addPosition()"
                        >Add a position</b-button
                    >
                    <b-button
                        class="card-bottom-button"
                        size="is-small"
                        type="is-text"
                        >Cancel</b-button
                    >
                </footer>
            </div>
        </b-modal>
    </div>
</template>

<script>
import AddEmp from "./AddEmp";
export default {
    components: {
        AddEmp,
    },
    data: function () {
        return {
            isAddEmp: false,

            tabBtnStyle: 0,
            selectPosition: null,

            search_val: null,
            def_data: [],

            departmentData: [],
            current_department_id: null,
            employeeData: [],
            isLoading: false,
            canEdit: false,
            isNewPosition: false,
            position: null,
            isEditProfile: false,
            account: null,
            password: null,
            position_level: null,
            mac: null,
            macsplit: [],
            department: null,
            activeTab: 0,
            multiline: true,
            editID: null,
            name: null,
            school: null,

            isAddPosition: false,

            editImage: null,
            selectedImageFile: null,

            positionsData: [],
            canEditProfile: false,

            canNewPosition: false,
            deleteshow: true,

            def_avatar: "images/img_profile_default.png",
        };
    },
    watch: {
        editImage(image) {
            if (image == null) {
                return;
            }
            var reader = new FileReader();
            reader.onload = (e) => {
                this.selectedImageFile = e.target.result;
            };
            reader.readAsDataURL(image);
        },
        editID(n, o) {
            if (n === o) {
                return;
            }
        },
        position(n, o) {
            if (n === o) {
                return;
            }
        },
        search_val(n_vel, o_vel) {
            if (n_vel === "") {
                this.employeeData = this.def_data;
            }
        },
    },
    created() {
        !sessionStorage.token ? (window.location.pathname = "/") : "";
    },
    mounted() {
        // this.animate();
        this.school = sessionStorage.school;
        this.getDepartments();
        this.getDepartmentEmployees();
        this.current_department_id = 0;
    },
    methods: {
        searchEmp() {
            if (!this.search_val) {
                this.$buefy.toast.open({
                    duration: 2000,
                    message: `The input box can't be empty`,
                    position: "is-top",
                    type: "is-danger",
                });
                return;
            }

            this.def_data = this.employeeData;
            const search_val = this.search_val;

            this.searchData = this.employeeData.filter((employee) => {
                return employee.name.search(search_val) != -1;
            });

            this.employeeData = this.searchData;
        },
        // setBlur(i) {
        //     // console.log(i);
        //     // console.log(event.target.getAttribute("maxlength"));
        //     // console.log(this.$refs.macnumber);
        //     // console.log(this.macsplit[i].length);

        //     if (
        //         this.macsplit[i].length ==
        //         event.target.getAttribute("maxlength")
        //     ) {
        //         // console.log(this.$refs.focusThis[i + 1].$el);
        //         this.$refs.input[i + 1].focus();
        //         console.log("success");
        //     }
        //     return;
        // },
        // setBlur(i) {
        //     if (
        //         this.macsplit[i].length ==
        //             event.target.getAttribute("maxlength") &&
        //         i != 3
        //     ) {
        //         // console.log("success", event.target.nextElementSibling);
        //         event.target.nextElementSibling.select();

        //         // console.log("success");
        //     }
        //     return;
        // },
        deleteEmp(index) {
            const user_id = index.id;
            const user_name = index.name;
            this.$buefy.snackbar.open({
                message:
                    '要刪除這個<span style="color: red;">' +
                    user_name +
                    "</span>學生嗎？",
                type: "is-info",
                position: "is-top",
                actionText: "好",
                queue: false,
                onAction: () => {
                    axios
                        .put("deleteEmployee?id=" + user_id)
                        .then((response) => {
                            if (response.data.result == true) {
                                var index = this.employeeData.findIndex(
                                    (d) => d.id === user_id
                                );
                                this.employeeData.splice(index, 1);
                                this.$buefy.toast.open({
                                    message: "已刪除",
                                    queue: false,
                                });
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
                        });
                },
            });
        },
        tabBtnOnclick(index) {
            this.tabBtnStyle = index;
            const current_de = this.departmentData[index];
            this.getDepartmentEmployees(current_de.id);
        },
        editProfile(profile) {
            this.deleteshow = false;
            console.log("profile", profile);
            if (this.canEditProfile == true) {
                if (
                    this.selectPosition != profile.id ||
                    this.name != profile.name ||
                    this.account != profile.account ||
                    this.mac != profile.mac ||
                    this.selectedImageFile != null ||
                    this.password != profile.password
                ) {
                    console.log("123");
                    var positionIndex = this.positionsData.filter(
                        (d) => d.id === this.selectPosition
                    )[0];
                    this.employeeData = this.employeeData.map((r) => {
                        if (r.id == this.editID) {
                            r.name = this.name;
                            r.account = this.account;
                            r.password = this.password;
                            r.mac = this.mac;
                            r.avatar = this.selectedImageFile;
                            r.position = positionIndex;
                        }
                        return r;
                    });
                    if (this.mac === null || this.mac.length < 8) {
                        return;
                    }
                    if (this._valid(this.mac)) {
                        return;
                    }
                    let formData = new FormData();
                    if (this.editID) {
                        formData.append("id", this.editID);
                    }
                    if (this.name) {
                        formData.append("name", this.name);
                    }
                    if (this.mac) {
                        formData.append("mac", this.mac);
                    }
                    if (this.password) {
                        formData.append("password", this.password);
                    }
                    if (this.account) {
                        formData.append("account", this.account);
                    }
                    if (this.editImage) {
                        formData.append("imageFile", this.editImage);
                    }
                    formData.append("position_id", 10);
                    // if (this.selectPosition) {
                    //     formData.append("position_id", this.selectPosition);
                    // }
                    axios
                        .post("updateEmployee", formData, {
                            headers: {
                                "Content-Type": "multipart/form-data",
                            },
                        })
                        .then((response) => {
                            this.$buefy.toast.open({
                                message: "更新成功",
                                type: "is-success",
                                queue: false,
                            });
                            this.selectedImageFile = null;
                            profile.mac = this.mac.match(/.{2}/g).join(":");
                        })
                        .catch((error) => {
                            this.$buefy.toast.open({
                                message: "更新失敗",
                                type: "is-danger",
                                queue: false,
                            });
                        })
                        .catch((error) => {});
                    this.deleteshow = true;
                }

                this.canEditProfile = false;
                this.editID = null;
                // this.position = profile.position;
            } else {
                this.canEditProfile = true;
                this.getPositions();
                this.editID = profile.id;
                this.name = profile.name;
                this.account = profile.account;
                this.password = profile.password;
                this.mac = profile.mac.split(":").join("");
                this.selectPosition = 10;
            }
        },
        getPositions() {
            axios
                .get("positions")
                .then((response) => {
                    this.positionsData = response.data;

                    // console.log('ppp:' + JSON.stringify(this.positionsData));
                })
                .catch((error) => {});
        },
        addPosition() {
            let formData = new FormData();
            if (this.position) {
                formData.append("position", this.position);
            }
            if (this.position_level) {
                formData.append("level", this.position_level);
            }
            axios
                .post("position", formData)
                .then((response) => {
                    this.$buefy.toast.open({
                        message: "新增成功",
                        type: "is-success",
                        queue: false,
                    });
                    this.position = null;
                    this.isAddPosition = false;
                })
                .catch((error) => {
                    this.$buefy.toast.open({
                        message: "新增失敗",
                        type: "is-danger",
                        queue: false,
                    });
                });
        },
        newPosition() {
            this.isAddPosition = true;
            // if (this.isNewPosition == false) {
            //     this.isNewPosition = true;
            //     this.canNewPosition = true;
            // } else {
            //     this.isNewPosition = false;
            //     this.canNewPosition = false;
            // }
        },
        addBtn() {
            // window.location.href = 'newEmployee?user';
            this.isAddEmp = true;
        },
        getDepartments() {
            // console.log('departmentsName');
            axios
                .post("departmentsName", { school_id: this.school })
                .then((response) => {
                    let departmentData = response.data;
                    departmentData.push({
                        id: 0,
                        name: "ALL",
                    });
                    departmentData = departmentData.sort((a, b) => a.id - b.id);

                    // console.log(departmentData);
                    this.departmentData = departmentData;
                })
                .catch((error) => {});
        },
        async getDepartmentEmployees() {
            try {
                this.isLoading = true;
                const response = await axios.post("departmentEmployees", {
                    school_id: this.school,
                    id: 0,
                });
                this.employeeData = response.data;
                // this.employeeData = response.data.map(user => ({
                //     ...user,
                //     register: user.created_at.split(" ")[0],
                // }));

                console.log("更新一次:" + JSON.stringify(this.employeeData));
            } catch (e) {
            } finally {
                this.isLoading = false;
            }
        },
        //check input is alphabet or number
        _valid(value) {
            return /[^\a-\z\A-\Z0-9]/.test(value);
        },
    },
};
</script>

<style lang="scss" scoped>
span {
    // font-family: Archivo;
    font-size: 16px;
    font-weight: 600;
    font-stretch: normal;
    font-style: normal;
    line-height: normal;
    letter-spacing: normal;
    color: #6c7887;
}
// input {
//     border: 1px solid rgba(206, 206, 208, 0.947);
//     border-radius: 5px;
//     padding: 9px 2vw;
//     // margin: 0 auto;
//     // box-sizing: border-box;
//     background-color: rgb(225, 225, 235);
// }
// input:hover {
//     border: 1px solid rgba(174, 174, 176, 0.947);
// }
// input:invalid {
//     border: 2px solid red;
// }

.department-group {
    background-color: white;
    margin: 5px 5px;
    padding: 10px;
    border-radius: 10px;

    .b-tabs {
        margin-bottom: 4px;

        .tab-content {
            padding: 0;
        }
    }
}

.profile_img {
    border-radius: 50%;
}

.upload_btn {
    border-width: 0;
}

.employee-name {
    display: flex;
    align-items: center;

    img {
        border-radius: 50%;
    }

    span {
        margin-left: 0.5rem;
    }
}
</style>
