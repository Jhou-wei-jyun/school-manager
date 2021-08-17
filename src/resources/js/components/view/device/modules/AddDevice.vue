<template>
    <div class="card card-body">
        <!-- {{deviceInfo}} -->
        <header class="card-bottom d-flex align-items-center">
            <p class="h4 has-text-weight-semibold">新增裝置</p>
        </header>

        <div>
            <table
                class="table table-bordered"
                id="dataTable"
                width="100%"
                cellspacing="0"
            >
                <thead>
                    <tr>
                        <th>MAC</th>
                        <th>Scan date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in new_device" :key="item.device_mac">
                        <td>{{ item.device_mac }}</td>
                        <td>{{ item.device_date }}</td>
                        <td>
                            <b-button class="table-btn pl-0 pr-0">
                                <img
                                    class="rounded-circle"
                                    width="40px"
                                    src="images/edit_icon.svg"
                                    @click="addBtn(item)"
                                />
                            </b-button>
                        </td>
                    </tr>
                </tbody>
            </table>
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
        </footer>
        <!-- Add device -->
        <b-modal :active.sync="isAddDevice" :width="300" scroll="clip">
            <div class="card card-body">
                <header class="card-bottom d-flex align-items-center">
                    <p class="h4 has-text-weight-semibold">新增裝置</p>
                </header>
                <div>
                    <b-field label="Mac">
                        {{ newDeviceMac }}
                    </b-field>
                    <b-field label="裝置名稱">
                        <b-input
                            type="text"
                            v-model="new_devicedata.name"
                            required
                        ></b-input>
                    </b-field>
                    <b-field label="SSID">
                        <b-input
                            type="text"
                            v-model="new_devicedata.ssid"
                            required
                            placeholder="請輸入WIFI名稱"
                        ></b-input>
                    </b-field>
                    <b-field label="PASSWORD">
                        <b-input
                            type="text"
                            v-model="new_devicedata.password"
                            required
                            placeholder="請輸入WIFI密碼"
                        ></b-input>
                    </b-field>
                    <b-field label="位置">
                        <b-select v-model="new_devicedata.selectedArea">
                            <option
                                v-for="option in areaOption"
                                placeholder="請選擇位置"
                                :value="option.id"
                                :key="option.id"
                            >
                                {{ option.name }}
                            </option>
                        </b-select>
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
                        @click="addNewDevice()"
                        >新增</b-button
                    >
                </footer>
            </div>
        </b-modal>
    </div>
</template>

<script>
export default {
    props: ["admin"],
    data: function () {
        return {
            new_device: null,
            isAddDevice: false,
            newDeviceMac: null,
            labelPosition: "on-border",
            areaOption: null,
            school: null,
            new_devicedata: {
                name: null,
                ssid: null,
                passworf: null,
                selectedArea: null,
            },
        };
    },
    mounted() {
        this.school = sessionStorage.school;
        this.getArea();
        this.getScanner();
        this.scannerTimer = setInterval(this.getScanner, 1000);
    },
    methods: {
        // refresh() {
        //     this.$emit("refresh");
        // },
        getScanner() {
            console.log("get scanner");
            axios
                .get("scanner")
                .then((response) => {
                    console.log(
                        "getScannerData:" + JSON.stringify(response.data)
                    );
                    if (response.data.length != 0) {
                        this.new_device = response.data;
                    }
                })
                .catch((error) => {})
                .finally(() => {
                    // clearInterval(this.timer)
                });
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
        addBtn(item) {
            this.isAddDevice = true;
            this.newDeviceMac = item.device_mac;
        },
        addNewDevice() {
            if (
                this.new_devicedata.name === null ||
                this.new_devicedata.selectedArea === null ||
                this.new_devicedata.ssid === null ||
                this.new_devicedata.password === null
            ) {
                return;
            }
            let formData = new FormData();
            if (this.admin) {
                formData.append("admin_id", this.admin);
            }
            if (this.school) {
                formData.append("school_id", this.school);
            }
            if (this.newDeviceMac) {
                formData.append("mac", this.newDeviceMac);
            }
            if (this.new_devicedata.name) {
                formData.append("name", this.new_devicedata.name);
            }
            if (this.new_devicedata.selectedArea) {
                formData.append("area", this.new_devicedata.selectedArea);
            }
            if (this.new_devicedata.ssid) {
                formData.append("ssid", this.new_devicedata.ssid);
            }
            if (this.new_devicedata.password) {
                formData.append("password", this.new_devicedata.password);
            }
            axios
                .post("device", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                })
                .then((response) => {
                    console.log("newnew:" + response.data);
                    if (response.data.result == true) {
                        this.$buefy.toast.open({
                            message: "已新增",
                            type: "is-success",
                            queue: false,
                        });
                        this.new_devicedata.name = null;
                        this.new_devicedata.ssid = null;
                        this.new_devicedata.password = null;
                        this.new_devicedata.selectedArea = null;
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
                    this.$parent.close();
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
