<template>
    <div class="container" style="background-color: #eef1f5">
        <div class="notification" style="backgroundcolor: #eef1f5">
            <b-button
                style="
                    margin-left: 20px;
                    float: right;
                    right: 10px;
                    font-size: 14px;
                "
                size="is-large"
                type="is-primary"
                class="animate__animated animate__fadeIn"
                @click="setupDevice"
                >How to setup the device</b-button
            >
            <b-button
                v-show="permit == 10"
                style="
                    float: right;
                    right: 10px;
                    font-size: 14px;
                    background-color: #e0e0e0;
                "
                size="is-large"
                type="is-normal"
                class="animate__animated animate__fadeIn"
                @click="getFiles()"
                >File Management</b-button
            >
            <span style="font-size: 24px; color: #181f38; font-weight: bold"
                >Devices Management</span
            ><br />
            <span>Here shows all devices information for overviewing.</span>
        </div>
        <form @submit.prevent="searchEmp">
            <div class="notification" style="background-color: #fff">
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
        <b-tabs class="device-tabs">
            <b-tab-item>
                <template slot="header">
                    <span class="device-type-name">Exist&nbsp;</span>
                    <span class="device-type-postfix">Device</span>
                </template>
                <b-table
                    :data="data"
                    :loading="isLoading"
                    mobile-cards
                    class="department-table"
                    :selected.sync="selectDevice"
                    @select="assignTag"
                >
                    <template slot-scope="props">
                        <b-table-column field="" label="Status" width="180">
                            <div class="device-alive">
                                <img
                                    :src="
                                        props.row.is_alive == true
                                            ? '/images/connected.png'
                                            : '/images/disconnected.png'
                                    "
                                />
                                <span>{{
                                    props.row.is_alive
                                        ? "connected"
                                        : "disconnected"
                                }}</span>
                            </div>
                        </b-table-column>
                        <b-table-column field="" label="Name" centered>
                            <span style="color: #0084ff">{{
                                props.row.name
                            }}</span>
                        </b-table-column>
                        <b-table-column field="" label="Area" centered>
                            <span>{{ props.row.area_name }}</span>
                        </b-table-column>
                        <b-table-column field="" label="IP" centered>
                            <span>{{ props.row.ip }}</span>
                        </b-table-column>
                        <b-table-column field="" label="SSID" centered>
                            <span>{{ props.row.ssid }}</span>
                        </b-table-column>
                        <b-table-column field="" label="MAC" centered>
                            <span>{{ props.row.mac }}</span>
                        </b-table-column>
                        <b-table-column field="" label="" centered>
                            <b-button
                                class="table-btn"
                                @click="deleteDevice(props.row)"
                                ><span>X</span></b-button
                            >
                        </b-table-column>
                    </template>
                    <template slot="empty">
                        <section class="section">
                            <div
                                class="content has-text-grey has-text-centered"
                            >
                                <p>
                                    <b-icon icon="emoticon-sad" size="is-large">
                                    </b-icon>
                                </p>
                                <p>Nothing here.</p>
                            </div>
                        </section>
                    </template>
                </b-table>
            </b-tab-item>
            <b-tab-item @click="tabBtnOnclick(1)">
                <template slot="header">
                    <span class="device-type-name">New&nbsp;</span>
                    <span class="device-type-postfix">Device</span>
                </template>
                <!--new device-->
                <b-table
                    :data="new_device"
                    :loading="isLoading"
                    mobile-cards
                    class="department-table"
                    :selected.sync="selectNewDevice"
                    @select="assignTagNewDevice"
                >
                    <template slot-scope="props">
                        <b-table-column field="" label="MAC" centered>
                            <span>{{ props.row.device_mac }}</span>
                        </b-table-column>
                        <b-table-column field="" label="Scan date" centered>
                            <span>{{ props.row.device_date }}</span>
                        </b-table-column>
                    </template>
                    <template slot="empty">
                        <section class="section">
                            <div
                                class="content has-text-grey has-text-centered"
                            >
                                <p>
                                    <b-icon icon="emoticon-sad" size="is-large">
                                    </b-icon>
                                </p>
                                <p>Nothing here.</p>
                            </div>
                        </section>
                    </template>
                </b-table>
                <!--new device-->
            </b-tab-item>
        </b-tabs>
        <b-modal :active.sync="isFilesPage" :width="640" scroll="clip">
            <div style="background-color: #fff">
                <div class="notification" style="background-color: #fff">
                    <span class="notification-title">File Management</span
                    ><br />
                    <form @submit.prevent="uploadFile">
                        <b-field class="file" style="margin-top: 20px">
                            <b-upload v-model="file" v-if="file == null">
                                <a class="button is-primary">
                                    <p style="font-size: 14px">Upload file</p>
                                </a>
                            </b-upload>
                            <button type="submit" class="button" v-if="file">
                                <span>上傳</span>
                            </button>
                        </b-field>
                        <div class="tags">
                            <span class="tag is-info" v-if="file">
                                {{ file.name }}
                                <button
                                    class="delete is-small"
                                    type="button"
                                    @click="deleteDropFile(index)"
                                ></button>
                            </span>
                        </div>
                    </form>
                </div>
                <b-table
                    :data="existFiles"
                    :loading="isLoading"
                    mobile-cards
                    class="department-table"
                >
                    <template slot-scope="props">
                        <b-table-column field="" label="File name">
                            <span>{{ props.row }}</span>
                        </b-table-column>
                        <b-table-column field="" label="" centered>
                            <b-button
                                class="table-btn"
                                @click="deleteFile(props.row)"
                                ><span>X</span></b-button
                            >
                        </b-table-column>
                    </template>
                </b-table>
            </div>
        </b-modal>
        <b-modal :active.sync="isEditDevice" has-modal-card>
            <form @submit.prevent="saveEditDevice">
                <div class="modal-card is-info">
                    <header class="modal-card-head">
                        <p class="modal-card-title">修改裝置</p>
                    </header>
                    <section class="modal-card-body">
                        <b-field
                            label="裝置名稱"
                            :label-position="labelPosition"
                        >
                            <b-input
                                type="text"
                                placeholder="請輸入此裝置名稱"
                                v-model="name"
                                required
                            ></b-input>
                        </b-field>
                        <b-field label="MAC" :label-position="labelPosition">
                            <b-input
                                type="text"
                                v-model="mac"
                                required
                            ></b-input>
                        </b-field>
                        <b-field label="SSID" :label-position="labelPosition">
                            <b-input
                                type="text"
                                placeholder="請輸入WIFI名稱"
                                v-model="ssid"
                                required
                            ></b-input>
                        </b-field>
                        <b-field
                            label="PASSWORD"
                            :label-position="labelPosition"
                        >
                            <b-input
                                type="text"
                                placeholder="請輸入WIFI密碼"
                                v-model="password"
                                required
                            ></b-input>
                        </b-field>
                        <b-field
                            label="RSSI設定"
                            :label-position="labelPosition"
                        >
                            <b-input
                                type="text"
                                placeholder="請輸入設定值"
                                v-model="rssi"
                                required
                            ></b-input>
                        </b-field>
                        <b-select
                            size="is-small"
                            placeholder="請選擇部門"
                            v-model="selectedOption"
                        >
                            <option
                                v-for="option in deaprtments"
                                :value="option.id"
                                :key="option.id"
                            >
                                {{ option.name }}
                            </option>
                        </b-select>
                        <div
                            v-for="record in scannerRecordsData"
                            :key="record.id"
                        >
                            {{ record.name }}: {{ record.rssi }}
                        </div>
                    </section>
                    <footer class="modal-card-foot">
                        <button type="submit" class="button is-info">
                            儲存
                        </button>
                    </footer>
                </div>
            </form>
        </b-modal>
        <b-modal :active.sync="isNewDevice" :width="300" scroll="clip">
            <form @submit.prevent="addNewDevice">
                <div class="modal-card is-info" style="width: auto">
                    <header class="modal-card-head">
                        <p class="modal-card-title">新增裝置</p>
                    </header>
                    <section class="modal-card-body">
                        <b-field>
                            <b-input
                                style="border-color: #28a745"
                                type="text"
                                placeholder="請輸入此裝置名稱"
                                v-model="name"
                                required
                            ></b-input>
                        </b-field>
                        <b-field>
                            <b-input
                                type="text"
                                v-model="mac"
                                required
                            ></b-input>
                        </b-field>
                        <b-field>
                            <b-input
                                type="text"
                                placeholder="請輸入WIFI名稱"
                                v-model="ssid"
                                required
                            ></b-input>
                        </b-field>
                        <b-field>
                            <b-input
                                type="text"
                                placeholder="請輸入WIFI密碼"
                                v-model="password"
                                required
                            ></b-input>
                        </b-field>

                        <b-select
                            size="is-small"
                            placeholder="請選擇區域"
                            v-model="selectedOption"
                        >
                            <option
                                v-for="option in deaprtments"
                                :value="option.id"
                                :key="option.id"
                            >
                                {{ option.name }}
                            </option>
                        </b-select>
                    </section>
                    <footer class="modal-card-foot">
                        <button type="submit" class="button is-success">
                            新增
                        </button>
                    </footer>
                </div>
            </form>
        </b-modal>
    </div>
