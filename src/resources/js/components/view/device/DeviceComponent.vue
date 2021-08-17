<template>
    <div>
        <profile-component></profile-component>
        <b-modal
            :active.sync="isEditDevice"
            :admin="admin_id"
            :width="350"
            scroll="clip"
        >
            <EditDevice :device-info="editdata"></EditDevice>
        </b-modal>
        <b-modal
            :active.sync="isAddDevice"
            :admin="admin_id"
            :width="600"
            scroll="clip"
        >
            <AddDevice :device-info="editdata"></AddDevice>
        </b-modal>
        <div class="container mt-5">
            <div class="card table shadow">
                <div class="card-header d-flex flex-column">
                    <div class="d-flex flex-row mt-3">
                        <div class="h5">無線基地台</div>
                        <div class="dropdown no-arrow ml-auto">
                            <a
                                class="nav-link dropdown-toggle"
                                href="#"
                                role="button"
                                id="dropdownMenuLink"
                                data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                            >
                                <i class="fas fa-ellipsis-v fa-2x"></i>
                            </a>

                            <div
                                class="dropdown-menu"
                                aria-labelledby="dropdownMenuLink"
                            >
                                <a
                                    class="dropdown-item"
                                    @click="addBtn"
                                    v-show="
                                        right['新增裝置'] == null
                                            ? false
                                            : right['新增裝置']['show']
                                    "
                                    >新增裝置</a
                                >
                                <a
                                    class="dropdown-item"
                                    @click="setupDevice"
                                    v-show="
                                        right['如何設置'] == null
                                            ? false
                                            : right['如何設置']['show']
                                    "
                                    >如何設置</a
                                >
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-row justify-content-between mt-3">
                        <b-input
                            v-model="search_val"
                            placeholder="Search"
                            type="search"
                            icon="magnify"
                            icon-clickable
                        ></b-input>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table
                            class="table table-bordered"
                            id="dataTable"
                            width="100%"
                            cellspacing="0"
                        >
                            <thead>
                                <tr>
                                    <th>狀態</th>
                                    <th>裝置名稱</th>
                                    <th>位置</th>
                                    <th>IP</th>
                                    <th>SSID</th>
                                    <th>MAC</th>
                                    <th
                                        v-show="
                                            right['編輯'] == null
                                                ? false
                                                : right['編輯']['show']
                                        "
                                    >
                                        編輯
                                    </th>
                                    <th
                                        v-show="
                                            right['移除'] == null
                                                ? false
                                                : right['移除']['show']
                                        "
                                    >
                                        移除
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in devicedata" :key="item.id">
                                    <td>
                                        <img
                                            :src="
                                                item.is_alive == true
                                                    ? '/images/green.svg'
                                                    : '/images/red.svg'
                                            "
                                            width="20px"
                                        />
                                    </td>
                                    <td>{{ item.name }}</td>
                                    <td>{{ item.area_name }}</td>
                                    <td>{{ item.ip }}</td>
                                    <td>{{ item.ssid }}</td>
                                    <td>{{ item.mac }}</td>
                                    <td
                                        v-show="
                                            right['編輯'] == null
                                                ? false
                                                : right['編輯']['show']
                                        "
                                    >
                                        <b-button class="table-btn pl-0 pr-0">
                                            <img
                                                class="rounded-circle"
                                                width="40px"
                                                src="images/edit_icon.svg"
                                                @click="editDevice(item)"
                                            />
                                        </b-button>
                                    </td>
                                    <td
                                        v-show="
                                            right['移除'] == null
                                                ? false
                                                : right['移除']['show']
                                        "
                                    >
                                        <b-button class="table-btn pl-0 pr-0">
                                            <img
                                                class="rounded-circle"
                                                width="40px"
                                                src="images/delete_icon.svg"
                                                @click="deleteDevice(item)"
                                            />
                                        </b-button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
// import Profile from "../../Profile";
import EditDevice from "./modules/EditDevice";
import AddDevice from "./modules/AddDevice";
export default {
    components: {
        EditDevice,
        AddDevice,
    },
    data: function () {
        return {
            school: null,
            isEditDevice: false,
            isAddDevice: false,
            editdata: [],
            //

            search_val: null,
            isLoading: false,

            devicedata: [],

            isEditDevice: false,
            labelPosition: "on-border",
            group_id: null,
            right: [],
            admin_id: null,
        };
    },
    watch: {
        file(n, o) {
            if (n === null) {
                this.file = null;
            }
        },
    },
    created() {
        !sessionStorage.token ? (window.location.pathname = "/") : "";
        this.group_id = sessionStorage.group;
        this.admin_id = sessionStorage.id;
        this.getRight();
    },
    mounted() {
        this.school = sessionStorage.school;
        this.getDevice();
        this.deviceTimer = setInterval(this.getDevice, 2000);
    },
    methods: {
        getRight() {
            axios
                .get("right/block", {
                    params: {
                        group_id: this.group_id,
                        tab_id: 12, //page_id :12 設備管理
                    },
                })
                .then((response) => {
                    this.right = response.data;
                });
        },
        setupDevice() {
            window.open("/setup");
        },
        addBtn() {
            // console.log("props", device);
            this.isAddDevice = true;
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
                        .put("device", {
                            id: id,
                            admin_id: this.admin_id,
                        })
                        .then((response) => {
                            console.log("Data:" + response.data);
                            if (response.data.result == true) {
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

