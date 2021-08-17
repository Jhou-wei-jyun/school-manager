<template>
    <div class="container" style="background-color: #eef1f5">
        <div
            class="notification"
            style="backgroundcolor: #eef1f5; margin-top: 30px"
        >
            <b-button
                style="float: right; font-size: 14px; margin-left: 10px"
                size="is-large"
                type="is-primary"
                class="animate__animated animate__fadeIn"
                @click="addBtn"
            >
                Add a Item.
            </b-button>
        </div>

        <div class="department-group" style="margin-top: 60px">
            <form @submit.prevent="searchItem">
                <div class="container" style="background-color: #fff">
                    <b-field grouped style="padding: 40px; margin-right: 336px">
                        <b-input
                            v-model="search_val"
                            placeholder="Search..."
                            type="search"
                            icon="magnify"
                            icon-clickable
                            expanded
                        ></b-input>
                        <p class="control">
                            <button type="submit" class="button is-primary">
                                Search
                            </button>
                        </p>
                    </b-field>
                </div>
            </form>
            <b-tabs class="device-tabs">
                <b-tab-item v-for="area in areaData" :key="area.id">
                    <template slot="header">
                        <span
                            class="device-type-name"
                            @click="getAreaProperties(area.id)"
                            >{{ area.name }}</span
                        >
                    </template>
                </b-tab-item>
            </b-tabs>
            <b-table
                :data="itemData"
                :loading="isLoading"
                mobile-cards
                class="department-table"
            >
                <!-- <b-table :data="itemData" :loading="isLoading" striped hover narrowed bordered hoverable backend-pagination backend-sorting :mobile-cards="false"> -->
                <!-- @page-change="onPageChange"
                    :total="recordData.total"
                    :paginated="isPaginated"
                    :current-page.sync="recordData.current_page"
                    :per-page="recordData.per_page" -->
                <template slot-scope="props">
                    <b-table-column
                        field="user_id"
                        label="No."
                        width="40"
                        centered
                    >
                        <span>{{ props.row.id }}</span>
                    </b-table-column>
                    <b-table-column
                        field="item_photo"
                        label="Photo"
                        width="60"
                        centered
                    >
                        <img
                            class="item_img"
                            v-show="editID != props.row.id"
                            width="40px;"
                            height="40px;"
                            :src="props.row.photo || def_photo"
                        />
                        <b-upload
                            v-show="editID === props.row.id"
                            v-model="editImage"
                            type="file"
                        >
                            <a class="button upload_btn">
                                <b-icon icon="upload"></b-icon>
                                <img
                                    class="item_img"
                                    :src="
                                        selectedImageFile
                                            ? selectedImageFile
                                            : props.row.photo || def_photo
                                    "
                                    width="40px;"
                                    height="40px;"
                                />
                            </a>
                        </b-upload>
                    </b-table-column>

                    <b-table-column
                        v-show="editID != props.row.id"
                        field="item_name"
                        label="Name"
                        width="40"
                        ><span>{{ props.row.name }}</span></b-table-column
                    >
                    <b-table-column
                        v-show="editID === props.row.id"
                        field="item_name"
                        label="Name"
                        width="40"
                    >
                        <b-input v-model="name"></b-input>
                    </b-table-column>
                    <b-table-column
                        field="item_category"
                        label="Type"
                        width="50"
                        ><span>{{ props.row.category }}</span></b-table-column
                    >

                    <b-table-column
                        v-show="editID != props.row.id"
                        field="item_mac"
                        label="Mac"
                        width="40"
                        ><span>{{ props.row.mac }}</span></b-table-column
                    >
                    <b-table-column
                        v-show="editID === props.row.id"
                        field="item_mac"
                        label="Mac"
                        width="40"
                    >
                        <b-input v-model="mac"></b-input>
                    </b-table-column>

                    <b-table-column
                        field="setupdate"
                        label="Setup date"
                        width="20"
                        ><span>{{ props.row.register }}</span></b-table-column
                    >
                    <b-table-column field="detail" label="" width="20">
                        <b-button class="table-btn"
                            ><span
                                style="color: #0084ff"
                                @click="showItemDetail(props.row)"
                                >View Detail</span
                            ></b-button
                        >
                    </b-table-column>
                    <b-table-column field="edit" label="  " width="20">
                        <b-button class="table-btn" @click="editItem(props.row)"
                            ><img width="20px;" src="images/editBtn.png"
                        /></b-button>
                    </b-table-column>
                    <b-table-column field="x" label="   " width="10">
                        <b-button
                            class="table-btn"
                            @click="deleteItem(props.row)"
                            ><span>X</span></b-button
                        >
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
        <b-modal :active.sync="isAddItem" :width="640">
            <AddItem :item-info="{ showDetails, modelType }"></AddItem>
        </b-modal>
    </div>
