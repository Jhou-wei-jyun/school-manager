<template>
    <div class="card card-body">
        <!-- {{questionInfo}} -->
        <header class="card-bottom d-flex align-items-center">
            <p class="h4 has-text-weight-semibold">編輯問題</p>
        </header>
        <section>
            <b-field label="問題">
                <b-input
                    type="text"
                    v-model="questionData.question"
                    required
                ></b-input>
            </b-field>
            <b-field label="解答">
                <b-input
                    type="textarea"
                    v-model="questionData.answer"
                    required
                ></b-input>
            </b-field>
        </section>
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
                @click="updateQuestion()"
                >編輯</b-button
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
    props: ["questionInfo", "admin"],
    data: function () {
        return {
            questionData: this.questionInfo,
            isLoading: false,
        };
    },
    mounted() {},
    methods: {
        refresh() {
            this.$emit("refresh");
        },
        updateQuestion() {
            if (
                this.questionData.question === null ||
                this.questionData.answer === null
            ) {
                return;
            }

            this.isLoading = true;
            let formData = new FormData();
            if (this.admin) {
                formData.append("admin_id", this.admin);
            }
            if (this.questionData.question_id) {
                formData.append("question_id", this.questionData.question_id);
            }
            if (this.questionData.question) {
                formData.append("question", this.questionData.question);
            }
            if (this.questionData.answer) {
                formData.append("answer", this.questionData.answer);
            }

            axios
                .post("question/update", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                })
                .then((response) => {
                    if (response.data.result == true) {
                        this.$buefy.toast.open({
                            message: "更新成功",
                            type: "is-success",
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
                })
                .finally(() => {
                    this.isLoading = false;
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