</template>

<script>
import Profile from "../../Profile";
import EditDevice from "./modules/EditDevice";
export default {
    components: {
        EditDevice,
        Profile,
    },
    data: function () {
        return {
            school: null,
            isEditDevice: false,
            editdata: [],
            //

            tabBtnStyle: 0,
            search_val: null,
            isLoading: false,
            isFullPage: false,
            isFilesPage: false,

            deaprtments: [],
            devicedata: [],
            name: null,
            mac: null,
            selectedOption: null,
            new_device: [],
            showDeviceBtn: false,
            isNewDevice: false,
            ssid: null,
            password: null,
            rssi: null,
            device_id: null,

            isEditDevice: false,
            labelPosition: "on-border",

            scannerRecordsData: [],

            dropFiles: [],
            file: null,
            existFiles: [],

            scannerTimer: null,
            deviceTimer: null,

            permit: null,

            selectDevice: null,
            selectNewDevice: null,
        };
    },
    watch: {
        file(n, o) {
            if (n === null) {
                this.file = null;
            }
        },
    },
    mounted() {
        !sessionStorage.token ? (window.location.pathname = "/") : "";
        // this.deviceTimer = setInterval(this.getDevice, 2000);
        this.school = sessionStorage.school;
        this.permit = sessionStorage.permit;
        // this.getFiles();
        this.getDevice();
        this.getScanner();
        // this.scannerTimer = setInterval(this.getScanner, 1000);
    },
    methods: {
        setupDevice() {
            window.location.pathname = "/setup";
        },
        assignTagNewDevice(selectNewDevice) {
            console.log("sele:" + JSON.stringify(selectNewDevice));
            this.name = null;
            this.password = null;
            this.ssid = null;
            this.selectedOption = null;
            this.isNewDevice = true;
            this.mac = selectNewDevice.device_mac;
        },
        assignTag(selectDevice) {
            console.log("按到records:" + JSON.stringify(selectDevice));
            this.isEditDevice = true;
            this.device_id = selectDevice.id;
            this.name = selectDevice.name;
            this.mac = selectDevice.mac;
            this.ssid = selectDevice.ssid;
            this.password = selectDevice.password;
            this.selectedOption = selectDevice.area_id;
            this.rssi = selectDevice.rssi_setting;

            axios
                .get("scannerRecords?mac=" + this.mac)
                .then((response) => {
                    console.log("show record:" + response.data);
                    this.scannerRecordsData = response.data;
                    // clearInterval(this.timer)
                })
                .catch((error) => {});
        },
        tabBtnOnclick(btn) {
            console.log("按到tab:" + btn);
            this.tabBtnStyle = btn;
            if (btn == 1) {
                this.getScanner();
                this.scannerTimer = setInterval(this.getScanner, 1000);
                clearInterval(this.deviceTimer);
            }
            if (btn == 0) {
                this.deviceTimer = setInterval(this.getDevice, 2000);
                clearInterval(this.scannerTimer);
            }
        },
        deleteFile(file, index) {
            console.log("按到delete file," + file);
            this.$buefy.snackbar.open({
                message:
                    '要刪除這個<span style="color: red;">' +
                    file +
                    "</span>檔案嗎？",
                type: "is-warning",
                position: "is-top",
                actionText: "好",
                queue: false,
                onAction: () => {
                    axios
                        .put("file?name=" + file)
                        .then((response) => {
                            console.log(response.data);
                            if (response.data.result == true) {
                                this.existFiles.splice(index, 1);
                                this.$buefy.toast.open({
                                    message: "已刪除",
                                    queue: false,
                                });
                            }
                        })
                        .catch((error) => {
                            if (error) {
                                this.$buefy.toast.open({
                                    message: "刪除失敗請聯繫相關人員協助處理",
                                    type: "is-danger",
                                    queue: false,
                                });
                            }
                        });
                },
            });
        },
        getFiles() {
            this.isLoading = true;
            this.isFilesPage = true;
            axios
                .get("allFiles")
                .then((response) => {
                    console.log("files:" + response.data);
                    this.existFiles = response.data;
                })
                .catch((error) => {})
                .finally(() => {
                    this.isLoading = false;
                });
        },
        uploadFile() {
            this.isLoading = true;
            let formData = new FormData();
            if (this.file) {
                formData.append("file", this.file);
            }
            axios
                .post("files", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                })
                .then((response) => {
                    this.$buefy.toast.open({
                        message: "上傳成功",
                        type: "is-success",
                        queue: false,
                    });
                })
                .catch((error) => {
                    this.$buefy.toast.open({
                        message: "上傳失敗",
                        type: "is-danger",
                        queue: false,
                    });
                })
                .finally(() => {
                    this.existFiles.push(this.file.name);
                    this.file = null;
                    this.isLoading = false;
                });
        },
        deleteDropFile() {
            this.file = null;
        },
        editDevice(device) {
            // console.log("props", device);
            this.isEditDevice = true;
            this.editdata = device;
        },

        deleteDevice(device) {
            const id = device.id;
            this.$buefy.snackbar.open({
                message:
                    '要刪除這個<span style="color: red;">' +
                    device.name +
                    "</span>裝置嗎？",
                type: "is-warning",
                position: "is-top",
                actionText: "好",
                queue: false,
                onAction: () => {
                    axios
                        .put("device?id=" + id)
                        .then((response) => {
                            console.log("Data:" + response.data);
                            if (response.data.result == true) {
                                var index = this.data.findIndex(
                                    (d) => d.id === device.id
                                );
                                this.data.splice(index, 1);
                                this.$buefy.toast.open({
                                    message: "已刪除",
                                    queue: false,
                                });
                                this.isEditDevice = false;
                            }
                        })
                        .catch((error) => {
                            if (error) {
                                this.$buefy.toast.open({
                                    message: "刪除失敗請聯繫相關人員協助處理",
                                    type: "is-danger",
                                    queue: false,
                                });
                            }
                        });
                },
            });
        },
        addNewDevice() {
            let formData = new FormData();

            if (this.mac) {
                formData.append("mac", this.mac);
            }
            if (this.name) {
                formData.append("name", this.name);
            }
            if (this.selectedOption) {
                formData.append("area", this.selectedOption);
            }
            if (this.ssid) {
                formData.append("ssid", this.ssid);
            }
            if (this.password) {
                formData.append("password", this.password);
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
                        var index = this.new_device.findIndex(
                            (d) => d.device_mac === this.mac
                        );
                        this.new_device.splice(index, 1);
                        this.data.push({
                            id: response.data.data,
                            name: this.name,
                            mac: this.mac,
                            ssid: this.ssid,
                        });
                        this.$buefy.toast.open({
                            message: "已新增",
                            type: "is-success",
                            queue: false,
                        });
                        this.isEditDevice = false;
                    }

                    this.data = this.data.sort((a, b) => b.id - a.id);
                    this.isNewDevice = false;
                    Dialog.alert({
                        title: "Message",
                        message: response.data.data,
                        confirmText: "OK",
                        onConfirm: () => {},
                    });
                })
                .catch((error) => {})
                .finally(() => {});
        },
        getScanner() {
            console.log("get scanner");
            axios
                .get("scanner")
                .then((response) => {
                    console.log(
                        "getScannerData:" + JSON.stringify(response.data)
                    );
                    if (response.data.length != 0) {
                        this.showDeviceBtn = true;
                        this.new_device = response.data;
                    } else {
                        this.showDeviceBtn = false;
                    }
                })
                .catch((error) => {})
                .finally(() => {
                    // clearInterval(this.timer)
                });
        },

        getDevice() {
            console.log("getDevice timer");
            axios
                .get("devices?school_id=" + this.school)
                .then((response) => {
                    console.log("devices:" + JSON.stringify(response.data));
                    this.devicedata = response.data.sort((a, b) => b.id - a.id);
                })
                .catch((error) => {})
                .finally(() => {});
        },
    },
};
</script>

<style lang="scss" scoped>
.table .card-body {
    padding: 0;
}
</style>