</template>

<script>
import AddItem from "./AddItem";
export default {
    components: {
        AddItem,
    },
    data: function () {
        return {
            modelType: null,
            showDetails: null,
            isAddItem: false,

            searchItem: null,
            search_val: null,

            areaData: [],
            current_area_id: null,
            itemData: [],
            isLoading: false,
            canEdit: false,
            isNewPosition: false,
            position: null,
            isEditProfile: false,
            account: null,
            mac: null,
            department: null,
            activeTab: 0,
            multiline: true,
            editID: null,
            name: null,
            category: null,

            details: null,

            editImage: null,
            selectedImageFile: null,

            // positionsData: [],
            // selectPosition: null,
            canEditItem: false,

            canNewPosition: false,

            def_photo: require("./image/no_item.png"),

            //animate
            newEmployeeBtnAnimate: null,
            editEmployeeBtnAnimate: null,
            showNewEmployeeBtn: false,
            showEditEmployeeBtn: false,

            editDetails: [
                {
                    title: null,
                    list: [
                        {
                            title: null,
                            type: null,
                            image: null,
                            video: null,
                            text: null,
                        },
                    ],
                },
            ],
            // newPositionBtnAnimate: 'animate__animated animate__slideInLeft',
        };
    },
    watch: {
        editImage(image) {
            if (image == null) {
                return;
            }
            var reader = new FileReader();
            reader.onload = (e) => {
                this.selectedImageFile = e.target.result;
            };
            reader.readAsDataURL(image);
        },
        editID(n, o) {
            if (n === o) {
                return;
            }
        },
        position(n, o) {
            if (n === o) {
                return;
            }
        },
    },
    mounted() {
        !sessionStorage.token ? (window.location.pathname = "/") : "";
        // this.animate();
        this.getItemAreas();
        this.getAreaProperties(0);
        this.current_area_id = 0;
    },
    methods: {
        showItemDetail(selected) {
            console.log("select row:" + selected);
            this.isAddItem = true;
            this.modelType = "detail";
            this.showDetails = selected;
        },
        editItem(item) {
            if (this.canEditItem == true) {
                // console.log('item.detail:' + JSON.stringify(item.details));
                // console.log('detail:' + JSON.stringify(this.details));
                if (
                    this.name != item.name ||
                    this.mac != item.mac ||
                    this.selectedImageFile != null
                ) {
                    // console.log('完成');
                    this.itemData = this.itemData.map((r) => {
                        if (r.id == this.editID) {
                            r.name = this.name;
                            // r.details = this.editDetails;
                            r.mac = this.mac;
                            r.photo = this.selectedImageFile;
                            // r.position = this.position;
                        }
                        return r;
                    });

                    // console.log(JSON.stringify(this.editDetails));
                    // return ;
                    let formData = new FormData();
                    if (this.editID) {
                        formData.append("id", this.editID);
                    }
                    if (this.name) {
                        formData.append("name", this.name);
                    }
                    if (this.mac) {
                        formData.append("mac", this.mac);
                    }
                    // console.log('edit:' + JSON.stringify(this.editDetails));
                    if (JSON.stringify(this.editDetails)) {
                        formData.append(
                            "details",
                            JSON.stringify(this.editDetails)
                        );
                    }
                    if (this.editImage) {
                        formData.append("imageFile", this.editImage);
                    }
                    //  if (this.position){
                    //      console.log('ppppp:'+JSON.stringify(this.position));
                    //      formData.append("position_id", this.position.id);
                    //  }
                    axios
                        .post("updateItem", formData, {
                            headers: {
                                "Content-Type": "multipart/form-data",
                            },
                        })
                        .then((response) => {
                            console.log("details:" + response.data);
                            this.$buefy.toast.open({
                                message: "更新成功",
                                type: "is-success",
                                queue: false,
                            });
                            this.selectedImageFile = null;
                        })
                        .catch((error) => {
                            this.$buefy.toast.open({
                                message: "更新失敗",
                                type: "is-danger",
                                queue: false,
                            });
                        })
                        .catch((error) => {});
                }

                this.canEditItem = false;
                this.editID = null;
            } else {
                // console.log(JSON.stringify(item.details));
                this.canEditItem = true;
                this.editID = item.id;
                this.name = item.name;
                // this.editDetails = item.details;
                this.mac = item.mac;
                // console.log('this.editDetails:'+this.editDetails);
            }
        },
        deleteItem(index) {
            const item_id = index.id;
            const item_name = index.name;
            this.$buefy.snackbar.open({
                message:
                    '要刪除這個<span style="color: red;">' +
                    item_name +
                    "</span>物品嗎？",
                type: "is-info",
                position: "is-top",
                actionText: "好",
                queue: false,
                onAction: () => {
                    axios
                        .put("deleteItem?id=" + item_id)
                        .then((response) => {
                            if (response.data.result == true) {
                                var index = this.itemData.findIndex(
                                    (d) => d.id === item_id
                                );
                                this.itemData.splice(index, 1);
                                this.$buefy.toast.open({
                                    message: "已刪除",
                                    queue: false,
                                });
                            }
                        })
                        .catch((error) => {
                            if (error) {
                                this.$buefy.toast.open({
                                    message: "刪除失敗請聯繫相關人員協助處理",
                                    type: "is-danger",
                                    queue: false,
                                });
                            }
                        });
                },
            });
        },
        addBtn() {
            // window.location.href = 'newEmployee?item';
            this.showDetails = null;
            this.isAddItem = true;
            this.modelType = "new";
        },
        getItemAreas() {
            // console.log('areas');
            axios
                .get("itemAreas")
                .then((response) => {
                    // console.log('areaData:' + JSON.stringify(response.data));
                    let areaData = response.data;
                    areaData.push({
                        id: 0,
                        name: "全部",
                    });
                    areaData = areaData.sort((a, b) => a.id - b.id);

                    // console.log('areaData:'.areaData);
                    this.areaData = areaData;
                })
                .catch((error) => {});
        },
        async getAreaProperties(area) {
            // console.log('areaID:'+area);
            // return;
            try {
                this.isLoading = true;
                const response = await axios.get("items?id=" + area);
                // console.log('areaProperty:'+JSON.stringify(response.data));
                // if (response.data.length > 0) {
                this.itemData = response.data.map((item) => ({
                    ...item,
                    register: item.created_at.split(" ")[0],
                }));
                // }else{
                //     this.itemData = [];
                // }
                // console.log('更新一次:' + JSON.stringify(this.itemData));
            } catch (e) {
                console.log("error:" + e);
            } finally {
                this.isLoading = false;
            }
        },
    },
};
</script>

<style lang="scss" scoped>
.device-tabs {
    .tabs {
        ul {
            li {
                a {
                    .device-type-name {
                        border-bottom: solid 2px transparent;
                        font-size: large;
                    }
                }

                &.is-active {
                    .device-type-name {
                        color: #000000;
                        border-bottom: solid 2px #0084ff;
                    }

                    .device-type-postfix {
                        color: #000000;
                    }
                }
            }
        }
    }
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

.item_img {
    // border-radius: 50%;
    height: 40px;
}

.upload_btn {
    border-width: 0;
}
</style>
