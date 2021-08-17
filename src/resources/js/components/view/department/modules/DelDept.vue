<template>
    <div class="card card-body">
        <!-- <header class="card-bottom d-flex align-items-center">
            <p class="h4 has-text-weight-semibold">新增班級</p>
        </header>-->

        <div
            id="main "
            class="d-flex flex-row justify-content-center align-items-center"
        >
            <div class="h4 mt-3">刪除{{ departInfo.name }}嗎</div>
        </div>
        <footer class="card-bottom d-flex align-items-center">
            <b-button
                class="notification_btn notification_btn_gray notification_btn_text_white ml-auto mr-1"
                size="is-small"
                @click="$parent.close()"
                >取消</b-button
            >
            <b-button
                class="notification_btn notification_btn_sky notification_btn_text_white mr-auto ml-1"
                size="is-small"
                @click="deleteDepartment()"
                >確定</b-button
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
    props: ["departInfo", "admin"],
    data: function () {
        return {
            delData: this.departInfo,
            admin_id: this.admin,
            isLoading: false,
        };
    },

    methods: {
        Departmentrefresh() {
            this.$emit("onDepartmentrefresh");
        },
        deleteDepartment() {
            this.isLoading = true;
            axios
                .put("department/delete", {
                    department_id: this.delData.id,
                    admin_id: this.admin_id,
                })
                .then((response) => {
                    // console.log("Data:" + response.data);
                    if (response.data.result == true) {
                        this.$buefy.toast.open({
                            message: "更新成功",
                            type: "is-success",
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
                })
                .finally(() => {
                    this.isLoading = false;
                    this.$parent.close();
                    this.Departmentrefresh();
                });
        },
    },
};
</script>

<style lang="scss" scoped>
#main {
    width: 100%;
    // height: 50%;

    .title {
        font-family: NotoSansCJKtc;
        font-size: 33px;
        font-weight: 500;
        font-stretch: normal;
        font-style: normal;
        line-height: 1.48;
        letter-spacing: normal;

        color: #545454;
    }
    // background-color: turquoise;
    // .red {
    //     width: 30%;
    // }

    // .blue {
    //     width: 60%;
    // }

    // .green {
    //     width: 10%;
    // }
}

.card-bottom {
    width: 100%;
    // height: 30%;
    .card-bottom-button {
        float: right;
        right: 1rem;
        margin-top: 40px;
    }
    .notification_btn {
        border-radius: 0.75rem;
    }
}

.profile-camera {
    position: absoulate;
    margin-bottom: 90px;
    margin-left: 80px;
    padding-bottom: 15px;
}

// .card-bottom {
//     width: 100%;
//     height: 100px;

//     .card-bottom-button {
//         float: right;
//         right: 1rem;
//         margin-top: 40px;
//         text-decoration: none;
//     }
// }
.text_center {
    text-align-last: center !important;
}
.edit_img {
    border-radius: 50%;
}
</style>
