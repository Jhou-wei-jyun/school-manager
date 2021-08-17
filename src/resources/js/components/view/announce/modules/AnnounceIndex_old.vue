<template>
    <div class="container mt-5">
        <!-- {{JSON.stringify(announceData)}} -->
        <div class="card table shadow">
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
</template>

<script>
export default {
    props: ["announceData"],
    data: function () {
        return {};
    },
    watch: {},
    mounted() {},
    methods: {
        refresh() {
            this.$emit("refresh");
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
                    axios
                        .post("announcement/delete", {
                            school_id: school_id,
                            id: id,
                        })
                        .then((response) => {
                            if (response.data.result == true) {
                                this.$buefy.toast.open({
                                    message: "已刪除",
                                    queue: false,
                                });
                                this.refresh();
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
