<template>
    <section class="hero is-info is-fullheight-with-navbar welcome-frame">
        <div
            class="no-blinking"
            style="position: absolute; left: 50%; top: 50%"
        >
            <div
                class="splash-logo"
                style="position: relative; left: -50%"
            ></div>
        </div>

        <div class="no-blinking popup-logo" style="position: fixed">
            <div style="border: dotted red 1px"></div>
        </div>

        <div
            class="delay-show slogan-box"
            style="position: absolute; left: 200px; top: 18%"
        >
            <div style="">
                一套整合學員健康管理、校園事務等多元服務的系統，同時具備即時性及互動性，一同為您守護校園大小事！
            </div>
        </div>

        <div class="hero-body">
            <div class="container delay-show">
                <div class="login-frame patch-frame">
                    <!-- <div class="frame-top-bar"></div>

                    <div class="title">
                        <p class="frame-logo"></p>
                    </div>
                    <div class="field">
                        <label for="account" class="label patch-label"
                            >帳號</label
                        >
                        <div class="control patch-input-box">
                            <input
                                id="account"
                                v-model="account"
                                class="input patch-input"
                                type="account"
                                placeholder
                            />
                        </div>
                    </div>

                    <div class="field">
                        <label for="password" class="label patch-label"
                            >密碼</label
                        >
                        <div class="control patch-input-box">
                            <input
                                id="password"
                                v-model="password"
                                class="input patch-input"
                                type="password"
                                placeholder
                            />
                        </div>
                    </div>

                    <div class="login-footer">
                        <div class="patch-panel">
                            <div class="d-flex justify-content-center mt-4">
                                <b-button
                                    id="login-btn"
                                    class="patch-button"
                                    @click="loginBtn"
                                    >登入</b-button
                                >
                            </div>
                        </div>
                    </div> -->
                    <div class="frame-top-bar"></div>

                    <div class="title">
                        <p class="frame-logo"></p>
                    </div>
                    <div class="tab-content" id="pills-tabContent">
                        <div
                            class="tab-pane fade"
                            :class="{
                                active: registerActive,
                                show: registerActive,
                            }"
                            id="pills-register"
                            role="tabpanel"
                        >
                            <div class="patch-input-box">
                                <b-field
                                    custom-class="is-small"
                                    :message="phoneValidity.message"
                                    :type="phoneValidity.type"
                                    label="電話"
                                >
                                    <b-input
                                        v-model="checkedPhone.phone"
                                        type="text"
                                        autocomplete="nope"
                                        placeholder="請輸入手機號碼"
                                        :name="'phone_register_' + nameIndex"
                                    />

                                    <b-button
                                        type="is-primary"
                                        v-if="phoneValidity.btn"
                                        @click="
                                            phoneMessageValidity(
                                                checkedPhone.phone,
                                                false
                                            )
                                        "
                                        label="驗證"
                                    >
                                    </b-button>
                                </b-field>
                            </div>
                            <div class="patch-input-box">
                                <b-field
                                    custom-class="is-small"
                                    :message="accountValidity.message"
                                    :type="accountValidity.type"
                                    label="帳號"
                                >
                                    <b-input
                                        v-model="checkedAccount.account"
                                        type="text"
                                        autocomplete="nope"
                                        :name="'account_register_' + nameIndex"
                                    />

                                    <b-button
                                        type="is-primary"
                                        v-if="accountValidity.btn"
                                        @click="
                                            accountMessageValidity(
                                                checkedAccount.account
                                            )
                                        "
                                        label="驗證"
                                    >
                                    </b-button>
                                </b-field>
                            </div>
                            <div class="patch-input-box">
                                <b-field
                                    custom-class="is-small"
                                    :message="passwordValidity.message"
                                    :type="passwordValidity.type"
                                    label="密碼"
                                >
                                    <div>
                                        <b-input
                                            v-model="checkedPassword.password"
                                            type="password"
                                            autocomplete="nope"
                                            :name="
                                                'password_register_' + nameIndex
                                            "
                                        />
                                    </div>
                                </b-field>
                            </div>

                            <div class="login-footer">
                                <div class="patch-panel">
                                    <div
                                        class="
                                            d-flex
                                            justify-content-center
                                            mt-4
                                        "
                                    >
                                        <b-button
                                            id="login-btn"
                                            class="patch-button"
                                            :disabled="!checkRegisterParams()"
                                            @click="registerBtn"
                                            >註冊</b-button
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div
                            class="tab-pane fade"
                            :class="{ active: loginActive, show: loginActive }"
                            id="pills-login"
                            role="tabpanel"
                        >
                            <div class="patch-input-box">
                                <b-field custom-class="is-small" label="帳號">
                                    <div>
                                        <b-input
                                            v-model="loginParams.account"
                                            type="account"
                                            autocomplete="nope"
                                            :name="'account_login_' + nameIndex"
                                        />
                                    </div>
                                </b-field>
                            </div>
                            <div class="patch-input-box">
                                <div class="field">
                                    <b-field
                                        custom-class="is-small"
                                        label="密碼"
                                    >
                                        <div>
                                            <b-input
                                                v-model="loginParams.password"
                                                type="password"
                                                autocomplete="nope"
                                                @keyup.enter.native="loginBtn"
                                                :name="
                                                    'password_login_' +
                                                    nameIndex
                                                "
                                            />
                                        </div>
                                    </b-field>
                                </div>
                            </div>
                            <div class="login-footer">
                                <div class="patch-panel">
                                    <div
                                        class="
                                            d-flex
                                            justify-content-center
                                            mt-4
                                        "
                                    >
                                        <b-button
                                            id="login-btn"
                                            class="patch-button"
                                            @click="loginBtn"
                                            >登入</b-button
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div
                            v-if="!isForgotPassword"
                            class="tab-pane fade"
                            :class="{
                                active: forgotActive,
                                show: forgotActive,
                            }"
                            id="pills-forgot"
                            role="tabpanel"
                        >
                            <div class="patch-input-box">
                                <b-field
                                    custom-class="is-small"
                                    :message="forgotPhoneValidity.message"
                                    :type="forgotPhoneValidity.type"
                                    label="電話"
                                >
                                    <b-input
                                        v-model="checkedForgotPhone.phone"
                                        type="text"
                                        placeholder="請輸入手機號碼"
                                        autocomplete="nope"
                                        :name="'phone_forgot_' + nameIndex"
                                    />

                                    <b-button
                                        type="is-primary"
                                        v-if="forgotPhoneValidity.btn"
                                        @click="
                                            phoneMessageValidity(
                                                checkedForgotPhone.phone,
                                                true
                                            )
                                        "
                                        label="驗證"
                                    >
                                    </b-button>
                                </b-field>
                            </div>

                            <div
                                v-show="checkedForgotPhone.account"
                                class="patch-input-box"
                            >
                                <b-field custom-class="is-small" label="帳號">
                                    <h5>{{ checkedForgotPhone.account }}</h5>
                                </b-field>
                            </div>
                            <div class="login-footer">
                                <div class="patch-panel">
                                    <div
                                        class="
                                            d-flex
                                            justify-content-center
                                            mt-4
                                        "
                                    >
                                        <b-button
                                            id="login-btn"
                                            class="patch-button"
                                            :disabled="!checkForgotParams()"
                                            @click="forgotBtn"
                                            >下一步</b-button
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div
                            v-else
                            class="tab-pane fade"
                            :class="{
                                active: forgotActive,
                                show: forgotActive,
                            }"
                            id="pills-forgot"
                            role="tabpanel"
                        >
                            <div class="patch-input-box">
                                <b-field
                                    custom-class="is-small"
                                    :message="
                                        forgotPhonePasswordValidity.message
                                    "
                                    :type="forgotPhonePasswordValidity.type"
                                    label="新密碼"
                                >
                                    <div>
                                        <b-input
                                            v-model="
                                                checkedForgotPhonePassword.newPassword
                                            "
                                            type="password"
                                            autocomplete="nope"
                                            :name="
                                                'password_forgot_' + nameIndex
                                            "
                                        />
                                    </div>
                                </b-field>
                            </div>
                            <div class="patch-input-box">
                                <b-field
                                    custom-class="is-small"
                                    :message="
                                        forgotPhonePasswordCheckValidity.message
                                    "
                                    :type="
                                        forgotPhonePasswordCheckValidity.type
                                    "
                                    label="新密碼確認"
                                >
                                    <div>
                                        <b-input
                                            v-model="
                                                checkedForgotPhonePassword.newPasswordCheck
                                            "
                                            type="password"
                                            autocomplete="nope"
                                            :name="
                                                'password_forgot_' + nameIndex
                                            "
                                        />
                                    </div>
                                </b-field>
                            </div>

                            <div class="login-footer">
                                <div class="patch-panel">
                                    <div
                                        class="
                                            d-flex
                                            justify-content-center
                                            mt-4
                                        "
                                    >
                                        <b-button
                                            id="login-btn"
                                            class="patch-button"
                                            @click="isForgotPassword = false"
                                            >上一步</b-button
                                        >
                                        <b-button
                                            id="login-btn"
                                            class="patch-button"
                                            :disabled="
                                                !checkForgotPasswordParams()
                                            "
                                            @click="resetBtn"
                                            >重設密碼</b-button
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="foot-frame">
                    <ul class="nav nav-pills" id="pills-tab" role="tablist">
                        <li
                            class="nav-item"
                            v-for="(item, index) in tabItem"
                            :key="index"
                        >
                            <a
                                class="nav-link"
                                :class="{ active: item.isActive }"
                                :id="item.id"
                                :aria-controls="item.control"
                                data-toggle="pill"
                                :href="item.link"
                                role="tab"
                                >{{ item.name }}</a
                            >
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <b-loading
            :active.sync="isLoading"
            :is-full-page="true"
            v-model="isLoading"
            :can-cancel="false"
        ></b-loading>
        <b-modal :active.sync="isValidity" :width="250" scroll="clip">
            <Validity
                :checkingPhone="checkedPhone.phone"
                @checkedCode="checkedPhoneChange"
            ></Validity>
        </b-modal>
        <b-modal :active.sync="isForgotValidity" :width="250" scroll="clip">
            <Validity
                :checkingPhone="checkedForgotPhone.phone"
                :forgotPassword="true"
                @checkedForgotPasswordCode="checkedForgotPhoneChange"
            ></Validity>
        </b-modal>
    </section>
