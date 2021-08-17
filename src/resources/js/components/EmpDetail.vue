<template>
    <div class="container" style="background-color: #fff">
        <div class="notification employee-name" style="background-color: #fff">
            <img
                class="photoImg"
                :src="userAvatar ? userAvatar : avatar"
                width="40px;"
                height="40px;"
            />
            <p class="has-text-weight-semibold" style="color: #6c7887">
                {{ user.employee_id }}
            </p>
            <p class="has-text-weight-semibold" style="color: #6c7887">
                {{ user.employee_position }}
            </p>
            <p class="has-text-weight-semibold" style="color: #0084ff">
                {{ user.employee_name }}
            </p>
        </div>
        <div class="employee-name">
            <img
                src="images/calendar.png"
                width="24px;"
                style="margin-left: 35px"
            />
            <p
                class="has-text-weight-semibold"
                style="color: #6c7887; margin-left: 12px"
            >
                {{ todayDate }}
            </p>
        </div>

        <b-table
            :data="recordData"
            :loading="isLoading"
            mobile-cards
            class="department-table"
        >
            <template slot-scope="props">
                <b-table-column
                    :style="
                        props.row.statu == 7
                            ? 'background-color:#FFB5B5; padding:25px;'
                            : 'padding:25px;'
                    "
                    field=""
                    label="Areas"
                >
                    <span v-show="props.row.statu == 7">外出</span>
                    <span v-show="props.row.statu != 7" style="">{{
                        props.row.area
                    }}</span>
                </b-table-column>
                <b-table-column
                    :style="
                        props.row.statu == 7 ? 'background-color:#FFB5B5;' : ''
                    "
                    field=""
                    label="Stay period"
                >
                    <span>{{ props.row.stay_period }}</span>
                </b-table-column>
                <b-table-column
                    :style="
                        props.row.statu == 7 ? 'background-color:#FFB5B5;' : ''
                    "
                    field=""
                    label="Stay time"
                    centered
                >
                    <span style="color: #0084ff">{{ props.row.time }}</span
                    ><span> hrs</span>
                </b-table-column>
            </template>

            <template slot="empty">
                <section class="section">
                    <div class="content has-text-grey has-text-centered">
                        <p>
                            <b-icon icon="emoticon-sad" size="is-large">
                            </b-icon>
                        </p>
                        <p>Nothing here.</p>
                    </div>
                </section>
            </template>
        </b-table>
    </div>
</template>

<script>
import moment from "moment";
export default {
    props: ["empInfo"],
    data: function () {
        return {
            user: this.empInfo,
            recordData: [],
            isLoading: false,
            todayDate: moment().format("YYYY-MM-DD"),
            avatar: "images/img_profile_default.png",
            userAvatar: this.empInfo.employee_avatar,
        };
    },
    watch: {},
    mounted() {
        !sessionStorage.token ? (window.location.pathname = "/") : "";
        this.getRecords();
    },
    methods: {
        getRecords() {
            /**暫時不顯示 */
            axios
                .get("records?user_id=" + this.user.employee_id)
                .then((response) => {
                    this.isLoading = true;
                    this.recordData = response.data
                        .map((record) => ({
                            ...record,
                            time:
                                Math.ceil(Number(record.stay_time).toFixed(1)) >
                                Number(record.stay_time).toFixed(1)
                                    ? Number(record.stay_time).toFixed(1)
                                    : Math.ceil(
                                          Number(record.stay_time).toFixed(1)
                                      ),
                        }))
                        .sort((a, b) => b.id - a.id);

                    console.log(this.recordData);
                })
                .catch((error) => {})
                .finally(() => {
                    this.isLoading = false;
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

.b-table.department-table .table th {
    padding-left: 24px;
}

.photoImg {
    border-radius: 50%;
}

.employee-name {
    display: flex;
    align-items: center;

    img .photoImg {
        border-radius: 50%;
    }

    p {
        margin-left: 1.2rem;
    }
}

.notification {
    margin-bottom: 0;
}
</style>
