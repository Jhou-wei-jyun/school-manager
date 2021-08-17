<template>
    <div class="card card-body">
        <!-- {{ deviceInfo }} -->
        <header class="card-bottom d-flex align-items-center">
            <p class="h4 has-text-weight-semibold">編輯裝置</p>
        </header>

        <div>
            <b-field label="裝置名稱">
                <b-input type="text" v-model="editData.name" required></b-input>
            </b-field>
            <b-field label="位置">
                {{ editData.school_name }} - {{ editData.area_name }}
            </b-field>

            <!-- <b-field label="IP位址">
                <b-input type="text" v-model="editData.ip" required></b-input>
            </b-field> -->
            <b-field label="SSID">
                <b-input type="text" v-model="editData.ssid" required></b-input>
            </b-field>
            <b-field label="PASSWORD">
                <b-input
                    type="text"
                    v-model="editData.password"
                    required
                ></b-input>
            </b-field>
            <b-field label="IP位址">
                {{ editData.ip }}
            </b-field>
            <b-field label="Mac">
                {{ editData.mac }}
                <!-- <b-input
                    placeholder="輸入英文數字共8碼"
                    pattern="[A-Za-z0-9]{12}"
                    v-model="editMac"
                    required
                /> -->
            </b-field>
            <b-field label="RSSI設定">
                <b-input type="text" v-model="editData.rssi"></b-input>
            </b-field>
            <b-field label="位置">
                <b-select v-model="selectedOption">
                    <option
                        v-for="option in areaOption"
                        :value="option.id"
                        :key="option.id"
                    >
                        {{ option.name }}
                    </option>
                </b-select>
            </b-field>
            <div v-for="record in scannerRecordsData" :key="record.id">
                {{ record.name }}: {{ record.rssi }}
            </div>
            <!-- <b-field label="Mac">
                    <b-input
                        size="8"
                        placeholder="輸入英文數字共8碼"
                        pattern="[A-Za-z0-9]{8}"
                        maxlength="8"
                        v-model="editMac"
                        required
                    />
                </b-field> -->
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
                @click="saveEditDevice()"
                >編輯</b-button
            >
        </footer>
    </div>
</template>

<script>
export default {
    props: ["deviceInfo", "admin"],
    data: function () {
        return {
            def_avatar: "images/img_department_default@2x.png",
            def_camera: "images/btn_camera@2x.png",
            editData: this.deviceInfo,
            editMac: null,
            areaOption: null,
            selectedOption: this.deviceInfo.area_id,
            school: null,
            scannerRecordsData: null,
        };
    },
    mounted() {
        this.school = sessionStorage.school;
        this.scannerRecords();
        this.getArea();
        if (this.editData.mac !== null) {
            this.editMac = this.editData.mac.split(":").join("");
        }
    },
    methods: {
        // refresh() {
        //     this.$emit("refresh");
        // },
        scannerRecords() {
            axios
                .get("scannerRecords?mac=" + this.editData.mac)
                .then((response) => {
                    console.log("show record:" + response.data);
                    this.scannerRecordsData = response.data;
                    // clearInterval(this.timer)
                })
                .catch((error) => {});
        },
        getArea() {
            axios
                .get("areaOptions?school_id=" + this.school)
                .then((response) => {
                    this.areaOption = response.data;
                })
                .catch((error) => {})
                .finally(() => {});
        },
        saveEditDevice() {
            if (
                this.editData.name === null ||
                this.selectedOption === null ||
                this.editData.ssid === null ||
                this.editData.password === null
                // this.editMac === null ||
                // this.editMac.length < 12
            ) {
                return console.log("1");
            }
            // if (this._valid(this.editMac)) {
            //     return console.log("2");
            // }
            // if (!this._ip_valid(this.editData.ip)) {
            //     return console.log("3");
            // }
            let formData = new FormData();
            if (this.admin) {
                formData.append("admin_id", this.admin);
            }
            if (this.editData.id) {
                formData.append("id", this.editData.id);
            }
            if (this.editData.name) {
                formData.append("name", this.editData.name);
            }
            // if (this.editMac) {
            //     formData.append("mac", this.editMac);
            // }
            if (this.selectedOption) {
                formData.append("area", this.selectedOption);
            }
            if (this.editData.ssid) {
                formData.append("ssid", this.editData.ssid);
            }
            if (this.editData.password) {
                formData.append("password", this.editData.password);
            }
            if (this.editData.rssi) {
                formData.append("rssi", this.editData.rssi);
            }
            axios
                .post("editDevice", formData, {
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
                })
                .catch((error) => {
                    this.$buefy.toast.open({
                        message: "更新失敗",
                        type: "is-danger",
                        queue: false,
                    });
                })
                .finally(() => {
                    this.$parent.close();
                });
        },
        //check input is alphabet or number
        _valid(value) {
            return /[^\a-\z\A-\Z0-9]/.test(value);
        },
        _ip_valid(value) {
            const ipRegex = /^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/;
            return ipRegex.test(value);
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
