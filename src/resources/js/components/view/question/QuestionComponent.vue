<template>
    <div class="container mt-5">
        <!-- {{ JSON.stringify(displayData) }}
        {{ JSON.stringify(questionDataShow) }} -->
        <div class="card table shadow">
            <div class="d-flex flex-row justify-content-end mt-3">
                <div class="dropdown no-arrow">
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
                        <a class="dropdown-item" @click="addBtn">新增提問</a>
                    </div>
                </div>
            </div>
            <div class="card-header d-flex flex-row justify-content-between">
                <b-input
                    v-model="search_val"
                    placeholder="Search"
                    type="search"
                    icon="magnify"
                    icon-clickable
                    expanded
                ></b-input>
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
                                <th>提問</th>
                                <th>解答</th>

                                <th>編輯</th>
                                <th>刪除</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="item in pageOfItems"
                                :key="item.question_id"
                            >
                                <td>{{ item.question }}</td>

                                <td>{{ item.answer }}</td>

                                <td>
                                    <b-button
                                        class="table-btn pl-0 pr-0"
                                        @click="editQuestion(item)"
                                    >
                                        <img
                                            class="rounded-circle"
                                            width="40px"
                                            src="images/edit_icon.svg"
                                        />
                                    </b-button>
                                </td>
                                <td>
                                    <b-button
                                        class="table-btn pl-0 pr-0"
                                        @click="deleteQuestion(item)"
                                    >
                                        <img
                                            class="rounded-circle"
                                            width="40px"
                                            src="images/delete_icon.svg"
                                        />
                                    </b-button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-center">
                <jw-pagination
                    :items="questionDataShow"
                    :pageSize="12"
                    :labels="customLabels"
                    @changePage="onChangePage"
                ></jw-pagination>
            </div>
        </div>
        <b-modal :active.sync="isAddQuestion" :width="320" scroll="clip">
            <AddQuestion
                :admin="admin_id"
                @refresh="getallQuestions"
            ></AddQuestion>
        </b-modal>
        <b-modal :active.sync="isEditQuestion" :width="320" scroll="clip">
            <EditQuestion
                :question-info="editdata"
                :admin="admin_id"
                @refresh="getallQuestions"
            ></EditQuestion>
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
import AddQuestion from "./modules/AddQuestion";
import EditQuestion from "./modules/EditQuestion";
export default {
    components: {
        AddQuestion,
        EditQuestion,
    },
    data: function () {
        return {
            isLoading: false,
            isAddQuestion: false,
            isEditQuestion: false,
            admin_id: null,
            search_val: "",
            questionData: [],
            questionDataShow: [],
            editdata: [],
            //分頁
            pageOfItems: [], //分割後Data
            customLabels: {
                //樣式
                first: "<<",
                last: ">>",
                previous: "<",
                next: ">",
            },
        };
    },
    filters: {},
    computed: {},
    watch: {
        //底層資料更新
        questionData() {
            this.questionDataShow = this.questionData;
            this.search(this.search_val);
        },
        //搜尋關鍵字更新
        search_val() {
            this.search(this.search_val);
        },
    },
    created() {
        !sessionStorage.token ? (window.location.pathname = "/") : "";
        if (sessionStorage.permission === "special_admin") {
            this.getallQuestions();
            this.admin_id = sessionStorage.id;
        } else {
            window.location.pathname = "/mainhome";
        }
    },
    mounted() {},
    methods: {
        //資料切割分頁
        onChangePage(pageOfItems) {
            // update page of items
            this.pageOfItems = pageOfItems;
        },

        search(n) {
            this.questionDataShow = this.questionData.filter(({ question }) =>
                question.includes(n)
            );
        },
        editQuestion(item) {
            this.isEditQuestion = true;
            this.editdata = item;
        },
        deleteQuestion(index) {
            const question_id = index.question_id;
            this.$buefy.snackbar.open({
                message: "要刪除這個提問嗎？",
                type: "is-info",
                position: "is-top",
                actionText: "好",
                queue: false,
                onAction: () => {
                    this.isLoading = true;
                    axios
                        .post("question/delete", {
                            question_id: question_id,
                            admin_id: this.admin_id,
                        })
                        .then((response) => {
                            if (response.data.result == true) {
                                var index = this.questionData.findIndex(
                                    (d) => d.id === question_id
                                );
                                this.questionData.splice(index, 1);
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
                        })
                        .finally(() => {
                            this.isLoading = false;
                        });
                },
            });
        },

        addBtn() {
            this.isAddQuestion = true;
        },
        async getallQuestions() {
            this.isLoading = true;
            axios
                .get("question/index", {
                    params: {},
                })
                .then((response) => {
                    if (response.data.result == true) {
                        this.questionData = response.data.data;
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
    },
};
</script>

<style lang="scss" scoped>
.notification {
    background-color: #eef1f5;
    margin-top: 30px;
}
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
    margin: auto 10px;
    border-radius: 1rem;

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