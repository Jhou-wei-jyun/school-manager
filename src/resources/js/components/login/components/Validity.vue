<template>
    <div class="card card-body d-flex flex-column">
        <div class="d-flex flex-row justify-content-around">
            <input
                v-for="(item, index) in 4"
                :key="index"
                ref="code"
                v-model="code[index]"
                required
                type="text"
                class="validity-input"
                maxlength="1"
                @keyup.enter="checkValidity"
            />

            <!-- <input
                ref="phone"
                v-model="number2"
                required
                type="text"
                class="validity-input"
                size="1"
                maxlength="1"
                @change="focusNextInput"
            />
            <input
                ref="phone"
                v-model="number3"
                required
                type="text"
                class="validity-input"
                size="1"
                maxlength="1"
                @change="focusNextInput"
            />
            <input
                ref="phone"
                v-model="number4"
                required
                type="text"
                class="validity-input"
                size="1"
                maxlength="1"
                @change="focusNextInput"
            /> -->
            <b-button @click="checkValidity()"> 驗證 </b-button>
        </div>
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
    props: ["checkingPhone", "forgotPassword"],
    props: {
        checkingPhone: {
            type: String,
            required: true,
        },
        forgotPassword: {
            type: Boolean,
            default: function () {
                return false;
            },
        },
    },
    data: function () {
        return {
            isLoading: false,
            code: {
                0: "",
                1: "",
                2: "",
                3: "",
            },
        };
    },
    filters: {},
    mounted() {},
    watch: {
        // @keypress="focusNextInput(index)"
        //         @keyup.delete="focusPreviousInput(index)"
        "code.0": function (n, o) {
            if (n.length === 1) {
                this.focusNextInput(0);
            }
            if (n.length === 0) {
                this.focusPreviousInput(0);
            }
        },
        "code.1": function (n, o) {
            if (n.length === 1) {
                this.focusNextInput(1);
            }
            if (n.length === 0) {
                this.focusPreviousInput(1);
            }
        },
        "code.2": function (n, o) {
            if (n.length === 1) {
                this.focusNextInput(2);
            }
            if (n.length === 0) {
                this.focusPreviousInput(2);
            }
        },
        "code.3": function (n, o) {
            if (n.length === 1) {
                this.focusNextInput(3);
            }
            if (n.length === 0) {
                this.focusPreviousInput(3);
            }
        },
    },
    computed: {
        codeStr: function () {
            return Object.values(this.code).join("");
        },
    },
    methods: {
        checkValidity() {
            if (this.codeStr.length !== 4) {
                this.$buefy.toast.open({
                    duration: 2000,
                    message: "填入驗證碼",
                    position: "is-top",
                    type: "is-danger",
                });
                return;
            }
            this.isLoading = true;
            axios
                .post("checkValidity", {
                    phone: this.checkingPhone,
                    code: this.codeStr,
                })
                .then((response) => {
                    if (response.data.result == true) {
                        // console.log(response.data.data);
                        if (!this.forgotPassword) {
                            this.$emit("checkedCode", response.data.data);
                        } else {
                            this.$emit(
                                "checkedForgotPasswordCode",
                                response.data.data
                            );
                        }
                    }
                })
                .catch((error) => {
                    this.$buefy.toast.open({
                        duration: 2000,
                        message: error.response.data.error,
                        position: "is-top",
                        type: "is-danger",
                    });
                })
                .finally(() => {
                    this.isLoading = false;
                    this.$parent.close();
                });
        },

        focusNextInput(index) {
            if (index + 1 === 4) {
                return;
            }
            this.$refs.code[index + 1].focus();
            // if (this.code[index].length === 1) {
            //     // this.$refs.code[index].blur();

            // }
        },
        focusPreviousInput(index) {
            if (index - 1 === -1) {
                return;
            }
            this.$refs.code[index - 1].focus();
            // if (this.code[index].length === 1) {

            // }
        },
    },
};
</script>

<style lang="scss" scoped>
.validity-input {
    width: 2vw;
    font-size: 1rem;
    background-color: #ffffff;
    text-align: center;
    border-width: 3px;
    border-top: none;
    border-left: none;
    border-right: none;
    box-shadow: none;
}
.validity-input:hover {
    border-color: black;
}
.validity-input:focus {
    background: rgb(233, 233, 233);
}
</style>
