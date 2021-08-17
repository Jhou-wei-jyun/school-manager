<template>
<div class="col-md-8">
    <div class="" style="backgroundColor:#fff; width:100%; height:70px;">
        <b-button class="animate__animated animate__slideInLeft" style="margin-top: 10px; margin-left:30px;" size="is-medium" type="is-text">New</b-button>
        <b-button class="animate__animated animate__slideInLeft"  style="margin-top: 10px;" size="is-medium" type="is-text">Modify</b-button>
    </div>
    <div style="margin-bottom:18px; margin-left:30px; margin-right:10px;">
        <!-- <div v-for="department in departments" :key="department.id"> -->
            <!-- {{department}} -->
            <!-- <strong style="font-size:20px; color:#005AB5; float:left; width:90px;"><u>{{ department.name }}</u></strong> -->
            <hr style="background-color: gray;">
            <section class="material" v-for="material in materials" :key="material.id">
               <div style="width:300px; height:220px;  float:left;"><LazyYoutubeVideo :src="material.url"/><p style="max-width:400px; overflow:hidden; text-overflow: ellipsis;"><strong>{{material.name}}</strong></p></div>
            </section>
        <!-- </div> -->
    </div>
</div>
</template>

<script>
export default {

    data: function () {
        return {
            materials: [],
            show: true,
            photoClass: null,
        }
    },
    watch: {},
    mounted() {
        !sessionStorage.token ? window.location.pathname = "/" : '';
        window.addEventListener('scroll', this.handleScroll)
        this.getDepartments();
    },
    methods: {
        // handleScroll() { //改变元素#searchBar的top值
        //     var scrollTop = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop;
        //     var offsetTop = document.querySelector('#searchBar').offsetTop;
        //     console.log('scroll:' + scrollTop);
        //     if (scrollTop <= 200) {
        //         this.photoClass = 'animate__animated animate__zoomInLeft';
        //         console.log('小於等於200')
        //         offsetTop = 300 - Number(scrollTop);
        //         document.querySelector('#searchBar').style.top = offsetTop + 'px';
        //     } else {
        //         console.log('大於200')
        //         // this.photoClass = 'animate__animated animate__zoomInUp';
        //         document.querySelector('#searchBar').style.top = '100px';
        //     }
        // },

        getDepartments() {
            axios.get('materials').then(response => {
                console.log('material:'+response.data.map(r => r.id));
                this.materials = response.data;
            }).catch(error => {

            });
        },
    },
    // destroyed() { //离开该页面需要移除这个监听的事件
    //     window.removeEventListener('scroll', this.handleScroll)
    // },
}
</script>

<style scoped>
.material {
    display: inline-block;
    justify-content: space-around;
    margin-left: 10px;
}

/*
.fixed {
    position: fixed;
    bottom: 100px;
    top: 300px;
    left: 218px;
    box-sizing: border-box;
    z-index: 2;
} */
</style>
