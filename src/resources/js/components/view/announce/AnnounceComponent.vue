<template>
    <div>
        <profile-component></profile-component>

        <div class="container mt-5">
            <!-- {{JSON.stringify(announceData)}} -->

            <div class="card table shadow">
                <div class="card-header d-flex flex-column">
                    <div class="d-flex flex-row h3 mt-4 ml-1">廣告輪播圖</div>
                    <div class="d-flex flex-row">
                        <b-button
                            size="is-medium"
                            class="
                                notification_btn notification_btn_yellow
                                shadow
                                animate__animated animate__fadeIn
                                ml-auto
                                mr-1
                            "
                            @click="addBtn()"
                            >新增</b-button
                        >
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
                                    <th>序號</th>
                                    <th>標題</th>
                                    <th>檔案</th>
                                    <th>建立者</th>
                                    <th>時間</th>
                                    <th>預覽</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in announceData" :key="item.id">
                                    <td>{{ item.id }}</td>
                                    <td>{{ item.title }}</td>
                                    <td>{{ item.filename }}</td>
                                    <td>{{ item.name }}</td>
                                    <td>{{ item.onboard_date }}</td>
                                    <td>
                                        <a @click="view_info(item)">
                                            <i class="fas fa-search"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <b-button class="table-btn pl-0 pr-0">
                                            <img
                                                class="rounded-circle"
                                                width="40px"
                                                src="images/delete_icon.svg"
                                                @click="deleteAnnounce(item)"
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
        <b-modal :active.sync="isAddAnn" :width="640" scroll="clip">
            <AddAnn :admin="admin_id" @refresh="getAnnounce"></AddAnn>
            <!-- @emprefresh="emprefresh" -->
        </b-modal>
        <b-modal :active.sync="isAnnInfo" :width="640" scroll="clip">
            <AnnInfo :AnnounceInfo="Info_Data"></AnnInfo>
            <!-- @emprefresh="emprefresh" -->
        </b-modal>
        <b-loading
            :active.sync="isLoading"
            :is-full-page="true"
            v-model="isLoading"
            :can-cancel="false"
        ></b-loading>
    </div>
</template>

<script>
// import Profile from "../../Profile";
import AddAnn from "./modules/AnnounceUpdate";
import AnnInfo from "./modules/AnnounceInfo";
export default {
    components: {
        AddAnn,
        AnnInfo,
    },
    data: function () {
        return {
            announceData: [],
            isLoading: false,
            school: null,
            isAddAnn: false,
            isAnnInfo: false,
            Info_Data: null,
            admin_id: null,
        };
    },
    watch: {},
    created() {
        !sessionStorage.token ? (window.location.pathname = "/") : "";
        this.admin_id = sessionStorage.id;
    },
    mounted() {
        this.school = sessionStorage.school;
        this.getAnnounce();
    },
    methods: {
        view_info(item) {
            this.isAnnInfo = true;
            this.Info_Data = item;
        },
        async getAnnounce() {
            try {
                const response = await axios.get("announcement/index", {
                    params: {
                        school_id: this.school,
                    },
                });
                this.announceData = response.data;

                // console.log("更新一次:" + JSON.stringify(this.announceData));
            } catch (e) {
            } finally {
            }
        },
        deleteAnnounce(item) {
            const school_id = item.school_id;
            const id = item.id;
            this.$buefy.snackbar.open({
                message: "要刪除這個通告嗎？",
                type: "is-info",
                position: "is-top",
                actionText: "好",
                queue: false,
                onAction: () => {
                    this.isLoading = true;
                    axios
                        .post("announcement/delete", {
                            school_id: school_id,
                            id: id,
                            admin_id: this.admin_id,
                        })
                        .then((response) => {
                            if (response.data.result == true) {
                                this.$buefy.toast.open({
                                    message: "已刪除",
                                    queue: false,
                                });
                                this.getAnnounce();
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
                        })
                        .finally(() => {
                            this.isLoading = false;
                        });
                },
            });
        },
        addBtn() {
            this.isAddAnn = true;
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
.table .card-body {
    padding: 0;
}
</style>
