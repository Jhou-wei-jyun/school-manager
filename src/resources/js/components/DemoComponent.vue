<template>
<body>
    <div class="allDiv container" style="margin-top:100px;">
        <div class="area01">
            <div class="inDiv" style="text-align: center;">
                <div v-for="option in enterData" :value="option.value" :key="option.key">
                    <img width="50px" style="border-radius: 50%;" :src="option.avatar || avatar " />
                </div>
            </div>
            <div class="demoDiv" style="align-content:flex-end;">
                <div v-for="option in demoData" :value="option.value" :key="option.key">
                    <img width="50px" style="border-radius: 50%;" :src="option.avatar || avatar " />
                </div>
            </div>
        </div>
        <div class="workDiv" style='text-align: center;'>
            <div style="margin-top:21px;" v-for="option in workData" :value="option.value" :key="option.key">
                <img width="50px" style="border-radius: 50%;" :src="option.avatar || avatar " />
            </div>
        </div>
        <div class="area01">
            <div class="meetingDiv" style="display:flex;">
                <div style="margin-top:21px;" v-for="option in meetingData" :value="option.value" :key="option.key">
                    <img width="50px" style="border-radius: 50%;" :src="option.avatar || avatar " />
                </div>
            </div>
            <div class="ceoDiv">
                <!-- 董事辦公室 -->
            </div>
        </div>
    </div>
</body>
</template>

<script>

export default {
    data: function () {
        return {

            enterData: [],
            demoData: [],
            workData: [],
            meetingData: [],
            avatar: '/images/img_profile_default.png',

            areaData: [],
            isLoading: false,
        }
    },
    watch: {

    },
    mounted() {
        !sessionStorage.token ? window.location.pathname = "/" : '';
        this.getData();
        this.getAreaData();
        this.Timer = setInterval(this.getData, 5000);

    },
    methods: {
        getAreaData() {
            axios.get('areas').then(response => {
                console.log('get all areas:' + response.data);
                this.areaData = response.data;
            }).catch(error => {

            })
        },
        getData() {
            this.enterData = [];
            this.demoData = [];
            this.workData = [];
            this.meetingData = [];
            console.log('in');
            axios.get('demo_area').then(response => {
                console.log('getData:' + JSON.stringify(response.data));
                let data = response.data;
                data.map(r => {
                    if (r.record.area_id == 1) {
                        this.enterData.push(r);
                    }

                    if (r.record.area_id == 2) {
                        this.demoData.push(r);
                    }

                    if (r.record.area_id == 3) {
                        this.workData.push(r);
                    }

                    if (r.record.area_id == 9) {
                        this.meetingData.push(r);
                    }
                });
            }).catch(error => {

            })
        }
    },
}
</script>

<style lang="scss" scoped>
.allDiv {
    display: flex;
    background-size: 100%;
    z-index: -100;
    width: 97.5rem;
    height: 43rem;
}

.workDiv {
    width: 50rem;
    height: 28rem;
    background-color: transparent;
    background: url('image/office.png') no-repeat;
    // background-size: cover;
}

.inDiv {
    width: 40rem;
    height: 14rem;
    text-align: center;
    margin: 0;
    padding: 0;
    background-color: transparent;
    background: url('image/enter.png') no-repeat;
    background-size: cover;
}

.demoDiv {
    width: 40rem;
    height: 14rem;
    text-align: center;
    margin: 0;
    padding: 0;
    background-color: transparent;
    background: url('image/demo.png') no-repeat;
    background-size: cover;
    // background-color: #D0D0D0;
}

.meetingDiv {
    width: 16rem;
    height: 14rem;
    text-align: center;
    margin: 0;
    padding: 0;
    background-color: transparent;
    background: url('image/meeting.png');
    background-size: cover;
}

.ceoDiv {
    width: 16rem;
    height: 14rem;
    text-align: center;
    margin: 0;
    padding: 0;
    background-color: transparent;
    background: url('image/ceo.png');
    background-size: cover;
}
</style>
