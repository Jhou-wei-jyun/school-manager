<template>
    <div>
        <profile-component></profile-component>

        <div class="container row">
            <div class="card card-body noshadow col-12 mb-5">
                <div class="row align-items-center">
                    <div class="col-9 ml-4 mt-1">
                        <span class="h5 text-gray-800">室內環境溫溼度</span>
                    </div>
                </div>
            </div>

            <b-select
                v-model="selectDepart"
                expanded
                class="col-6 text_center my-4 white"
                placeholder="班級"
            >
                <option
                    v-for="option in departmentsData"
                    :key="option.id"
                    :value="option.id"
                >
                    {{ option.name }}
                </option>
            </b-select>

            <div class="card card-body col-12" v-show="selectDepart != null">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-2">
                        <span class="h6 text-gray-600">室內環境狀態</span>
                    </div>
                    <div class="col-12 col-md-2 d-flex flex-column">
                        <span class="text-gray-600 text_center">溫度</span>
                        <DepartTemperatureRing
                            :selectData="selectData"
                        ></DepartTemperatureRing>
                    </div>
                    <div class="col-12 col-md-2 d-flex flex-column">
                        <span class="text-gray-600 text_center">濕度</span>
                        <DepartHumidityRing
                            :selectData="selectData"
                        ></DepartHumidityRing>
                    </div>
                    <div class="col-12 col-md-2 d-flex flex-column">
                        <span class="text-gray-600 text_center">PM2.5</span>
                        <DepartPM25Ring
                            :selectData="selectData"
                        ></DepartPM25Ring>
                    </div>
                    <div class="col-12 col-md-3 align-self-end d-flex">
                        <span class="text-gray-500 ml-auto">最後更新時間</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
// import Profile from "./Profile";
import DepartTemperatureRing from "./v-chart/DepartTemperatureRing";
import DepartHumidityRing from "./v-chart//DepartHumidityRing";
import DepartPM25Ring from "./v-chart/DepartPM25Ring";
export default {
    components: {
        DepartTemperatureRing,
        DepartHumidityRing,
        DepartPM25Ring,
    },
    data: function () {
        return {
            a: {
                temperature: 36.3,
                humidity: 20,
                PM25: 10,
            },
            b: {
                temperature: 40.3,
                humidity: 10,
                PM25: 15,
            },
            c: {
                temperature: 33.3,
                humidity: 25,
                PM25: 1,
            },
            school: null,
            departmentsData: [],
            selectDepart: null,
            selectData: null,
            // areaData: [],
            // search_val: null,
            // isLoading: false,
            // isAddItem: false,
            // showDetails: false,
            // canEditArea: false,
            // modelType: null,
            // name: null,
            // editID: null,
            // max_capacity: null,
            // isAddArea: false,
        };
    },
    watch: {
        selectDepart(n, o) {
            if (n == 28) {
                this.selectData = this.a;
            }

            if (n == 32) {
                this.selectData = this.b;
            }
            if (n == 34) {
                this.selectData = this.c;
            }
        },
        // editID(n, o) {
        //     if (n === o) {
        //         return;
        //     }
        // },
    },
    created() {
        !sessionStorage.token ? (window.location.pathname = "/") : "";
    },
    mounted() {
        this.school = sessionStorage.school;
        this.getDepartments();

        // this.getAreas();
    },
    methods: {
        getDepartments() {
            axios
                .get("department/index", { params: { school_id: this.school } })
                .then((response) => {
                    this.departmentsData = response.data;
                })
                .catch({});
        },
        // getAreas() {
        //     axios
        //         .get("areas")
        //         .then((response) => {
        //             console.log("area:" + response.data);
        //             this.areaData = response.data;
        //         })
        //         .catch((error) => {});
        // },
        // addBtn() {
        //     console.log("新增");
        //     this.showDetails = null;
        //     this.isAddArea = true;
        //     this.modelType = "new";
        // },
        // searchArea() {
        //     console.log("search");
        // },
        // showAreaDetail(selected) {
        //     console.log("show details");
        //     console.log("select row:" + selected);
        //     this.isAddArea = true;
        //     this.modelType = "detail";
        //     this.showDetails = selected;
        // },
        // editArea(area) {
        //     console.log("edit area");
        //     if (this.canEditArea == true) {
        //         if (
        //             this.name != area.name ||
        //             this.max_capacity != area.max_num_peoples
        //         ) {
        //             this.areaData = this.areaData.map((r) => {
        //                 if (r.id == this.editID) {
        //                     r.name = this.name;
        //                     r.max_num_peoples = this.max_capacity;
        //                 }
        //                 return r;
        //             });
        //             let formData = new FormData();
        //             if (this.editID) {
        //                 formData.append("id", this.editID);
        //             }
        //             if (this.name) {
        //                 formData.append("name", this.name);
        //             }
        //             if (this.max_capacity) {
        //                 formData.append("max_num_peoples", this.max_capacity);
        //             }
        //             axios
        //                 .post("updateArea", formData, {
        //                     headers: {
        //                         "Content-Type": "multipart/form-data",
        //                     },
        //                 })
        //                 .then((response) => {
        //                     console.log("details:" + response.data);
        //                     this.$buefy.toast.open({
        //                         message: "更新成功",
        //                         type: "is-success",
        //                         queue: false,
        //                     });
        //                     this.selectedImageFile = null;
        //                 })
        //                 .catch((error) => {
        //                     this.$buefy.toast.open({
        //                         message: "更新失敗",
        //                         type: "is-danger",
        //                         queue: false,
        //                     });
        //                 })
        //                 .catch((error) => {});
        //         }
        //         this.canEditArea = false;
        //         this.editID = null;
        //     } else {
        //         this.canEditArea = true;
        //         this.editID = area.id;
        //         this.name = area.name;
        //         this.max_capacity = area.max_num_peoples;
        //     }
        // },
        // deleteArea() {
        //     console.log("delete area");
        // },
    },
};
</script>

<style lang="scss" scoped>
.text_center {
    text-align-last: center !important;
}
</style>
