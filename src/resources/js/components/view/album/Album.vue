<template>
    <div class="container">
        <div class="mt-4"></div>
        <div v-for="(depart_item, index) in departmentIsFiltered" :key="index">
            <span>{{ index }}</span>

            <hr class="divider my-0" />

            <div class="row d-flex flex-row">
                <div
                    class="col-lg-3 col-md-4 col-sm-6"
                    style="padding: 15px 15px"
                    v-for="depart in depart_item"
                    :key="depart.id"
                >
                    <div class="card">
                        <div class="card-body ml-auto mr-auto">
                            <!-- <div class="topbar-divider d-none d-sm-block"></div> -->
                            <div>
                                <img
                                    :src="depart.avatar || def_card"
                                    width="150"
                                    style="height: 150px"
                                    class="image"
                                    @click="assignTag(depart.id)"
                                />
                            </div>
                        </div>

                        <div class="card-footer">
                            <span class="ml-auto mr-auto">{{
                                depart.name
                            }}</span>
                        </div>
                    </div>
                </div>
            </div>
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
    components: {},
    data: function () {
        return {
            //
            def_card: "/images/card_class_penguin.png",
            //
            departmentsData: [],
            isLoading: false,
            school: null,

            teacher_id: null,
        };
    },
    watch: {},
    created() {
        //sessionStorage　出來是string
        if (sessionStorage.teacher_id !== "null") {
            this.teacher_id = Number(sessionStorage.teacher_id);
        }
    },
    mounted() {
        !sessionStorage.token ? (window.location.pathname = "/") : "";
        this.school = sessionStorage.school;
        this.getDepartments();
    },
    computed: {
        departmentIsFiltered: function () {
            if (this.teacher_id === null) {
                return this.departmentsData;
            } else {
                let teacher = this.teacher_id;
                let filtered = {};
                for (let [key, value] of Object.entries(this.departmentsData)) {
                    let result = value.filter(
                        (v) => v.supervisor_id === teacher
                    );
                    filtered[key] = result;
                }
                return filtered;
            }
        },
    },
    methods: {
        assignTag(id) {
            window.open("albumDetail?" + id);
        },
        getDepartments() {
            axios
                .get("department/index", { params: { school_id: this.school } })
                .then((response) => {
                    this.departmentsData = response.data;
                })
                .catch({});
        },
    },
};
</script>

<style lang="scss" scoped>
//
.span {
    font-size: 20px;
    font-weight: bold;
}
.divider {
    filter: alpha(opacity=100, finishopacity=0, style=2);
    height: 6px;
}
.dropdown-menu {
    width: clamp(200px, 50vw, 502px);

    &.show {
        display: grid;
        // grid-auto-rows: 100px;
        // grid-auto-columns: 100px;
        // grid-template-columns: repeat(auto-fill, 100px);
        grid-template-columns: repeat(auto-fill, 100px) [last-col] 100px;
        grid-template-rows: auto [last-line];
    }
}
a.dropdown-item {
    // grid-area: auto;
    padding-right: 16px;
}
.item-plus {
    grid-column: last-col / span 1;
    grid-row: 1 / last-line;
}
.img-close {
    position: absolute;
    top: 0;
}
.img-edit {
    position: absolute;
    bottom: 50px;
    right: 0;
}
.card-delete {
    position: absolute;
    top: 0;
    right: 0;
}
.fix {
    padding: 8px 16px;
}
</style>
