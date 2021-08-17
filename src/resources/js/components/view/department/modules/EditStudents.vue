<template>
    <div class="card card-body">
        <header class="card-bottom d-flex flex-column">
            <p class="h4 has-text-weight-semibold">配對綁定</p>
            <div>
                <span class="d-flex flex-row justify-content-center">
                    說明：先選擇“尚未配對學生”再點選““>”放入右邊欄位，按新增即可
                </span>
                <span
                    class="d-flex flex-row justify-content-end"
                    style="font-size: 12px"
                    >（如需刪除，選擇“已配對學生”再選“<”放入左邊欄位）</span
                >
            </div>
        </header>
        <div class="d-flex flex-row">
            <span class="flex-fill"></span>
            <span class="flex-fill pl-5">尚未配對學生</span>
            <span class="flex-fill text-center">已配對學生</span>
            <span class="flex-fill"></span>
        </div>
        <div class="d-flex justify-content-center">
            <template>
                <a-transfer
                    :data-source="mockData"
                    show-search
                    :filter-option="filterOption"
                    :target-keys="targetKeys"
                    :render="(item) => item.title"
                    @change="handleChange"
                    @search="handleSearch"
                />
            </template>
        </div>
        <footer class="card-bottom d-flex align-items-center">
            <b-button
                class="notification_btn notification_btn_gray notification_btn_text_white ml-auto mr-2"
                size="is-medium"
                @click="$parent.close()"
                >取消</b-button
            >
            <b-button
                class="notification_btn notification_btn_sky notification_btn_text_white"
                size="is-medium"
                @click="SaveData()"
                >新增</b-button
            >
        </footer>
    </div>
</template>

<script>
import moment from "moment";
export default {
    props: ["depId", "admin"],
    data() {
        console.log("depId", this.depId);
        return {
            user: this.depId,
            // todayDate: moment().format("YYYY-MM-DD"),
            allStudents: [],
            pairStudents: [],
            mockData: [],
            allid: [],
            targetKeys: [],
            // school_id: 1,
            user_ids: "",
            school: null,
        };
    },
    mounted() {
        this.school = sessionStorage.school;
        this.getAllStudents(), this.getPairStudents();
    },
    methods: {
        Classrefresh() {
            this.$emit("onclassrefresh");
        },
        async getAllStudents() {
            this.isLoading = true;
            const url = "pair/department/all_student_list";
            const response = await axios.get(url, {
                params: {
                    department_id: this.user,
                    school_id: this.school,
                },
            });
            this.allStudents = response.data;
            console.log(this.allStudents);
            const mockData = [];
            const allid = [];
            for (let i = 0; i < this.allStudents.length; i++) {
                const data = {
                    key: this.allStudents[i].id.toString(),
                    title: this.allStudents[i].name,
                    description: this.allStudents[i].name,
                };
                mockData.push(data);
                allid.push(data.key);
            }
            this.mockData = mockData;
            this.allid = allid;
        },
        async getPairStudents() {
            try {
                this.isLoading = true;
                const url = "pair/department/pair_student_list";
                const response = await axios.get(url, {
                    params: {
                        department_id: this.user,
                        school_id: this.school,
                    },
                });
                this.pairStudents = response.data;
                console.log(this.pairStudents);
                const targetKeys = [];
                for (let i = 0; i < this.pairStudents.length; i++) {
                    const data = {
                        key: this.pairStudents[i].id.toString(),
                        title: this.pairStudents[i].name,
                        description: `description of content${i + 1}`,
                    };
                    targetKeys.push(data.key);
                }
                this.targetKeys = targetKeys;
                //this.targetKeys = this.pairStudents;
                // console.log('更新一次:' + JSON.stringify(this.employeeData));
            } catch (e) {
            } finally {
                this.isLoading = false;
            }
        },
        SaveData() {
            // if (this.user_ids == "") {
            //     return;
            // }
            this.isLoading = true;
            let formData = {};
            formData["department_id"] = this.depId;

            if (this.mockData) {
                formData["all_id"] = this.allid.join();
            }
            if (this.user_ids) {
                formData["pair_id"] = this.user_ids.join();
            }
            if (this.admin) {
                formData["admin_id"] = this.admin;
            }
            if (this.school) {
                formData["school_id"] = this.school;
            }
            console.log(formData);

            // 新增
            axios
                .post("pair/department/relationship_change", formData, {})
                .then((response) => {
                    if (response.data.result == true) {
                        this.$buefy.toast.open({
                            message: "更新成功",
                            type: "is-success",
                            queue: false,
                        });
                        this.Classrefresh();
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
                    this.$parent.close();
                    this.isLoading = false;
                });
        },

        filterOption(inputValue, option) {
            return option.description.indexOf(inputValue) > -1;
        },
        handleChange(targetKeys, direction, moveKeys) {
            console.log(targetKeys, direction, moveKeys);
            this.user_ids = targetKeys;
            this.targetKeys = targetKeys;
        },
        handleSearch(dir, value) {
            console.log("search:", dir, value);
        },
    },
};
</script>


<style lang="scss" scoped>
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
