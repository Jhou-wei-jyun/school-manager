<template>
    <div id="main">
        <ve-ring
            :data="chartData"
            :settings="chartSettings"
            :tooltip-visible="false"
            :legend-visible="false"
            :grid="grid"
        ></ve-ring>
    </div>
</template>

<script>
export default {
    props: ["item"],
    data() {
        this.grid = {
            show: true,
        };
        this.chartSettings = {
            digit: 0,
            hoverAnimation: false,
            // metrics: "比率",
            // radius: [1, 10],
        };
        return {
            show: false,
            datarray: [],
            data: null,
            chartData: {
                columns: ["類別", "比率"],
                rows: [
                    // { 日期: "1/1", 访问用户: 1393 },
                    // { 日期: "1/2", 访问用户: 3530 },
                ],
            },
        };
    },
    watch: {
        item: function (n, o) {
            this.data = n;
            this.getchartdata();
        },
    },
    mounted() {
        // this.getchartdata();
    },
    methods: {
        getchartdata() {
            console.log("a", this.data);
            if (this.data > 0) {
                this.show = true;
            }
            this.datarray = [
                { type: "出席", parcent: this.data },
                { type: "未到", parcent: 100 - this.data },
            ];
            this.chartData.rows = [];
            for (var i = 0; i < this.datarray.length; i++) {
                this.chartData.rows.push({
                    類別: this.datarray[i].type,
                    比率: this.datarray[i].parcent,
                });
            }
            // this.chartData.rows = this.dataarray;
        },
    },
};
</script>
<style lang="scss" scoped>
#main {
    width: clamp(50px, 5vw, 100px);
    height: clamp(50px, 5vw, 100px);
}
</style>
