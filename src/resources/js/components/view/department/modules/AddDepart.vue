<template>
    <input
        placeholder="按enter加入新部門..."
        v-model="name"
        @keyup.enter="(name === '') | (name === null) ? false : addDepart(name)"
    />
</template>

<script>
export default {
    props: ["school"],
    data: function () {
        return {
            name: null,
        };
    },
    mounted() {},
    methods: {
        addDepart() {
            if (this.name === null) {
                return;
            }
            if (this.school === null) {
                return;
            }

            let formData = new FormData();
            if (this.school) {
                formData.append("school_id", this.school);
            }
            if (this.name) {
                formData.append("name", this.name);
            }

            axios
                .post("department/addDepart", formData, {
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
                        this.$emit("refreshDepart");
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
                });
        },
    },
};
</script>

<style lang="scss" scoped>
</style>
