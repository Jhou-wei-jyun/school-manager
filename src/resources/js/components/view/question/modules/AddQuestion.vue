<template>
    <div class="card card-body">
        <header class="card-bottom d-flex align-items-center">
            <p class="h4 has-text-weight-semibold">新增提問</p>
        </header>
        <section>
            <b-field label="提問">
                <b-input type="text" v-model="question" required></b-input>
            </b-field>
            <b-field label="解答">
                <b-input type="textarea" v-model="answer" required></b-input>
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
                @click="addNewQuestion()"
                >新增</b-button
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
    props: ["admin"],
    data: function () {
        return {
            question: null,
            answer: null,
            isLoading: false,
        };
    },
    watch: {},
    mounted() {},
    methods: {
        refresh() {
            this.$emit("refresh");
        },

        addNewQuestion() {
            if (this.question === null || this.answer === null) {
                return;
            }
            this.isLoading = true;
            let formData = new FormData();
            if (this.admin) {
                formData.append("admin_id", this.admin);
            }
            if (this.question) {
                formData.append("question", this.question);
            }
            if (this.answer) {
                formData.append("answer", this.answer);
            }

            axios
                .post("question/store", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                })
                .then((response) => {
                    if (response.data.result == true) {
                        this.$buefy.toast.open({
                            message: "新增成功",
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
    border-radius: 50%;
}
</style>