</template>

<script>
import Validity from "./components/Validity";
export default {
    components: {
        Validity,
    },
    data: function () {
        let ption = null;
        ption = [
            {
                link: "#pills-register",
                id: "pills-register-tab",
                control: "pills-register",
                name: "註冊帳號",

                isActive: false,
            },
            {
                link: "#pills-login",
                id: "pills-login-tab",
                control: "pills-login",
                name: "登入",
                isActive: true,
            },
            {
                link: "#pills-forgot",
                id: "pills-forgot-tab",
                control: "pills-forgot",
                name: "忘記密碼",
                isActive: false,
            },
        ];
        let r = Math.random().toString(36).substring(7);
        return {
            nameIndex: r,
            isForgotPassword: false,
            isValidity: false,
            isForgotValidity: false,
            isLoading: false,
            tabItem: ption,
            loginParams: {
                account: null,
                password: null,
            },

            // phone: null,
            setCheckedPhone: false,
            checkedPhone: {
                phone: null,
                verified: false,
                token: null,
            },
            phoneValidity: {
                message: null,
                type: null,
                btn: false,
            },
            checkedAccount: {
                account: null,
                verified: false,
            },
            accountValidity: {
                message: null,
                type: null,
                btn: false,
            },
            checkedPassword: {
                password: null,
                verified: false,
            },
            passwordValidity: {
                message: null,
                type: null,
            },
            checkedForgotPhone: {
                phone: null,
                verified: false,
                token: null,
                account: null,
            },
            forgotPhoneValidity: {
                message: null,
                type: null,
                btn: false,
            },
            checkedForgotPhonePassword: {
                newPassword: null,
                newPasswordCheck: null,
                id: null,
                token: null,
                verified: false,
                checkVerified: false,
            },
            forgotPhonePasswordValidity: {
                message: null,
                type: null,
            },
            forgotPhonePasswordCheckValidity: {
                message: null,
                type: null,
            },
        };
    },
    mounted() {
        sessionStorage.clear();
    },
    watch: {
        "checkedPhone.phone": function (n, o) {
            this.checkedPhone.verified = false;
            if (n === null || n === "") {
                //尚未輸入值
                this.phoneValidity.message = null;
                this.phoneValidity.type = null;
                this.phoneValidity.btn = false;
            } else if (!this._phone_valid(n)) {
                //密碼長度或型態錯誤
                this.phoneValidity.message = "請填寫標準電話號碼10碼";
                this.phoneValidity.type = "is-danger";
                this.phoneValidity.btn = false;
            } else {
                //開啟驗證按鈕

                this.phoneValidity.message = null;
                this.phoneValidity.btn = true;
            }
        },
        "checkedPhone.verified": function (n, o) {
            if (this.checkedPhone.phone === null && !n) {
                //註冊失敗時
                this.phoneValidity.message = null;
                this.phoneValidity.type = null;
                this.phoneValidity.btn = false;
            } else if (!n) {
                //驗證失敗時
                this.phoneValidity.message = null;
                this.phoneValidity.type = null;
                this.phoneValidity.btn = true;
            } else {
                //驗證成功時
                this.phoneValidity.message = null;
                this.phoneValidity.type = "is-success";
                this.phoneValidity.btn = false;
            }
        },
        "checkedAccount.account": function (n, o) {
            this.checkedAccount.verified = false;
            if (n === null || n === "") {
                //尚未輸入值

                this.accountValidity.message = null;
                this.accountValidity.type = null;
                this.accountValidity.btn = false;
            } else if (!this._account_valid(n)) {
                //密碼長度或型態錯誤

                this.accountValidity.message =
                    "請填寫至少8位至多24位英文或數字";
                this.accountValidity.type = "is-danger";
                this.accountValidity.btn = false;
            } else {
                //開啟驗證按鈕

                this.accountValidity.message = null;
                this.accountValidity.btn = true;
            }
        },
        "checkedAccount.verified": function (n, o) {
            if (this.checkedAccount.account === null && !n) {
                //註冊失敗時
                this.accountValidity.message = null;
                this.accountValidity.type = null;
                this.accountValidity.btn = false;
            } else if (!n) {
                //驗證失敗時
                this.accountValidity.message = null;
                this.accountValidity.type = null;
                this.accountValidity.btn = true;
            } else {
                //驗證成功時
                this.accountValidity.message = null;
                this.accountValidity.type = "is-success";
                this.accountValidity.btn = false;
            }
        },
        "checkedPassword.password": function (n, o) {
            this.checkedPassword.verified = false;
            if (n === null || n === "") {
                //尚未輸入值
                this.passwordValidity.message = null;
                this.passwordValidity.type = null;
            } else if (!this._account_valid(n)) {
                //密碼長度或型態錯誤
                this.passwordValidity.message =
                    "請填寫至少8位至多24位英文或數字";
                this.passwordValidity.type = "is-danger";
            } else {
                //驗證成功時
                this.passwordValidity.message = null;
                this.passwordValidity.type = "is-success";
                this.checkedPassword.verified = true;
            }
        },
        "checkedForgotPhone.phone": function (n, o) {
            this.checkedForgotPhone.verified = false;
            if (n === null || n === "") {
                //尚未輸入值
                this.forgotPhoneValidity.message = null;
                this.forgotPhoneValidity.type = null;
                this.forgotPhoneValidity.btn = false;
            } else if (!this._phone_valid(n)) {
                //密碼長度或型態錯誤
                this.forgotPhoneValidity.message = "請填寫標準電話號碼10碼";
                this.forgotPhoneValidity.type = "is-danger";
                this.forgotPhoneValidity.btn = false;
            } else {
                //開啟驗證按鈕
                this.forgotPhoneValidity.message = null;
                this.forgotPhoneValidity.btn = true;
            }
        },
        "checkedForgotPhone.verified": function (n, o) {
            if (this.checkedForgotPhone.phone === null && !n) {
                //註冊失敗時
                this.forgotPhoneValidity.message = null;
                this.forgotPhoneValidity.type = null;
                this.forgotPhoneValidity.btn = false;
            } else if (!n) {
                //驗證失敗時
                this.forgotPhoneValidity.message = null;
                this.forgotPhoneValidity.type = null;
                this.forgotPhoneValidity.btn = true;
            } else {
                //驗證成功時
                this.forgotPhoneValidity.message = null;
                this.forgotPhoneValidity.type = "is-success";
                this.forgotPhoneValidity.btn = false;
            }
        },

        "checkedForgotPhonePassword.newPassword": function (n, o) {
            this.checkedForgotPhonePassword.verified = false;
            if (n === null || n === "") {
                //尚未輸入值
                this.forgotPhonePasswordValidity.message = null;
                this.forgotPhonePasswordValidity.type = null;
            } else if (!this._account_valid(n)) {
                //密碼長度或型態錯誤
                this.forgotPhonePasswordValidity.message =
                    "請填寫至少8位至多24位英文或數字";
                this.forgotPhonePasswordValidity.type = "is-danger";
            } else {
                //驗證成功時
                this.forgotPhonePasswordValidity.message = null;
                this.forgotPhonePasswordValidity.type = "is-success";
                this.checkedForgotPhonePassword.verified = true;
            }
            if (n !== this.checkedForgotPhonePassword.newPasswordCheck) {
                //新密碼與新密碼確認不符
                this.forgotPhonePasswordCheckValidity.message =
                    "請填寫與新密碼相同字元";
                this.forgotPhonePasswordCheckValidity.type = "is-danger";
                this.checkedForgotPhonePassword.checkVerified = false;
            } else {
                this.forgotPhonePasswordCheckValidity.message = null;
                this.forgotPhonePasswordCheckValidity.type = "is-success";
                this.checkedForgotPhonePassword.checkVerified = true;
            }
        },
        "checkedForgotPhonePassword.newPasswordCheck": function (n, o) {
            this.checkedForgotPhonePassword.checkVerified = false;
            if (n === null || n === "") {
                //尚未輸入值
                this.forgotPhonePasswordCheckValidity.message = null;
                this.forgotPhonePasswordCheckValidity.type = null;
            } else if (n !== this.checkedForgotPhonePassword.newPassword) {
                //新密碼確認與新密碼不符
                this.forgotPhonePasswordCheckValidity.message =
                    "請填寫與新密碼相同字元";
                this.forgotPhonePasswordCheckValidity.type = "is-danger";
            } else {
                //驗證成功時
                this.forgotPhonePasswordCheckValidity.message = null;
                this.forgotPhonePasswordCheckValidity.type = "is-success";
                this.checkedForgotPhonePassword.checkVerified = true;
            }
        },
    },
    computed: {
        registerActive: function () {
            return this.tabItem[0]["isActive"];
        },
        loginActive: function () {
            return this.tabItem[1]["isActive"];
        },
        forgotActive: function () {
            return this.tabItem[2]["isActive"];
        },
    },
    methods: {
        _phone_valid(value) {
            const Regex = /^09\d{8}$/;
            return Regex.test(value);
        },
        _account_valid(value) {
            const Regex = /^[A-Za-z0-9]{8,24}$/;
            return Regex.test(value);
        },
        checkRegisterParams() {
            if (
                this.checkedAccount.verified === true &&
                this.checkedPassword.verified === true &&
                this.checkedPhone.verified === true
            ) {
                return true;
            } else {
                return false;
            }
        },
        checkForgotParams() {
            if (
                (this.checkedForgotPhone.verified === true,
                this.checkedForgotPhone.account !== null)
            ) {
                return true;
            } else {
                return false;
            }
        },
        checkForgotPasswordParams() {
            if (
                (this.checkedForgotPhonePassword.verified === true,
                this.checkedForgotPhonePassword.checkVerified === true)
            ) {
                return true;
            } else {
                return false;
            }
        },
        checkedPhoneChange(data) {
            // this.checkedPhone = data;
            this.checkedPhone = Object.assign(this.checkedPhone, data);
        },
        checkedForgotPhoneChange(data) {
            this.checkedForgotPhone = Object.assign(
                this.checkedForgotPhone,
                data
            );
            this.getAccount(data.phone);
        },
        async getAccount(phone) {
            await axios
                .get("getAccount", {
                    params: {
                        phone: phone,
                    },
                })
                .then((response) => {
                    if (response.data.result == true) {
                        this.checkedForgotPhone = Object.assign(
                            this.checkedForgotPhone,
                            { account: response.data.data }
                        );
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
                .finally(() => {});
        },

        phoneMessageValidity(phone, forgotPassword) {
            this.isLoading = true;
            axios
                .post("phoneValidity", {
                    phone: phone,
                    forgot_password: forgotPassword,
                })
                .then((response) => {
                    if (response.data.result == true) {
                        if (!forgotPassword) {
                            this.isValidity = true;
                        } else {
                            this.isForgotValidity = true;
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
                });
        },
        accountMessageValidity(account) {
            this.isLoading = true;
            axios
                .post("accountValidity", {
                    account: account,
                })
                .then((response) => {
                    if (response.data.result == true) {
                        this.checkedAccount.verified = true;
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
                });
        },
        registerBtn() {
            this.isLoading = true;
            axios
                .post("register", {
                    phone: this.checkedPhone,
                    account: this.checkedAccount.account,
                    password: this.checkedPassword.password,
                })
                .then((response) => {
                    if (response.data.result == true) {
                        this.$buefy.toast.open({
                            message: response.data.data,
                            type: "is-success",
                            queue: false,
                        });
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
                    this.checkedPhone = Object.assign(this.checkedPhone, {
                        phone: null,
                        verified: false,
                        token: null,
                    });
                    this.checkedAccount = Object.assign(this.checkedAccount, {
                        account: null,
                        verified: false,
                    });
                    this.checkedPassword = Object.assign(this.checkedPassword, {
                        password: null,
                        verified: false,
                    });
                    this.isLoading = false;
                });
        },
        loginBtn() {
            this.isLoading = true;
            const data = {
                account: this.loginParams.account,
                password: this.loginParams.password,
            };
            axios
                .post("login", data)
                .then((response) => {
                    if (response.data.result) {
                        sessionStorage.id = response.data.data.id;
                        sessionStorage.name = response.data.data.name;
                        sessionStorage.account = response.data.data.account;
                        sessionStorage.token = response.data.data.api_token;
                        sessionStorage.group = response.data.data.group_id;
                        sessionStorage.teacher_id =
                            response.data.data.teacher_id;
                        sessionStorage.school = response.data.data.school;
                        sessionStorage.school_info = JSON.stringify(
                            response.data.data.school_info
                        );
                        sessionStorage.teacher_type =
                            response.data.data.teacher_type;
                        sessionStorage.student_type =
                            response.data.data.student_type;
                        if (response.data.data.avatar != null) {
                            sessionStorage.avatar = response.data.data.avatar;
                        }
                        sessionStorage.permission =
                            response.data.data.permission;
                        window.location.pathname = "/mainhome";
                    }
                })
                .catch((error) => {
                    console.log(error.response);
                    this.$buefy.toast.open({
                        duration: 2000,
                        message: error.response.data.error,
                        position: "is-top",
                        type: "is-danger",
                    });
                })
                .finally(() => {
                    this.account = null;
                    this.password = null;
                    this.isLoading = false;
                });
        },
        forgotBtn() {
            this.isLoading = true;
            axios
                .post("forgotPassword", {
                    phone: this.checkedForgotPhone,
                    account: this.checkedForgotPhone.account,
                })
                .then((response) => {
                    if (response.data.result == true) {
                        this.checkedForgotPhonePassword = Object.assign(
                            this.checkedForgotPhonePassword,
                            response.data.data
                        );
                        this.isForgotPassword = true;
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
                    this.checkedForgotPhone = {
                        phone: null,
                        verified: false,
                        token: null,
                        account: null,
                    };
                    this.isLoading = false;
                });
        },
        resetBtn() {
            this.isLoading = true;
            axios
                .post("resetPassword", {
                    data: this.checkedForgotPhonePassword,
                })
                .then((response) => {
                    if (response.data.result == true) {
                        this.$buefy.toast.open({
                            message: response.data.data,
                            type: "is-success",
                            queue: false,
                        });
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
                    this.checkedForgotPhonePassword = {
                        newPassword: null,
                        newPasswordCheck: null,
                        id: null,
                        token: null,
                        verified: false,
                        checkVerified: false,
                    };

                    this.isForgotPassword = false;
                    this.isLoading = false;
                });
        },
    },
};
</script>

<style lang="scss" scoped>
body {
    overflow-y: hidden; /* Hide vertical scrollbar */
    overflow-x: hidden;
}
.welcome-frame {
    margin: -1rem 0;
    min-height: calc(100vh - 4rem - 60px);
}
.login-frame {
    // position: absolute;
    margin-top: -100px;
    left: 60%;
    background-color: white;
    width: 350px;
    min-height: 450px;
    border-radius: 2px;
    border: solid 1px #e6ebf1;

    .title {
        margin-top: 16px;
        font-size: 24px;
        font-weight: 700;
        color: #181f38;
        text-align: center;
    }

    .title-line {
        margin-top: 16px;
        width: 40px;
        height: 4px;
        background-color: #0084ff;
    }

    .field {
        margin-top: 16px;

        .label {
            margin-bottom: 0.25em;
            font-size: 0.75rem;
        }
    }

    .login-footer {
        #login-btn {
            width: 108px;
            font-weight: 600;
        }

        #forgot-btn {
            font-weight: normal;
        }
        #forgot-text {
            color: rgba(119, 119, 119, 0.338);
            font-size: 11px;
        }
    }
}

@keyframes fade {
    0% {
        opacity: 1;
        background-image: url("/images/rotate_iocn_01.svg");
        color: blue;
    }
    25% {
        opacity: 1;
        background-image: url("/images/rotate_iocn_02.svg");
        color: green;
    }
    50% {
        opacity: 1;
        background-image: url("/images/rotate_iocn_03.svg");
        color: green;
    }
    75% {
        opacity: 1;
        background-image: url("/images/rotate_iocn_04.svg");
        color: yellow;
    }
    100% {
        opacity: 0;
        background-image: url("/images/rotate_iocn_05.svg");
        color: black;
    }
}

@keyframes wait-appear {
    0% {
        opacity: 0;
    }
    80% {
        opacity: 0;
    }
    100% {
        opacity: 1;
    }
}

@keyframes fade2 {
    0% {
        opacity: 0.4;
        background-image: url("/images/ilolly_logo-01.png");
        width: 400px;
        height: 400px;
        background-size: 400px 400px;
    }
    25% {
        opacity: 0.4;
        background-image: url("/images/ilolly_logo-02.png");
        width: 500px;
        height: 500;
        background-size: 500 500;
    }
    75% {
        opacity: 0.4;
        background-image: url("/images/ilolly_logo-03.png");
        width: 600px;
        height: 600px;
        background-size: 600px 600px;
    }
    100% {
        opacity: 0.4;
        background-image: url("/images/ilolly_logo-04.png");
        width: 2000px;
        height: 2000px;
        background-size: 2000px 2000px;
    }
}

.no-blinking {
    filter: none;
    -webkit-transform-style: preserve-3d;
    -webkit-backface-visibility: hidden;
    -moz-backface-visibility: hidden;
    -ms-backface-visibility: hidden;
    backface-visibility: hidden;
    -webkit-perspective: 1000;
    -moz-perspective: 1000;
    -ms-perspective: 1000;
    perspective: 1000px;
}

.splash-logo {
    color: red;
    height: 500px;
    width: 500px;
    margin-top: -250px;
    background-image: url("/images/rotate_iocn_01.svg");
    background-repeat: no-repeat;
    background-size: 500px 500px;
    animation-name: fade;
    animation-timing-function: linear;
    animation-iteration-count: inherit;
    animation-duration: 4s;
    animation-direction: normal;
    animation-fill-mode: forwards;
}

.popup-logo {
    opacity: 0.4;
    height: 2000px;
    width: 2000px;
    top: -200px;
    left: -60%;
    margin-top: -100px;
    background-image: url("/images/ilolly_logo-01.png");
    background-repeat: no-repeat;
    background-size: 2000px 2000px;
    animation-name: fade2;
    animation-timing-function: linear;
    animation-iteration-count: inherit;
    animation-duration: 4s;
    animation-direction: alternate;
    //animation-fill-mode: forwards;
}

.delay-show {
    opacity: 0;
    animation-name: wait-appear;
    animation-timing-function: linear;
    animation-iteration-count: inherit;
    animation-delay: 2s;
    animation-duration: 2s;
    animation-direction: alternate;
    animation-fill-mode: forwards;
}

.slogan-box {
    opacity: 0;
    color: #333;
    font-size: 17px;
    height: 300px;
    width: 400px;
    padding: 160px 0px 0px 85px;
    margin-top: -100px;
    //background-image: url("/images/loginpage_icon_green.svg");
    //background-repeat: no-repeat;
    //background-size: 300px 300px;
    //background-position: -50px -50px;
}

.frame-top-bar {
    height: 8px;
    background-color: #8ecfc7;
}

.frame-logo {
    height: 150px;
    margin-top: -50px;
    background-image: url("/images/loginpage_icon_green.svg");
    background-repeat: no-repeat;
    background-size: 300px 300px;
    background-position: 25px -50px;
}

.hero.is-info {
    background-color: white;
    .nav-item > a {
        color: #888 !important;
    }
}
.foot-frame {
    width: 350px;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    margin-left: auto;
    margin-right: 5vw;
    .nav-pills {
        .nav-link {
            font-size: 0.8rem;
        }

        .nav-link.active {
            border-style: none !important;
        }
    }
}
.patch-frame {
    background-color: white;
    border: solid 1px transparent;
    bottom: auto;
    margin-left: auto;
    margin-right: 5vw;
    margin-top: 20vh;
}

.patch-input-box {
    padding: 0px 30px;
}

.patch-input {
    background-color: #ffffff;
    border-radius: 10px;
    box-shadow: 0px 1px 10px #c8c8c8;
}
.patch-input:invalid {
    border-color: pink;
}
.error-input {
    border-color: rgb(255, 64, 96) !important;
}

.patch-label {
    color: #888;
    padding: 0px 35px;
}

.patch-footer {
    position: absolute;
    left: 50%;
}

.patch-panel {
    position: relative;
    color: #777;
    //background-color: white;
}

.patch-button {
    border-radius: 20px;
    box-shadow: 0px 1px 10px #c8c8c8;
    background-color: #8ecfc7;
    color: white;
}

.cardFooter {
    display: flex;
    margin-top: 70px;
}
.ValidityBtn {
    position: absolute;
    top: 0px;
    right: 30px;
}
</style>
