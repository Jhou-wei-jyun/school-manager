<template>
    <div>
        <apexchart
            width="200"
            type="radialBar"
            :options="options"
            :series="series"
        >
        </apexchart>
    </div>
</template>

<script>
export default {
    props: ["selectData"],
    data: function () {
        return {
            series: [],
            // colors: ["#20E647"],
            options: {
                chart: {
                    offsetY: 0,
                },
                plotOptions: {
                    radialBar: {
                        hollow: {
                            margin: 0,
                            size: "70%",
                            background: "#ffffff",
                        },
                        // track: {
                        //     background: "#636363",
                        // },
                        startAngle: 0,
                        endAngle: 360,
                        dataLabels: {
                            value: {
                                color: "#636363",
                                fontSize: "20px",
                                show: true,
                                offsetY: -10,
                            },
                            name: {
                                fontSize: "15px",
                                color: "#636363",
                                // fontFamily: "SegoeUI",
                                offsetY: 30,
                            },
                        },
                    },
                },
                fill: {
                    opacity: 1.5,
                    colors: ["#ff9966"],
                    type: "gradient",
                    gradient: {
                        gradientToColors: ["#ff5e62"],
                        shadeIntensity: 1,
                        opacityFrom: 1,
                        opacityTo: 2,
                        stops: [0, 50, 100],
                        inverseColors: false,
                    },
                },
                stroke: {
                    lineCap: "round",
                },
                labels: [""],
            },
        };
    },
    watch: {
        selectData(n, o) {
            this.series = [n.PM25];
        },
    },
};
</script>
