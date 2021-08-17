<template>
    <div>
        <ve-pie v-if="show" :data="chartData"></ve-pie>
    </div>
</template>

<script>
export default {
    props: ["item"],
    data() {
        // this.chartSettings = {
        //     level: [["體溫異常", "遲到", "早退"]],
        // };
        return {
            show:false,
            datarray: [],
            test: this.item,
            chartData: {
                columns: ["類別", "比率"],
                rows: [],
            },
        };
    },
    watch: {
        item: function (newVal, oldVal) {
            this.test = newVal;
            this.getchartdata();
        },
    },
    mounted() {
        this.getchartdata();
    },
    methods: {
        getchartdata() {
            console.log("a", this.test);
            if(this.test > 0){
                this.show = true;
            }
            this.datarray = [
                { type: "體溫異常", parcent: this.test },
                { type: "遲到", parcent: 0.0 },
                { type: "早退", parcent: 0.0 },
            ];
            this.chartData.rows = [];
            for (var i = 0; i < this.datarray.length; i++) {
                this.chartData.rows.push({
                    類別: this.datarray[i].type,
                    比率: this.datarray[i].parcent,
                });
            }
            // this.chartData.rows = this.testarray;
        },
    },
};
</script>
