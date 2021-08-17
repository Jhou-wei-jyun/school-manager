<template>
<div class="col-md-8">
    <div class="tools" style="backgroundColor:#fff; width:100%; height:100px;">
        <b-field style="padding-top:40px; padding-left:20px; max-width:400px;" type="is-dark" class="moblie animate__animated animate__slideInLeft">
            <b-input v-model="searchValue" placeholder="Search.." rounded></b-input>
        </b-field>
    </div>
    <b-table class="animate__animated animate__fadeInUp" :data="recordData.data" :loading="isLoading" striped hover narrowed bordered hoverable backend-pagination backend-sorting :mobile-cards="false" @page-change="onPageChange" :total="recordData.total" :paginated="isPaginated" :current-page.sync="recordData.current_page" :per-page="recordData.per_page" style="border-radius: 10px; margin:20px 140px;">
        <template slot-scope="props">
            <b-table-column field="user_name" :style="props.row.statu_id == 9 ? 'backgroundColor:#D7FFEE;':'' || props.row.statu_id == 6 ? 'backgroundColor:#FFB5B5;': '' || props.row.statu_id == 7 ? 'backgroundColor:#FFB5B5;': ''" label="人員名稱" width="40">{{props.row.user.name || props.row.item.name}}</b-table-column>
            <b-table-column field="name" :style="props.row.statu_id == 9 ? 'backgroundColor:#D7FFEE;':'' || props.row.statu_id == 6 ? 'backgroundColor:#FFB5B5;': '' || props.row.statu_id == 7 ? 'backgroundColor:#FFB5B5;': ''" label="區域" width="100">{{ props.row.name }}</b-table-column>
            <b-table-column v-show="props.row.statu_id != 7" field="detail" :style="props.row.statu_id ==  9 ? 'backgroundColor:#D7FFEE;':'' || props.row.statu_id == 6 ? 'backgroundColor:#FFB5B5;': ''" label="詳細說明" width="100" style="color:#003060;">{{ props.row.user.name || props.row.item.name}} 在 <span style="color:red;">{{ props.row.detailTime }}</span> 至 <span style="color:red;">{{ props.row.leaveDate }}</span> 時出現在 {{props.row.name}}</b-table-column>
            <b-table-column v-show="props.row.statu_id == 7" field="detail" label="詳細說明" width="100" style="color:#003060; backgroundColor:#FFB5B5;">{{ props.row.user.name }} 在 <span style="color:red;">{{ props.row.detailTime }}</span> 至 <span style="color:red;">{{ props.row.leaveDate }}</span> 時不在公司</b-table-column>
        </template>

        <template slot="empty">
            <section class="section" style="border-radius: 10px;">
                <div class="content has-text-grey has-text-centered;">
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
</template>

<script>
export default {
    data: function () {
        return {
            realTimeAnimate: false,
            timer: '',
            vv: 0,
            onTimeData: false,
            bTitle: 'Real time Off',
            bClass: 'is-info',

            recordData: {
                data: []
            },
            isLoading: false,
            perPage: 100,
            sortField: "id",
            sortOrder: "desc",
            total: 0,
            currentPage: 1,
            isPaginated: true,

            searchValue: null,
        }
    },
    watch: {
        searchValue(n, o) {
            if (n === o) {
                return;
            }
            if (n === "") {
                this.getRecord()
            } else {
                setTimeout(() => this.getSearch(n), 5000);
            }
        }
    },
    mounted() {
        this.timer = setInterval(this.getRecord, 300000);
        this.getRecord();
    },
    methods: {
        onTimeFunc() {
            if (this.bTitle == 'Real time Off') {
                this.realTimeAnimate = true;
                this.onTimeData = true;
                this.bTitle = 'Real time On';
                this.bClass = 'is-danger';
                this.timer = setInterval(this.getRecord, 3000);
            } else {
                this.realTimeAnimate = false;
                this.onTimeData = false;
                this.bTitle = 'Real time Off';
                this.bClass = 'is-info';
                clearInterval(this.timer)
            }
        },
        onPageChange(page) {
            this.currentPage = page;
            this.getRecord();
        },
        getSearch(value) {
            var result = this.recordData.data.filter(r => {
                if (r.user.name == value) {
                    return r;
                }
            });
            // var seee = this.recordData.data.filter( r => {
            //     return r.user.name == value
            // });
            // this.recordData.data = result;
            console.log('res:' + JSON.stringify(result))
            //  let formData = new FormData();
            // formData.append("value", value);
            // axios.post('searchRecord',formData).then(response =>{
            //     console.log('res search:'+JSON.stringify(response.data));
            //     this.recordData = response.data;

            // }).catch({});
        },
        getRecord() {
            this.vv++;
            console.log("times:" + this.vv);
            if (this.onTimeData == false) {
                this.isLoading = true;
            }
            const page = this.currentPage;
            const perPage = 20;
            const url = `recordData?page=${page}&perPage=${perPage}`;
            axios.get(url).then(response => {
                    console.log('recordData:' + JSON.stringify(response.data));
                    let data = response.data.data;
                    data = data.sort((a, b) => b.leave_at - a.leave_at);
                    data.map(i => {
                        if (i.user == null) {
                            i.user = i.item;
                        }
                        if (i.leave_at != null) {
                            i.b = i.leave_at.split(" ")[1];
                            i.leaveDate = i.b.split(":")[0] + "點" + i.b.split(":")[1] + "分" + i.b.split(":")[2] + "秒";
                        }
                        i.a = i.date_time.split(" ")[1];
                        i.detailTime = i.a.split(":")[0] + "點" + i.a.split(":")[1] + "分" + i.a.split(":")[2] + "秒";
                    });
                    this.recordData = response.data;
                })
                .catch(error => {})
                .finally(() => {
                    this.isLoading = false;
                });
        },
    },
}
</script>
