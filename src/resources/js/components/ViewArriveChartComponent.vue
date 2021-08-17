<template>
    <ve-line :data="chartData" :legend-visible="false" :settings="chartSettings"></ve-line>
</template>

<script>
export default {
    props: ["item"],
    data() {
        this.chartSettings = {
            stack: { 用户: ["访问用户", "下单用户"] },
            area: true,
        };
        return {
            dd: JSON.parse(this.item),
            chartData: {
                columns: ["日期", "出席率"],
                rows: [
                    { 日期: "Sum", 出席率: 0.0 },
                    { 日期: "Mon", 出席率: 0.0 },
                    { 日期: "Tue", 出席率: 0.0 },
                    { 日期: "Wed", 出席率: 0.0 },
                    { 日期: "Thu", 出席率: 0.0 },
                    { 日期: "Fri", 出席率: 0.0 },
                    { 日期: "Sat", 出席率: 0.0 },
                ],
            },
        };
    },
    watch: {
        item: function (newVal, oldVal) {
            this.dd = newVal;
            this.getchartdata();
        },
    },
    mounted() {
        // this.getchartdata();
    },
    methods: {
        getchartdata() {
            let xx = JSON.parse(this.dd);
            //this.chartData.rows = [];
            for (var i = 0; i < 7; i++) {
                switch (i) {
                    case 0:
                        this.chartData.rows[1]["出席率"] = xx[i];
                        break;
                    case 1:
                        this.chartData.rows[2]["出席率"] = xx[i];
                        break;
                    case 2:
                        this.chartData.rows[3]["出席率"] = xx[i];
                        break;
                    case 3:
                        this.chartData.rows[4]["出席率"] = xx[i];
                        break;
                    case 4:
                        this.chartData.rows[5]["出席率"] = xx[i];
                        break;
                    case 5:
                        this.chartData.rows[6]["出席率"] = xx[i];
                        break;
                    case 6:
                        this.chartData.rows[0]["出席率"] = xx[i];
                        break;
                }
            }
        },
    },
};
</script>
