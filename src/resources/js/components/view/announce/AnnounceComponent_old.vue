<template>
    <div class="container pt-5">
        <ul class="nav nav-pills mt-5" id="pills-tab" role="tablist">
            <li class="nav-item">
                <a
                    class="nav-link active"
                    id="pills-update-tab"
                    data-toggle="pill"
                    href="#pills-update"
                    role="tab"
                    aria-controls="pills-update"
                    aria-selected="true"
                    >公告欄</a
                >
            </li>
            <li class="nav-item">
                <a
                    class="nav-link"
                    id="pills-index-tab"
                    data-toggle="pill"
                    href="#pills-index"
                    role="tab"
                    aria-controls="pills-index"
                    aria-selected="false"
                    >公告列表</a
                >
            </li>
        </ul>

        <div class="tab-content" id="pills-tabContent">
            <div
                class="tab-pane fade show active"
                id="pills-update"
                role="tabpanel"
                aria-labelledby="pills-update-tab"
            >
                <Update @refresh="getAnnounce"></Update>
            </div>
            <div
                class="tab-pane fade"
                id="pills-index"
                role="tabpanel"
                aria-labelledby="pills-index-tab"
            >
                <Index
                    :announceData="announceData"
                    @refresh="getAnnounce"
                ></Index>
            </div>
        </div>
    </div>
</template>

<script>
import Update from "./modules/AnnounceUpdate";
import Index from "./modules/AnnounceIndex";
export default {
    components: {
        Update,
        Index,
    },
    data: function () {
        return {
            announceData: [],
            school: null,
        };
    },
    watch: {},
    mounted() {
        this.school = sessionStorage.school;
        this.getAnnounce();
    },
    methods: {
        async getAnnounce() {
            try {
                const response = await axios.post("announcement/index", {
                    school_id: this.school,
                });
                this.announceData = response.data;

                // console.log("更新一次:" + JSON.stringify(this.announceData));
            } catch (e) {
            } finally {
            }
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
</style>
