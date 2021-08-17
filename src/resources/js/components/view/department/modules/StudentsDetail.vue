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
    props: ["empInfo", "admin"],
    data() {
        console.log("empInfo", this.empInfo);
        return {
            user: this.empInfo,
            todayDate: moment().format("YYYY-MM-DD"),
            allStudents: [],
            pairStudents: [],
            mockData: [],
            targetKeys: [],
            user_ids: "",
            school: null,
        };
    },
    mounted() {
        this.school = sessionStorage.school;
        this.getAllStudents(), this.getPairStudents();
    },
    methods: {
        Parentrefresh() {
            this.$emit("Parentrefresh");
        },
        async getAllStudents() {
            this.isLoading = true;
            const url = "pair/parent/all_student_list";
            const response = await axios.get(url, {
                params: {
                    parent_id: this.user.parent_id,
                    school_id: this.school,
                },
            });
            this.allStudents = response.data;
            console.log(response);
            console.log(this.allStudents);
            const mockData = [];
            for (let i = 0; i < this.allStudents.length; i++) {
                const data = {
                    key: this.allStudents[i].id.toString(),
                    title: this.allStudents[i].name,
                    description: this.allStudents[i].name,
                };
                mockData.push(data);
            }
            this.mockData = mockData;
        },
        async getPairStudents() {
            try {
                this.isLoading = true;
                const url = "pair/parent/pair_student_list";
                const response = await axios.get(url, {
                    params: {
                        parent_id: this.user.parent_id,
                        school_id: this.school,
                    },
                });
                this.pairStudents = response.data;
                console.log(response);
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
            } catch (e) {
            } finally {
                this.isLoading = false;
            }
        },
        SaveData() {
            this.isLoading = true;
            let formData = {};
            if (this.school) {
                formData["school_id"] = this.school;
            }
            if (this.user.parent_id) {
                formData["parent_id"] = this.user.parent_id;
            }
            if (this.user_ids) {
                formData["user_id"] = this.user_ids.join();
            }
            if (this.admin) {
                formData["admin_id"] = this.admin;
            }
            console.log(formData);
            // 新增
            axios
                .post("pair/parent/relationship_change", formData, {})
                .then((response) => {
                    if (response.data.result == true) {
                        this.$buefy.toast.open({
                            message: "更新成功",
                            type: "is-success",
                            queue: false,
                        });
                        this.Parentrefresh();
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
            // console.log(dir, value);
            // this.mockDataShow = this.mockData.filter(({ title }) =>
            //     title.includes(value)
            // );
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
